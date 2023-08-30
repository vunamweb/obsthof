<?php
global $dir, $navID, $lan, $last_ebene, $first_call, $multilang, $morpheus;

$dir = $morpheus["url"];
$lang_arr = $morpheus["lan_arr"];
$multilang = 0; // $morpheus["multilang"];

foreach ($lang_arr as $lan_id=>$lan) {
	include("../nogo/navID_".$lan.".inc");
	$homeID = $morpheus["home_ID"][$lan];
	$navbar = '';
	
	$que  	= "SELECT * FROM `morp_cms_nav` WHERE (ebene=1 AND sichtbar=1 AND `lock` < 1 AND lang=$lan_id AND bereich < 2) ORDER BY `sort` ASC, navid ASC";
	$res 	= safe_query($que);
	$menge	= mysqli_num_rows($res);
	while ($rw = mysqli_fetch_object($res)) {
		$nm 	= $rw->name;
		$par	= $rw->parent;
		$ebene	= $rw->ebene;
		$navid	= $rw->navid;
		$manuellerLink 	= $rw->lnk;
		$nocontent = $rw->nocontent;
		
		if($manuellerLink) {
			if(isin("http", $manuellerLink)) {
				$extern=1;
			}
			else $manuellerLink = $xmlpfad.$navID[$manuellerLink];
		}
		
		$url = $manuellerLink ? $manuellerLink : $dir.($multilang ? $lan.'/' : '').($navid != $homeID ? $navID[$navid] : '');
		$hasParent = count_result($navid, 'navid', 'morp_cms_nav', 'parent');
		$lastUrl = $url;
		// $navbar .= '<li class="nav-item'.($hasParent ? ' dropdown has-megamenu' : '').' cc'.$navid.' hh'.$navid.'"><a class="nav-link" href="'.$url.'">'.$nm.'</a>';
		$navbar .= '<li class="nav-item'.($hasParent ? ' dropdown has-megamenu' : '').' cc'.$navid.' hh'.$navid.'"><a class="nav-link" href="'.($nocontent ? '' : ($hasParent ? '#' : $url)).'">'.$nm.'</a>';
		if($hasParent) {
			$first_call = 0;
			$last_ebene = 0;
			$navbar .= '		
	<div class="dropdown-menu megamenu mm'.$navid.'" role="menu">
		<div class="row g-3">
';
			$navbar .= get_nav_mega($navid, 0, '', 1, 2);
			$navbar .= '		</div>
	</div>
</li>	
';
			
		}
		else $navbar .= '</li>
';
	}

	// echo $navbar;
	$mega .= $navbar;
// $parent_arr = array();
}
save_data("../nogo/mega.inc", $mega, "w");

// die();

function get_nav_mega($getid, $aktiv, $giveClass, $ul, $getebene) {
	global $dir, $navID, $lan, $last_ebene, $isopen, $first_call, $morpheus, $multilang;
	// print_r($navID);
	$noColumn = array(22,23);
	
	$sql = "SELECT * FROM `morp_cms_nav`
		WHERE
			parent=".$getid." AND
			sichtbar=1
		ORDER BY `sort`";
	$res = safe_query($sql);
	$x = 0;
	
	$ret = $getebene > 3 ? '<ul>' : '';
	
	if(mysqli_num_rows($res) > 0) {
		while ($row = mysqli_fetch_object($res)) {
			$x++;
			$id = $row->navid;
			$nm = $row->name;
			$manuellerLink 	= $row->lnk;
			$eb = $row->ebene;
			$nocontent = $row->nocontent;
			
			if($manuellerLink) {
				if(isin("http", $manuellerLink)) {
					$extern=1;
				}
				else $manuellerLink = getUrl($manuellerLink);
			}
			
			// special GLHS
			if($getid==2) {
				$columnClass = 'col-lg-3 col-12 mm'.$x.'';
			}
			else $columnClass = 'col-lg-4 col-12';
			
			if($eb == 2) {
				$setColumn = in_array($id, $noColumn) ? 0 : 1;
				$ret .= ($first_call && $setColumn ? '
			</div>
		</div>' : '').'		
		'.($setColumn ? '<div class="'.$columnClass.' mm'.$getid.'">
			<div class="col-megamenu">' : '').'
				<h6 class="sn'.$id.'"><a href="'.($nocontent ? '' : ($manuellerLink ? $manuellerLink : $dir.$navID[$id])).'"'.($nocontent ? ' class="nolink"' : '').'>'.$nm.'</a></h6>
				<ul>';
			}
			
			$first_call = 1;
			
			if($eb == 2) $isopen[$id] = 1;
			$eb++;

			if ($aktiv == $id) 	$class = ' class="active"';
			else $class = '';

			$space = '';
			$anz = $eb+2;
			for($i=0; $i<=$anz;$i++) $space .= '	';
						
			$split = get_nav_mega($id, $aktiv, '', 1, $eb);
			
			$checkopen = 'isopen'.$id;
			
			$url = $manuellerLink ? $manuellerLink : $dir.($multilang ? $lan.'/' : '').$navID[$id];
		
			$ret .= ($row->ebene > 2 ? $space.'<li'.$class.'><a href="'.($nocontent ? '#' : $url).'" class="'.($nocontent ? 'nolink ' : '').'nav-'.($row->ebene > 2 ? 'sub-' : '').'link c'.$id.'">'.$nm.'</a>' : '').($split ? $split.'
					'.($isopen[$id] ? '</ul>' : '	</ul>
			</li>').''.($eb==3 ? '' : '').'
' : '</li>
');
			// $$checkopen=0;
		}

		if ($split) $ret .= '		
									
';
	}
	else $ret = '';

	return $ret;
}

// die();