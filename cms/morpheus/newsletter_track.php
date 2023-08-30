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
	
$nlid = isset($_POST["nlid"]) ? $_POST["nlid"] : '';

$query  = "SELECT * FROM `morp_newsletter_track` WHERE 1 GROUP BY nlid ORDER BY kurzdat";
$result = safe_query($query);
$sel = '';
$first = '';

while($row = mysqli_fetch_object($result)) {
	if(!$first) $first = $row->nlid;
	
	$tmp = $row->nlid;
	$rw = mysqli_fetch_object($res);	
	$sql  = "SELECT * FROM `morp_newsletter` WHERE nlid=$tmp";
	$res = safe_query($sql);
	$rw = mysqli_fetch_object($res);	
	
	$sel .= '<option value="'.$row->nlid.'"'.($row->nlid == $nlid ? ' selected' : '').'>'.$rw->nlname.'</option>';
}

if(!$nlid) $nlid = $first;

echo "<p><b>TRACKING</b></p>".'
<p>&nbsp;</p>

<form method="post" name="dat">
	<select name="versandid" style="width:500px;" >'.$sel.'</select> &nbsp; &nbsp; &nbsp; 
	<input type="submit" />
</form>
<p>&nbsp;</p>';

$query  = "SELECT vid FROM `morp_newsletter_track` WHERE nlid=$nlid GROUP BY vid";
$result = safe_query($query);
$row = mysqli_fetch_object($result);
$ct = mysqli_num_rows($result);
$empfangen = $ct;

$query = "SELECT vid FROM morp_newsletter_track  WHERE nlid=$nlid AND site <> 'Social Image geladen' GROUP BY vid";
$result = safe_query($query);
$row = mysqli_fetch_object($result);
$ct = mysqli_num_rows($result);
$klicks = $ct;

$query = "SELECT vid FROM morp_newsletter_track  WHERE nlid=$nlid AND site <> 'Social Image geladen'";
$result = safe_query($query);
$row = mysqli_fetch_object($result);
$ct = mysqli_num_rows($result);
$all_klicks = $ct;

$query  = "SELECT * FROM `morp_newsletter_vt_track` WHERE nlid=$nlid AND vid>0";
$result = safe_query($query);
$row = mysqli_fetch_object($result);
$ct = mysqli_num_rows($result);
$versendet = $ct;

// $query  = "SELECT * FROM `morp_newsletter_vt_live` WHERE 1";
// $result = safe_query($query);
// $row = mysqli_fetch_object($result);
// $ct = mysqli_num_rows($result);
// $angemeldete = $ct;


# SELECT id FROM tabelle ORDER BY id DESC LIMIT 1



echo '
<div style="display:block; width:300px; float:left;">
	<p>Anzahl Versand: <b>'.$versendet.'</b></p>
<!--	<p>Anzahl angemeldete: <b>'.$angemeldete.'</b></p>-->
	<p>Anzahl geöffnete Newsletter: <b>'.$empfangen.'</b></p>
	<p>Anzahl Empfänger, die geklickt haben: <b>'.$klicks.'</b></p>
	<p>Anzahl alle Klicks: <b>'.$all_klicks.'</b></p>
';

// $anteil = ($gesendet - $empfangen);
// $proz = ($empfangen / $gesendet);
// $proz = $proz * 100;
// $proz = number_format($proz, 2);
// 
// echo '
// 	<p>Erhalten: <b>'. $proz .' %</b></p>
// 	<p>Anzahl Empfänger, die einen Link geklickt haben: <b>'. $klicks .'</b></p>
// </div>
// ';


// echo '
// 
//     <script type="text/javascript">
//     $(function() {
//         $(\'.inlinebar\').sparkline(\'html\', {type: \'pie\', barColor: \'red\', height: \'100\'} );
//     });
//     </script>
// <p><span class="inlinebar">'.($empfangen-$klicks).', '.$anteil.', '.$klicks.'</span></p>
// 
// <br style="clear:left;" />
// ';


echo '
<div class="container">
<div class="row">
<table class="autocol" style="width:100%;">';

$query  = "SELECT * FROM `morp_newsletter_track` t1, morp_newsletter_vt t2 WHERE t1.vid=t2.vid AND t1.nlid=$nlid ORDER by email";
$result = safe_query($query);

while ($row = mysqli_fetch_object($result)) {
	// echo $row->email;
	$datum = explode(" ", $row->datum);
	$class = '';
	if (isin("^Social", $row->site)) $class = ' style="background:#e2e2e2;"';
	echo '<tr><td'.$class.'>'.$row->vid.'</td><td'.$class.'>'.$row->email.'</td><td'.$class.'>'.$row->site.'</td><td nowrap'.$class.'>'.euro_dat($datum[0]).' - '.$datum[1].'</td></tr>';
}

?>

</table>
</div>
</div>

</div>

<?php
include("footer.php");
