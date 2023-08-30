<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bj&ouml;rn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

global $morpheus;

$multilangXML = $morpheus["multilang"];

function saveData($datei,$data,$art)  {
	$write = fopen($datei,$art);	   		//oeffne datei zum schreiben der daten
	if ($write!=0) fwrite($write, $data);				   //write data
	fclose($write);
}

$url = $morpheus["url"];

$sitemap = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">
<!-- Created by www.pixel-dusche.de - CMS morpheus -->
<!-- Last update of sitemap '.date("Y-m-d").' -->
';

$site_start = '<url>
<loc>';

$site_end = '</loc>
<lastmod>#dat#</lastmod>
<changefreq>weekly</changefreq>
<priority>#prior#</priority>
</url>
';

foreach ($morpheus["lan_arr"] as $key=>$lang) {
	$homeID = $morpheus["home_ID"][$lang];
	include("../nogo/navarray_".$lang.".php");

	$arrXML		= array();
	$arrSmap	= array();
	$arrDate	= array();
	$arrPrio	= array();
	$navarr_ID	= array();
	$smap 		= $sitemap;

	$tld 		= "com";

	$prio = 0.5;

	for ($i = 1; $i <= 5; $i++) {
		$sqlXML  = "SELECT * FROM `morp_cms_nav` WHERE ebene=$i AND lang=$key";
		$resXML = safe_query($sqlXML);

		while ($rowXML = mysqli_fetch_object($resXML)) {
			$idXML 	= $rowXML->navid;
			$nmXML 	= $rowXML->name;
			$paXML 	= $rowXML->parent;
			$vi 	= $rowXML->sichtbar;
			$nmXML 	= strtolower(eliminiere($nmXML));
			$setlink = $rowXML->setlink;
			$dat	= $rowXML->updated_dat;

			$ebeneXML 		= $rowXML->ebene;
			$bereichXML 	= $rowXML->bereich;
			$lnk		= $rowXML->lnk;

			if($bereichXML == 2) $prio = 0.4;
			elseif($ebeneXML > 2) $prio = 0.5;
			elseif($ebeneXML == 2) $prio = 0.7;
			else $prio = 0.9;

			// if($lnk) {}
			if ($paXML) {
				$path 		= $arrXML[$paXML].$setlink."/";
				$arrXML[$idXML] 	= $path;
				$arrDate[$idXML] 	= $dat;
				$arrPrio[$idXML] 	= $prio;
				if ($vi) $arrSmap[$idXML] = $path;
			}
			else {
#				if ($homeID != $idXML) {
					$arrXML[$idXML] 	= $setlink."/";
					$arrDate[$idXML] 	= $dat;
					$arrPrio[$idXML] 	= $prio;
					if ($vi) $arrSmap[$idXML] = $setlink."/";
/*				}
				else {
					$arr[$idXML] 	= "";
					if ($vi) $arrSmap[$idXML] = "";
				}
*/			}
		}
	}

	foreach($arrXML as $idXML=>$path) {
		$navarr_ID[] = $idXML.'=>"'.$path.'"';
	}
	foreach($arrSmap as $idXML=>$path) {
		$addDatum = str_replace(array("#dat#", "#prior#"),array($arrDate[$idXML],$arrPrio[$idXML]), $site_end);

		if ($homeID == $idXML && $lang == "de") $smap .= $site_start.$url.$addDatum;
		else $smap .= $site_start.$url.($multilangXML ? $lang.'/' : '').$path.$addDatum;
	}

	#print_r($n_arr);
	# sort($arr);

// falls drop down menu gewuenscht
/*
	$sel = "<select name=\"search\" style=\"width:150px;\" class=\"qf\" onchange='qb(this.options[this.selectedIndex].value)'><option value='.'>Index</option>\n";
	foreach ($arr as $val) {
		$val = explode("|", $val);
		$nmXML  = repl('\+', " ", $val[0]);
		$na  = $val[1];

		$sel .= "<option value='$na'>$nmXML</option>\n";
	}
	$sel .= "</select>";
*/
	# saveData("../quickbar".$lang.".php",$sel,"w");

	if ($lang == "de") 	saveData("../sitemap.xml",$smap."</urlset>","w");
	else				saveData("../sitemap_".$lang.".xml",$smap."</urlset>","w");
#echo $smap;

	saveData("../nogo/navID_".$lang.".inc", '<?php $navID = array('.implode(", ", $navarr_ID).'); ?>', "w");
}
#die();
?>