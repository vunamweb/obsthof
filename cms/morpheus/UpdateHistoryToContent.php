<?php
session_start();

global $mylink;

include("../nogo/config.php");
include("../nogo/config_morpheus.inc");
include("../nogo/db.php");
dbconnect();
include("login.php");
include("../nogo/funktion.inc");

$id = $_POST["get"];
$id = check_valid_value($id, "i");
if(!$id) die("Fehler 333");

$sql  = "SELECT content, cid FROM `morp_cms_content_history` WHERE id=$id";
$res = safe_query($sql);
$row = mysqli_fetch_object($res);

$toID = $row->cid;
$cont = $row->content;

$sql  = "UPDATE `morp_cms_content` SET content='$cont' WHERE cid=$toID";
safe_query($sql);
