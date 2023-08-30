<?php
session_start();

# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bj&ouml;rn t. knetter                             #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

$myauth = 10;
$redaktion_in = 'in';

include("cms_include.inc");

$lan_arr = array(1=>"de", 2=>"en");
$sprache = $_REQUEST["sprache"];
?>
<link rel="stylesheet" type="text/css" href="uploadifive/uploadifive.css">
<script src="uploadifive/jquery.min.js" type="text/javascript"></script>
<script src="uploadifive/jquery.uploadifive.min.js" type="text/javascript"></script>

<style type="text/css">
.uploadifive-button {
	float: left;
	margin-right: 10px;
}
#queue { border: 1px solid #1997c6; margin-left: 100px; height: 100px; overflow: auto; margin-bottom: 10px; padding: 0 3px 3px; width: 200px; float: left;
	background: url(images/drag-drop.png) no-repeat center center; }
.content_big i.fa {  color: #1997c6 !important; margin: 0; margin-right: .5em;  }
.uploadifive-button { background: #1997c6 !important; }
</style>
<?php

// print_r($_POST);

# welche ebene und welche subnav wurde ausgewaehlt
$ebene  = $_REQUEST["ebene"];
$parent = $_REQUEST["parent"];
$edit	= $_REQUEST["edit"];
$cid	= $_REQUEST["cid"];
$save	= $_REQUEST["save"];
$back	= $_REQUEST["back"];
$gruppe	= $_REQUEST["gruppe"];
$back 	= repl(";;", "&", $back);
$back 	= repl(";:;", "=", $back);
$blink  = "navigation.php?$back&sprache=$sprache&gruppe=$gruppe";

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

if ($cid) {
	$query 	= "UPDATE `morp_cms_nav` set lnk=$cid WHERE navid=$edit";
	$result = safe_query($query);
}

$name 	= $_POST["name"];

if ($save && !$name) $warn = "<p><font color=#ff0000><b>Der Name eines Navigation-Elementes darf nicht leer sein</b></font></p>";

elseif ($save && $name) {
	$title 		= trim($_POST["title"]);
	$desc 		= trim($_POST["desc"]);
	$keyw 		= trim($_POST["keyw"]);
	$sichtbar 	= $_POST["sichtbar"];
	$lock 		= $_POST["lock"];
	if (!$bereich = $_POST["bereich"]) $bereich=1;
	$button		= $_POST["button"];
	$link		= $_POST["lnk"];
	$design		= $_POST["design"];
	$name 		= trim($name);
	$nocontent 	= $_POST["nocontent"];
	$accesskey 	= $_POST["accesskey"];
	$oldlnk		= $_POST["oldlnk"];
	$setlink	= trim($_POST["setlink"]);
	$blog		= $_POST["blog"] ? $_POST["blog"] : 0;
	// $datum		= $_POST["datum"];
	$author		= $_POST["author"];
	$shortlink	= $_POST["shortlink"];
	$fbimage	= $_POST["fbimage"];
	$subname	= $_POST["subname"];

	$updated_dat = date("Y-m-d").'T'.date("H:i:s").'+00:00';

	if(!$setlink) $setlink = $name;

	if (!$parent) $parent = 0;
	if (!$design) $design = $morpheus["standard_des"];
	if (!$sichtbar) $sichtbar = 0;
	else $sichtbar = 1;

	if($setlink) $setlink = eliminiere(strtolower($setlink));

	if (!$edit)	{
		$query  = "SELECT * FROM `morp_cms_nav` WHERE ebene=$ebene AND parent=$parent ORDER BY `sort` DESC";
		$result = safe_query($query);
		$sort = mysqli_num_rows($result);
		$sort++;
	}

	$set = "set name='$name', title='$title', keyw='$keyw', lnk='$link', `desc`='$desc', `subname`='$subname', `fbimage`='$fbimage', design='$design', `oldlnk`='$oldlnk', `setlink`='$setlink', `shortlink`='$shortlink', updated_dat='$updated_dat', author='$author', blog='$blog', sichtbar=$sichtbar, bereich=$bereich, button='$button', lang=$sprache, edit=1, `lock`=".($lock ? 1 : 0).", `nocontent`=".($nocontent ? 1 : 0).", accesskey='$accesskey'";

	# print_r($_REQUEST);

	if ($edit) {
		$query 	= "UPDATE `morp_cms_nav` " .$set ." WHERE navid=$edit";

		// die($query);
		// echo $query;
		
		$result = safe_query($query);
		protokoll($uid, "nav", $edit, "edit");

		# url's werden nicht mehr aufgrund von id's gefunden. also muss nach umbenennung, auch die alte url fuer einen redirect gespeichert werden
		# alte urls werden mit einem tool gepflegt. jede erzeugte url, wird hier festgehalten
		# art = 1     =>    content-seiten

		$url	= $setlink; // eliminiere($name);

		if ($parent > 0) {
			$query  = "SELECT * FROM `morp_cms_nav` WHERE navid=$parent";
			$result = safe_query($query);
			$row 	= mysqli_fetch_object($result);
			$url 	= $row->setlink."/".$url;
		}
		$url   .= "/";
		$sql 	= "SELECT * FROM `morp_cms_pfad` WHERE url='$url'";
		$res 	= safe_query($sql);
		$x		= mysqli_num_rows($res);
		if ($x < 1) {
			$sql 	= "INSERT `morp_cms_pfad` SET navid=$edit, parent='$parent', url='$url'";
			$res 	= safe_query($sql);
		}
	}

	else {
		$query 	= "INSERT `morp_cms_nav` " .$set .", published='$updated_dat', ebene=$ebene, parent=$parent, sort=$sort";
		$result = safe_query($query);
		
		$cid	= mysqli_insert_id($mylink);
		$x = 1;

		foreach($morpheus["standard_tid"] as $tid) {
			$query 	= "INSERT `morp_cms_content` SET navid=$cid, tpos=".$x.", tid=".$tid;
			$result = safe_query($query);
			$c 		= mysqli_insert_id($mylink);
			protokoll($uid, "nav", $c, "neu");
			$x++;
		}
	}
	
	# # # # #
	# # # # #
	# # # # # array fuer interne links schreiben
	$query  = "SELECT setlink, navid, name FROM `morp_cms_nav` WHERE lang=$sprache";
	$result = safe_query($query);

	$nav_arr 	= '<?php
$navarray = array("0"=>""';
	$nav_arrF 	= '<?php
$navarrayFULL = array("0"=>""';

	while ($row = mysqli_fetch_object($result)) {
		$id		= $row->navid;
		$name	= $row->name;
		$nnm 	= $row->setlink;
		// $nnm 	= eliminiere(strtolower($name));

		$name = str_replace(array('"', "'", '„', '“'), "", $name);

		if ($nnm) {
			$nav_arr .= ', "'.$id.'"=>"'.($nnm).'"';
			$nav_arrF .= ', "'.$id.'"=>"'.($name).'"';

			// $sql = "UPDATE `morp_cms_nav` SET setlink='$nnm' WHERE navid=$id";
			// safe_query($sql);
		}
	}

	$nav_arr .= ');
?>'.
	$nav_arrF .= ');
?>';

	save_data("../nogo/navarray_".$morpheus["lan_arr"][$sprache].".php",$nav_arr,"w");
	include("quickbar.php");
	include("set_nav_new.php");
	include("sitemap_create.php");
	include("sitemap_create_footer.php");
	include("set_nav.php");
	# # # # #
	# # # # #
	# # # # #
	// die();
	echo "<script language='javascript'>
		document.location = '$blink';
	</script>";

}

elseif ($edit) {
	$query  	= "SELECT * FROM `morp_cms_nav` n WHERE n.navid=$edit";
	$result 	= safe_query($query);
	$row 		= mysqli_fetch_object($result);
	$sort 		= $row->sort;
	$ebene 		= $row->ebene;
	$parent 	= $row->parent;
	$name 		= $row->name;
	$title 		= $row->title;
	$desc 		= $row->desc;
	$design		= $row->design;
	$keyw 		= $row->keyw;
	$accesskey  = $row->accesskey;
	$sichtbar 	= $row->sichtbar;
	$lock 		= $row->lock;
	$nocontent 		= $row->nocontent;
	$bereich 	= $row->bereich; // hier kann zw 2 haupt-navigationsebenen gewaehlt werden
	$link 		= $row->lnk;
	$button		= $row->button;
	$oldlnk		= $row->oldlnk;
	$setlink	= $row->setlink;
	$shortlink	= $row->shortlink;
	$fbimage	= $row->fbimage;
	$subname	= $row->subname;

	$navbar_image = $row->navbar_image;

	$blog		= $row->blog;
	$datum		= $row->datum;
	$author		= $row->author;

	if($datum) $datum = euro_dat($datum);
}

# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # bereich zuweisung, falls es 2 unterschiedliche hauptnavigationen gibt
$bereich_bez = $morpheus["navpos"];
$bereich_anz = count($bereich_bez);
if (!$bereich) $bereich = 1;

foreach($bereich_bez as $key=>$val) {
	$radio .= '
        <div class="funkyradio-danger">
            <input type="radio" name="bereich" id="bereich'.$key.'"  value="'.$key.'" '. ($bereich == $key ? ' checked' : '') .' />
            <label for="bereich'.$key.'">'. $val .'</label>
        </div>
';
}
# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #
$templ 	= $morpheus["design"];
$i 		= 0;
if (!$tid) $tid = 1;

foreach($templ as $key=>$val) {
	if ($val) $template .= '
        <div class="funkyradio-success">
            <input type="radio" name="design" id="design'.$key.'"  value="'.$key.'" '. ($design == $key ? ' checked' : '') .' />
            <label for="design'.$key.'">'. $val .'</label>
        </div>
';
}
# # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # #

echo '<form method="post" name="nav_edit" id="nav_edit">'."
	<input type=hidden name=save value=1>
	<input type=hidden name=edit value=$edit>
	<input type=hidden name=ebene value=$ebene>
	<input type=hidden name=sprache value=$sprache>
	<input type=hidden name=parent value=$parent>";

echo '

	<div class="row">
		<div class="col-md-2 col-xs-4 center">
			<p><a href="'.$blink.'" title="zur&uuml;ck" class="button bt2"><i class="fa fa-arrow-circle-left"></i> zur&uuml;ck</a></p>
			<p><input type="submit" name="erstellen" value="speichern"></p>
		</div>

		<div class="col-md-8 col-xs-8 center">

			<h2>Struktur - Navigationselement anlegen</h2>
		<br><br>
	';

echo $warn;

if ($sichtbar || !$edit) $s_chk = "checked";

echo '
<div class="row">
		<div class="col-md-6 col-xs-6 center">
			<b>Name, der in der Navigation als Link angezeigt wird.</b>
			<input type=text name="name" value="'.$name.'" class="long"><br>
			<!--Subline-->
			<input type=hidden name="subname" value="'.$subname.'" class="long"><br>&nbsp;<br>
		</div>
		<div class="col-md-6 col-xs-6 center">
			Url<br/><input type="text" name="setlink" value="'.$setlink.'" class="long">
		</div>
</div>
<div class="row">
		<div class="col-md-6 col-xs-6 center">
			<input type="text" name="accesskey" value="'.$accesskey.'" style="width:60px;"> AC-Key: 0: Home / 3: Kontakt / 4: Sitemap / 5: Suche
		</div>
		<div class="col-md-6 col-xs-6 center">
			<input type="text" name="lnk" value="'.$link.'"> &nbsp;
				<a href="link.php?navLink='.$edit.'&sprache='.$sprache.'">
				' .$bittew .' &raquo; Verlinkung auf eine andere Seite</a>
		</div>
		<div class="col-md-6 col-xs-6 center">
			<br/><input type="text" name="shortlink" value="'.$shortlink.'" > Short-Url<br>'.($shortlink ? $morpheus["url"].'info/'.$shortlink.'/' : '').'
		</div>
		<div class="col-md-6 col-xs-6 center">
		 ';

echo " <br><input type=text name=oldlnk value='".$oldlnk."' class=\"long\" style=\"background:#337ab7;color:#fff;\"> &nbsp; Alte Url (de/name-url/) <br>&nbsp;<br>";
// echo " <input type=text name=button value='".$button."' class=\"long\"> &nbsp; CSS Klasse";

echo '

		</div>
</div>


		</div>
	</div>

';

echo '
	<div class="row">
		<div class="col-md-6 col-sm-6">

			<div class="panel panel-default">
				<div class="panel-heading">
					Seiten Design / Aufbau
				</div>
				<div class="panel-body">
					<div class="inner">
						<div class="funkyradio">
							'.$template.'
						</div>
					</div>
				</div>
<!--
				<hr>
				<div class="inner">
					<div class="funkyradio">
					  	<div class="funkyradio-primary">
				            <input type="checkbox" name="blog" id="blog" value="1" '.($blog ? ' checked' : '').' />
				            <label for="blog">'.($ebene < 2 ? ' Unterseiten sind Blog-Einträge' : ' Blog').'</label>
				        </div>
					</div>
					<input type=text" name="datum" id="datum" value="'.$datum.'" placeholder="Datum" /> &nbsp; &nbsp;
					<input type=text" name="author" id="author" value="'.$author .'" placeholder="Autor" />
				</div>
-->
			</div>

		</div>

		<div class="col-md-6 col-sm-6">

			<div class="panel panel-default">
				<div class="panel-heading">
					Menü / Navigation Position
				</div>
				<div class="panel-body">
					<div class="inner">
';



if ($ebene < 2) echo '
					<div class="funkyradio">
						'.$radio.'
					</div>

					'."<hr style=\"height: 1px;\">";

echo '

					<div class="funkyradio">
					  	<div class="funkyradio-primary">
				            <input type="checkbox" name="sichtbar" id="sichtbar"  value="sichtbar" '.$s_chk.' />
				            <label for="sichtbar">Link in Navigaton sichtbar</label>
				        </div>
					  	<div class="funkyradio-primary">
				            <input type="checkbox" name="nocontent" id="nocontent"  value="1" '.($nocontent ? ' checked' : '').' />
				            <label for="nocontent">Seite ohne Inhalt</label>
				        </div>
					  	<div class="funkyradio-primary">
				            <input type="checkbox" name="lock" id="lock"  value="1" '.($lock ? ' checked' : '').' />
				            <label for="lock">Nicht bearbeiten</label>
				        </div>
					</div>

				</div>
			</div>

';


// if ($lock) echo "<strong>Diese Seite geh&ouml;rt zu einem gesch&uuml;tzten Kundenaccount</strong>";

echo "

			</div>
		</div>

	</div>

	<div class=\"row\">
		<div class=\"col-md-12\">
".'
			<div class="panel panel-default">
				<div class="panel-heading">
					Suchmaschinen / Meta Tags
				</div>
				<div class="panel-body">
					<div class="inner">
'."
						<p><i>Achten Sie vor allem bei Beschreibung und Schl&uuml;sselw&ouml;rtern auf das Vorkommen der Begriffe in der Seite!</i></p>
						<p><b>Titel der Seite</b><br/>
						<input type=text name=title value='$title' style=\"width:50%;\" size=62 maxlength=255></p>

						<p><b>Kurze Beschreibung dieser Seite</b>
						<input type=text name=desc value='$desc' style=\"width:100%;\" size=255 maxlength=255></p>

						<p><b>Schl&uuml;sselbegriffe dieser Seite</b>
						<input type=text name=keyw value='$keyw' style=\"width:100%;\" size=255 maxlength=255></p>
".'
				</div>
			</div>
		</div>

		<div class="col-md-6" id="navbar_image" style="background: url(../images/navbar/'.$navbar_image.') no-repeat;">
';
?>
			<div id="queue"></div>
			<input id="file_upload" name="file_upload" type="file" multiple="true">
		</div>
		<div class="col-md-6">

<?php
	echo '

		</div>
	</div>
</div>

'."
<div class=\"col-md-12\">
";

echo '<p>&nbsp;</p>
	<p><input type="submit" name="erstellen" value="speichern"></p>

';

echo '<p><a href="'.$blink.'" title="zur&uuml;ck" class="button bt2"><i class="fa fa-arrow-circle-left"></i> zur&uuml;ck</a></p>'."

	<p><b>Content dieser Seite</b>. Holen Sie sich die Begriffe per copy/paste => kopieren/einf&uuml;gen</p>

	<div class=\"col-md-8\" style=\"background-color:silver; padding: 6px; margin: 10px 0px 0px 0px;\">

";

global $fbAuswahl;

$sql = "SELECT content FROM `morp_cms_content` WHERE navid=$edit ORDER BY `pos`";
$res = safe_query($sql);
$showText = '';
while ($row = mysqli_fetch_object($res)) {
	$txt = $row->content;
	$showText .= get_raw_text_BR ($txt, $lang, $fbimage)."<br><br>";
//	$showText .= get_raw_text ($text, $lang);
//	$showText .= get_cms_text ($text, $lang, $dir);
}

echo "<u>INHALT:</u><br>";
echo $showText;
echo "</div>
<div class=\"col-md-4\"><u>Bildauswahl Social Media</u><br><br>
".$fbAuswahl;
?>
		</div>
	</form>

</div>


	<script type="text/javascript">
		<?php $timestamp = time();?>
		$(function() {
			$('#file_upload').uploadifive({
				'auto'             : true,
				'checkScript'      : 'uploadifive/check-exists_img.php',
				'formData'         : {
									   'timestamp' : '<?php echo $timestamp;?>',
									   'token'     : '<?php echo md5('pixeld' . $timestamp);?>',
									   'navid'	   : '<?php echo $edit; ?>'
				                     },
				'queueID'          : 'queue',
				'uploadScript'     : 'uploadifive/uploadifive_img_navbar.php',
				'onUploadComplete' : function(file, data) { console.log(data); $("#navbar_image").css({"background":"url(../images/navbar/"+data+") no-repeat"}); $("#queue").html();   }
			});
		});
	</script>


<?php
include("footer.php");
?>