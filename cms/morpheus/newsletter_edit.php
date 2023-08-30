<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
# edit 27.11.2006                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

session_start();
#$box = 1;
$newsletter_in = 'in';
include("cms_include.inc");

// print_r($_REQUEST);

global $arr_form, $vorschau, $gruppen_arr, $bgcol1, $bgcol2;

$bgcol1 = '999';
$bgcol2 = '9d9d9c';

// NICHT VERAENDERN ///////////////////////////////////////////////////////////////////
$edit 	= $_REQUEST["edit"];
$liste 	= $_REQUEST["liste"];
$dupl 	= $_REQUEST["dupl"];
$delimg = $_REQUEST["delimg"];
$delpdf = $_REQUEST["delpdf"];
$delcsv = $_REQUEST["delcsv"];
$delnm  = $_REQUEST["delnm"];
$neu	= $_REQUEST["neu"];
$save	= $_REQUEST["save"];
$del	= $_REQUEST["del"];
$delete	= $_REQUEST["delete"];
$deletecsv	= $_REQUEST["deletecsv"];
$gruppenid	= $_REQUEST["gruppenid"];
$sortid  = $_REQUEST["sortid"];
$sort 	 = $_REQUEST["sort"];
$setimg	 = $_REQUEST["setimg"];
$whichimg = $_REQUEST["imgid"];

$webvis = $_REQUEST["vis"];
$onoff = $_REQUEST["onoff"];

//$id		= $_REQUEST["id"];
///////////////////////////////////////////////////////////////////////////////////////

include("_tinymce.php");

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

$db = "morp_newsletter_cont";
$id = "nlcid";
$sortfield = 'nlsort';

//// EDIT_SKRIPT
$um_wen_gehts 	= "module";
$titel			= "Edit Module";
$csv = 0;
///////////////////////////////////////////////////////////////////////////////////////

#if($edit || $neu) $vorschau = '';
 $vorschau = '<p><br/> <a href="../preview.php?nlid='.$liste.'" rel="gb_page_center[640]" target="_blank" class="btn btn-info"><i class="fa fa-desktop large"></i></a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<a href="../testmail.php?nlid='.$liste.'" rel="gb_page_center[340]" target="_blank"  title="send mail" class="btn btn-warning"><i class="fa fa-send "></i></a></p>';

$new = '<p><a href="?neu=1&liste='.$liste.'">&raquo; NEUES Modul anlegen</a></p>';

setArray($db, $edit);

if($webvis) {
	$sql = "UPDATE $db set webvis=$onoff WHERE nlcid=$webvis";
	safe_query($sql);
}

if($setimg && $whichimg && !$_POST) {
	$sql = "SELECT imgname FROM morp_newsletter_image WHERE imgid=$setimg";
	$rs  = safe_query($sql);
	$rw  = mysqli_fetch_object($rs);
	$newimg = $rw->imgname;
	$sql = "UPDATE morp_newsletter_cont set $whichimg='$newimg' WHERE nlcid=$edit";
	safe_query($sql);
	copy("../img/".$newimg, "../img/".$liste."/".$newimg);
}


function setArray($db, $edit) {
	global $arr_form;

	if($edit) {
		$sql = "SELECT nlart FROM $db WHERE nlcid=$edit";
		$res = safe_query($sql);
		$row = mysqli_fetch_object($res);
		$thisart = $row->nlart;
	}
	else $thisart = 1;

	$sql = "SELECT * FROM morp_newsletter_template WHERE nltid=".$thisart;
	$res = safe_query($sql);
	$rw  = mysqli_fetch_object($res);

	$arr_form = array(
		// array("nlsort", "Position", '<input type="text" value="#v#" name="#n#" class=" unloadmsg" style="width:30px;">'),
		array("nlname", "Individuelle Bezeichnung des Moduls", '<input type="Text" value="#v#" class=" unloadmsg" name="#n#" style="#s#">'),
		array("nlart", "Template", 'banner', 'image', 'imgname', 1, 'gid'),
		// array("nlrubrik", "Rubrik", 'rubrik', 'image', 'imgname', 1, 'gid'),
	);

	$sql = '';

	if($rw->img1) $arr_form[] = array("nlimg1", "Bild", 'sel', 'image', 'imgname', 6, 'gid');
	if($rw->texte3) $arr_form[] = array("text3", "Link Text ODER unformatierte Headline", '<input type="Text" value="#v#" class=" unloadmsg" name="#n#" style="#s#">');
	if($rw->link) $arr_form[] = array("nllink", "Link", '<input type="Text" value="#v#" class=" unloadmsg" name="#n#" style="#s#">');
	if($rw->texte) $arr_form[] = array("text", "Text", '<textarea class="summernote" name="#n#" style="#s# width:100% !important;">#v#</textarea>', 'text');
	if($rw->textweb) $arr_form[] = array("textweb", "Web Text", '<textarea class="summernote" name="#n#" style="#s# ; width:100% !important;">#v#</textarea>', 'text');


	if($rw->cta) $arr_form[] = array("nlcta", "CTA", 'sel', 'image', 'imgname', 6, 'gid');

	if($rw->cta2) $arr_form[] = array("nlcta2", "CTA 2", 'sel', 'image', 'imgname', 6, 'gid');
	if($rw->link2) $arr_form[] = array("nllink2", "Link 2", '<input type="Text" value="#v#" name="#n#" style="#s#">');

	if($rw->img2) $arr_form[] = array("nlimg2", "Bild 2", 'sel', 'image', 'imgname', 6, 'gid');
	if($rw->img3) $arr_form[] = array("nlimg3", "PDF", 'sel', 'image', 'imgname', 6, 'gid');

	if($rw->sign) $arr_form[] = array("nlsign", "Signatur", 'sel', 'image', 'imgname', 6, 'gid');
	if($rw->texte2) $arr_form[] = array("text2", "Link Text", '<input type="Text" value="#v#" name="#n#" style="#s#">');

	if($rw->social) {
		$arr_form[] = array("soc1", '<img src="../img/twitter.png" /> Twitter', '<input type="Text" class="unloadmsg" value="#v#" name="#n#" style="#s#">');
		$arr_form[] = array("soc2", '<img src="../img/fb.png" /> facebook', '<input type="Text" class="unloadmsg" value="#v#" name="#n#" style="#s#">');
		$arr_form[] = array("soc3", '<img src="../img/yt.png" /> YouTube', '<input type="Text" class="unloadmsg" value="#v#" name="#n#" style="#s#">');
		$arr_form[] = array("soc4", '<img src="../img/linkedin.png" /> Linkedin', '<input type="Text" class="unloadmsg" value="#v#" name="#n#" style="#s#">');
		$arr_form[] = array("soc5", '<img src="../img/insta.png" /> Instagram', '<input type="Text" class="unloadmsg" value="#v#" name="#n#" style="#s#">');
	}

	if($rw->pdf1) $arr_form[] = array("pdf1", "PDF 1", 'sel', 'pdf');
	if($rw->pdf2) $arr_form[] = array("pdf2", "PDF 2", 'sel', 'pdf');
	if($rw->pdf3) $arr_form[] = array("pdf3", "PDF 3", 'sel', 'pdf');


	for($i=11; $i<=26; $i++) {
		$tmp = "img".$i;
		if($rw->$tmp) {
			$arr_form[] = array($tmp, "Bild #$tmp# ", 'sel', 'image', 'imgname', 6, 'gid');
			$arr_form[] = array("bu".$i, "Bildbeschreibung", '<textarea class="noformat unloadmsg" name="#n#" style="#s# ; height:50px;">#v#</textarea>', 'text');
		}
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function liste($liste) {
	global $gruppen_arr;
	//// EDIT_SKRIPT
	$db = "morp_newsletter_cont";
	$id = "nlcid";
#	$ord = "f1sort ASC, nlsort ASC";
	$ord = " nlsort ASC";
	$anz = "nlname";
	$anz2 = "text";
	////////////////////

	$tabelle_start = '
		<span><a href="?repair=1&liste='.$liste.'"><i class="fa fa-refresh"></i> Sortierung aktualisieren</a></span><br/>

		<table class="autocol p20 newsletter">
			<tr style="background:#e2e2e2;">
				<td width="20"></td>
				<td width="20"></td>
				<td width="20"></td>
				<td>name</td>
				<td>template</td>
				<td></td>
				<td></td>
			</tr>
';
	$tabelle_ende = '</table><p>&nbsp;</p>';

	$echo = '';

	$sql = "SELECT * FROM $db t1 LEFT JOIN morp_newsletter_filter_1 t2 ON t1.nlrubrik=t2.f1id WHERE t1.nlid=$liste ORDER BY ".$ord."";
	$res = safe_query($sql);

	$n = mysqli_num_rows($res);
	if($n < 1) return;
	$x=0;

	while ($row = mysqli_fetch_object($res)) {
		$edit = $row->$id;
		$vis = $row->webvis;
		$x++;
		$rub = '';

		// if($row->nlrubrik) {
			$sq = "SELECT * FROM  `morp_newsletter_template` WHERE nltid=".$row->nlart."";
			$rs = safe_query($sq);
			$rw = mysqli_fetch_object($rs);
			$nltname = $rw->nltname;
		// }
		
		$tplID = substr($nltname, 0,1);
		$echo .= '<tr'.($tplID==5 ? ' style="border-bottom:solid 2px blue;"' : '').'>
			<td  class=" c2">'.($x > 1 ? '<a href="?sort=up&sortid='.$row->nlsort.'&liste='.$liste.'"><i class="fa fa-chevron-up small"></i></a>' : '').'</td>
			<td  class=" c2">'.($x < $n ? '<a href="?sort=down&sortid='.$row->nlsort.'&liste='.$liste.'"><i class="fa fa-chevron-down small"></i></a>' : '').'</td>
			<td ><p><a href="?edit='.$edit.'&liste='.$liste.'"><strong>'.$row->nlsort.'</strong></a></p></td>
			<td ><p><a href="?edit='.$edit.'&liste='.$liste.'">'.$row->$anz.'</a></p></td>
			<td ><p><a href="?edit='.$edit.'&liste='.$liste.'">'.strip_tags(substr($row->$anz2,0,100)).'</a></p></td>
<!--
			<td ><p><a href="?liste='.$liste.'&vis='.$edit.'&onoff='.($vis ? '0' : 1).'">'.($vis ? '<i class="red fa fa-facebook-square"></i>' : '<i class="gray fa fa-minus-square"></i>').'</a></p></td>
			<td ><p><a href="?edit='.$edit.'&liste='.$liste.'">'.$row->f1name.'</a></p></td>
-->
			<td ><p><a href="?edit='.$edit.'&liste='.$liste.'">'.$nltname.'</a></p></td>
<!--			<td ><img src="../mthumb.php?w=200&amp;src=images/'.$row->nltpreview.'" alt="" /></td>-->
			<td valign="middle"><a href="?del='.$edit.'&liste='.$liste.'"><i class="fa fa-trash-o"></i></a></td>
		</tr>';
	}

	$echo = $tabelle_start.$echo.$tabelle_ende;

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function getBrand($nlid) {
	/***** zu welcher Marke gehört Mailing ******/
	$sql = "SELECT nlart FROM morp_newsletter WHERE nlid=".$nlid."";
	$rs = safe_query($sql);
	$rw = mysqli_fetch_object($rs);
	return $rw->nlart;
	/**************************************/
}

function edit($edit,$liste) {
	global $arr_form, $vorschau, $csv;

	//// EDIT_SKRIPT
	$db = "morp_newsletter_cont";
	$id = "nlcid";
	/////////////////////

	$sql = "SELECT * FROM $db WHERE $id=".$edit."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);

	$nlart = getBrand($liste);

	$insertsubmit = 1;

	$echo .= '
		<input type="Hidden" name="neu" value="'.$neu.'">
		<input type="Hidden" name="edit" value="'.$edit.'">
		<input type="Hidden" name="liste" value="'.$liste.'">
		<input type="Hidden" name="save" value="1">

	<table class="autocol ">';

	$echo .= '<tr>
		<td></td>
		<td><input type="submit" name="speichern" value="speichern" class="save"></td>
	</tr>
';

	foreach($arr_form as $arr) {
		$get = $arr[0];
		if ($arr[3] == "select") {
			$arr_sel = $arr[4];
			$echo .= '<tr><td>'.$arr[1].'</td><td><select name="'.$get.'">';
			$i = 0;
			foreach($arr_sel as $val) {
				$echo .= '<option value="'.$i.'" '. ($row->$get == $i ? ' selected' : '') .'>'.$val.'</option>';
				$i++;
			}
			$echo .= '</select></td></tr>';
			$csv = $row->$get;
		}
		elseif ($arr[3] == "checkbox") {
			$echo .= '<tr><td>'.$arr[1].'</td><td><input type="checkbox" value="1" name="'.$get.'"'.($row->$get ? ' checked':'').'>';
			$echo .= '</td></tr>';
		}
		elseif ($arr[3] == "pdf") {
			if($insertsubmit) {
				$echo .= '	<tr>
		<td><span style="color:#ff0000;">Vor PDF Upload bitte Änderungen speichern</span></td>
		<td><input type="submit" name="speichern" value="speichern"></td>
	</tr>
';
				$insertsubmit=0;
			}
			$echo .= '<tr><td width="160">'.$arr[1].'</td><td><input type=hidden name='.$get.' value="' .$row->$get.'" style="width:500px"><a href="image_folder_upload.php?cedit='.$edit.'&pdf='.$get.'">';

			if ($row->$get) $echo .=  'neues PDF</a> &nbsp; &nbsp; &nbsp; &nbsp; <a href="?delpdf='.$get.'&edit='.$edit.'&delnm='.$row->$get.'"><img src="images/delete.gif" width="9" height="10" alt="PDF löschen" border="0" hspace="6"></a> &nbsp; &nbsp; &nbsp; &nbsp; <a href="../nldownloads/'.$row->$get.'" target="_blank"><img src="images/an.gif" width="9" height="10" alt="PDF anzeigen" border="0" hspace="6"></a>';
			else $echo .=  '<i class="fa fa-cloud-upload"></i> bitte wählen</a>';

			$echo .= '</td></tr>';
		}
		elseif ($arr[3] == "csv") {
			$echo .= '<tr><td width="160">'.$arr[1].'</td><td><input type=hidden name='.$get.' value="' .$row->$get.'" style="width:500px"><a href="image_folder_upload.php?cedit='.$edit.'&csv='.$get.'">';

			if ($row->$get) {
				$echo .=  'neuer Verteiler</a> &nbsp; &nbsp; &nbsp; &nbsp; <a href="?delcsv='.$get.'&edit='.$edit.'&delnm='.$row->$get.'"><img src="images/delete.gif" width="9" height="10" alt="XLSX löschen" border="0" hspace="6"></a> &nbsp; &nbsp; &nbsp; &nbsp; <a href="newsletter_xlsx.php?vt='.$row->$get.'&edit='.$edit.'"><img src="images/an.gif" width="9" height="10" alt="XLSX anzeigen" border="0" hspace="6"></a>';

				echo $vorschau = preg_replace('/#vt#/', $row->$get, $vorschau);
				//$csv = 1;
			}
			else {
				$echo .=  ''.$arr[1].': <i class="fa fa-cloud-upload"></i> bitte wählen</a>';
				echo $vorschau = preg_replace('/#vt#/', '', $vorschau);
			}

			$echo .= '</td></tr>';
		}
		elseif ($arr[2] == "banner") {
			$thimg = $row->$get;
			$show = '';

			$sql  = "SELECT * FROM morp_newsletter_template WHERE nltart=1 ORDER BY nltname";
			$rs = safe_query($sql);
			$echo .= '<tr>
				<td>'.$arr[1].'</td>
				<td>
					<select name="'.$get.'" onchange="this.form.submit();">';
			while ($rw = mysqli_fetch_object($rs)) {
				$imgid = $rw->nltid;
				$imgnm = $rw->nltname;

				$echo .= '<option value="'.$imgid.'"'.($imgid == $thimg ? ' selected' : '').'>'.$imgnm.'</option>';
				if($imgid == $thimg) $show = $rw->imgname;
			}
			$echo .= '</select> &nbsp; <!--<a href="newsletter_template_preview2.php?nlart='.$nlart.'" rel="gb_page_center[620]" ><i class="fa fa-info-circle" style="font-size:18px;"></i></a>--> <br/>
				</td>
			</tr>
			';

			if($show) {
				$echo .= '
			<tr>
				<td colspan=2>
					<img src="../newsletter/'.$show.'" /><br/>&nbsp;<br/>
				</td>
			</tr>
';

			}



			$echo .= '</td></tr>';

		}
		elseif ($arr[2] == "rubrik") {
			$thimg = $row->$get;
			$show = '';

			$sql  = "SELECT * FROM morp_newsletter_filter_1 WHERE 1 ORDER BY f1sort";
			$rs = safe_query($sql);
			$echo .= '<tr>
				<td>'.$arr[1].'</td>
				<td>
					<select name="'.$get.'" onchange="this.form.submit();">';
			while ($rw = mysqli_fetch_object($rs)) {
				$imgid = $rw->f1id;
				$imgnm = $rw->f1name;

				$echo .= '<option value="'.$imgid.'"'.($imgid == $thimg ? ' selected' : '').'>'.$imgnm.'</option>';
				if($imgid == $thimg) $show = $rw->imgname;
			}

			$echo .= '</select> <br/>
				</td>
			</tr>
			';

			$echo .= '</td></tr>';

		}
		elseif ($arr[2] == "sel") {
			// $echo .= '<tr><td>'.$arr[1].'</td><td><select name="'.$get.'">'.pulldown ($row->$get, $arr[3], $arr[4], $get, $arr[5], $arr[6]).'</select></td></tr>';
			// if ($get == "imgid") $image = pfad ($get, $arr[3], $arr[4], $row->$get);

			$echo .= '<tr><td colspan="2"><input type=hidden name='.$get.' value="' .$row->$get.'" style="width:500px"><a href="image_folder_upload.php?nlcid='.$edit.'&liste='.$liste.'&tn='.$morpheus["img_size_news_tn"].'&imgid='.$get.'&nl=1"><br/>';

			if ($row->$get) $echo .=  $arr[1].'<br/>'.($arr[1] == "PDF" ? $row->$get  : ' <img src="../img/'.$row->$get.'">').'</a> &nbsp; &nbsp; <a href="?delimg='.$get.'&edit='.$edit.'&liste='.$liste.'"><img src="images/delete.gif" width="9" height="10" alt="Bild löschen" border="0" hspace="6"></a>';
			else $echo .=  $arr[1].': <i class="fa fa-cloud-upload"></i> upload file</a>';

			$echo .= '</td></tr>';

		}
		elseif ($arr[3] == "text") {
			$echo .= '<tr>
				<td colspan="2">Ein manueller Link sollte die folgende Struktur haben, um vom Tracking erfasst zu werden:<br/>
					Beispiel: <br>https://www.adler-pollak-institut.de/<br/>
						<span style="color:red">https://www.adler-pollak-institut.de/adler.php?uid=#uid#&nl=#nlid#&il=</span><b>https://www.adler-pollak-institut.de/</b>
					'. str_replace(
					array("#v#", "#n#", "#s#", "#db#", '#e#', '#id#', '#s1#', '#s0#'),
					array($row->$get, $get, 'width:750px;height:500px;', $db2, $edit, $id2, $sel1, $sel2),
			$arr[2]).'</td>
			</tr>';
		}
		elseif ($arr[3] == "dat") {
			$echo .= '<tr>
				<td>'.$arr[1].'</td>
				<td>'. str_replace(
					array("#v#", "#n#", "#s#", "#db#", '#e#', '#id#', '#s1#', '#s0#'),
					array(euro_dat($row->$get), $get, '', $db2, $edit, $id2, $sel1, $sel2),
			$arr[2]).'</td>
			</tr>';
		}
		else {
			$value = $row->$get ? $row->$get : $arr[3];
			$echo .= '<tr>
		<td>'.$arr[1].':</td>
		<td>'. str_replace(
					array("#v#", "#n#", "#s#", "#db#", '#e#', '#id#', '#s1#', '#s0#'),
					array($value, $get, 'width:400px;', $db2, $edit, $id2, $sel1, $sel2),
			$arr[2]).'</td>
	</tr>';
		}
	}

	if ($image) $echo .= '<tr><td></td><td><img src="../img/' .$image .'" /></td></tr>';

	$echo .= '
	<tr>
		<td></td>
		<td><input type="submit" name="speichern" value="speichern" class="save"></td>
	</tr>
</table>';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function neu($liste) {
	global $arr_form, $bgcol1, $bgcol2;

	$db = "morp_newsletter_cont";
	$id = "nlcid";
	$ord = "nlsort ASC";
	$anz = "nlname";
	////////////////////
	$sql = "SELECT $id FROM $db WHERE nlid=$liste";
	$res = safe_query($sql);
	$n = mysqli_num_rows($res);
	$n++;

	$x = 0;

	$echo .= '<input type="Hidden" name="neu" value="1"><input type="Hidden" name="save" value="1"><input type="Hidden" name="liste" value="'.$liste.'">
<input type="text" name="nlsort" value="'.$n.'">
	<table class="autocol p20">';

	foreach($arr_form as $arr) {
		$get = $arr[0];
		if ($x <= 1) {
			if($get == "nlsort") $echo .= '<tr>
			<td>'.$arr[1].':</td>
			<td>'. str_replace(array("#v#", "#n#", "#s#"), array($n, $get, 'width:100%;'), $arr[2]).'</td>
		</tr>';

			else $echo .= '<tr>
			<td>'.$arr[1].':</td>
			<td>'. str_replace(array("#v#", "#n#", "#s#"), array('', $get, 'width:100%;'), $arr[2]).'</td>
		</tr>';
		}

		$x++;
	}

	$echo .= '<tr>
		<td></td>
		<td><input type="submit" name="speichern" value="speichern"></td>
	</tr>
</table>';


	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function verteiler() {
	//// EDIT_SKRIPT
	$db = "morp_newsletter_vt";
	$id = "vid";
	$ord = "vid";
	$anz = "email";
	$anz2 = "firma";
	$anz3 = "nlcid";
	////////////////////

	$sql = "SELECT * FROM $db WHERE versand<1";
	$res = safe_query($sql);

	$echo .= "<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>Noch zu versendende Mails: <b>".mysqli_num_rows($res)."</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href='newsletter_vt_pruef.php'>&raquo; Details</a>";


	return $echo;
}

function gruppen_pd($gruppenid) {
	global $gruppen_arr;

	$pd = '<select name="gruppenid" onchange="document.verwaltung.submit();">';

	foreach($gruppen_arr as $key=>$val) {
		$pd .= '<option value="'.$key.'"'.($key==$gruppenid ? ' selected' : '').'>'.($val ? $val : 'Alle').'</option>';
	}
	return $pd .= '</select>';
}
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


echo '<div id=vorschau>
	'.(!$edit ? '<a href="newsletter.php?">&laquo; zurück zur Newsletter Übersicht</a>' : '').' &nbsp; <!--'.$logo.'--><br/>'.$vorschau.'

		'. ($edit || $neu ? '<p><a href="?liste='.$liste.'">&laquo; zurück zu den Modulen</a></p>' : '') .'
	<form action="" onsubmit="" name="verwaltung" method="'. ($edit || $neu ? 'post' : 'get').'">
';


/////////////////////////////////////////////////////////////////////////////////////////////////////

if ($save) {
	global $arr_form;
	$sql = '';
	foreach($arr_form as $arr) {
		$tmp = $arr[0];
		$art = $arr[3];
		$no = $arr[4];
		$val = $_POST[$tmp];

		if ($no == "nosave") {}
		elseif ($art == "dat") $sql .= $tmp. "='" .us_dat($val). "', ";
		else $sql .= $tmp. "='" .addslashes($val). "', ";
	}

	$sql = substr($sql, 0, -2);

	if ($neu) {
		$pos = $_POST["nlsort"];
		$sql  = "INSERT $db set $sql , nlid=$liste ,  nlsort=$pos";
		//die();
		$res  = safe_query($sql);
		$edit = mysqli_insert_id($mylink);
		unset($neu);
	}
	else {
		$sql = "UPDATE $db set $sql WHERE $id=$edit";
		$res = safe_query($sql);
	}

	$saveart = $_POST['nlart'];
	if($thisart != $saveart) setArray($db, $edit);

	// echo $sql;
}
elseif ($sort) {
	if ($sort == "up") $s2 = $sortid - 1;
	else $s2 = $sortid + 1;

	$sort_    = array($sortid, $s2);
	$sort_new = array($s2, $sortid);
	$sort_arr = array();

	for($i=0; $i<=1; $i++) {
		$query  = "SELECT $id FROM $db WHERE nlid=$liste AND $sortfield=$sort_[$i]";
		$result = safe_query($query);
		$row = mysqli_fetch_object($result);
		$sort_arr[] = $row->$id;
	}

	for($i=0; $i<=1; $i++) {
		$query  = "UPDATE $db SET $sortfield=$sort_new[$i] WHERE $id=$sort_arr[$i]";
		safe_query($query);
	}
}
elseif ($_REQUEST["repair"]) {
	$arr 		= array();
	$xx 		= 0;

	$sql  		= "SELECT * FROM $db WHERE nlid=$liste ORDER BY $sortfield";
	$res 		= safe_query($sql);

	while ($rw = mysqli_fetch_object($res)) $arr[] = $rw->$id;

	foreach ($arr as $val) {
		$xx++;
		$sql  = "UPDATE $db set $sortfield=$xx WHERE $id=$val";
		$res = safe_query($sql);
	}
}
elseif ($dupl) {
	global $arr_form;

	//// EDIT_SKRIPT
	$db = "morp_newsletter_cont";
	$id = "nlcid";
	/////////////////////

	$sql = "SELECT * FROM $db WHERE $id=".$dupl."";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);
	$sql = '';

	foreach($arr_form as $arr) {
		$tmp = $arr[0];
		$art = $arr[3];
		$val = $row->$tmp;

		if ($art == "dat") $sql .= $tmp. "='" .us_dat($val). "', ";
		elseif ($tmp == "nlvdatum") {}
		elseif ($tmp != "nlname") $sql .= $tmp. "='" .$val. "', ";
	}

	$sql .= "nlname='dupliziert'";

	$sql  = "INSERT $db set $sql";
	$res  = safe_query($sql);
	$edit = mysqli_insert_id($mylink);

	unset($edit);
	// echo $sql;
}
elseif ($del) {
	die('<p>Do you like to delete the '.$um_wen_gehts.'?</p>
	<p><a href="?delete='.$del.'&liste='.$liste.'">yes</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="?liste='.$liste.'">no</a></p>
	');
}
elseif ($delcsv) {
	die('<p>M&ouml;chten Sie den Verteiler wirklich l&ouml;schen?</p>
	<p><a href="?deletecsv='.$delnm.'&edit='.$edit.'">Ja</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="?">Nein</a></p>
	');
}
elseif ($delpdf) {
	$sql = "update $db set $delpdf='' WHERE $id=$edit";
	$res = safe_query($sql);
	unlink('../nlnewsletter/'.$delnm);
}
elseif ($delimg) {
	$sql = "update $db set $delimg='' WHERE $id=$edit";
	$res = safe_query($sql);
}
elseif ($deletecsv) {
	$sql = "update $db set csv='' WHERE $id=$edit";
	$res = safe_query($sql);
	unlink('../nlverteiler/'.$deletecsv);
}
elseif ($delete) {
	$sql = "DELETE FROM morp_newsletter_cont WHERE nlcid=$delete";
	$res = safe_query($sql);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


if ($neu) 		echo neu($liste);
elseif ($edit) 	echo edit($edit,$liste);
else {
				echo '<p>&nbsp;</p>'.liste($liste).$new;
}

if ($edit) echo '
</form>

<!--		<p><a href="newsletter/versenden.php?nlcid='.$edit.'&liste=TEST'.($csv==4 ? '&csv=1' : '').'">&raquo; Test-Versand</a></p>

		<p><a href="newsletter/versenden.php?nlcid='.$edit.'&live=1'.($csv==4 ? '&csv=1' : '').'">&raquo; LIVE-Versand</a></p>-->

<script>
$(document).ready(function() {
   $(".unloadmsg").change(function() {
		$(window).bind("beforeunload", function() {
        	return "Wollen Sie die Seite wirklich verlassen?";
		});
   });

   $("form").submit(function() {
      $(window).unbind("beforeunload");
   });

});


</script>

';

include("footer.php");

