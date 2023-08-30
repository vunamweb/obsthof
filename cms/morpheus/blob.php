<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # # 
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # # 

include("../nogo/config.php");
include("../nogo/db.php");
dbconnect();

$imgid = $_GET["imgid"];

$query  = "SELECT type, image FROM morp_cms_image WHERE imgid=$imgid";
$result = safe_query($query);
$row    = mysqli_fetch_object($result);

switch ($row->type) {
	case 10: $ct="image/gif"; break;
	case 11: $ct="image/jpeg"; break;	
}

header("Content-type: ".$ct);
echo $row->image;
?>