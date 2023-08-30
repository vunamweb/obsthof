<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

include("cms_include.inc");

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<head>
		<title>ECONECT Administration</title>
	</head>

	<link rel="stylesheet" href="../font.css" type="text/css">

<body>

<?php
	function set_df ($fb_arr, $dozentID) {
		$query = "update ec_doz_fb set aktiv=0 where dozentID=$dozentID";
		safe_query($query);

		foreach ($fb_arr as $val) {
			$query = "SELECT * FROM ec_doz_fb where dozentID=$dozentID AND fbid=$val";
			$result = safe_query($query);
			if (mysqli_num_rows($result) > 0) { $row = mysqli_fetch_object($result); $upd = $row->dfid; }
			else unset($upd);

			if ($upd) $query = "update ec_doz_fb set ";
			else $query = "insert ec_doz_fb set dozentID=$dozentID, fbid=$val, ";

			$query .= "aktiv=1";

			if ($upd) $query .= " where dfid=$upd";

			safe_query($query);
			 echo "<p>$query</p>\n";
		}

	}

	$dozentID 	= $_POST["dozentID"];
	$neu 		= $_POST["neu"];
	$aktiv 		= $_POST["aktiv"];
	$bereich 	= $_REQUEST["bereich"];
	$save 		= "";
	$fb_arr		= array();

	while (list($key, $value) = each($_POST))
	{
		if ($key == "text") $value = repl("\r\n", "<br>", $value);
		# echo "$key = $value <p>";
		if (isin("^fbid", $key)) $fb_arr[] = $value;
		elseif ($key != "neu" && $key != "aktiv") $save .= $key ."='" .$value ."',";
	}

	if ($aktiv == "on") $save .= "aktiv='1'";
	else $save .= "aktiv='2'";

	# echo "$dozentID<p>$save<p>";
	if ($neu == 1) $query = "insert ec_dozenten set $save";
	else $query = "update ec_dozenten set $save where dozentID=$dozentID";

	//echo $query;
	print_r($fb_arr);

	safe_query($query);
	set_df ($fb_arr, $dozentID);

	# echo "<p>live</p>";
	# print_r($fb_arr);

#	include("db_live.php");
#	dbconnect_live();
#	safe_query($query);
#	set_df ($fb_arr, $dozentID);

	echo "<script language='javascript'>\ndocument.location = 'dozenten-edit.php?bearbeiten=$dozentID&bereich=$bereich'\n</script>";
	# echo "<script language='javascript'>\ndocument.location = 'admin-dozenten.php?bereich=$bereich'\n</script>";
?>


</font>
<?
include("footer.php");
?>