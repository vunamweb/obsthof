<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bjoern t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

$box = 1;
global $mylink;

include("../nogo/config.php");
include("../nogo/config_morpheus.inc");
include("../nogo/db.php");
dbconnect();
include("login.php");
include("../nogo/funktion.inc");


$edit	= $_REQUEST["id"];
$navid = $_SESSION["navid"];

$x = 0;
$sql	= "SELECT * FROM `morp_cms_content_history` WHERE cid=$edit ORDER BY timestamp DESC";
$res	= safe_query($sql);
$y		= mysqli_num_rows($res);
$echo = '<div class="ekko" style="height:500px; overflow-y: scroll;">';

while ($row = mysqli_fetch_object($res)) {
	$id 	= $row->id;
	$text 	= $row->content;
	$datum 	= $row->timestamp;

	 $get = get_raw_text_history($text);
	//$get = get_cms_text ($text, "de", $morpheus["url"]);


	$echo .= '
	<div class="col-md-10 rahmen2 inv'.$id.'">
		<h5>'.$datum.'</h5>
		<div class="history" style="font-weight:200 !important;">'.$get.'</div>
	</div>
	<div class="col-md-2 rahmen2 inv'.$id.'">
		<button class="btn btn-danger deleteme" ref="'.$id.'">DELETE</button>
		<button class="btn btn-info refresh" ref="'.$id.'">Wieder herstellen</button>

		<p>Die hier vorgenommenen Änderungen können nicht rückgängig gemacht werden</p>
	</div>

	<p>&nbsp;</p>

';

}

#echo '</table>';
$echo .= '	</div>';

echo $echo;


?>

<script>
$( document ).ready(function() {
	setSize();

});
$( window ).resize(function() {
	setSize();
});

$(".deleteme").on('click', function(e) {
	var todel = $(this).attr("ref");

    request = $.ajax({
        url: "UpdateDelete.php",
        type: "post",
        data: 'todel='+todel+'&table=morp_cms_content_history&tid=id',
        success:function(res, status) {
        	console.log(res);
        	// console.log(status);
        	$(".inv"+todel).hide();
		},
        error:function() {
        	console.log(":-(");
		}
    });

});


$(".refresh").on('click', function(e) {
	var refresh = $(this).attr("ref");

    request = $.ajax({
        url: "UpdateHistoryToContent.php",
        type: "post",
        data: 'get='+refresh,
        success:function(res, status) {
        	console.log(res);
        	 console.log(status);
        	// $(".inv"+todel).hide();
        	location.href="?edit=<?php echo( $edit ); ?>&navid=<?php echo( $navid ); ?>";
		},
        error:function() {
        	console.log(":-(");
		}
    });

});


// UpdateHistoryToContent

function setSize() {
	var w = $( document ).width();
	var h = $( document ).height();

	// console.log(w);

	if(w < 990) 		h = h-160;
	else if(w < 1100) 	h = 450;
	else if(w < 1200) 	h = 400;
	else 			 	h = 500;


	$(".ekko").css({ "height":h+"px"});

//  $( "#log" ).append( "<div>Handler for .resize() called.</div>" );

}

</script>