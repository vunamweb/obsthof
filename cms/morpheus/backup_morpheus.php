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

include("cms_include.inc");

$f = fopen($morpheus["dfile"], "w");
// $res 		= mysql_list_tables($morpheus["dbname"]);
$res = "SHOW TABLES FROM ".$morpheus["dbname"];
$res = safe_query($res);

$ausschluss = array(
"morp_newsletter",
"morp_newsletter_cont",
"morp_newsletter_track",
"morp_newsletter_versand",
"morp_newsletter_vt",
"morp_newsletter_vt_csv",
"morp_newsletter_vt_test",
"morp_newsletter_vt_test-nurich",
"morp_register",
);

while ($row = mysqli_fetch_row($res)) {
	#print_r($row);
    if(in_array($row[0], $ausschluss)) {}
    else $tables[] = $row[0];
}

# print_r($tables);

$n = 0;

foreach ($tables as $table) {
	# echo $table."<br>";
	fwrite($f,"DROP TABLE `$table`;\n");
	$sql = "SHOW CREATE TABLE `$table`";
	$res = safe_query($sql);

	if ($res) {
		$create = mysqli_fetch_array($res);
		$create[1] .= ";";
		$line = str_replace("\n", "", $create[1]);

		fwrite($f, $line."\n");
		$que 	= "SELECT * FROM `$table`";
		$result = safe_query($que);
		$num 	= mysqli_num_fields($result);

		while ($row = mysqli_fetch_array($result)){
			$n++;
		    $line = "INSERT INTO `$table` VALUES(";
		    for ($i=1;$i<=$num;$i++) {
		     	$line .= "'".mysqli_real_escape_string($mylink, stripslashes($row[$i-1]))."', ";
		    }
	    	$line = substr($line,0,-2);
	    	fwrite($f, $line.");\n");
	 	}
	}
}
fclose($f);

echo '
<div id=content_big class=text>
';

?>
<style>
	.lds-ring {
	  display: inline-block;
	  position: relative;
	  width: 80px;
	  height: 80px;
	}
	.lds-ring div {
	  box-sizing: border-box;
	  display: block;
	  position: absolute;
	  width: 64px;
	  height: 64px;
	  margin: 8px;
	  border: 8px solid red;
	  border-radius: 50%;
	  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
	  border-color: red transparent transparent transparent;
	}
	.lds-ring div:nth-child(1) {
	  animation-delay: -0.45s;
	}
	.lds-ring div:nth-child(2) {
	  animation-delay: -0.3s;
	}
	.lds-ring div:nth-child(3) {
	  animation-delay: -0.15s;
	}
	@keyframes lds-ring {
	  0% {
		transform: rotate(0deg);
	  }
	  100% {
		transform: rotate(360deg);
	  }
	}
</style>


<div class="lds-ring"><div></div><div></div><div></div><div></div></div>

<script>
	location.href="backup_morpheus_run.php";
</script>
