<?php
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bj&ouml;rn t. knetter                             #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

# $sess = print_r($_SESSION,1);

global $sprache;

// print_r($_POST);
// echo $_SESSION["comeFrom"];

#if (!session_is_registered('sprache')) 	session_register('sprache');

if (isset($_REQUEST["sprache"])) {
	$sprache = $_REQUEST["sprache"];
	$_SESSION["sprache"] = $sprache;
}
$sprache = isset($_SESSION["sprache"]) ? $_SESSION["sprache"] : 1;

$_SESSION["comeFrom"] = 'navigation.php';

$myauth = 10;
$redaktion_in = 'in';
$nav_active = ' class="active"';
include("cms_include.inc");

# welche ebene und welche subnav wurde ausgewaehlt
$ebene 		= 1;
$parent 	= 0;
$lan_arr 	= $morpheus["lan_arr"];

# print_r($_REQUEST);

foreach($_REQUEST as $key=>$val) {
	$arr = explode("_", $key);

	if ($arr[0] == "p") $parent = $val;		// hier wird der hoechste p(arent)-wert ermittelt

	if ($key == "ebene") $ebene = $val;

	elseif ($arr[0] == "p") {
		$nav  .= "&p_" .$arr[1] ."=" .$val;
		$n_arr[] = $val;
	}

	elseif ($arr[0] == "n") {
		$hist .= "&n_" .$arr[1] ."=" .$val;
		$h_arr[] = $val;
	}
}

if (!$nav) {
	$nav	= "p_0=0";
	$hist	= "n_0=Hauptnavigation";	// history
	$h_arr	= array("Hauptnavigation");
	$n_arr 	= array("0");
}

# print_r($h_arr);
$link   = "navigation.php";

# positionsaenderung
$sortid  = $_REQUEST["sortid"];
$sort 	 = $_REQUEST["sort"];
$az 	 = $_REQUEST["az"];
$gruppe	 = $_REQUEST["gruppe"];


$unvis	 = $_REQUEST["unvis"];
$vis	 = $_REQUEST["vis"];
#
$del 	 = $_REQUEST["del"];
$delete	 = $_REQUEST["delete"];

$jsSAVE = 0;

# hole eine ordnerstruktur auf diese ebene!!! :-) legga feature
$ordnerget	 = $_REQUEST["ordnerget"];
$pageget	 = $_REQUEST["pageget"];

if ($pageget) {
	$query  = "SELECT * FROM `morp_cms_nav` WHERE ebene=$ebene AND parent=$parent";
	$result = safe_query($query);
	$anzahl = mysqli_num_rows($result);
	$anzahl++;

	$query  = "SELECT * FROM `morp_cms_nav` WHERE navid=$pageget";
	$result = safe_query($query);
	$row = mysqli_fetch_object($result);

	$design = $row->design;

	$get_arr = array("content","pos","layout","tid","ton","tpos","tlink","tbackground","timage", "theadl", "tcolor", "tref", );

	$sql = "INSERT `morp_cms_nav` SET name='DUPLIZIERTER CONTENT', sichtbar=0, design=$design, ebene=$ebene, parent=$parent, edit=1, `sort`=$anzahl";
	$res = safe_query($sql);
	$newNavID = mysqli_insert_id($mylink);

	$query  = "SELECT * FROM `morp_cms_content` WHERE navid=$pageget";
	$result = safe_query($query);

	while($row = mysqli_fetch_object($result)) {
		$sql = "INSERT `morp_cms_content` SET ";
		foreach($get_arr as $val) {
			$tmp = addslashes($row->$val);
			$sql .= "`$val`='$tmp', ";
		}
		$sql .= "navid=$newNavID , edit=1";
		safe_query($sql);
	}


}

else if ($ordnerget) {
	$this_ebene 	= $ebene;
	$this_parent 	= $parent;
	$next_eb1 		= ($ebene+1);
	$next_eb2 		= ($ebene+2);
	$next_eb3 		= ($ebene+3);
	$next_eb4 		= ($ebene+4);

	$sql = "UPDATE `morp_cms_nav` SET parent=$this_parent, ebene=$this_ebene, edit=1 WHERE navid=$ordnerget";
	$res = safe_query($sql);

	$tmp_arr = array();
	$query  = "SELECT * FROM `morp_cms_nav` WHERE parent=$ordnerget";
	$result = safe_query($query);

	while ($row = mysqli_fetch_object($result)) {
		$id = $row->navid;
		$row->parent;
		$row->ebene;
		$tmp_arr[] = $id;
		$sql = "UPDATE `morp_cms_nav` SET ebene=$next_eb1, edit=1 WHERE navid=$id";
		$res = safe_query($sql);
	}

	if ($tmp_arr) {
		$tmp_arr2 = array();
		foreach($tmp_arr as $val) {
			$query  = "SELECT * FROM `morp_cms_nav` WHERE parent=$val";
			$result = safe_query($query);
			while ($row = mysqli_fetch_object($result)) {
				$id = $row->navid;
				$tmp_arr2[] = $id;
				$sql = "UPDATE `morp_cms_nav` SET ebene=$next_eb2, edit=1 WHERE navid=$id";
				$res = safe_query($sql);
			}
		}
	}

	if ($tmp_arr2) {
		$tmp_arr3 = array();
		foreach($tmp_arr2 as $val) {
			$query  = "SELECT * FROM `morp_cms_nav` WHERE parent=$val";
			$result = safe_query($query);
			while ($row = mysqli_fetch_object($result)) {
				$id = $row->navid;
				$tmp_arr3[] = $id;
				$sql = "UPDATE `morp_cms_nav` SET ebene=$next_eb3, edit=1 WHERE navid=$id";
				$res = safe_query($sql);
			}
		}
	}

	if ($tmp_arr3) {
		foreach($tmp_arr3 as $val) {
			$query  = "SELECT * FROM `morp_cms_nav` WHERE parent=$val";
			$result = safe_query($query);
			while ($row = mysqli_fetch_object($result)) {
				$id = $row->navid;
				$sql = "UPDATE `morp_cms_nav` SET ebene=$next_eb4, edit=1 WHERE navid=$id";
				$res = safe_query($sql);
			}
		}
	}

	# include("set_nav.php");
	echo "<p>&nbsp;</p>";
	$jsSAVE = 1;
}

elseif ($vis || $unvis) {
	if($vis) $sql = "UPDATE `morp_cms_nav` SET sichtbar=1 WHERE navid=".$vis;
	elseif($unvis) $sql = "UPDATE `morp_cms_nav` SET sichtbar=0 WHERE navid=".$unvis;
	// echo $sql;
	safe_query($sql);
	$jsSAVE = 1;
}

elseif ($_REQUEST["repair"]) {
	$arr 		= array();
	$ebene 		= $_REQUEST["ebene"];
	$parent	 	= $_REQUEST["parent"];
	$xx 		= 0;

	if($az) $sql  		= "SELECT * FROM `morp_cms_nav` WHERE ebene=$ebene AND parent=$parent AND lang=$sprache order by name";
	else	$sql  		= "SELECT * FROM `morp_cms_nav` WHERE ebene=$ebene AND parent=$parent AND lang=$sprache order by sort";
	$res 		= safe_query($sql);

	while ($rw = mysqli_fetch_object($res)) $arr[] = $rw->navid;

	foreach ($arr as $val) {
		$xx++;
		$sql  = "UPDATE `morp_cms_nav` SET sort = $xx WHERE navid=$val";
		$res = safe_query($sql);
	}

	$back	= $_REQUEST["back"];
	$bck 	= str_replace(array(";;", ";:;"), array("&", "="), $back);
	$jsSAVE = 1;
}
# echo "ebene: $ebene<br>parent: $parent<br>";

# wenn sortierung geaendert wurde, jetzt in db schreiben
elseif ($sort) {
	$ebene 	 = $_REQUEST["ebene"];
	$parent	 = $_REQUEST["parent"];

	if ($sort == "up") $s2 = $sortid - 1;
	else $s2 = $sortid + 1;

	$sort_    = array($sortid, $s2);
	$sort_new = array($s2, $sortid);
	$sort_arr = array();

	for($i=0; $i<=1; $i++) {
		$query  = "SELECT * FROM `morp_cms_nav` WHERE ebene=$ebene AND parent=$parent AND lang=$sprache AND sort=$sort_[$i]";
		$result = safe_query($query);
		$row = mysqli_fetch_object($result);
		$sort_arr[] = $row->navid;
	}

	for($i=0; $i<=1; $i++) {
		$query  = "UPDATE `morp_cms_nav` SET sort=$sort_new[$i], edit=1 WHERE navid=$sort_arr[$i]";
		safe_query($query);
	}

	$jsSAVE = 1;
	# include("set_nav.php");
}

# navigationspunkt l&ouml;schen????
elseif ($del) {
	$back	= $_REQUEST["back"];
	$back 	= repl(";;", "&", $back);
	$back 	= repl(";:;", "=", $back);

	$link  = "navigation.php?$back&sprache=$sprache";

	$warnung = "<p><font color=#ff0000><b>Wollen Sie den Link mit all seinen Inhalten wirklich l&ouml;schen?</b><br>
				Denken Sie daran, dass Sie Links auch unsichtbar schalten k&ouml;nnen!</font></p>".'
				<span class="button"><a href="'.$link.'&delete='.$del.'">endg&uuml;ltig l&ouml;schen</a></span> <span class="button"><a href="'.$link.'">nein</a></span>';
}

# ein navigationspunkt wird endg&uuml;ltig gel&ouml;scht
elseif ($delete) {
	$query = "SELECT * FROM `morp_cms_nav` WHERE navid=$delete";
	$res = safe_query($query);
	$row = mysqli_fetch_object($res);
	$descr = htmlspecialchars($row->name);

	$query = "DELETE FROM `morp_cms_nav` WHERE navid=$delete OR parent=$delete";
	safe_query($query);

	// $query_ = "insert `morp_cms_delete` SET `descr`='Seiten und Navigation von: $descr', `query`='$query'";
	// safe_query($query_);

	$jsSAVE = 1;
	// include("set_nav.php");
}

elseif(!$ebene || $ebene == 1) { echo '<span class="small">Sitemap set ...</span>'; include("quickbar.php"); }


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# navigations historie wird zusammengestellt
$x = 0;


$echo = '
<div class="container-fluid" style="padding:0">
	<div class="row">
		<ul class="breadcrumb"><li><img src="images/flag-'.($lan_arr[$sprache]).'.jpg" style="height:41px;"></li>';

for ($i=0; $i<$ebene; $i++) {
	$x++;
	$h_nm .= "&n_" .$i . "=" .$h_arr[$i];
	$h_pa .= "&p_" .$i . "=" .$n_arr[$i];

	if ($x < $ebene) {
		$fotoback  = $h_arr[$i];
		// hier wird zurueck definiert
		$back = "<a href=\"navigation.php?ebene=" .($i+1) .$h_pa .$h_nm ."&sprache=$sprache\" title=\"Sprung zu Navigationspunkt $h_arr[$i]\">";
		$echo .= '<li>'.$back."$h_arr[$i]</a></li>";
	}
	else {
		$fotoback  .= " <i class=\"fa fa-angle-right\"></i> " .$h_arr[$i];
		$echo .= '<li class="active"><a>'.$h_arr[$i].'</a></li></ul></div></div>';
		$edit_back = "ebene=" .($i+1) .$h_pa .$h_nm;
		$edit_back = repl("&", ";;", $edit_back);
		$edit_back = "gruppe=$gruppe&back=".repl("=", ";:;", $edit_back);
	}
}

echo "<div id=content_big class=text>$sess
	<span class=\"sp200\"><h2>Seitenverwaltung</h2></span>
	<span class=\"sp200\"><a href=\"?$edit_back&repair=1&ebene=$ebene&parent=$parent&sprache=$sprache\"  class=\"btn btn-default\"><i class=\"fa fa-refresh\"></i> sortierung aktualisieren</a></span>
	<!--	<span class=\"sp200\"><a href=\"?$edit_back&repair=1&az=1&ebene=$ebene&parent=$parent&sprache=$sprache\">&raquo; sortierung nach A-Z &auml;ndern</a></span> -->
".'	<span class="lang">';

foreach ($morpheus["lan_arr"] as $key=>$lan) {
	echo '<a href="?sprache='.$key.'" class="btn btn-default"><img src="images/'.$lan.'.gif" alt="" width="16" height="9" border="0"></a> &nbsp; &nbsp; ';
}

echo '</span>

<br style="clear: both;" />

';

echo $echo;

// print_r($_REQUEST);

if ($warnung) die ($warnung ."</div></body></html>");

echo "";
# _historie
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# zurueck
if ($ebene > 1) echo "<p>$back" .backlink() ." zur&uuml;ck</a></p>
<!-- <p><a href=\"navigation.php?ebene=1&parent=0&sprache=$sprache\" title=\"zur&uuml;ck zum Hauptverzeichnis\">" .backlink() ." zur&uuml;ck zum Hauptverzeichnis</a></p> -->";
#else echo "<p>&nbsp;</p>";
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #


# jetzt wird subnav ausgewertet und entsprechend query zusammengestellt

if($parent == 1 && $ebene == 2 && $gruppe && $gruppe != "all")  $query  = "SELECT * FROM `morp_cms_nav` WHERE ebene=$ebene AND parent=$parent AND lang=$sprache AND button='$gruppe' ORDER BY button, name, `sort`";
elseif($parent == 1 && $ebene == 2) 							$query  = "SELECT * FROM `morp_cms_nav` WHERE ebene=$ebene AND parent=$parent AND lang=$sprache ORDER BY button, name, `sort`";
else 															$query  = "SELECT * FROM `morp_cms_nav` WHERE ebene=$ebene AND parent=$parent AND lang=$sprache ORDER BY `sort`";

$result = safe_query($query);




/* Konzept fuenf SPEZIAL */
if($ebene == 200) echo '
<a href="navigation.php?'.$nav.'&'.$hist.'&'.$edit_back.'&ebene='.$ebene.'&parent='.$parent.'&sprache='.$sprache.'&gruppe=all">ALLE</a> &nbsp; &nbsp; &nbsp;
<a href="navigation.php?'.$nav.'&'.$hist.'&'.$edit_back.'&ebene='.$ebene.'&parent='.$parent.'&sprache='.$sprache.'&gruppe=agentur">AGENTUR</a> &nbsp; &nbsp; &nbsp;
<a href="navigation.php?'.$nav.'&'.$hist.'&'.$edit_back.'&ebene='.$ebene.'&parent='.$parent.'&sprache='.$sprache.'&gruppe=branchen">BRANCHEN</a> &nbsp; &nbsp; &nbsp;
<a href="navigation.php?'.$nav.'&'.$hist.'&'.$edit_back.'&ebene='.$ebene.'&parent='.$parent.'&sprache='.$sprache.'&gruppe=digital">DIGITAL</a> &nbsp; &nbsp; &nbsp;
<a href="navigation.php?'.$nav.'&'.$hist.'&'.$edit_back.'&ebene='.$ebene.'&parent='.$parent.'&sprache='.$sprache.'&gruppe=print">PRINT</a> &nbsp; &nbsp; &nbsp;
<a href="navigation.php?'.$nav.'&'.$hist.'&'.$edit_back.'&ebene='.$ebene.'&parent='.$parent.'&sprache='.$sprache.'&gruppe=service">SERVICE</a>
';




echo '
	<div id="sortable" class="grid">
';




$x = 0;
$y = mysqli_num_rows($result);

$ct  = 0;

while ($row = mysqli_fetch_object($result)) {
	$id 	= $row->navid;
	$nm 	= $row->name;
	$but 	= $row->button;
	$so 	= $row->sort;
	$si 	= $row->sichtbar;
	$visib 	= $row->sichtbar;
	$meta 	= $row->bereich;
	$blog	= $row->blog;
	$lock	= $row->lock;

	$ti		= $row->title;
	$ke		= $row->keyw;
	$des	= $row->desc;
	$accesskey = $row->accesskey;

	$oldlnk	= $row->oldlnk;
	$nocontent = $row->nocontent;

	$setting = '';
	$setting .= $ti ? "T | " : "&nbsp;|&nbsp;";
	$setting .= $des ? "D | " : "&nbsp;|&nbsp;";
	$setting .= $ke ? "K" : "";

	if ($si == 1) 	$si = '<a href="?'.$nav.'&'.$hist.'&'.$edit_back."&parent=$parent&sprache=$sprache&ebene=$ebene&unvis=$id".'"><i class="fa fa-eye vis" ref="0"></i></a>';
	else			$si = '<a href="?'.$nav.'&'.$hist.'&'.$edit_back."&parent=$parent&sprache=$sprache&ebene=$ebene&vis=$id".'"><i class="gray fa fa-eye-slash vis" ref="1"></i></a>';

	if ($nm) {
		$x++;
//		if ($meta > 1) $ct = 2;
		$ct = $meta;

		$metaC = $lock ? 4 : $meta;
		$metaC = !$visib ? 3 : $meta;
		if ($meta > 2 && $visib) $metaC = 4;

//		echo "<tr style=\"background:".$morpheus["col"][$ct]."\">

		// ************** EDIT URL ********************************************************************************************************************************************
		$edit_lnk = "<a href=\"nav_edit.php?edit=$id&$edit_back&sprache=$sprache\" class=\"btn btn-info\">";

		// ************** SET ARROWS DOWN/UP for ORDERING  ********************************************************************************************************************
		$reihenfolge = '';
		if ($x > 1) $reihenfolge .= " <a href=\"navigation.php?$nav&$hist&sort=up&sortid=$so&$edit_back&ebene=$ebene&parent=$parent&sprache=$sprache\" title=\"eine Position nach oben\"><i class=\"fa fa-chevron-up small\"></i></a>";
		if ($x < $y) $reihenfolge .= " &nbsp; &nbsp;  <a href=\"navigation.php?$nav&$hist&sort=down&sortid=$so&$edit_back&ebene=$ebene&parent=$parent&sprache=$sprache\" title=\"eine Position nach unten\" ><i class=\"fa fa-chevron-down small\"></i></a>\n";



		// ************** SET CONTAINER FOR EDIT, ORDERING, ETC ****************************************************************************************************************
		echo "
	<div class=\"zeile item row col$metaC\" id=\"$id\">
".'
		<div class="col-md-1 col-sm-1 col-xs-1 mobileOff">
				<a>'.$so.'</a> &nbsp;
';
		echo '
		</div>
';

		// MOBILE VIEW *****************************
		echo "		<div class=\"col-md-4 col-sm-4 col-xs-4 mobileOn\">
			".($nocontent ? '' : "<a href=\"content.php?edit=$id&db=content&$edit_back&sprache=$sprache\"> $nm &nbsp; &nbsp; ".($parent == 1 && $ebene == 2 ? '<i style="text-transform:uppercase;">'.$but.'</i> ' : '<i class="fa fa-pencil"></i> &nbsp; '))."
		</div>
		<div class=\"col-md-4 col-sm-4 col-xs-2 mobileOn\">
			".$edit_lnk."<i class=\"fa fa-cogs\"></i></a>
		</div>
";

		echo '
		<div class="col-md-1 col-sm-1 col-xs-2">
';

		echo "<a href=\"navigation.php?ebene=" .($ebene+1) ."&$nav&p_" .($parent+1) ."=$id&$hist&n_" .($parent+1) ."=$nm&sprache=$sprache\" title=\"Subnavigation aufrufen\" class=\"btn btn-default\"><i class=\"fa fa-folder\"></i> <i class=\"fa fa-level-down fa-abs\"></i></a>
		</div>

		<div class=\"col-md-1 col-sm-1 col-xs-4 mobileOff\">
			$setting".($accesskey != '' ? " | $accesskey" : "")."".($blog ? " | Blog" : "")."
		</div>

		<div class=\"col-md-4 col-sm-4 col-xs-6 mobileOff\">
			".$edit_lnk."<i class=\"fa fa-cogs\"></i></a>  &nbsp;  &nbsp;
			".($nocontent ? '' : "<a href=\"content.php?edit=$id&db=content&$edit_back&sprache=$sprache\" title=\"Content/Inhalt bearbeiten\" class=\"btn btn-default btn-100\">".($parent == 1 && $ebene == 2 ? '<i style="text-transform:uppercase;">'.$but.'</i> | ' : '<i class="fa fa-pencil"></i> &nbsp; '))."$nm <!--| <u style=\"color:red;\">$oldlnk</u>--></a>
		</div>

		<div class=\"col-md-1 col-sm-1 col-xs-2\">
			$si <br/>
		</div>

		<div class=\"col-md-1 col-sm-1 col-xs-4 mobileOff\">
			ID $id
		</div>

<!--
		<div class=\"col-md-1 col-sm-1 col-xs-4\">
			".$edit_lnk."<i class=\"fa fa-cogs\"></i></a>
		</div>
		<div class=\"col-md-1 col-sm-1 col-xs-4\">
			<a href=\"content.php?edit=$id&db=content&$edit_back&sprache=$sprache\" title=\"Content/Inhalt bearbeiten\"><i class=\"fa fa-pencil-square-o\"></i></a>
		</div>
-->

		<div class=\"col-md-1 col-sm-1 col-xs-2\">
			<a href=\"navigation.php?del=$id&$edit_back&sprache=$sprache\" title=\"Link mit allen Inhalten l&ouml;schen! Denken Sie daran, dass Sie Links auch unsichtbar schalten k&ouml;nnen!\" class=\"btn btn-danger\"><i class=\"fa fa-trash-o\"></i></a>
		</div>

		<div class=\"col-md-2 col-sm-2 col-xs-offset-6 col-md-offset-0 col-sm-offset-0 col-xs-6\">
".$reihenfolge."
		</div>
	</div>
";

		if ($ct == 0) $ct = 1;		//farbendefenition
		else $ct = 0;
	}
}
echo "</div>
	<p>&nbsp;</p>";

echo "<a href=\"nav_edit.php?ebene=$ebene&parent=$parent&$edit_back&sprache=$sprache\" title=\"Neuen Link erstellen\" class=\"button\"><i class=\"fa fa-plus\"></i>  NEUEN Menü-Eintrag ERSTELLEN</a><br/>";
echo "<a href=\"link.php?ebenenew=1&$edit_back&sprache=$sprache&amp;copy=1\" title=\"Ordnerstruktur hierher verschieben\" class=\"button\"><i class=\"fa fa-window-restore\"></i>  Ordnerstruktur hierher verschieben</a><br/>";
echo "<a href=\"link.php?ganzeseite=1&ebenenew=1&$edit_back&sprache=$sprache&amp;copy=1\" title=\"Ganze Seite hierher kopieren\" class=\"button\"><i class=\"fa fa-retweet\"></i>  Ganze Seite hierher kopieren</a>";


?>

	<p>&nbsp;</p>
	<p><b><u>Symbole</u></b></p>
	<p><i class="fa fa-trash-o"></i> &nbsp; l&ouml;schen</p>
	<p><i class="fa fa-pencil-square-o"></i> &nbsp; den inhalt/content des links editieren</p>
	<p><i class="fa fa-folder"></i> &nbsp; in der navigation eine ebene tiefer gehen</p>
	<p><i class="fa fa-cogs"></i> &nbsp; den link-namen editieren, Meta Tags setzen</p>

</div>

<?php
include("footer.php");
?>

  <script>
  $( function() {
	var grid = new Muuri('.grid', {
		dragEnabled: true,
		dragAxis: 'y',
		threshold: 10,
		action: 'swap',
		distance: 0,
		delay: 100,
		layoutOnResize: true,
		setWidth: true,
		setHeight: true,
		sortData: {
			foo: function (item, element) {
				//console.log(item);
			},
			bar: function (item, element) {
				//console.log(77);
			}
  		}
	});


	grid.on('dragEnd', function (item) {

		var order = grid.getItems().map(item => item.getElement().getAttribute('id'));

		console.log(order);

	    request = $.ajax({
	        url: "UpdatePosNav.php",
	        type: "post",
	        data: "data="+order+"&id=<?php echo $edit; ?>"+"&sprache="+<?php echo $sprache; ?>,
	        success: function(data) {
				console.log(data);
  			}
	    });

		// MuuriPosition = ($.inArray(ref, order));

	});

/*
	$( "#sortable" ).sortable({
		start: function(e, ui) {
		    // puts the old positions into array before sorting
		    // var old_position = ui.item.index();
		    // console.log(old_position);
		},
		update: function(event, ui) {
			var data = $(this).sortable('serialize');
		    // grabs the new positions now that we've finished sorting
		    var new_position = ui.item.index();
		    console.log(data);

		    request = $.ajax({
		        url: "UpdatePosNav.php",
		        type: "post",
		        data: "data="+data+"&id=<?php echo $edit; ?>"+"&sprache="+<?php echo $sprache; ?>
		    });

		}
	});

    $( "#sortable" ).disableSelection();
*/

/*
		var order = $('#test-list').sortable('serialize');
	alert(order);
*/

  });

<?php
	if($jsSAVE) {
?>
		    request = $.ajax({
		        url: "UpdateNav.php",
		        type: "post",
		        data: "sprache="+<?php echo $sprache; ?>,
				success: function(data) {
					console.log(data);
				}
		    });
<?php

	}
?>

  </script>
