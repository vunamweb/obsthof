<?
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
include("cms_include.inc");

# print_r($_REQUEST);

global $arr_form;

$tm 	= $_REQUEST["tm"];
$neu	= $_REQUEST["neu"];
$save	= $_REQUEST["save"];
$del	= $_REQUEST["del"];
$id		= $_REQUEST["kid"];

echo '<div id=vorschau>
	<h2>TRASHMAIL Liste <a href="http://www.brand-audience.de/trashmail-blacklist.txt" target="_blank">LINK &raquo;</a></h2>

	<form action="" onsubmit="" name="verwaltung" method="post">
';

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

echo '<table width="250" cellspacing="0">';

$db = "morp_trashmail";
$id = "id";
	
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

if ($save) {
	global $arr_form;
	
	$db = "morp_trashmail";
	$id = "id";

	if ($tm) {
		$sql  = "DELETE FROM $db WHERE 1";
		$res  = safe_query($sql);

		$tm = explode("\n", $tm);
	
		foreach($tm as $val) {
			$val = trim($val);
			if($val) {
				$sql  = "INSERT $db set tm='$val'";
				$res  = safe_query($sql);
			}
		}
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////


echo '
	<tr>
		<td></td>
		<td><textarea cols="100" rows="30" name="tm"></textarea></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="speichern" value="speichern"><input type="hidden" name="save" value="1"></td>
	</tr>
</table>

</form>
';

$sql  = "SELECT tm FROM $db WHERE 1 ORDER BY tm";
$res  = safe_query($sql);
$x=0;
while($row = mysqli_fetch_object($res)) {
	$x++;
	echo '<p>'.$x.': '.$row->tm.'</p>
';
}

include("footer.php");

?>
