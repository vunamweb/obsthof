<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>konvert</title>
</head>

<body>

<?php
include("cms_include.inc");

$db = "morp_cms_content"; #$_GET["db"];
$id = "cid";

$query  = "SELECT * FROM $db";
$result = safe_query($query);
$cnt_arr = array();

while ($row = mysqli_fetch_object($result)) {
	$cnt_arr[] = $row->$id;
}

print_r($cnt_arr);

foreach($cnt_arr as $val) {
	$query  = "SELECT * FROM $db where $id=$val";
	$result = safe_query($query);
	$row = mysqli_fetch_object($result);
	$tmp = $row->content;
//	$tmp = str_replace("headline", "t1__headline", $tmp);
//	$tmp = str_replace("t1__t1__headline", "t1__headline", $tmp);
	$tmp = str_replace("t15__bild@@175", "t15__bild@@202", $tmp);
//	$tmp = str_replace("fliesstext", "t1__fliesstext", $tmp);

	# $tmp = repl("^headline@@", "1_headline@@", $tmp);
	# $tmp = repl("##1_1_headline", "##1_headline", $tmp);
	# $tmp = repl("##2_sub1_headline", "##2_subheadline", $tmp);
//	$tmp = repl("2_subt1__headline", "t1__subheadline", $tmp);
	# $tmp = repl("##fliesstext_ohne_bild", "##3_fliesstext", $tmp);
	# $tmp = repl("##bild", "##4_bild", $tmp);
	# $tmp = repl("##karte", "##5_karte", $tmp);



	$query  = "update $db set content='$tmp' where $id=$val";
	 $result = safe_query($query);
}

?>
fertig!
<?php
include("footer.php");
?>