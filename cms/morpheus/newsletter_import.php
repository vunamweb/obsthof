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
//error_reporting(E_ALL);
include("cms_include.inc");

?>

<div id=vorschau>

<?php 
	echo $sql = "UPDATE morp_register set `checked`=0 WHERE 1";
	$res = safe_query($sql); 
	
	echo $sql = "SELECT * FROM lm_users WHERE cnf=1 AND `list`>0 AND `list`<4";
	$res = safe_query($sql); 
	
	while ($row = mysqli_fetch_object($res)) {	
		$sql = "SELECT * FROM morp_register WHERE email='".$row->email."'";
		$rs = safe_query($sql); 
		
		$art = "art".$row->list;

		if(mysqli_num_rows($rs)>0) {
			$rw = mysqli_fetch_object($rs);
			$sql = "UPDATE morp_register set $art=1, checked=1 WHERE vid=".$rw->vid;
			safe_query($sql);
		}
		else {
			$sql = "INSERT morp_register set $art=1, vorname='".$row->fname."', name='".$row->lname."', email='".$row->email."', checked=1";
			safe_query($sql);	
		}
	}

?>

</div>

</body>
</html>