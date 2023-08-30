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

$copy 	 = $_REQUEST["copy"];
$edit	 = $_REQUEST["edit"];
$gid	 = $_REQUEST["gid"];

echo '<div id=content class=text>
<p><a href="image_liste.php?gid='.$gid.'"><i class="fa fa-chevron-left"></i> zurück</a></p>
<p>&nbsp;</p>
<p><b>Bild INFO</b></p>
';

$copy = check_valid_value($copy, "i");
if(!$copy) die("Fehler 555");

$sql  = "SELECT * FROM `morp_cms_image` WHERE imgid=$copy";
$res = safe_query($sql);
$row = mysqli_fetch_object($res);

$tx = $row->itext;
$lt = $row->longtext;
$english_alt = $row->english_alt;

$imgname = $row->imgname;
echo "

imgname = $file <br/>
itext = $tx <br/>
longtext = $lt <br/>
english_alt = $english_alt <br/>

<hr>
";

$imgFolder = "../images/userfiles/image/";
$file = $imgFolder.$imgname;

$file_size = round(filesize($file) / 1024);
$img_size = getimagesize($file);
$img_w = $img_size[0];
$img_h = $img_size[1];
  
echo '<p>Breite: '.$img_w.' px<br/>Höhe: '.$img_h.' px<br/>'.$file_size.' kb</p>
<hr>';

// print_r($_POST);


$save = isset($_POST["save"]) ? 1 : 0;

$new_val = isset($_POST["new_val"]) ? $_POST["new_val"] : '600';
$q = isset($_POST["q"]) ? $_POST["q"] : '70';
$w_or_h = "w";

if($new_val) {
	$w_or_h = $_POST["w_or_h"];	
	$info = pathinfo($file);
	// print_r($info);	
	$name = $info["filename"];
	$new_name = $name.'-'.$new_val;
	copyImage($file, $new_name, $imgFolder, $new_val, $q);
	
	$n_file = $imgFolder.$new_name.'.jpg';
	$n_file_size = round(filesize($n_file) / 1024);
	$n_img_size = getimagesize($n_file);
	$n_img_w = $n_img_size[0];
	$n_img_h = $n_img_size[1];
	  
	echo '<p>neue Breite: '.$n_img_w.' px<br/>neue Höhe: '.$n_img_h.' px<br/>'.$n_file_size.' kb</p>';
	
	$echo = '
	<img src="'.$imgFolder.$new_name.'.jpg" id="cropbox" class="img" /><br />
';
	
	
	if($save) {
		$sql  = "INSERT `morp_cms_image` SET imgname='$new_name.jpg',
			`itext` = '$tx',
			`longtext` = '$lt',
			`english_alt` = '$english_alt',
			`size`='$n_file_size',
			`gid`=$gid
		";
		$res = safe_query($sql);	
	}
}
?>

<p>&nbsp;</p>
<p><b>Neue Größe</b></p>


	<form method="post">
		<div class="row">
			<div class="col-md-2">
				<select name="w_or_h" id="w_or_h" class="form-control">
					<option value="w"<?php echo $w_or_h == "w" ? ' selected' : ''; ?>>Breite</option>
					<option value="h"<?php echo $w_or_h == "h" ? ' selected' : ''; ?>>Höhe</option>
				</select>
			</div>
			
			<div class="col-md-2">
				<input type="text" name="new_val" id="new_val" class="form-control" value="<?php echo $new_val; ?>" placeholder="Wert in Pixel">
			</div>
			<div class="col-md-2">
				<input type="text" name="q" id="q" class="form-control" value="<?php echo $q; ?>" placeholder="Qualität in Prozent">
			</div>
			
			<div class="col-md-2">
				<button class="btn btn-info" type="submit" name="kalk">kalkulieren</button>
			</div>
			
			<div class="col-md-2">
				<button class="btn btn-info" type="submit" name="save">Speichern</button>
			</div>
		</div>
	</form>

<?php echo $echo ;?>	
	<div>
		<input type='button' id="crop" value='CROP'>
		<input type='button' id="SAVE" value='SAVE'>
	</div>
	
	
	
</div>

<img src="#" id="cropped_img" style="display: none;">
<img src="#" id="crop_save" style="display: none;">


<?php
include("footer.php");
?>

<script type="text/javascript">
	$(document).ready(function(){
		var size;
		$('#cropbox').Jcrop({
		  //aspectRatio: 1,
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
		
		$("#SAVE").click(function(){
			var img = $("#cropbox").attr('src');
			// $("#crop_save").show();
			$("#crop_save").attr('src','image-crop-save.php?x='+size.x+'&y='+size.y+'&w='+size.w+'&h='+size.h+'&gid=<?php echo $gid; ?>&img='+img+'&imgname=<?php echo $imgname; ?>');
		});
	});
	
</script>