<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # # 
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
# edit 27.11.2006                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # # 

session_start();
#$box = 1;
include("cms_include.inc");

///////////////////////////////////////////////////////////////////////////////////////

if ($edit || $neu) $vorschau = '<p><a href="newsletter.php?" target="_blank">&raquo; Newsletter Liste</a></p>';	
else $vorschau = '';

echo '<div id=vorschau>
	<h2>NICHT versendete Newsletter/Mails</h2>
	

	'. ($edit || $neu ? '<p><a href="?">&laquo; zur&uuml;ck</a></p>' : '') .'	
';

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

	$db = "morp_newsletter_vt";
	$id = "vid";
	$ord = "vid";
	$anz = "email";
	$anz2 = "firma";
	$anz3 = "nlid";
	////////////////////
	
	$sql = "SELECT * FROM $db WHERE versand<1 ORDER BY nlid,vid";
	$res = safe_query($sql); 
	$x = mysqli_num_rows($res);
	
	if($x > 0) {
		$y=0;
		echo '<table>';
		while ($row = mysqli_fetch_object($res)) {	
			$y++;
			echo '<tr><td>'.$y.'</td><td width=40 align=center>'.$row->nlid.'</td><td>'.$row->email.' &nbsp; &nbsp; &nbsp;</td><td>'.$row->firma.'</td><td>'.$row->name.'</td></tr>';
		}
		echo '</table>';
	}


/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////




include("footer.php");

?>
