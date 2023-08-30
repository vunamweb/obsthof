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

$myauth = 10;

include("cms_include.inc");
?>

<?php
$imgFolder = "../images/userfiles/image/";
$imagePath = $imgFolder."Familie_Betrieb_Siglinde_Hiestand-750.jpg";
if (! empty($uploadedImagePath)) {
	$imagePath = $uploadedImagePath;
}
?>
<div class="bgColor">
	<form id="uploadForm" action="" method="post" enctype="multipart/form-data">
		<div id="uploadFormLayer">
			<input name="userImage" id="userImage" type="file"
				class="inputFile"><br> <input type="submit"
				name="upload" value="Submit" class="btnSubmit">
		</div>
	</form>
</div>
<div>
	<img src="<?php echo $imagePath; ?>" id="cropbox" class="img" /><br />
</div>
<div id="btn">
	<input type='button' id="crop" value='CROP'>
</div>
<div>
	<img src="#" id="cropped_img" style="display: none;">
</div>



<script type="text/javascript">
$(document).ready(function(){
	var size;
	$('#cropbox').Jcrop({
	  aspectRatio: 1,
	  onSelect: function(c){
	   size = {x:c.x,y:c.y,w:c.w,h:c.h};
	   $("#crop").css("visibility", "visible");     
	  }
	});
 
	$("#crop").click(function(){
		var img = $("#cropbox").attr('src');
		$("#cropped_img").show();
		$("#cropped_img").attr('src','image-crop.php?x='+size.x+'&y='+size.y+'&w='+size.w+'&h='+size.h+'&img='+img);
	});
});
</script>


</body>
</html>