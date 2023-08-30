<?php
	// print_r($_SESSION);

global $dir, $navID, $acceptCookie, $countLogins, $userIsLogIn, $mid, $js;

$pw = isset($_POST["pwd"]) ? $_POST["pwd"] : '';
$un = isset($_POST["unr"]) ? $_POST["unr"] : '';
$setMeFree = isset($_POST["cls"]) ? $_POST["cls"] : '';

$pwA = isset($_POST["pw1"]) ? $_POST["pw1"] : '';
$pwB = isset($_POST["pw2"]) ? $_POST["pw2"] : '';
$sec = isset($_POST["sec"]) ? $_POST["sec"] : '';

// VORSICHT vor Attacken * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
if($pw != no_injection ($pw) || $un != no_injection ($un)) die();
//  * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

$warn = '';
$min = 6;
$maxVersuche = 6;
$mitarbeiter = 0;

$sessMNR = isset($_SESSION["uname"]) ? $_SESSION["uname"] : '';
$sessPASS = isset($_SESSION["pd"]) ? $_SESSION["pd"] : '';
$countLogins = isset($_SESSION["cl"]) ? $_SESSION["cl"] : 0;

$_SESSION["cl"] = 0;

if($sessMNR && $sessPASS) {
	$sql = "SELECT * FROM `morp_intranet_user` WHERE pw='".$sessPASS."' AND uname='".$sessMNR."'";
	$res = safe_query($sql);
	$x = mysqli_num_rows($res);
	if($x > 0) { $haslogin = 0; $_SESSION["mIL"] = get_token(); $userIsLogIn = $_SESSION["mIL"]; }
	else { $_SESSION["uname"]='';$_SESSION["pd"]='';}
}
elseif($setMeFree && $un) {
	$sql = "SELECT * FROM `morp_intranet_user` WHERE setMeFree='".($setMeFree)."' AND uname='$un'";
	$res = safe_query($sql);
	$x = mysqli_num_rows($res);
	if($x>0) {
		$_SESSION["cl"] = 0;
		$countLogins = 0;

		$row = mysqli_fetch_object($res);
	 	$sql = "UPDATE `morp_intranet_user` SET countLogins=0 WHERE mid='".$row->mid."'";
		safe_query($sql);

		$output_login .= '<p><br/><a href="?"><i class="fa fa-chevron-right"></i> Please log in again</a></p>';
	}
}
else {
	if($pwA && $pwB) {
		$mid = holeID($sec);
		$nolog = 0;
		if($pwA != $pwB) $output_login .= '<h2>The passwords do not match</h2><p>&nbsp;</p>'.newPW($min, $mid);
		elseif(strlen($pwA) < $min) $output_login .= '<h2>The password must contain at least '.$min.' characters.</h2><p>&nbsp;</p>'.newPW($min, $mid);
		else {
			$zahl = 0;
			$zeichen = 0;
			if(preg_match("/\d/", $pwA)) $zahl = 1;
			if(preg_match("/[a-zA-Z]/", $pwA)) $zeichen = 1;
			if(!$zahl) $output_login .= '<h2>The password must contain at least one number.</h2><p>&nbsp;</p>'.newPW($min, $mid);
			elseif(!$zeichen) $output_login .= '<h2>The password must contain at least one letter.</h2><p>&nbsp;</p>'.newPW($min, $mid);
			else if($mid) {
				$sql = "UPDATE `morp_intranet_user` set kontrolle='1', pass='".md5($pwA)."', newpass=0 WHERE mid='".$mid."'";
				$res = safe_query($sql);
				#$sql = "SELECT mnr FROM `morp_intranet_user` WHERE `morp_intranet_user`=$mid";
				#$res = safe_query($sql);
				#$row = mysqli_fetch_object($res);
				$output_login .= '<h2>The password was changed successfully.</h2><p>&nbsp;</p>';
				$_SESSION["custname"] = $mid;
				$_SESSION["pd"] = md5($pwA);
			}
		}
	}
	elseif($pw && $un) {
		// $sql = "SELECT * FROM `morp_intranet_user` WHERE pw='".md5($pw)."' AND uname='$un' AND kontrolle=1";
		$sql = "SELECT * FROM `morp_intranet_user` WHERE pw='".md5($pw)."' AND uname='$un'";
		$res = safe_query($sql);
		$x = mysqli_num_rows($res);

		if($x > 0) {
			$row = mysqli_fetch_object($res);
			if($row->isallowed) {
			#$sql = "UPDATE `morp_intranet_user` set countLogins=0 WHERE mid='".$row->mid."'";
			#safe_query($sql);
			 	# echo " YOU ARE IN !!!!";
			
				if($row->newpass) {
					$nolog = 0;
					$newpass=1;
					$output_login .= newPW($min, $row->mid);
				}
				else {
					$haslogin = 0;
					$_SESSION["uname"] = $row->uname;
					$_SESSION["vname"] = $row->vname;
					$_SESSION["nname"] = $row->nname;
					$_SESSION["anrede"] = $row->anrede;
					$_SESSION["email"] = $row->email;
					$_SESSION["pd"] = $row->pw;
					$_SESSION["mid"] = $row->mid;
					$mid = $row->mid;
					
					// morpheusIntranetLogged
					$_SESSION["mIL"] = get_token();
					$userIsLogIn = $_SESSION["mIL"]; 
	                $firstLogin = 1;
					
	                $sql = "INSERT morp_intranet_user_track SET mid=".$row->mid;
					safe_query($sql);
	
					$lastlog = date("Y-m-d H:i");
					$sql = "UPDATE `morp_intranet_user` SET lastlog='$lastlog' WHERE mid='".$row->mid."'";
					safe_query($sql);
					
					$js = 'document.location.href="'.$dir.($lan=="de" ? '' : $lan.'/').'";';
	
					// $_SESSION["account"] = $row->testaccount ? 11 : 1;
					// $sprung = $_SESSION["wherefrom"];
					// $warn = '<p>Ihr Login war erfolgreich.<br><br>'.($sprung ? '<a href="'.$dir.$navID[$sprung].'"><i class="fa fa-external-link-square"></i> Weiter zur zuletzt besuchten Seite </p>' : '</p>');
					// $_SESSION["wherefrom"] = '';
					// $col = 'green';
				}
			} else $warn = '<div class="alert alert-danger" role="alert">'.textvorlage(26).'</div>';
		}
		else {
			$_SESSION["cl"] = $countLogins+1;
			$sql = "SELECT countLogins, mid FROM `morp_intranet_user` WHERE uname='$un'";
			$res = safe_query($sql);
			$x = mysqli_num_rows($res);
			if($x>0) {
				$row = mysqli_fetch_object($res);
				$a = $row->countLogins;
				$a++;
			 	$sql = "UPDATE `morp_intranet_user` SET countLogins=$a WHERE mid='".$row->mid."'";
				safe_query($sql);
			}
			$warn = '<div class="alert alert-danger" role="alert">'.textvorlage(25).'</a></div>';
		}
	}

}

if($newpass) { 
	
}

else if($haslogin && $countLogins > $maxVersuche)

		$output_login .= '

<form name="golog" method="post">
	<div class="form-group">
		<label for="cls">Username</label>
		<input type="text" class="form-control" id="unr" name="unr" placeholder="'.textvorlage(10).'" style="background:red; color:#fff;">
	</div>
	<div class="form-group">
		<label for="cls">Code from Admin</label>
		<input type="text" class="form-control" id="cls" name="cls" placeholder="Code" style="background:red; color:#fff;">
	</div>

	<button type="submit" class="btn btn-info btn-default">Unlock</button>
</form>
	';

else if($haslogin)
		$output_login .= '
<form name="golog" method="post" class="text-center">
	<div class="form-group">
		<input type="text" class="form-control" id="unr" name="unr" placeholder="'.textvorlage(10).'">
	</div>
	<div class="form-group">
		<input type="password" class="form-control" id="pwd" name="pwd" placeholder="'.textvorlage(16).'">
	</div>

	<button type="submit" class="btn btn-info mt3">'.textvorlage(24).'</button>
</form>
	';


	$output_login = $warn.$output_login;
	//echo $warn.$output_login;
