<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bj&ouml;rn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #
//$jq = 1;

$myauth = 10;
if(isset($_GET["navbar"])) $box = 1;
include("cms_include.inc");

$edit	= $_REQUEST["edit"];
$ilink	= $_REQUEST["ilink"];
$save	= $_REQUEST["speichern"];
$delimg = $_REQUEST["delimg"];
$imgsav = $_REQUEST["imgsav"];
$vorlage= $_REQUEST["vorlage"];

if ($_REQUEST["navid"]) {
	$navid = $_REQUEST["navid"];
	$_SESSION["navid"] = $navid;
}
$navid = $_SESSION["navid"];

// print_r($_POST);

///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////

if ($save) {
	$arr = array("tlink", "tbackground", "timage", "theadl", "tcolor", "klasse");
	foreach ($arr as $val) {
		$set[] = $val."='".$_POST[$val]."'";
	}
	$arr = array("tid", "tref", "tende", "tabstand", "tschmal", "tabstand_bottom");
	foreach ($arr as $val) {
		$set[] = $val."='".($_POST[$val] ? $_POST[$val] : 0)."'";
	}

	$set = implode(", ", $set);
	$sql = "UPDATE `morp_cms_content` SET $set , edit=1 WHERE cid=$edit";
	$res = safe_query($sql);

	if ($save == "speichern und zurueck") {
		echo '<script language="JavaScript" type="text/javascript">
		document.location.href=\'';
		if ($vorlage) echo 'template.php';
		else echo 'content.php?edit='.$navid;
		echo '\';
	</script>
';
	}
}
elseif ($ilink) {
	$sql  = "UPDATE `morp_cms_content` SET tlink='$ilink' , edit=1 WHERE cid=$edit";
	$res = safe_query($sql);
}
elseif ($imgsav) {
	$sql  = "UPDATE `morp_cms_content` SET timage='$imgsav' , edit=1 WHERE cid=$edit";
	$res = safe_query($sql);
}
elseif ($delimg) {
	$sql  = "UPDATE `morp_cms_content` SET timage='', edit=1 WHERE cid=$edit";
	$res = safe_query($sql);
}
///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////

$sql  	= "SELECT * FROM `morp_cms_content` WHERE cid=$edit";
$res	= safe_query($sql);
$row 	= mysqli_fetch_object($res);
// $titel	= $row->name;

///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////

echo '<div id="content" class="no_preview'.($box ? ' pad' : '').'">'.$out.'
		<h2>Template Verwaltung</h2>

		<p><a href="';

if ($vorlage) echo 'template.php';
else echo 'content.php?edit='.$navid;

echo '"><i class="fa fa-arrow-circle-left"></i> zur&uuml;ck / fertig</a></p>';
echo '';

$cid = $row->cid;
$lnk = $row->tlink;
$tid = $row->tid;
$bac = $row->tbackground;
$thead = $row->theadl;
$img = $row->timage;
$twidth = $row->twidth;
$height = $row->theight;
$tcolor = $row->tcolor;
$tref = $row->tref;
$tende = $row->tende;
$tabstand = $row->tabstand;
$tschmal = $row->tschmal;
$tabstand_bottom = $row->tabstand_bottom;
$klasse = $row->klasse;


echo '<form method="post" class="form-inline">
	<div class="row">
		<div class="col-md-4">
';

# # # # # # # # # # # # # # # # # # # # # # # # #
$templ 	= $morpheus["template"];
$i 		= 0;
if (!$tid) $tid = 1;

foreach($templ as $key=>$val) {
	if ($val) $template .= '
		<p><input type="radio" name="tid" id="tid'.$key.'" value="'.$key.'"'. ($tid == $key ? ' checked' : '') .'> &nbsp;

		<label for="tid'.$key.'" style="font-weight:300;">
			<span class="tip" data-tip="my-tip'.$key.'" data-placement="right">'. str_replace("<br>", "", $val) .' ('.$key.')</span>
		</label>

		'.
			(isin("<br>", $val) ? '<br>&nbsp;<br>' : '')
		.'
<!--
			<div id="my-tip'.$key.'" class="tip-content hidden">
				<img src="images/screen/'.$key.'.jpg" alt="" />
			</div>
-->
		</p>';
}
# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #

echo '<input type="Hidden" name="edit" value="'.$cid.'">
';

 #echo '<p>Anzahl Spalten:<br><input type="Text" name="theadl" id="theadl" value="'.$thead.'" style="width: 50px;"></p>
 #<p>&nbsp;</p>';

 echo '<p class="mb1 mt1">Anker Link: &nbsp; <input type="Text" name="tlink" value="'.$lnk.'"></p>';
 echo '<p class="mb1 mt1">Klasse / Class: &nbsp; <input type="Text" name="klasse" value="'.$klasse.'"></p>';

# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #
# # KUNDEN REFERENZEN

$arr_sp = $morpheus["templ_conf".$tid];
if($arr_sp) {
echo '<select name="tref" class="form-control mb1 mt1">';

foreach($arr_sp as $key=>$val) {
	echo '<option value="'.$key.'"'. ($key == $tref ? ' selected' : '') .'>'.$val.'</option>';
}

echo '</select>
';
}


echo '</p><p>&nbsp;</p>

'.(in_array($tid, $morpheus["template_ende"]) ? '<p>Section Ende manuell setzen: &nbsp; <input type="checkbox" name="tende" id="tende" value="1"'.($tende ? ' checked' : '').'></p>' : '').'
'.(in_array($tid, $morpheus["template_abstand"]) ? '<p>KEIN Padding am Anfang Section: &nbsp; <input type="checkbox" name="tabstand" id="tabstand" value="1"'.($tabstand ? ' checked' : '').'>' : '').'
'.(in_array($tid, $morpheus["template_abstand"]) ? '<p>KEIN Padding am Ende Section: &nbsp; <input type="checkbox" name="tabstand_bottom" id="tabstand_bottom" value="1"'.($tabstand_bottom ? ' checked' : '').'></p>' : '').'
'.(in_array($tid, $morpheus["template_schmal"]) ? '<p>Eingerückt / 1 Leerspalte: &nbsp; <input type="checkbox" name="tschmal" id="tschmal" value="1"'.($tschmal ? ' checked' : '').'></p>' : '').'
'.($tid == 100 ? '<p>Kein Rand / Komplette Breite: &nbsp; <input type="checkbox" name="twidth" id="twidth" value="1"'.($twidth ? ' checked' : '').'></p>' : '').'

';

# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #
/*

echo '</p><p>&nbsp;</p>
<p>Breite Template: <input type="Text" name="twidth" id="tbackground" value="'.$width.'"></p>
<p>H&ouml;he Template: <input type="Text" name="theight" id="tbackground" value="'.$height.'"></p>
<!-- <p>Typo Farbe: <input type="Text" name="tcolor" id="tbackground" value="'.$tcolor.'"></p> -->
<p>&nbsp;</p>
';
<p>emotionales Bild (links-, rechtsb&uuml;ndig oder zentriert)<br><!-- <input type="Text" name="tbackground" id="tbackground" value="'.$bac.'"> --></p>
<!-- <p>
<img src="farben/5e616d.jpg" alt="" width="20" height="20" border="0" onclick="change(\'#5e616d\')"> &nbsp;
<img src="farben/8ec95b.jpg" alt="" width="20" height="20" border="0" onclick="change(\'#8ec95b\')"> &nbsp;
<img src="farben/a7da8b.jpg" alt="" width="20" height="20" border="0" onclick="change(\'#a7da8b\')"> &nbsp;
<img src="farben/ff0000.jpg" alt="" width="20" height="20" border="0" onclick="change(\'#ff0000\')"> &nbsp;
<img src="farben/fffb00.jpg" alt="" width="20" height="20" border="0" onclick="change(\'#fffb00\')">
</p> -->

';

/////////////// BACKGR.-IMAGE
 echo '<input type=hidden name="timage" value="' .$img .'"><a href="image.php?cedit='.$edit.'&navid='.$navid.'&db=content&vorlage='.$vorlage.'">';

 if ($img) echo '<img src="../images/userfiles/image/'.$img.'"></a><br><a href="?delimg='.$i.'&edit='.$edit.'&navid='.$navid.'&vorlage='.$vorlage.'"><img src="images/delete.gif" width="9" height="10" alt="Bild l&ouml;schen" border="0" hspace="6"></a>';
 else echo '<b>Foto</b>: bitte w&auml;hlen</a>';
////////////////////////
*/

echo '
				<p>&nbsp;</p>
				<p class="mb1 mt1"><input type="submit" name="speichern" value="speichern"></p>
				<p class="mb1 mt1"><input type="submit" name="speichern" value="speichern und zurueck"></p>

			</div>
		<div class="col-md-4">

';



echo "<div style=\"border: solid 1px #cccccc; padding: 10px 4px 4px 10px; width: 300px; top: 50px; left: 450px; z-index:100;\">Template:<br>".$template."</div>\n";

?>

		</div>
	</div>

</form>
</div>

<?php
include("footer.php");
?>

<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

   $(document).ready(function () {
        // Tooltips
        $('.tip').each(function () {
            $(this).tooltip(
            {
                html: true,
                title: $('#' + $(this).data('tip')).html()
            });
        });



		$('.list-group .list-group-item').each(function(index){
			var	th = $(this);
		    setTimeout(function () {
				$(th).addClass("show");
			}, index*200);
		});

    });
</script>