 <?php

/// ------------------ start der regulaeren sitemap.create


if(file_exists("../lokal.dat")) $xmlpfad = $morpheus["local"];
else $xmlpfad = $morpheus["url"];

$lang_arr = array("1"=>"de", "2"=>"en");


foreach ($lang_arr as $lan_id=>$lan) {
	include("../nogo/navID_".$lan.".inc");

	// hauptnav, ohne meta menu, ohne footer menu
	$que  	= "SELECT * FROM `morp_cms_nav` WHERE (sichtbar=1 AND `lock` < 1 AND lang=$lan_id AND bereich < 2 AND ebene=1) ORDER BY `sort` ASC";
	$res 	= safe_query($que);
	$menge	= mysqli_num_rows($res);

	$arr_H=array();
	$sub_on = false;
	$sub_on2 = false;
	// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	//Struktur richtige Reihenfolge
	while ($row = mysqli_fetch_object($res)) {
		$startEbene1 = 1;

		$arr_H[] = $row->navid;

		//start ebene 2
		$res_2 = getNav($lan_id, $row->navid);
		while ($row2 = mysqli_fetch_object($res_2)) {
			$arr_H[] = $row2->navid;

			//start ebene 3
			$res_3 = getNav($lan_id, $row2->navid);
			while ($row3 = mysqli_fetch_object($res_3)) {
				$arr_H[] = $row3->navid;
				
				//start ebene 4
				$res_4 = getNav($lan_id, $row3->navid);
				while ($row4 = mysqli_fetch_object($res_4)) {
					$arr_H[] = $row4->navid;
				
				}

			}
		}
	}
	// print_r($arr_H);

	// jetzt gehts los. jeden wert in en oder de auslesen und richtigen link erzeugen!

	$lnk_arr = array();
	$nm_arr  = array();

	$footer_set = '<?php  $output .= \'<ul class="sitemap">
';
	$structure = '<?php  $structure = \'<ul class="sitemap"><li class="closeHeader"><i class="fa fa-close"></i></li>
';
	$structure_edit_mode = '<?php  $structure_edit_mode = \'<ul class="sitemap_edit"><li class="closeHeader"><i class="fa fa-close"></i></li>
';

	$ausnahme = array(9999);		// das sind hauptnavigatiosIDs, die direkt ein zusätzliches modul bedienen
	$n=0;
	foreach ($arr_H as $val) {
		$y++;
		if ($lastid != $val) {
			settype($val, "integer");	# warum auch immer der typ festgesetzt werden muss. bei einigen abfragen gab es fehler :-((
			$que  	= "SELECT * FROM `morp_cms_nav` where navid=$val";
			$res 	= safe_query($que);
			$rw 	= mysqli_fetch_object($res);

			$nm 	= $rw->name;
			$par	= $rw->parent;
			$ebene	= $rw->ebene;
			$nid	= $rw->navid;
			$ilnk	= $rw->lnk;
			$lock	= $rw->lock;

			if($lock) $lock = '<span class="fa fa-lock"></span> &nbsp;';
			else $lock = '';

			// link id's einsetzen
			$lnk_arr[$ebene] = $nid;
			$linkX			 = "";

			// nav namen einsetzen
			$nm_arr[$ebene]  = $nm;
			$nnm			 = "";

			if ($ebene < 2) {
				$n++;
				$togo = $ilnk ? $ilnk : $val;
				$lnk = '<a href="'.$xmlpfad.$navID[$togo].'">'.($nm).'</a>';
				$structure_lnk = '<a data-clipboard-text="<a:'.$togo.':>Link_Name</a>" class="intLink">'.($nm).'</a>';
				$structure_lnk_edit_mode = '<a data-clipboard-text="anker:'.$togo.'" class="intLink">'.($nm).'</a>';

				$cl = "parent";

				$footer_set .= ($sub_on2 ? '</ul>
		</li>
' : '') .($sub_on ? '</ul>
		</li>
' : '') .'
		<li class="'.$cl.' n__'.$val.'">'.$lnk.'
		 	<ul class="sub-menu'.$n.'">';

				$structure .= ($sub_on2 ? '</ul>
		</li>
' : '') .($sub_on ? '</ul>
		</li>
' : '') .'
		<li class="'.$cl.' n__'.$val.'">'.$structure_lnk.'
		 	<ul class="sub-menu'.$n.'">';

				$structure_edit_mode .= ($sub_on2 ? '</ul>
		</li>
' : '') .($sub_on ? '</ul>
		</li>
' : '') .'
		<li class="'.$cl.' n__'.$val.'">'.$structure_lnk_edit_mode.'
		 	<ul class="sub-menu'.$n.'">';

				$sub_on = 1;
				$sub_on2 = 0;
				$sub_on3 = 0;
				$sub_on4 = 0;

			}

/*********************************************************************/

			elseif ($ebene == 2 && $par > 1) {
				if (!$sub_on) {
					$footer_set .= '
';
					$structure .= '
				<ul>
';
					$structure_edit_mode .= '
				<ul>
';
					$sub_on = 1;
				}
				$footer_set .= ($sub_on2 ? endUL(1) : endUL(0)).($sub_on3 ? endUL(1) : endUL(0)).($sub_on4 ? endUL(1) : endUL(0)).'				<li><a href="'.$xmlpfad.$navID[$val].'" title="'.($nm).'">'.$lock.($nm).'</a>';

				$structure .= ($sub_on2 ? endUL(1) : '').($sub_on3 ? endUL(1) : '').($sub_on4 ? endUL(1) : '').'				<li><a data-clipboard-text="<a:'.$val.':>Link_Name</a>" class="intLink">'.($nm).'</a>';

				$structure_edit_mode .= ($sub_on2 ? endUL(1) : '').($sub_on3 ? endUL(1) : '').($sub_on4 ? endUL(1) : '').'				<li><a data-clipboard-text="<anker:'.$val.':>" class="intLink">'.($nm).'</a>';

				$sub_on2 = 0;
				$sub_on3 = 0;
				$sub_on4 = 0;
			}


/*********************************************************************/


			elseif ($ebene == 3 && $par > 1) {
				if (!$sub_on2) {
					$footer_set .= '
				<ul>
';
					$structure .= '
				<ul>
';
					$structure_edit_mode .= '
				<ul>
';
					$sub_on2 = 1;
				}
				
				$footer_set .= ($sub_on3 ? endUL(1) : endUL(0)).($sub_on4 ? endUL(1) : endUL(0)).'						<li><a href="'.$xmlpfad.$navID[$val].'" title="'.($nm).'">'.$lock.($nm).'</a></li>
';
				$structure .= ($sub_on3 ? endUL(1) : '').($sub_on4 ? endUL(1) : '').'						<li><a data-clipboard-text="<a:'.$val.':>Link_Name</a>" class="intLink">'.($nm).'</a></li>
';
				$structure_edit_mode .= ($sub_on3 ? endUL(1) : '').($sub_on4 ? endUL(1) : '').'						<li><a data-clipboard-text="<anker:'.$val.':>" class="intLink">'.($nm).'</a></li>
';
				$sub_on3 = 0;
				$sub_on4 = 0;
			}

/*********************************************************************/


			elseif ($ebene == 4 && $par > 1) {
				if (!$sub_on3) {
					$footer_set .= '
				<ul>
';
					$structure .= '
				<ul>
';
					$structure_edit_mode .= '
				<ul>
';
					$sub_on3 = 1;
				}
				$footer_set .= ($sub_on4 ? endUL(1) : endUL(0)).'						<li><a href="'.$xmlpfad.$navID[$val].'" title="'.($nm).'">'.$lock.($nm).'</a></li>
';
				$structure .= ($sub_on4 ? endUL(1) : '').'						<li><a data-clipboard-text="<a:'.$val.':>Link_Name</a>" class="intLink">'.($nm).'</a></li>
';
				$structure_edit_mode .= ($sub_on4 ? endUL(1) : '').'						<li><a data-clipboard-text="<anker:'.$val.':>" class="intLink">'.($nm).'</a></li>
';
				$sub_on4 = 0;
			}

/*********************************************************************/


			elseif ($ebene == 5 && $par > 1) {
				if (!$sub_on4) {
					$footer_set .= '
				<ul>
';
					$structure .= '
				<ul>
';
					$structure_edit_mode .= '
				<ul>
';
					$sub_on4 = 1;
				}
				$footer_set .= '						<li><a href="'.$xmlpfad.$navID[$val].'" title="'.($nm).'">'.$lock.($nm).'</a></li>
';
				$structure .= '						<li><a data-clipboard-text="<a:'.$val.':>Link_Name</a>" class="intLink">'.($nm).'</a></li>
';
				$structure_edit_mode .= '						<li><a data-clipboard-text="<anker:'.$val.':>" class="intLink">'.($nm).'</a></li>
';
			}
		}
	}



	if($sub_on3) $footer_set .= '</li>
							</ul>
						</li>
					</ul>
				</li>
';
	else if($sub_on2) $footer_set .= '</li>
					</ul>
				</li>
';
	else if($sub_on) $footer_set .= '</li>
';


	if($sub_on3) $structure .= '</li>
							</ul>
						</li>
					</ul>
				</li>
';
	else if($sub_on2) $structure .= '</li>
					</ul>
				</li>
';
	else if($sub_on) $structure .= '</li>
';

	if($sub_on3) $structure_edit_mode .= '</li>
							</ul>
						</li>
					</ul>
				</li>
';
	else if($sub_on2) $structure_edit_mode .= '</li>
					</ul>
				</li>
';
	else if($sub_on) $structure_edit_mode .= '</li>
';


	$que  	= "SELECT * FROM `morp_cms_nav` WHERE ebene=1 AND sichtbar=1 AND lang=$lan_id AND bereich = 2 ORDER BY `sort`";
	$res 	= safe_query($que);
	$nav_meta = '';

	while ($rw = mysqli_fetch_object($res)) {
		$par	 = $rw->parent;
		$nid	 = $rw->navid;
		$nm	 	 = $rw->name;
		$name	 = eliminiere($nm);

		$nav_meta .= '		<li><a href="'.$xmlpfad.$lan.'/'.$navID[$nid].'" title="'.$name.'"><strong>'.$nm.'</strong></a></li>'."\n\t";
	}




#	$xmlpfad = "/peakom/";
	$footer_set = '
'.$footer_set.'</ul>
\';
?>
';

	$structure = '
'.$structure.$nav_meta.'</ul>
\';
?>
';

	$structure_edit_mode = '
'.$structure_edit_mode.$nav_meta.'</ul>
\';
?>
';


	$footer_set = preg_replace('/<ul class="sub-menu"><\/ul>/', '', $footer_set);
	save_data("../page/footer_".$lan.".inc",$footer_set.'</ul>',"w");
	save_data("../page/structure_".$lan.".inc",$structure.'</ul>',"w");
	save_data("../page/structure_edit_mode_".$lan.".inc",$structure_edit_mode.'</ul>',"w");
	unset($footer_set);
}
// echo "did";
// die();


function endUL($x) {
	return $x ? '
				</ul>
			</li>
' : '</li>
';
}

