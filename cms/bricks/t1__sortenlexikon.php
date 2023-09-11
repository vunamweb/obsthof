<?php

$table 	= 'morp_sortenlexikon';
$tid 		= 'sortenID';
$nameField	= "sorte";
$sortField	= 'reihenfolge';
$targetID = 34;

$sorte = isset($_GET["nid"]) ? $_GET["nid"] : false;
	
	print_r($_GET);

// echo mysqli_num_rows($res);

// $output .= '<div class="container">';


if($sorte) {
	$sql = "SELECT * FROM $table WHERE $tid=$sorte";
	$res = safe_query($sql);
	
	while ($row = mysqli_fetch_object($res)) {
		$name = $row->$nameField;
		$status = $row->status;
		$weitere = $row->weitere;
		$herkunft = $row->herkunft;
		$story = $row->story;
		$beurteilung = $row->beurteilung;
		$img = $row->img;
		
		$output .= '
				<div class="row vertical-align">
					<div class="col-xs-12 col-sm-6 col-md-6 order-2 order-md-1 contentPad">
						<div>
							<h2>Andreas Schneider</h2>
							<h3>ein Apfelweinpionier</h3>
							<p>Nur wenige Jahre nach Gründung des „Obsthof am Steinberg“ im Jahr 1965 durch Albert und Waltraud Schneider erblickt Andreas Schneider das Licht der Welt. Der elterliche Apfelhain in Frankfurt/Nieder-Erlenbach ist für ihn Spielplatz und Lernfeld zugleich.</p>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 order-1 order-md-2 imgColR btn-abs">
						<img src="https://www.morpheus-cms.de/shop/cms/images/userfiles/image/Andreas_im_Haus.jpg" alt=" Andreas_im_Haus.jpg" class="img-fluid"><div class="btn-container"></div>
					</div>	
				</div>';
	}
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
		
		$url = getUrl($targetID).eliminiere($name).'+'.$row->$tid.'/';
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