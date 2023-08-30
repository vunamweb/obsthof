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

include("cms_include.inc");

$from	= $_REQUEST["from"];
$edit 	= $_REQUEST["id"];
$tbl  	= $_REQUEST["tbl"];
$col  	= $_REQUEST["col"];
$prim  	= $_REQUEST["prim"];
$gid  	= $_REQUEST["gid"];

echo '<div id="content">
	<h2>Bildarchiv</h2>';

echo '<p class="mt2"><a href="'.$from.'.php?edit='.$edit.'"><i class="fa fa-arrow-circle-left"></i> zur&uuml;ck</a></p>';

$query  = "SELECT * FROM morp_cms_image WHERE gid=$gid ORDER BY imgid DESC";
$result = safe_query($query);

echo '<div class="container"><div class="row">';

$x = 0;
$y = mysqli_num_rows($result);

while ($row = mysqli_fetch_object($result)) {
	$id = $row->imgid;
	$nm = $row->imgname;
	if ($nm) {
		$x++;
		echo '
		<div class="col-md-4 col-lg-3 col-xs-6 rahmen">
			<a href="'.$from.'.php?edit='.$edit.'&imgCol='.$col.'&imgID='.$id.'">
				<img src="../mthumb.php?w=300&amp;src=images/userfiles/image/'.$nm.'" class="img-responsive" /><br>
				'.$nm.'
			</a>
		</div>';
	}
}

echo '</div></div>';

?>
</div>
<?php
include("footer.php");
