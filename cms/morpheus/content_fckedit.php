<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bj&ouml;rn t. knetter                                  #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

session_start();

$box = 0;
include("cms_include.inc");

//----------------------------------------------------------------
//
// KEIN FCKedit mehr - sucks ;-)
include("editor.php") ;
?>
<script type="text/javascript">

function FCKeditor_OnComplete( editorInstance )
{
	var oCombo = document.getElementById( 'cmbToolbars' ) ;
	oCombo.value = editorInstance.ToolbarSet.Name ;
	oCombo.style.visibility = '' ;
}

function ChangeToolbar( toolbarName )
{
	window.location.href = window.location.pathname + "?Toolbar=" + toolbarName ;
}

		</script>

<div id=vorschau>
<form action="content_edit.php" method="post">

<?php
// hidden felder setzen
echo '
<input type="Hidden" name="edit" value="'.$_REQUEST["cid"].'">
<input type="Hidden" name="fckedit" value="'.$_REQUEST["cid"].'">
<input type="Hidden" name="db" value="'.$_REQUEST["db"].'">
<input type="Hidden" name="back" value="'.$_REQUEST["back"].'">
<input type="Hidden" name="stelle" value="'.$_REQUEST["stelle"].'">
<input type="Hidden" name="sprache" value="'.$_REQUEST["sprache"].'">
';

// text aus db holen
$sql	= "SELECT * FROM `morp_cms_content` WHERE cid=".$_REQUEST["cid"];
$res 	= safe_query($sql);
$row 	= mysqli_fetch_object($res);
$text	= $row->content;
$tx 	= explode("##", $text);
$stelle	= $_REQUEST["stelle"];

for($i=0; $i <= count($tx); $i++) {
	$x		= $i+1;

	if ($x == $stelle) {
		$txt 	= $tx[$i];
		$txt 	= explode("@@", $txt);
		$text 	= $txt[1];
	}
}

echo '<textarea name="FCKeditor1" style="height:700px; width:100%;">'.stripslashes($text).'</textarea>
';
?>
			<br>
			<input type="submit" value="Speichern und schlie&szlig;en">
		</form>
	</body>
</html>
