<?
$arr = array("admin", "coach", "verein", "schwimmen", "wasserball", "live", "news", "basketball", "boot", "angeln", "tauchen", "tennis", "fuenfkampf", "mehrkampf");

global $uid;

$un  = $_SESSION["sun"];
$pw  = $_SESSION["spw"];
$uid = $_SESSION['sid'];
$_SESSION['uid'] = $uid;

if ($un || $pw) {
	$un = check_valid_value($un, "s", "50");
	if(!$un) die("Fehler 888");
	$query = "SELECT * from morp_cms_user where uname='" .$un ."'";
	$result = safe_query($query);
	$row = mysqli_fetch_object($result);
	if ($row->pw == $pw && $row->uname == $un) {
		$_SESSION['user_name'] = $row->uname;
		$_SESSION['log'] = "pixel-dusche";
		$_SESSION['sun']  = $un;
		$_SESSION['spw']  = $pw;
		foreach($arr as $val) {
			$_SESSION[$val] = $row->$val;
			global $$val;
			$$val = $row->$val;
		}
	}
}
