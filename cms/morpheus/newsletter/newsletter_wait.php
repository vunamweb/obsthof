<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Untitled</title>
</head>

<body bgcolor="#c0c0c0">

<img src="../images/ajax_loader.gif" alt="" width="100" height="100" border="0">

<?php
$nlgetid = $_GET["nlid"];

ob_start();


# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

		echo '';

    ob_flush();
    flush();

		#die();
		sleep(30);

#	$nl 	= $_GET["nl"];
#	$liste 	= $_GET["liste"];

		echo '		<script language="JavaScript">document.location="versenden.php?nlid='.$nlgetid.'";</script>		';


include("../footer.php");

?>

