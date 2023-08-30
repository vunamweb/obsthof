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

include("cms_include.inc");

$id 		= $_POST["id"];
$bereich	= $_REQUEST["bereich"];
$newdata 	= $_POST["newdata"];
$aktiv 		= $_POST["aktiv"];
$save 		= "";
$del 		= $_GET["del"];
$neu 		= $_REQUEST["neu"];
$check 		= $_GET["check"];

if (empty($del)) {
	while (list($key, $value) = each($_POST))
	{	
		# echo "$key = $value <p>";
		if ($key != "newdata" && $key != "aktiv" && $key != "neu" && $key != "pdf_liste") $save .= $key ."='" .$value ."',";
	}
	if ($aktiv == "on") $save .= "aktiv='1',";
	else $save .= 		"aktiv='2',";

	if ($neu == "on") 	$save .= "neu='1'";
	else 				$save .= "neu='2'";
	
	#if ($neu == "on") 	$save .= "neu='1', prior='100'";
	#else 				$save .= "neu='2', prior='100'";
	
	if ($newdata) 		$query = "insert ec_dokumente set $save";		
	else 				$query = "update ec_dokumente set $save where id=$id";		
}

elseif ($del && $check) $query = "delete from ec_dokumente where id=$del";

else {
	echo "<div id=content class=text>Wollen Sie den Datensatz wirklich l&ouml;schen?<p><a href=\"download-save.php?del=$del&check=1&bereich=$bereich\"><strong>&raquo; ja</strong></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; <a href=admin-download.php?bereich=$bereich>nein</a></div>";
	die();
}

#echo $query;
safe_query($query);
if ($newdata) $id = mysqli_insert_id($mylink);

if ($del) 	echo "<script language='javascript'>\ndocument.location = 'admin-download.php?bereich=$bereich'\n</script>";	 
else		echo "<script language='javascript'>\ndocument.location = 'download-edit.php?id=$id&bereich=$bereich'\n</script>";	 
?>
 

</font>
<?
include("footer.php");
?>