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
$nav_active = ' class="active"';
$redaktion_in = 'in';

include("cms_include.inc");
$_SESSION["comeFrom"] = 'content.php';


# # # # # # # # # # # # # # # # # # # # # # # # # # #
# formular pull down # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # #
function pulldownContent ($id) {
	$query = "SELECT vorl_name, cid FROM `morp_cms_content` WHERE vorlage=1 ORDER BY vorl_name";
	$result = safe_query($query);

	while ($row = mysqli_fetch_object($result)) {
		if ($row->cid == $id) $sel = "selected";
		else $sel = "";

		$nm = $row->vorl_name;
		$pd .= "<option value=\"" .$row->cid ."\" $sel>$nm</option>\n";
	}
	return $pd;
}

function repair_nav($navid) {
	$arr 		= array();
	$xx 		= 0;
	$sql  		= "SELECT * FROM `morp_cms_content` WHERE navid=$navid ORDER BY tpos";
	$res 		= safe_query($sql);

	while ($rw = mysqli_fetch_object($res)) $arr[] = $rw->cid;

	foreach ($arr as $val) {
		$xx++;
		$sql  = "UPDATE `morp_cms_content` set tpos=$xx WHERE cid=$val";
		$res = safe_query($sql);
	}
//	repairall();
}

function repairall() {
	for($i=1; $i<=200; $i++) {
		$arr 		= array();
		$xx 		= 0;
		$sql  		= "SELECT * FROM `morp_cms_content` WHERE navid=$i ORDER BY tpos";
		$res 		= safe_query($sql);

		while ($rw = mysqli_fetch_object($res)) $arr[] = $rw->cid;

		foreach ($arr as $val) {
			$xx++;
			$sql  = "UPDATE `morp_cms_content` set tpos=$xx WHERE cid=$val";
			$res = safe_query($sql);
		}
	}
}


$Settende 	 = $_REQUEST["tende"];
$Settabstand 	 = $_REQUEST["tabstand"];
$Settabstand_bottom 	 = $_REQUEST["tabstand_bottom"];

$del 	 = $_REQUEST["del"];
$delete	 = $_REQUEST["delete"];
$neu	 = $_REQUEST["neu"];
$sort	 = $_REQUEST["sort"];
$sortid	 = $_REQUEST["sortid"];
$id	 	 = $_REQUEST["id"];
$cid	 = $_REQUEST["cid"];
$copy 	 = $_REQUEST["copy"];
$vorl 	 = $_REQUEST["vorl"];
$pos 	 = $_REQUEST["pos"];
$sichtbar= $_REQUEST["sichtbar"];
$vorlage= $_REQUEST["vorlage"];
$gruppe = $_REQUEST["gruppe"];

if ($_REQUEST["sprache"]) {
	$sprache = $_REQUEST["sprache"];
	$_SESSION["sprache"] = $sprache;
}
$sprache = $_SESSION["sprache"];

///////////////////////////////////////////////////////////////////////////
if ($_GET["back"]) { $back = $_GET["back"].'&gruppe='.$gruppe; $_SESSION["back"] = $back; }
else $back = $_SESSION["back"];
$bck 	= str_replace(array(";;", ";:;"), array("&", "="), $back);
$zuruck = '<a href="navigation.php?'.$bck.'"><i class="fa fa-arrow-circle-left"></i> zur&uuml;ck</a>';

///////////////////////////////////////////////////////////////////////////

$edit	= $_REQUEST["edit"];
$sql  	= "SELECT * FROM `morp_cms_nav` WHERE navid=$edit";
$res	= safe_query($sql);
$row 	= mysqli_fetch_object($res);
$titel	= $row->name;

///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
if ($neu) {
	$sql  	= "SELECT * FROM `morp_cms_content` WHERE navid=$edit ORDER BY tpos";
	$res 	= safe_query($sql);
	$y		= mysqli_num_rows($res);
	$y++;
	$tid 	= $morpheus["standard_tid"][0];

	if ($vorlage) 	$sql = "INSERT `morp_cms_content` set navid=$edit, vid=1, tpos=$y";
	else			$sql = "INSERT `morp_cms_content` set navid=$edit, tpos=$y, tid='$tid'";
	$res	= safe_query($sql);
}

elseif ($cid && $vorlage) {
	$sql	= "UPDATE `morp_cms_content` set vid=$vorlage WHERE cid=$cid";
	$res	= safe_query($sql);
}

elseif ($copy) {
	$y 		= $pos++;
	if(!$y) {
		$sql  	= "SELECT navid FROM `morp_cms_content` WHERE navid=$edit ORDER BY tpos";
		$res 	= safe_query($sql);
		$y		= mysqli_num_rows($res);
		$y++;
	}

	$sql  	= "SELECT * FROM `morp_cms_content` WHERE cid=$copy";
	$res 	= safe_query($sql);
	$row 	= mysqli_fetch_object($res);
	$tid 	= $row->tid;

	$th 	= $row->theadl;

	if($vorl) 	{ $de = ''; $vorl = 1; $vID = $copy; }
	else 		{ $de = addslashes($row->content); $vorl = 0; $vID = 0; }

	$tlink  = $row->tlink;
	$tb		= $row->tb;
	$timage	= $row->timage;
	$tref   = $row->tref ? $row->tref : 0;
	$tclass   = $row->tclass;
	$klasse = $row->klasse;
	$tabstand   = $row->tabstand ? $row->tabstand : 0;
	$tabstand_bottom  = $row->tabstand_bottom;
	// $tende   = $row->tende;

	$sql	= "INSERT `morp_cms_content` set navid=$edit, tpos=$y, theadl='$th', content='$de', tid='$tid', vid='$vID', tlink='$tlink', tbackground='$tb', timage='$timage', tref='$tref', tclass='$tclass', tabstand='$tabstand', klasse='$klasse', tabstand_bottom='$tabstand_bottom'";
	$res	= safe_query($sql);

	repair_nav($edit);
}
elseif ($del) {
	$warnung = '
	<div class="col-md-12">
		<h4>Wollen Sie das Template wirklich l&ouml;schen?</h4>

		<p><a href="?delete='.$del.'&edit='.$edit.'" title="Content l&ouml;schen!"><i class="fa fa-thumbs-up"></i> Template endg&uuml;ltig l&ouml;schen</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
		<a href="?edit='.$edit.'"><i class="fa fa-thumbs-o-down"></i> NEIN</a></p>
	</div>
';
}
elseif ($delete) {
	$sql = "DELETE FROM `morp_cms_content` WHERE cid=$delete";
	safe_query($sql);
	repair_nav($edit);
	protokoll($uid, "content", $delete, "del");
}
elseif ($Settende) {
	$sql = "SELECT tende FROM `morp_cms_content` WHERE cid=$Settende";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);
	if($row->tende > 0) $set=0;
	else $set=1;

	$sql = "UPDATE `morp_cms_content` set tende=$set WHERE cid=$Settende";
	safe_query($sql);
}
elseif ($Settabstand) {
	$sql = "SELECT tabstand FROM `morp_cms_content` WHERE cid=$Settabstand";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);
	if($row->tabstand > 0) $set=0;
	else $set=1;

	$sql = "UPDATE `morp_cms_content` set tabstand=$set WHERE cid=$Settabstand";
	safe_query($sql);
}
elseif ($Settabstand_bottom) {
	$sql = "SELECT tabstand_bottom FROM `morp_cms_content` WHERE cid=$Settabstand_bottom";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);
	if($row->tabstand_bottom > 0) $set=0;
	else $set=1;

	$sql = "UPDATE `morp_cms_content` set tabstand_bottom=$set WHERE cid=$Settabstand_bottom";
	safe_query($sql);
}
elseif ($_REQUEST["repair"]) {
	repair_nav($edit);
}
elseif ($sichtbar) {
	$query  = "UPDATE `morp_cms_content` SET ton=$sichtbar WHERE cid=$id";
	safe_query($query);
}

// wenn sortierung geaendert wurde, jetzt in db schreiben
elseif ($sort) {
	if ($sort == "up") $s2 = $sortid - 1;
	else $s2 = $sortid + 1;

	$sort_    = array($sortid, $s2);
	$sort_new = array($s2, $sortid);
	$sort_arr = array();

	for($i=0; $i<=1; $i++) {
		$query  = "SELECT * FROM `morp_cms_content` WHERE navid=$edit AND tpos=$sort_[$i]";
		$result = safe_query($query);
		$row = mysqli_fetch_object($result);
		$sort_arr[] = $row->cid;
	}

	for($i=0; $i<=1; $i++) {
		$query  = "UPDATE `morp_cms_content` SET tpos=$sort_new[$i], edit=1 WHERE cid=$sort_arr[$i]";
		safe_query($query);
	}
}

///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////

echo "
<div class=\"row\">

	<div class=\"col-md-3 col-sm-3  \">
		<h2>
			Inhalt: <strong>$titel</strong> &nbsp; <img src=\"images/flag-".$morpheus["lan_arr"][$sprache].".jpg\" style=\"height:20px;\"> &nbsp; &nbsp; &nbsp; &nbsp;
<!--			<a href=\"../index.php?vs=1&cid=$edit&navid=$edit&lan=$sprache\" rel=\"gb_page_center[1240]\" target=\"_blank\">vorschau " .ilink() ."</a>-->
		</h2>
<br>			".($del ? '' : $zuruck )."
	</div>

". ($warnung ? $warnung : "
	<div class=\"col-md-9 col-sm-9 col-xs-12 mb2\">


		<a href=\"?repair=1&edit=$edit\" class=\"button\"><i class=\"fa fa-refresh\"></i> repariere Sortierung</a>

		".'<a href="?neu=1&edit='.$edit.'&navid='.$edit.'" class="button"><i class="fa fa-plus"></i> Neues leeres Template erzeugen</a>

		 <a href="link.php?edit='.$edit.'&navid='.$edit.'" class="button"><i class="fa fa-copy"></i> Template holen</a>

		 '.($sprache > 1 ? '<a href="link.php?edit='.$edit.'&navid='.$edit.'&de=1" class="button"><i class="fa fa-copy"></i>  DE Template GET</a>' : '')) .'

<!--		 <a href="../index.php?vs=1&cid='.$edit.'&navid='.$edit.'&lan='.$sprache.'" class="button" target="_blank"><i class="fa fa-binoculars"></i> Vorschau</a> -->
		 <a href="../index.php?vs=1&cid='.$edit.'&navid='.$edit.'&lan='.$sprache.'&morpheus_edit=xxx" class="button btn-success" style="color:#fff !important"><i class="fa fa-edit" style="color:#fff !important"></i> Live edit</a>

		 <!--<a class="btn btn-info hideDIV"><i class="fa fa-eye"></i></a>-->
	</div>
</div>

';

if ($warnung) die();

$sql	= "SELECT * FROM `morp_cms_content` WHERE navid=$edit ORDER BY tpos";
$res	= safe_query($sql);
$y		= mysqli_num_rows($res);
$x		= 0;


echo '<div id="sortable" class="grid">';

while ($row = mysqli_fetch_object($res)) {
	$x++;
	$id = $row->cid;
	$tid = $row->tid;
	$vid = $row->vid;
	$tl = $row->tlink;
	$tr = $row->tref;
	$th = $row->theadl;
	$po = $row->tpos;
	$on = $row->ton;
	$de = $row->content;
	$en = $row->c_en;
	$tende = $row->tende;
	$tabstand = $row->tabstand;
	$tabstand_bottom = $row->tabstand_bottom;
	$tclass = $row->tclass;

	if($tr) $tr_anzeige = explode(" | ", $morpheus["templ_conf".$tid][$tr]);

	# $txt = substr(get_raw_text($de),0,100) ."...";
	$txt = get_raw_text_morp($de);

	echo '
	<div class="row zeile item'. ($on == 1 ? "" : " off") .($tende ? ' ende' : '').'" id="'.$id.'">
';

	$reihenfolge = '';
	if ($x > 1) 			$reihenfolge .= '<a href="content.php?edit='.$edit.'&cid='.$id.'&sort=up&sortid='.$x.'" title="eine Position nach oben" class="button2"><i class="fa fa-chevron-up small"></i></a>';
	if ($x < $y && $y > 1) 	$reihenfolge .= ' <a href="content.php?edit='.$edit.'&cid='.$id.'&sort=down&sortid='.$x.'" title="eine Position nach oben" class="button2"><i class="fa fa-chevron-down small"></i></a>';

	echo '
		<div class="col-md-5 col-sm-5">'.$po.' /
	';
	if ($vid) {
		echo '
					*** VORLAGE *** &nbsp;

					<form name="form'.$vid.'" style="float:right;">
						<input type="hidden" name="edit" value="'.$edit.'">
						<input type="hidden" name="cid" value="'.$id.'">
						<select name="vorlage" style="float:left;"><option>bitte w&auml;hlen</option>'.pulldownContent ($vid).'</select>

						<button type="submit" name="speichern" class="button bt4"><i class="fa fa-save"></i></button>
					</form>

				</div>
				<div class="col-md-1 col-sm-1">
					<a href="?del='.$id.'&edit='.$edit.'" title="Content l&ouml;schen" class="button" style="margin:0;"><i class="fa fa-trash-o"></i></a>
				</div>

';
	}

	else echo '
			<a href="content_edit.php?edit='.$id.'&navid='.$edit.'" title="Content editieren" class="light ">'.substr(strip_tags($txt), 0, 200).'</a>
		</div>
		<div class="col-md-4 col-sm-4">
			<a href="content_edit.php?edit='.$id.'&navid='.$edit.'" title="Content editieren" class="button bt4"><i class="fa fa-pencil-square-o "></i> edit</a>

			<a href="?sichtbar='. ($on == 1 ? "2" : "1") .'&id='.$id.'&edit='.$edit.'" class="button bt3 '. ($on == 1 ? "" : " aus") .'"><i class="fa fa-eye'. ($on == 1 ? "" : "-slash") .'"></i></a>

			<a href="?copy='.$id.'&edit='.$edit.'&pos='.$po.'" title="Content duplizieren" class="button bt3"><i class="fa fa-copy"></i></a>

			<a href="?del='.$id.'&edit='.$edit.'" title="Content l&ouml;schen" class="button bt3"><i class="fa fa-trash-o"></i></a>

			'.(in_array($tid, $morpheus["template_ende"]) ? '<a href="?tende='.$id.'&edit='.$edit.'" title="Container schließen" class="button bt3" style="margin-left:10px;"><i class="fa fa-minus" style="color:red !important;font-weight:bold;"></i></a>' : '').'

			'.(in_array($tid, $morpheus["template_abstand"]) ? '<a href="?tabstand='.$id.'&edit='.$edit.'" title="Container Abstand oben" class="button bt3 btop'.($tabstand ? ' gray' : '').'"><i class="fa fa-arrows-'.($tabstand ? 'h' : 'v').'"></i></a>' : '').'
			
			'.(in_array($tid, $morpheus["template_pad"]) ? '<a href="?tabstand_bottom='.$id.'&edit='.$edit.'" title="Container Abstand oben" class="button bt3 bbottom'.($tabstand_bottom ? ' gray' : '').'"><i class="fa fa-arrows-'.($tabstand_bottom ? 'h' : 'v').'"></i></a>' : '').'
			
		</div>
		<div class="col-md-2 col-sm-2 pad0">
			<a href="content_template.php?edit='.$id.'&navid='.$edit.'"  class="button bt3 w100 tla templDesc"><i class="fa fa-cogs"></i> &nbsp; '. $morpheus["template"][$tid].''.($tr ? ' | <b>'.$tr_anzeige[0].'</b>' : '').($tclass ? ' / <font class="klasse">'.$tclass.'</font>' : '').($tende ? ' &nbsp; <i class="fa fa-terminal red"></i>' : '').'</a>'. ($th ? ' - <strong>'.$th."</strong>" : "").'
		</div>
		<div class="col-md-1 col-sm-1">
				'.($tl ? '<i class="fa fa-anchor"></i>'.$tl : '').'
				'.$reihenfolge.'
		</div>

';
	echo '

	</div>';
}

echo '</div>';

echo '<p><a href="?neu=1&edit='.$edit.'&navid='.$edit.'" class="button"><i class="fa fa-plus"></i> Neues leeres Template erzeugen</a></p>';
echo '<p><a href="?neu=1&vorlage=1&edit='.$edit.'&navid='.$edit.'" class="button"><i class="fa fa-plus"></i> Neue <strong>VORLAGE</strong> platzieren</a></p>';
?>

</div>

<?php
include("footer.php");
?>


<script>
$( function() {
	wW = $(window).width();
	
	if(wW > 1000) {
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
			pos = "tpos";
			feld = "cid";
			table = "morp_cms_content";
	    	request = $.ajax({
	        	url: "UpdatePos.php",
	        	type: "post",
	        	data: "data="+order+"&pos="+pos+"&feld="+feld+"&table="+table+"&id=<?php echo $edit; ?>",
	        	success: function(data) {
					// console.log(data);
  				}
	    	});
			// MuuriPosition = ($.inArray(ref, order));
		});
		$( ".hideDIV" ).click(function() {
			a = $(this).hasClass( "showDIV" );
			if(a) {
				$(this).removeClass( "showDIV" );
				$(".off").show(500);
			}
			else {
				$(this).addClass( "showDIV" );
				$(".off").hide(500);
			}
		});
	} else {
		$(".row").removeClass( "item" );
		// $(".row").removeClass( "zeile" );
	}
});
</script>

<style>
	.zeile {
    	height: 60px;
	}
</style>