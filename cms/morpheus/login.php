<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

#$admin  = 1;
#$uid    = 1;
#$counter = $_SESSION['counter'];

# print_r($_SESSION);

$log = isset($_SESSION['log']) ? $_SESSION['log'] : '';
$arr = array("admin", "uid");
$anmelden = isset($_POST["anmelden"]) ? $_POST["anmelden"] : '';

function logform($warn='') {
		echo '

<div class="conatiner">
<div class="col-xs-12 center">
	<div id="logform" class="text">
		<p><b>'.$warn.'</b></p>

		<p>Sie betreten einen gesch&uuml;tzten Kundenaccount.<br>
			Bitte melden Sie sich an.</p>

		<form method="post" name="logform">
			<p><input type="text" name="un"> benutzername</p>
			<p><input type="password" name="pw"> passwort</p>
			<p><input type="submit" name="anmelden" value="anmelden"></p>
		</form>
	</div>
</div>
 </div>

<script>
<!--
	document.logform.un.focus();
-->
</script>
';
}

function endhtml() {
	echo "</body></html>";
}


// print_r($_SESSION);

if ($log == "pixel-dusche") {
	global $user_name;

	foreach($arr as $val) {
		global $$val;
		$$val = $_SESSION[$val];
	}
	$auths = explode("|", $_SESSION['auths']);

	#echo '<p style="color:#FFFFFF">log: ';
	#echo $admin .", " .$user_name = $_SESSION['user_name'];
	#echo '</p>';
}
elseif ($anmelden) {
	$un  = isset($_POST["un"]) ? $_POST["un"] : '';
	$pw  = isset($_POST["pw"]) ? $_POST["pw"] : '';

	if ($un || $pw) {
		$pw = md5($pw);
		$un = check_valid_value($un, "s", "50");
		if(!$un) die("Fehler 888");
		$query = "SELECT * from morp_cms_user where uname='" .$un ."'";
		$result = safe_query($query);
		$row = mysqli_fetch_object($result);
		if ($row->pw == $pw && $row->uname == $un) {
			$_SESSION['user_name'] 	= $row->uname;
			$_SESSION['log'] 		= "pixel-dusche";
			$_SESSION['sun']  		= $un;
			$_SESSION['sid']  		= $row->uid;
			$_SESSION['spw']  		= $pw;
			$_SESSION['firstname']	= $row->firstname;
			$_SESSION['lastname']	= $row->lastname;
			$_SESSION['admin'] 		= $row->admin;
			$_SESSION['auths'] 		= $row->auths;
			$auths = explode("|", $row->auths);
			#$uid    = 1;

			foreach($arr as $val) {
				$_SESSION[$val] = $row->$val;
				global $$val;
				$$val = $row->$val;
			}
		}
		else {
			logform("<p><font color=#ff0000>Ihre UserID und/oder Passwort waren falsch</font></p>");
			endhtml();
			die();
		}
	}
	else {
		logform();
		endhtml();
		die();
	}
}
else {
	logform();
	endhtml();
	die();
}

