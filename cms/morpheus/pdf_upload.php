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

$box = 1;
$myauth = 30;
include("cms_include.inc");

?>

<link rel="stylesheet" type="text/css" href="uploadifive/uploadifive.css">
<script src="uploadifive/jquery.min.js" type="text/javascript"></script>
<script src="uploadifive/jquery.uploadifive.min.js" type="text/javascript"></script>

<style type="text/css">
body {

}
.uploadifive-button {
	float: left;
	margin-right: 10px;
}
#queue {
	border: 1px solid #1997c6;
	height: 377px;
	overflow: auto;
	margin-bottom: 10px;
	padding: 0 3px 3px;
	width: 500px;
}
</style>

<?php


$rootDir = dirname(__FILE__);
$rootDir = str_replace("morpheus", "", $rootDir);

/*
UploadiFive
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
*/

// Set the uplaod directory
$pid 	 = isset($_REQUEST["pid"]) 		? $_REQUEST["pid"] : '';
$pgid 	 = isset($_REQUEST["pgid"]) 	? $_REQUEST["pgid"] : '';
$neu 	 = isset($_REQUEST["neu"]) 		? $_REQUEST["neu"] : '';
$reload	 = isset($_REQUEST["reload"]) 	? $_REQUEST["reload"] : '';


// echo $uploadDir = getPDFtargetDirectoy($pgid);


echo "<div>\n";


?>

	<p>&nbsp;</p>
	<p><a href="pdf.php?pgid=<?php echo $pgid; echo $reload ? '&edit='.$reload : ''; ?>"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i> Fertig und Reload</a></p>
	<p>&nbsp;</p>
	<form>
		<div id="queue"></div>
		<input id="file_upload" name="file_upload" type="file" multiple="true">
		<a style="position: relative; top: 8px; border:solid 1px #1997c6; color:#1997c6; font-weight:bold; height:27px; display:block; float:left; margin-top:-6px; padding:0 8px; text-transform:uppercase; line-height:28px; background:#fff;" href="javascript:$('#file_upload').uploadifive('upload')">Upload Files</a>
	</form>

	<script type="text/javascript">
		<?php $timestamp = time();?>
		$(function() {
			$('#file_upload').uploadifive({
				'auto'             : true,
				'checkScript'      : 'uploadifive/check-exists.php',
<?php if($reload) { ?>				'queueSizeLimit'   : 1,  <?php } ?>
				'formData'         : {
									   'timestamp' : '<?php echo $timestamp;?>',
									   'token'     : '<?php echo md5('pixeld' . $timestamp);?>',
									   'pgid'	   : '<?php echo $pgid; ?>',
									   'pid'	   	   : '<?php echo $pid; ?>',
									   'rootDir'	   : '<?php echo $rootDir; ?>',
									   'reload'	   : '<?php echo $reload; ?>'
				                     },
				'queueID'          : 'queue',
				'uploadScript'     : 'uploadifive/uploadifive.php',
				'onUploadComplete' : function(file, data) { console.log(data); }
			});
		});
	</script>

