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

$newsletter_in = 'in';
include("cms_include.inc");

echo '
  	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.sparkline.min.js"></script>
 
';

echo '<div id=content_big>
';
	


$vz = isset($_POST["versandid"]) ? $_POST["versandid"] : '';

$query  = "SELECT versandid, versandzeit, vid FROM `morp_newsletter_vt` WHERE 1 GROUP BY versandid ORDER BY vid";
$result = safe_query($query);
$sel = '';
$first = '';

while($row = mysqli_fetch_object($result)) {
	if(!$first) $first = $row->versandid;
	
	$tmp = $row->versandid;
	$sql  = "SELECT versandid, versandzeit, nlid FROM `morp_newsletter_vt` WHERE versandid='$tmp'";
	$res = safe_query($sql);
	$ct = mysqli_num_rows($res);

	$rw = mysqli_fetch_object($res);	
	$tmp = $rw->nlid;
	$sql  = "SELECT nlname, nlvdatum FROM `morp_newsletter` WHERE nlid='$tmp' ORDER BY nlvdatum";
	$res = safe_query($sql);
	$rw = mysqli_fetch_object($res);	
	
	$sel .= '<option value="'.$row->versandid.'"'.($row->versandid == $vz ? ' selected' : '').'>'.euro_dat($rw->nlvdatum).' | '.$rw->nlname.'</option>';
}

if(!$vz) $vz = $first;


echo "<p><b>TRACKING</b></p>".'
<p>&nbsp;</p>

<form method="post" name="dat">
	<select name="versandid" style="width:500px;" >'.$sel.'</select> &nbsp; &nbsp; &nbsp; 
	<input type="submit" />
</form>

<p>&nbsp;</p>

';

$query  = "SELECT vid FROM `morp_newsletter_vt` WHERE versandid = '$vz'";
$result = safe_query($query);
$row = mysqli_fetch_object($result);
$ct = mysqli_num_rows($result);
$gesendet = $ct;

$query  = "SELECT vid FROM `morp_newsletter_vt` WHERE versandid = '$vz' ORDER BY vid LIMIT 0,1";
$result = safe_query($query);
$row = mysqli_fetch_object($result);
$erster = $row->vid;

$query  = "SELECT vid FROM `morp_newsletter_vt` WHERE versandid = '$vz' ORDER BY vid DESC LIMIT 0,1";
$result = safe_query($query);
$row = mysqli_fetch_object($result);
$letzter = $row->vid;



$query  = "SELECT vid FROM `morp_newsletter_track` WHERE vid >= '$erster' AND vid <= '$letzter' GROUP BY vid";
$result = safe_query($query);
$row = mysqli_fetch_object($result);
$ct = mysqli_num_rows($result);
$empfangen = $ct;



$query = "SELECT vid FROM morp_newsletter_track  WHERE vid >= '$erster' AND vid <= '$letzter' AND site <> 'LOGO geladen' GROUP BY vid";
$result = safe_query($query);
$row = mysqli_fetch_object($result);
$ct = mysqli_num_rows($result);
$klicks = $ct;


# SELECT id FROM tabelle ORDER BY id DESC LIMIT 1



echo '
<div style="display:block; width:300px; float:left;">
	<p>Anzahl gesendete Newsletter: <b>'.$gesendet.'</b></p>
	<p>Anzahl Empfänger: <b>'.$empfangen.'</b></p>
';

$anteil = ($gesendet - $empfangen);
$proz = ($empfangen / $gesendet);
$proz = $proz * 100;
$proz = number_format($proz, 2);

echo '
	<p>Erhalten: <b>'. $proz .' %</b></p>
	<p>Anzahl Empfänger, die einen Link geklickt haben: <b>'. $klicks .'</b></p>
</div>
';


echo '

    <script type="text/javascript">
    $(function() {
        $(\'.inlinebar\').sparkline(\'html\', {type: \'pie\', barColor: \'red\', height: \'100\'} );
    });
    </script>
<p><span class="inlinebar">'.($empfangen-$klicks).', '.$anteil.', '.$klicks.'</span></p>

<br style="clear:left;" />
';


echo '
<table width="700">';





$query  = "SELECT * FROM `morp_newsletter_track` t, morp_newsletter_vt v WHERE v.vid=t.vid AND t.vid >= '$erster' AND t.vid <= '$letzter' ORDER by email, firma, datum";
$result = safe_query($query);

while ($row = mysqli_fetch_object($result)) {
	$datum = explode(" ", $row->datum);
	$class = '';
	if (eregi("^LOGO", $row->site)) $class = ' style="background:#e2e2e2;"';
	echo '<tr><td'.$class.'>'.$row->vid.'</td><td'.$class.'>'.$row->nachname.'</td><td'.$class.'>'.$row->email.'</td><td'.$class.'>'.$row->site.'</td><td nowrap'.$class.'>'.euro_dat($datum[0]).' - '.$datum[1].'</td></tr>';
}

?>

</table>
</div>

<?php
include("footer.php");
?>