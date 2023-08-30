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
$box = 1;
include("cms_include.inc");

///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
// print_r($_REQUEST);

global $arr_form;
global $edit, $navid, $pos;

// NICHT VERAENDERN ///////////////////////////////////////////////////////////////////
$edit 	= $_REQUEST["edit"];
$navid 	= $_REQUEST["navid"];
$pos 	= $_REQUEST["pos"];

///////////////////////////////////////////////////////////////////////////////////////


//// EDIT_SKRIPT
$um_wen_gehts 	= "Icons";
$titel			= "Icons";
///////////////////////////////////////////////////////////////////////////////////////


echo '<div>
	<h2>'.$titel.'</h2>

	'. ($edit || $neu ? '<p><a href="content_edit.php?edit='.$edit.'&navid='.$navid.'">&laquo; zur&uuml;ck</a></p>' : '') .'
';

$arr_form = array(
	array("fa", "Icon Code", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
	array("beschreibung", "Bezeichnung Icon f&uuml;r Kunden", '<input type="Text" value="#v#" name="#n#" style="#s#">'),
);

///////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

function liste() {
	global $edit, $navid, $pos;
	//// EDIT_SKRIPT
	$db = "morp_fa";
	$id = "faid";
	$ord = "beschreibung";
	$anz = "beschreibung";
	$anz2 = "fa";
#	$anz3 = "";
	////////////////////

	$echo .= '<ul class="icons">';

	$sql = "SELECT * FROM $db WHERE 1 ORDER BY ".$ord."";
	$res = safe_query($sql);

	while ($row = mysqli_fetch_object($res)) {
		$icon = $row->$id;
		$echo .= '<li style="width:80px;background:transparent;">
			<span class="fl100" style="width:80px;height:auto;"><a href="content_edit.php?edit='.$edit.'&navid='.$navid.'&newIconPos='.$pos.'&newIcon='.$icon.'"><i class="fa '.$row->$anz2.' mrg30" style="font-size:2.5em; margin-left:0;width:70px; text-align:center"></i></a></span>
		</li>';
	}

	$echo .= '</ul><br style="clear:both;" />';

	return $echo;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

echo liste($id).$new;

echo '
</form>
';

include("footer.php");

?>