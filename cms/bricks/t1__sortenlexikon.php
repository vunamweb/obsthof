<?php

$table 	= 'morp_sortenlexikon';
$tid 		= 'sortenID';
$nameField	= "sorte";
$sortField	= 'reihenfolge';
$targetID = 34;

$sorte = isset($_GET["nid"]) ? $_GET["nid"] : false;
	
// print_r($_GET);

// echo mysqli_num_rows($res);

// $output .= '<div class="container">';


if($sorte) {
	$sql = "SELECT * FROM $table WHERE $tid=$sorte";
	$res = safe_query($sql);
	
	$row = mysqli_fetch_object($res);
	$name = $row->$nameField;
	$status = $row->status;
	$weitere = $row->weitere;
	$herkunft = $row->herkunft;
	$story = $row->story;
	$beurteilung = $row->beurteilung;
	$img = $row->img;		
	
	$sql = "SELECT * FROM $table WHERE 1 ORDER BY $sortField";
	$res = safe_query($sql);
	$liste = '';
	
	while ($row = mysqli_fetch_object($res)) {
		$linkname = $row->$nameField;	
		$url = getUrl($targetID).eliminiere($linkname).'--'.$row->$tid.'/';
		$liste .= '<a href="'.$url.'" class="small btn btn-info'.($sorte==$row->$tid ? ' active' : '').'">'.$linkname.'</a>';
	}	

	$output = '
		<div class="row vertical-align mb4">
			<div class="col-xs-12 col-sm-6 col-md-6 order-2 order-md-1 contentPad">
				<div>
					<h1>'.$name.'</h1>
					<p><b>'.$status.' </b></p>
					<p>Weitere Namen:<br/><b>'.nl2br($weitere).'</b></p>
					'.($herkunft ? '<p>Herkunft:<br/><b>'.nl2br($herkunft).'</b></p>' : '').'
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 order-1 order-md-2 imgColR btn-abs">
				<img src="'.$dir.'images/sorten/'.urlencode($img).'" alt="'.$name.'" class="img-fluid">
			</div>	
		</div>
		
		
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 order-2 order-md-1 contentPad">
				<div>
					<h3>Geschichte:</h3>
					<p>'.nl2br($story).'</p>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 order-1 order-md-2 imgColR btn-abs">
				<div>
					'.($beurteilung ? '<h3>Beurteilung:</h3>
					<p>'.nl2br($beurteilung).'</p>	
					<hr>' : '').'
					'.$liste.'						
				</div>
			</div>	
		</div>

		<div class="col-xs-12 text-center mt6 mb4">
			<a href="'.getUrl($targetID).'" class="btn btn-info">zurück zum Apfel Sortenlexikon</a>
		</div>

';
	
} 

else {
	$sql = "SELECT * FROM $table WHERE 1 ORDER BY $sortField";
	$res = safe_query($sql);
	
	while ($row = mysqli_fetch_object($res)) {
		$name = $row->$nameField;
		$status = $row->status;
		$weitere = $row->weitere;
		$herkunft = $row->herkunft;
		$story = $row->story;
		$beurteilung = $row->beurteilung;
		$img = urlencode($row->img);
		
		$url = getUrl($targetID).eliminiere($name).'--'.$row->$tid.'/';
		$output .= '
		<div class="row sortenliste">
			<div class="col-4 col-md-4 col-lg-3">
				<a href="'.$url.'"><img src="./cms/images/sorten/'.$img.'" alt="'.$name.'" class="img-fluid" /></a>
			</div>
			<div class="col-8 col-md-8 col-lg-9">
				<h2><a href="'.$url.'">'.$name.'</a></h2>
				<p>'.nl2br($herkunft).'</p>
				<a href="'.$url.'" class="btn btn-info">Mehr zu '.$name.'</a>
				<hr>
			</div>
		</div>';
	}
}


// $output .= '</div>';

$morp = 'Sortenlexikon / ';