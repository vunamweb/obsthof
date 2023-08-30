<?php
session_start();
error_reporting(0);

//include("cms_include.inc");
//include("_tinymce.php");
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

$arr = array(4 => "Kein Zugang Morpheus", 2 => "Redakteur", 1 => "Administrator");

$aktuere_in = 'in';
$akt1_active = ' class="active"';

if (!$_REQUEST["add_properties"] && !$_REQUEST["list_properties"] && !$_REQUEST["search_combine"]) {
    include "cms_include.inc";
    include "_tinymce.php";
} else {
    include "cms_include_properties.inc";
}

$mid = $_REQUEST["mid"];
$neu = $_REQUEST["neu"];
$save = $_REQUEST["save"];
$unm = $_REQUEST["unm"];
$vnm = $_REQUEST["vname"];
$nnm = $_REQUEST["nname"];
$email = $_REQUEST["email"];
$organisation = $_REQUEST["organisation"];
$titel = $_REQUEST["titel"];
$website = $_REQUEST["website"];
$notes = $_REQUEST["notes"];
$confirm_email = $_REQUEST['confirm-email'];

$isallowed = $_REQUEST["isallowed"];
$optin = $_REQUEST["optin"];

$anrede = $_REQUEST["anrede"];
$fon = $_REQUEST["fon"];
$pwd = $_REQUEST["pwd"];
$adm = $_REQUEST["adm"];

$newpass = $_REQUEST["newpass"];

$del = $_REQUEST["del"];
$delete = $_REQUEST["delete"];

$add_properties = $_REQUEST['add_properties'];
$list_properties = $_REQUEST['list_properties'];

$hashtags = $_REQUEST['hashtags'];
$hashtags_interests = $_REQUEST['hashtags_interests'];
$search_combine = $_REQUEST['search_combine'];
$search_value = $_REQUEST['search_value'];

$GH = ($_REQUEST['GH'] == 'on') ? 1 : 0;
$BC = ($_REQUEST['BC'] == 'on') ? 1 : 0;

$value_first_time = $_REQUEST['value_first_time'];
$receive_mail = $_REQUEST['receive_mail'];
$check_receive_mail = $_REQUEST['check_receive_mail'];

echo "<div>";

if ($add_properties) {
    $response = '';

    $sql = "SELECT * FROM morp_intranet_user_properties";
    $res = safe_query($sql);

    while ($row = mysqli_fetch_object($res)) {
        $response .= '<div><input type="checkbox" value=' . ($row->mpid) . ' class="modal_checkbox_properties">&nbsp; &nbsp; &nbsp;<label>' . ($row->property) . '</label></div>';
    }

    echo $response;die();
} elseif ($list_properties) {
    $list_user = $_REQUEST['list_user'];
    $list_user = explode(";", $list_user);

    $delete = $_REQUEST['delete'];

    //print_r($list_user); die();

    foreach ($list_user as $mid) {
        if ($mid != '') {
            if (!$delete) {
                updatePropertiesUser($list_properties, $mid);
            } else {
                deletePropertiesUser($list_properties, $mid);
            }

        }
    }

    die();
} elseif ($delete && $admin) {
    $sql = "DELETE FROM morp_intranet_user WHERE mid=$delete";
    $res = safe_query($sql);
} elseif ($del) {
    $sql = "SELECT uname FROM morp_intranet_user WHERE mid=$del";
    $res = safe_query($sql);
    $row = mysqli_fetch_object($res);

    echo ('
		<p>M&ouml;chten Sie den morp_intranet_user <b>' . $row->uname . '</b> wirklich l&ouml;schen?</p>
		<p><a href="?delete=' . $del . '" class="button">Ja</a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href="?" class="button">Nein</a></p><br><br><br><br>
');
}

if ($save) {
    $ct = count($arr);

    $isallowed = $isallowed ? 1 : 0;
    $optin = $optin ? 1 : 0;
    $adm = $adm ? $adm : 0;
    //$newpass=1;
    $pwd = md5($pwd);
    $set .= "uname='$unm', vname='$vnm', email='$email', anrede='$anrede', fon='$fon', nname='$nnm'" . ($neu || $newpass ? ", pw='$pwd'" : '') . ", admin=$adm,
	isallowed=$isallowed , optin=$optin, GH=$GH, BC=$BC, receive_mail=".getValueReceiveMail($value_first_time, $isallowed).", organisation='$organisation', titel='$titel', website='$website', 
	notes='$notes', mail_text='$confirm_email'";

    if ($neu) {
        $query = "insert morp_intranet_user ";
    } else {
        $query = "update morp_intranet_user ";
    }

    $query .= "set " . $set;

    if (!$neu) {
        $query .= " WHERE mid=$mid";
    }

    //echo $query; die();

    safe_query($query);

    // echo $query;

    // update properties list
    $listProperties = $_POST['listProperties'];
    $listInterests = $_POST['listInterests'];
    
    updatePropertiesUser($listProperties, $mid);
    updateInterestsUser($listInterests, $mid);
    // if option is check, then send mail
    if($optin) {
       include '../inc/send.php';
       include "../nogo/config.php";

       if(chceksendMail($value_first_time, $isallowed, $check_receive_mail))
          sendMail($isallowed, $vnm.' '.$nnm, $email, $confirm_email);
    }

    unset($neu);
    unset($mid);
}

if ($mid || $neu) {
    echo "<h2>Mitgliederverwaltung</h2>";

    if (!$neu) {
        $query = "SELECT * FROM morp_intranet_user WHERE mid=$mid ";
        $result = safe_query($query);
        $row = mysqli_fetch_object($result);
    }

    foreach ($arr as $val) {
        if ($row->$val == 1) {
            $$val = "checked";
        }

    }

    $admin = $row->admin || $row->berechtigung == 1 ? " checked" : '';
    $bere = $neu ? " checked" : '';

    $input = ($row->receive_mail) ? '<input type="hidden" name="receive_mail" value="1"><input type="checkbox" name="check_receive_mail">&nbsp;&nbsp;<label>Send mail to user</label><br></br>' : '<input type="hidden" name="value_first_time" value="1"><input type="hidden" name="receive_mail" value="1">';

	// Bjoern added / edited - 2022-06-07
	if($row->anrede=="Frau") $mail_txt_confirm_from_lang_file = str_replace('(r)','',textvorlage(61));
	else if($row->anrede=="Herr") $mail_txt_confirm_from_lang_file = str_replace('(r)','r',textvorlage(61));
	else $mail_txt_confirm_from_lang_file = textvorlage(61);
    $mail_txt_confirm = $row->mail_text ? $row->mail_text : $mail_txt_confirm_from_lang_file;

    echo '<p><a href="?">' . backlink() . ' zur&uuml;ck</a></p><p>&nbsp;</p>';
    echo '
	<form method="post" id="form_user_intranet">
		<div class="container-full">
			<div class="row">
				<div class="col-md-4">
					<input type="hidden" name="neu" value="' . $neu . '">
					<input type="hidden" name="mid" value="' . $mid . '">
					<input type="text" name="unm" value="' . $row->uname . '" class="form-control" readonly>
					<input type="hidden" name="save" value="1">
					
					<p class="mt1"><label>Anrede</label><input type="text" name="anrede" value="' . $row->anrede . '" class="form-control" placeholder=""></p>
					<p class="mt1"><label>Titel</label><input type="text" name="titel" value="' . $row->titel . '" class="form-control" placeholder=""></p>
					<p class="mt1"><label>Vorname</label><input type="text" name="vname" value="' . $row->vname . '" class="form-control" placeholder=""></p>
					<p class="mt1"><label>Nachname</label><input type="text" name="nname" value="' . $row->nname . '" class="form-control" placeholder=""></p>
					<p class="mt1"><label>E-Mail</label><input type="text" name="email" value="' . $row->email . '" class="form-control" placeholder=""></p>
					<p class="mt1"><label>Institution/Organisation</label><input type="text" name="organisation" value="' . $row->organisation . '" class="form-control" placeholder=""></p>
					<p class="mt1"><label>Webseite</label><input type="text" name="website" value="' . $row->website . '" class="form-control" placeholder=""></p>
					
					
				</div>
				<div class="col-md-3">
        			<p class="mt1"><label>Type</label></p>
        			<p class="mt1">' . showTypeUser($mid, "GH") . '</p>
        			<p class="mt1">' . showTypeUser($mid, "BC") . '</p>
        			<p class="mt1"><label>Properties</label>' . showProperties($mid) . '</p>
        			<p class="mt1"><label>Interests</label>' . showInterests($mid) . '</p>
        			<!--<p class="mt1"><label>Passwort</label><input type="text" name="pwd" value="' . $row->pw . '" class="form-control" placeholder=""></p>-->
					<p>&nbsp;</p>
				</div>
				<div class="col-md-5">
					<p class="mt1"><input type="checkbox" name="optin" id="optin" value="1" class=""' . ($row->optin ? ' checked' : '') . '> &nbsp; <label for="optin">E-Mail bestätigt</label></p>
					<p class="mt1"><input type="checkbox" name="isallowed" id="isallowed" value="1" class=""' . ($row->isallowed ? ' checked' : '') . '> &nbsp; <label for="isallowed">freischalten</label></p>					
					<p>&nbsp;</p>
        			'.$input.'
					<p class="mt1"><label>Text E-Mail</label><textarea class="summernote form-control" name="confirm-email" />'.$mail_txt_confirm.'</textarea></p>			
					<p><input type="button" class="button submit_user_intranet ui fluid" name="speichern" value="speichern"></p>
				</div>
       		</div>
			<div class="row">
				<div class="col-md-4">
					<img src="../mthumb.php?w=200&amp;src=images/portrait/'.$row->img.'" class="img-fluid mt2 mb3">
				</div>
				<div class="col-md-8">
					<p class="mt1"><label>Interne Notizen</label><textarea class="summernote form-control" name="notes" />'.$row->notes.'</textarea></p>			
        			<p class="mt1"><label>Interest</label><textarea class="form-control" name="interests" readonly />'.$row->interests.'</textarea></p>			
				</div>
			</div>
		</div>
   ';
   echo '<div id="waitbg" class="hide"></div>';
   echo '<div id="wave1" class="hide"></div>';

} elseif ($admin) {
    //modal
    echo '<!-- The Modal -->
	<div class="modal" id="myModal">
	  <div class="modal-dialog">
		<div class="modal-content">

		  <!-- Modal Header -->
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		  </div>

		  <!-- Modal body -->
		  <div class="modal-body">

		  </div>

		  <!-- Modal footer -->
		  <div class="modal-footer">
			<button type="submit" class="btn save_properties" data-dismiss="modal">Add</button>
			<button type="submit" class="btn delete_properties" data-dismiss="modal">Delete</button>
			<input type="hidden" value="" id="list_user">
		  </div>

		</div>
	  </div>
	</div>';
    // end modal

    echo "<h2>Liste berechtigter Mitarbeiter f&uuml;r das Intranet</h2><p>&nbsp;</p>";

    // search
    echo formSearch();

    echo '<div style="clear:left;">
	<p>
	  <a href="#" class="button add_properties" data-toggle="modal" data-target="#myModal"><i class="fa fa-users small"></i> <i class="fa fa-plus small"></i> Eigenschaften hinzufügen </a>
	</p>
	<p class="message_info hide"></p>
	</div>
	';

    echo '<div id="list_user_search"><table border=0 cellspacing=1 cellpadding=0 class="autocol p20">';
    echo '<tr>
		<th>Benutzername</th>
		<th>Anrede</th>
		<th>Vorname</th>
		<th>Name</th>
		<th>Institution</th>
		<th>berechtigt</th>
		<th>Mail bestätigt</th>
		<th>E-Mail</th>
	</tr>';

    $query = "SELECT * FROM morp_intranet_user WHERE 1 ORDER BY isallowed DESC, optin DESC, nname, vname";
    $result = safe_query($query);
    $ct = mysqli_num_rows($result);
    $change = $ct / 3;

    while ($row = mysqli_fetch_object($result)) {
        $c++;

        $auth = explode("|", $row->auths);
        $authliste = array();
        foreach ($auth as $val) {
            $authliste[] = $auths_arr[$val];
        }

        echo '<tr' . ($row->isallowed ? '' : ' class="NOT"') . '>
			<td><input type="checkbox" value=' . ($row->mid) . ' class="td_checkbox">&nbsp; &nbsp;<a href="?mid=' . $row->mid . '">' . $row->email . '</a></td>
			<td>' . $row->anrede . ' ' . $row->titel . '</td>
			<td>' . $row->vname . '</td>
			<td>' . $row->nname . '</td>
			<td>' . $row->organisation . '</td>
			<td>' . $row->isallowed . '</td>
			<td>' . $row->optin . '</td>
			<td>' . $row->email . '</td>
			<td><a href="?del=' . $row->mid . '" class="btn btn-danger"><i class="fa fa-trash-o small"></i></a></td>
		</tr>';
    }

    echo '</table></div><div style="clear:left;"><p>&nbsp;</p>
		<p><a href="?neu=1" class="button"><i class="fa fa-plus small"></i> NEU </a></p></div>
		<div id="waitbg" class="hide"></div>
		<div id="wave1" class="hide"></div>
		';
} elseif ($search_combine) {
    echo '<table border=0 cellspacing=1 cellpadding=0 class="autocol p20">';
    echo '<tr>
		<td><p>Benutzername</p></td>
		<td>Vorname</td>
		<td>Name</td>
		<td>berechtigt</td>
		<td>Mail bestätigt</td>
		<td>E-Mail</td>
	</tr>';

    $hashtags = explode(',', $hashtags);
    $hashtags_interests = explode(',', $hashtags_interests);

    $sql = "select mid from morp_intranet_user_allocation where ( ";
    if (count($hashtags) > 1) {
        for ($count = 0; $count < count($hashtags); $count++) {
            if ($hashtags[$count]) {
                if ($count < (count($hashtags) - 2)) {
                    $sql .= 'property = ' . $hashtags[$count] . ' or ';
                } else {
                    $sql .= 'property = ' . $hashtags[$count] . '';
                }

            }
        }
    }

    $sql .= ')';
    $sql .= ' group by mid';
    $sql .= ' having count(mid) = ' . (count($hashtags) - 1) . ' ';

    $where = '';

    // if search for both GH AND BC 
    //echo $BC . '/' . $GH; die();
	$search_project = "";
	if($BC && $GH) $search_project = " AND (BC=$BC AND GH=$GH )";
	else if($BC) $search_project = " AND BC=$BC ";
	else if($GH) $search_project = " AND GH=$GH ";
	
    //if((string) $GH != 'null' && (string) $BC != 'null')
    $que = "SELECT * FROM `morp_intranet_user` g WHERE
			(g.nname like '%" . $search_value . "%' OR g.vname like '%" . $search_value . "%')
            " . $search_project . "
            ";
    /*// if only seach for GH
    elseif((string) $GH != 'null')
    $que = "SELECT * FROM `morp_intranet_user` g WHERE
    (g.nname like '%" . $search_value . "%' OR g.vname like '%" . $search_value . "%')
    AND GH = ".$GH."
    ";
    // if only seach for BC
    elseif((string) $BC != 'null')
    $que = "SELECT * FROM `morp_intranet_user` g WHERE
    (g.nname like '%" . $search_value . "%' OR g.vname like '%" . $search_value . "%')
    AND BC = ".$BC."
    ";
    // if not search for BC AND GH
    // if only seach for GH
    else
    $que = "SELECT * FROM `morp_intranet_user` g WHERE
    (g.nname like '%" . $search_value . "%' OR g.vname like '%" . $search_value . "%')
    ";  */

    if (count($hashtags) > 1) {
        $que .= " AND g.mid in ($sql)";
    }

    $que .= 'ORDER BY g.mid DESC';

    echo $que; //die();
    //$query  = "SELECT * FROM morp_intranet_user WHERE 1 ORDER BY isallowed DESC, optin, nname, vname";
    $result = safe_query($que);
    $ct = mysqli_num_rows($result);
    $change = $ct / 3;

    while ($row = mysqli_fetch_object($result)) {
        $c++;

        $auth = explode("|", $row->auths);
        $authliste = array();
        foreach ($auth as $val) {
            $authliste[] = $auths_arr[$val];
        }

        echo '<tr>
			<td><input type="checkbox" value=' . ($row->mid) . ' class="td_checkbox" name="listUser[]">&nbsp; &nbsp;<a href="?mid=' . $row->mid . '">' . $row->email . '</a></td>
			<td>' . $row->vname . '</td>
			<td>' . $row->nname . '</td>
			<td>' . $row->isallowed . '</td>
			<td>' . $row->optin . '</td>
			<td>' . $row->email . '</td>
			<td><a href="?del=' . $row->mid . '" class="btn btn-danger"><i class="fa fa-trash-o small"></i></a></td>
		</tr>';
    }

    echo '</table>';
    die();
} else {
    die('<p><strong>Keine Berechtigung</strong></p>');
}

function getValueReceiveMail($value_first_time, $isallowed) {
   if($value_first_time == 1) {
       if($isallowed)
         return 1;
       else 
          return 0;  
   } else
       return 1; 
}

function chceksendMail($value_first_time, $isallowed, $check_receive_mail) {
    if($value_first_time == 1) {
        if($isallowed)
          return 1;
        else 
           return 0;  
    } else {
        if($check_receive_mail)
          return 1;
        else 
          return 0;  
    }
 }
function sendMail($isallowed, $name, $email, $txt_confirm_email) {
    global $morpheus;

    /*$seperate = "-----";

    $txt_confirm_email = explode($seperate, $txt_confirm_email);*/

    $mail_txt = $txt_confirm_email; //($isallowed) ? $txt_confirm_email[0] : $txt_confirm_email[1];

    $mail_txt = str_replace("{name}", $name, $mail_txt);
    $mail_txt = str_replace("{email}", $email, $mail_txt);
    $mail_txt = str_replace("{link}", $morpheus['url'], $mail_txt);
    
    $headerMail = $morpheus["mail_start"];
    $footerMail = $morpheus["mail_end"];

    $mail_txt = $headerMail . $mail_txt . $footerMail;

    $to = $email;
    //$to = 'vukynamkhtn@gmail.com';
    $to = 'post@pixel-dusche.de';
    $betreff = "Confirm Register";

    sendMailSMTP($to, utf8_decode($betreff), utf8_decode($mail_txt));
}
function showProperties($mid)
{
    $response = '';
    $array_properties = array();

    $query = "SELECT property FROM morp_intranet_user_allocation where mid = " . $mid . "";
    $result = safe_query($query);

    while ($row = mysqli_fetch_object($result)) {
        $array_properties[] = $row->property;
    }

    $query = "SELECT * FROM morp_intranet_user_properties";
    $result = safe_query($query);

    while ($row = mysqli_fetch_object($result)) {
        $check = (in_array($row->mpid, $array_properties)) ? "checked" : '';
        $response .= '<div><input ' . $check . ' type="checkbox" value=' . ($row->mpid) . ' name="listProperties[]">&nbsp; &nbsp; &nbsp;<label>' . ($row->property) . '</label></div>';
    }

    return $response;
}

function showInterests($mid)
{
    $response = '';
    $array_properties = array();

    $query = "SELECT inID FROM morp_intranet_user_allocation_interests where mid = " . $mid . "";
    $result = safe_query($query);

    while ($row = mysqli_fetch_object($result)) {
        $array_properties[] = $row->inID;
    }

    $query = "SELECT * FROM morp_intranet_user_interests";
    $result = safe_query($query);

    while ($row = mysqli_fetch_object($result)) {
        $check = (in_array($row->inID, $array_properties)) ? "checked" : '';
        $response .= '<div><input ' . $check . ' type="checkbox" value=' . ($row->inID) . ' name="listInterests[]">&nbsp; &nbsp; &nbsp;<label>' . ($row->interest) . '</label></div>';
    }

    return $response;
}

function showTypeUser($mid, $type)
{
    $response = '';
    $array_properties = array();

    $query = "SELECT $type FROM morp_intranet_user where mid = " . $mid . "";
    $result = safe_query($query);
    $row = mysqli_fetch_object($result);

    $check = ($row->$type) ? "checked" : '';

    $response .= '<div><input ' . $check . ' type="checkbox" name="' . $type . '">&nbsp; &nbsp; &nbsp;<label>' . $type . '</label></div>';

    return $response;
}

function updatePropertiesUser($list_properties, $mid)
{
    //print_r($list_properties); die();
    //echo is_array($list_properties) .' ddd'; die();
    if (!is_array($list_properties) && $list_properties != '') {
        $list_properties = explode(";", $list_properties);
    } else {
        $sql = 'delete from morp_intranet_user_allocation where mid = ' . $mid . '';
        safe_query($sql);
    }

    foreach ($list_properties as $property) {
        if ($property != '') {
            // delete if exist
            $sql = 'delete from morp_intranet_user_allocation where mid = ' . $mid . ' and property = ' . $property . '';
            //echo $sql; die();
            safe_query($sql);

            // insert
            $sql = 'insert into morp_intranet_user_allocation(mid, property)values(' . $mid . ', ' . $property . ')';
            //echo $sql . '<br>';
            safe_query($sql);
        }
    }
}

function updateInterestsUser($list_interests, $mid)
{
    $sql = 'delete from morp_intranet_user_allocation_interests where mid = ' . $mid . '';
    safe_query($sql);

    foreach ($list_interests as $property) {
        if ($property != '') {
            // insert
            $sql = 'insert into morp_intranet_user_allocation_interests(mid, inID)values(' . $mid . ', ' . $property . ')';
            //echo $sql . '<br>';
            safe_query($sql);
        }
    }
}

function deletePropertiesUser($list_properties, $mid)
{
    $list_properties = explode(";", $list_properties);

    foreach ($list_properties as $property) {
        if ($property != '') {
            $sql = 'delete from morp_intranet_user_allocation where mid = ' . $mid . ' and property = ' . $property . '';
            //echo $sql; die();
            safe_query($sql);
        }
    }
}
?>

</div>

<?php
include "footer.php";
?>

<script>
	$("#isallowed").click(function() {
		opt = $("#optin").prop('checked');
		isa = $("#isallowed").prop('checked');
		if(isa == true && opt == false) {
			alert("Der Benutzer hat sich nicht per Double Opt In frei geschaltet");
			$("#isallowed").prop('checked', false);
			
		}
		
	});
</script>