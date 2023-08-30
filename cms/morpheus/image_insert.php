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

$myauth = 10;

include("cms_include.inc");


echo "<div id=content class=text>\n<p><b>Bild Upload</b></p><p>&nbsp;</p>";

# lets go
$gid 	 = $_REQUEST["gid"];
$x = 0;

for($i=0; $i<=10; $i++) {
	# bilddaten empfangen

	$tmp = $_FILES['image']['tmp_name'][$i];
	
	// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
	// VALIDATE UPLOAD IMAGE FILE
	// Dateierweiterungen, die erlaubt sind
	$erlaubte_erweiterungen = array('jpg', 'jpeg', 'png', 'gif', 'svg');
	// Überprüfe, ob die hochgeladene Datei die richtige Erweiterung hat
	$dateierweiterung = strtolower(pathinfo($_FILES['image']['name'][$i], PATHINFO_EXTENSION));
	if(!in_array($dateierweiterung, $erlaubte_erweiterungen)) {
		die("Unerlaubte Dateierweiterung.");
	}
	// Überprüfe den MIME-Typ der hochgeladenen Datei
	$mime_typ = mime_content_type($_FILES['datei']['tmp_name'][$i]);
	if($mime_typ != 'image/jpeg' && $mime_typ != 'image/png' && $mime_typ != 'image/gif' && $mime_typ != 'image/svg+xml') {
		die("Unerlaubter MIME-Typ.");
	}
	// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
	
	if ($tmp) echo "<li>".$tmp."<br>";
	$chk  = strtolower(eliminiere($_FILES['image']['name'][$i]));
//	$img  = date("Y-m-d").'+'.$chk;
	$img  = $chk;

	if (!move_uploaded_file($tmp, "../images/userfiles/image/".$img)) {} # die("upload in ordner fehlgeschlagen!");
	@chmod("../images/userfiles/image/".$img, 0777);

	$tmp = "../images/userfiles/image/".$img;

	if ($chk) {
		# dateityp auswerten
		$type_ = explode(".", $img);
		$type_ = $type_[(count($type_)-1)];

		if ($type_ == "jpg") 		$type = 11;
		elseif ($type_ == "gif")  	$type = 10;
		elseif ($type_ == "png")  	$type = 12;
		else 						die("<br><br>upload von unbekannten datenformat");
		# _dateityp

		# daten vorbereiten. size auswerten
		$size  = filesize($tmp);
		$fp    = @fopen($tmp, "r");
//		$data  = mysqli_escape_string(@fread($fp, $size));
		fclose($fp);
//		unlink($tmp);

		$query 	= "SELECT * FROM morp_cms_image where imgname='$img' and gid=$gid";
		$result = safe_query($query);
		$edit 	= mysqli_num_rows($result);
		if ($edit) 	$query = "update ";
		else 		$query = "insert ";

		$query .= " image set gid=$gid, type=$type, imgname='$img', size=$size";

		if ($edit)  $query .= " where imgname='$img' and gid=$gid";

		$result = safe_query($query);
		if(! $result) printf("ERROR: Query [$query] failed.");
		else echo "upload <strong>erfolgreich</strong>";
	}
}
echo "<p>&nbsp;</p><p><a href=\"image_liste.php?gid=$gid\">" .backlink() ." fertig</a></p>";

// create_img_liste();
?>