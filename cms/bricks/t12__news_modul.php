<?php
global $hn, $nid, $ns, $dir, $lan, $navID, $cid, $morpheus;

$nid = $_GET["nid"];
$ns  = $_GET["ns"];

if ($nid) {
	$news 	= "";
	$query 	= "SELECT * FROM morp_cms_news n, morp_cms_news_group ng WHERE nid=$nid AND n.ngid=ng.ngid";
	$result = safe_query($query);
	$row  	= mysqli_fetch_object($result);
	$tlnk 	= $lnk_arr[$row->ngid];
	$nau  	= $row->nautor;
	$ngid  	= $row->ngid;
	$ns		= $_SESSION["nsite"];
	$lnk_t 	= get_text(2);
	$bck_l 	= $navID[$cid];
	
	$news .= '
	<div class="row">
		<div class="col-12 col-md-4">
			<h2 class="mexcellent">'.euro_dat($row->nerstellt).'</h2>
		</div>
		<div class="col-12 col-md-8 news-content">
';

	$news .= '<h1>' .$row->ntitle .'</h1>';
	$news .= "<p>".nl2br(stripslashes($row->ntext))."</p>\n";
	
	$nlink = $row->nlink;
	if ($nlink) {
		if (isin("^http", $nlink)) {	$news .= "<p><a href=\"".$nlink ."\" title=\"".$nlink ."\" target=\"_blank\">".$nlink ."</a></p>";	}
		else {
 			$news .= "<p><a href=\"$dir".$nlink ."\" title=\"". $navID[$nlink] ."\">".get_text(15)."</a></p>";
		}
	}
	
	# # # # # # # # # # # # 
	# pdf oder anderes dokument doc, xls, etc. wird angezeigt
	if ($pid = $row->pid) {
		$query 	= "SELECT * FROM morp_cms_pdf where pid=$pid";
		$result = safe_query($query);
		$row 	= mysqli_fetch_object($result);		
		$typ 	= explode(".", $row->pname);
		$c	 	= (count($typ)-1);
		$img 	= $typ[$c]."_s.gif";
			
		#$go 	= $dir."upload.php?dfile=".$row->pname;
		$go    = $dir."pdf/".$row->pname;
		$news .= '<div class="pressetitel"><a class="ta" href="'.$go.'" target="_blank" title="' .$row->pdesc .'">'.$row->pdesc.'</a></div>';
	}
	# _pdfs 
	# # # # # # # # # # # # 
	

	$output = $news.'		
		</div>
		
		<div class="col-12">
			<a href="'.$dir.$bck_l.'" class="btn btn-info">zum News Archiv</a>
		</div>
	</div>
';

}

else {
	$news = '';
	$query 	= "SELECT * FROM morp_cms_news n, morp_cms_news_group ng WHERE n.ngid=ng.ngid AND n.ngid=$text ORDER BY nerstellt DESC, nid DESC";
	$result = safe_query($query);
	$x 		= mysqli_num_rows($result);
	$n 		= 0;
	while ($row = mysqli_fetch_object($result))	{	
		// $dat 	= explode("-", $row->nerstellt);
		// $link 	= "<a href=\"".$dir.$navID[27]."" .eliminiere(strip_tags($row->ntitle)).'+'.$row->nid.'/" class="lnk">';
		
		$nlink = $row->nlink;
		$target = '';
		$url = '';
		if(isin('https',$nlink)) { $url = $row->nlink; $target = ' target="_blank"'; }
		else if($row->nlink) $url = getUrl($row->nlink);
		
		$pdf = '';
		if ($pid = $row->pid) {
			$sql 	= "SELECT * FROM morp_cms_pdf WHERE pid=$pid";
			$rs 	= safe_query($sql);
			$rw 	= mysqli_fetch_object($rs);		
			$go    = $dir."pdf/".$rw->pname;
			$pdf = '<br><a class="btn btn-info" href="'.$go.'" target="_blank" title="' .$rw->pdesc .'">'.($rw->pdesc ? $rw->pdesc : 'PDF').'</a></p>';
		}
		
		$n++;
		$news  .= '
		  <div class="swiper-slide news-slide text-center">
			<div class="news-inner">
				<h3>'.($row->ntitle) .'<br />
				<span>'.($row->nsubtitle) .'</span></h3>		
				'.($row->ntext).'
				'.$pdf.'
				'.($url ? '<a href="'.$url.'"'.$target.'>Weitere Informationen ></a>' : '').'
			</div>
		  </div>';
		
	}
	
		// <h3>'.nl2br($row->nsubtitle) .'</h3>
	$output .= $news;
}
	
$morp = ' News Modul / ';