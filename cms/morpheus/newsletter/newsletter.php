<?php
session_start();

# # # # # # # # # # # # # # # # # # # # # # # # # # # 
# www.pixel-dusche.de                               #
# bjˆrn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # # 

function eliminiere ($nm) {
	global $no_str, $nw_str, $search_str;
	
#	if (eregi("/", $nm)) $nm = ereg_replace("/", "+", $nm);
	if (eregi($search_str, $nm)) $nm = preg_replace($no_str, $nw_str, $nm);
	
	return $nm;
}

function pulldown ($img) {
	$dir = opendir('fotos');
	
	while ($name = readdir($dir)) {
		if (eregi(".jpg$", $name) || eregi(".gif$", $name)) {
			if ($name == $img) $sel = "selected";
			else $sel = "";
			$pd .= "<option value=\"" .$name ."\" $sel>$name</option>\n";
		}
	}

	return $pd;
}

include("cms_header.php");
include("login.php");


echo '<div id=content class=text><a href="index.php">&laquo; startseite</a>';

include("function.php");

$subject	= $_POST["subject"];
$fokus		= $_POST["fokus"];
$anz		= $_POST["anzahl"];

# print_r($_REQUEST);
#$no_str = array("/\\\/");
#$nw_str = array("");
#$search_str = ereg_replace("/", "", implode("|", $no_str));

for ($i=1; $i <= $anz; $i++) {
	$hea = $_POST["headl_".$i];
	$txt = $_POST["text_".$i];
	$img = $_POST["image_".$i];
	$lnk = $_POST["link_".$i];
	$txt = $txt;
	
	if ($hea || $txt | $img || $lnk) $save .= $hea ."@@" .$txt ."@@" .$img ."@@" .$lnk ."####";
}

//
// jetzt werden die ‰nderungen gespeichert
if ($save || $subject) {
	save_data("subject.txt", trim($subject), "w");
	save_data("fokus.txt", trim($fokus), "w");
	save_data("text.txt", trim($save), "w");
}

$subject	= read_data("subject.txt");
$fokus		= read_data("fokus.txt");
$text 		= read_data("text.txt");

$t = "\\";
$text 		= explode("####", $text);

echo '<form action="newsletter.php" method="post">';

echo "<p><strong>Betreff</strong></p>
		<input type=\"text\" name=\"subject\" value=\"$subject\" size=60><p>
		<input type=\"submit\" name=\"speichern\" value=\"speichern\"><p>
		
	  <p><strong>Ausgabe:</strong></p>
		<input type=\"text\" name=\"fokus\" value=\"$fokus\" size=40><p>
		<a href=\"vorschau.php\" target=_blank><strong>&raquo; Vorschau</strong></a><p>

		<p style=\"border: solid 1px #000000; padding: 8px; width: 300px; background-color:#cccccc;\">&lt;b&gt;Fett&lt;/b&gt; = <strong>Fett</strong><br>
		&lt;i&gt;Kursiv&lt;/i&gt; = <strong>Kursiv</strong><br>
		&nbsp;<br>
		Liste/Aufz‰hlung:<br>
		mit <strong>&lt;ul&gt;</strong> starten<br>
		vor jede zeile <strong>&lt;li&gt;</strong><br>
		mit <strong>&lt;/ul&gt;</strong> abschlieﬂen.</p>
		<p>&nbsp;</p>
";
$ct = 0;

foreach ($text as $val) {		
	$ct++;
	$split = explode("@@", $val);
	$headl = $split[0];
	$txt   = $split[1];
	$img   = $split[2];
	$link  = $split[3];
	
#	$txt = ereg_replace("\\", "", $txt);
	
	echo "<p>Headline: <input type=\"text\" name=\"headl_".$ct."\" value=\"$headl\" style=\"width: 300px;\"><br>
		Text:<br>
		<textarea cols=\"90\" rows=\"12\" name=\"text_".$ct."\" style=\"width:400px;\">".$txt."</textarea>
		";
	if ($link) echo "<a href=\"$link\" target=_blank>";
	if ($img)  echo "<img src=\"fotos/$img\" alt='' border=0>";
	
	echo "</a><br>
		<!-- Image: <input type=\"text\" name=\"image_".$ct."\" value=\"$img\" style=\"width: 300px;\"><br> -->
		Image: <select name=\"image_".$ct."\"><option value=\"\"></option>".pulldown ($img)."</select><br>
		Link: &nbsp; &nbsp; <input type=\"text\" name=\"link_".$ct."\" value=\"$link\" style=\"width: 300px;\"></p>
		<p><input type=\"submit\" name=\"speichern\" value=\"speichern\"></p>
		<p><hr width=400 align=left></p>
		<p>&nbsp;</p>";
}


echo "<input type=\"hidden\" name=\"anzahl\" value=\"$ct\"></form>";

?>

</body>
</html>
