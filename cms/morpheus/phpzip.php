<?php
// --------------------------------------------------------------------------------
// PhpZip Application 1.8-RC1
// --------------------------------------------------------------------------------
// License GNU/GPL - Vincent Blavet - December 2005
// http://www.phpconcept.net
// --------------------------------------------------------------------------------
// Français :
//   La description de l'installation et des fonctions de PhpZip  peut être
//   trouvée dans le fichier readme.txt associé à cette distribution.
//   Une description complète de PhpZip 1.6 est disponible sur le site
//   http://www.phpconcept.net
//   (Cette description sera disponible sur le site quelques temps après la
//   distribution).
//
// English :
//   The install instructions and a short description of the PhpZip 1.6 functions
//   can be found in the attached readme.txt file.
//   A more complete description of PhpZip usage will be available soon on
//   http://www.phpconcept.net
//   English language can be selected by the "Options" menu of PhpZip.
//
// --------------------------------------------------------------------------------
//
//   * Avertissement :
//
//   Cette application a été créée de façon non professionnelle.
//   Son usage est au risque et péril de celui qui l'utilise, en aucun cas l'auteur
//   de ce code ne pourra être tenu pour responsable des éventuels dégats qu'il pourrait
//   engendrer.
//   Il est entendu cependant que l'auteur a réalisé ce code par plaisir et n'y a
//   caché aucun virus ou ni malveillance.
//   Cette application est distribuée sous la license GNU/GPL (http://www.gnu.org)
//
//   * Auteur :
//
//   Ce code a été écrit par Vincent Blavet (vincent@phpconcept.net) sur ses temps de
//   loisir.
//
// --------------------------------------------------------------------------------
// CVS : $Id: phpzip.php,v 1.2 2005/12/22 14:44:10 vblavet Exp $
// --------------------------------------------------------------------------------

  // ----- Global variables
  $g_phpzip_app_version = "1.8-RC1";

  // ----- Configuration file name
  $g_config_file = $g_config_dir."config/phpzip.cfg.php";

  // ----- Check the configuration file
  if (!is_file($g_config_file)) {
    // ----- Error message
    die("<div align=center><font size=4 color=red>Unable to find configuration file '$g_config_file' in file '".__FILE__."', line ".__LINE__."</font></div>");
  }

  // ----- Include the configuration file
  include ($g_config_file);

  // ----- Check the PclTrace Library
  if (!is_file($g_lib_dir."/pcltrace.lib.php")) {
    // ----- Error message
    die("<div align=center><font size=4 color=red>Unable to find library '$g_lib_dir/pcltrace.lib.php' in file '".__FILE__."', line ".__LINE__."</font></div>");
  }

  // ----- Include Library
  include ($g_lib_dir."/pcltrace.lib.php");

  // ----- Check the PhpZip Library
  if (!is_file($g_lib_dir."/phpzip.lib.php")) {
    // ----- Error message
    die("<div align=center><font size=4 color=red>Unable to find library '$g_lib_dir/phpzip.lib.php' in file '".__FILE__."', line ".__LINE__."</font></div>");
  }

  // ----- Include Library
  include ($g_lib_dir."/phpzip.lib.php");

  // ----- Check the PclTar Library
  if (!is_file($g_lib_dir."/pcltar.lib.php")) {
    // ----- Error message
    die("<div align=center><font size=4 color=red>Unable to find library '$g_lib_dir/pcltar.lib.php' in file '".__FILE__."', line ".__LINE__."</font></div>");
  }

  // ----- Include Library
  $g_pcltar_lib_dir = $g_lib_dir;
  include ($g_lib_dir."/pcltar.lib.php");

  // ----- Modification of phpzipunzip path
  $g_phpzip_autounzip = $g_lib_dir."/phpunzip.lib.php";
  if (!is_file($g_phpzip_autounzip)) {
    // ----- Error message
    die("<div align=center><font size=4 color=red>Unable to find unzip code '$g_phpzip_autounzip' in file '".__FILE__."', line ".__LINE__."</font></div>");
  }

  // ----- Check the PclZip Library
  if (!is_file($g_lib_dir."/pclzip-trace.lib.php")) {
    // ----- Error message
    die("<div align=center><font size=4 color=red>Unable to find library '$g_lib_dir/pclzip.lib.php' in file '".__FILE__."', line ".__LINE__."</font></div>");
  }

  // ----- Include Library
  include ($g_lib_dir."/pclzip-trace.lib.php");

  // ----- Include language file
  if (!is_file("$g_language_dir/lang-".$g_language.".inc.php")) {
    function Translate($text) { return $text; }
  }
  else {
    include ("$g_language_dir/lang-".$g_language.".inc.php");
  }

  // ----- Application local vars
  $v_result = 1;

  // ----- Check for no $_REQUEST['a_action'] argument
  if (!isset($_REQUEST['a_action'])) {
    $_REQUEST['a_action'] = "start";
  }

  // ----- Trace initialisation
  // 0 means no trace
  //TrOn($g_trace, "log"/*$g_trace_mode*/, "trace.htm"/*$g_trace_filename*/);
  TrOn($g_trace, $g_trace_mode, $g_trace_filename);

  // ----- Module inclusion
  include ('phpzip-zip.php');

  // ----- Functions

  // --------------------------------------------------------------------------------
  // Function : AppHeader()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipHeader($p_archive)
  {
    global $g_phpzip_app_version;
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipHeader", $p_archive);

    // ----- Look for empty
    if ($p_archive == "")
      $p_archive_name = Translate("Aucune archive");
    else
      $p_archive_name = $p_archive;

    // ----- Compose help extension
    if ($_REQUEST['a_action'] == "")
      $v_help_ext = "start";
    else
      $v_help_ext = $_REQUEST['a_action'];

    // ----- Recuperate the current script name.
//    $v_url = basename($PATH_TRANSLATED);
    $v_url = "phpzip.php";

    // ----- Compose the title
    $v_title = "PhpZip $g_phpzip_app_version - $p_archive_name - PHP3 Archiveur";

    // ----- Select the image
    if ($p_archive == "")
      $v_image = $g_images_dir."/file-piz-16.gif";
    else if (($v_archive_type = AppPhpzipArchiveType($p_archive)) == "phpzip")
      $v_image = $g_images_dir."/file-piz-16.gif";
    else if ($v_archive_type = "tar")
      $v_image = $g_images_dir."/file-tar-16.gif";
    else if ($v_archive_type = "tgz")
      $v_image = $g_images_dir."/file-zip-16.gif";
    else
      $v_image = $g_images_dir."/file-piz-16.gif";

    // ----- Include the custom header
    if (@is_file($g_header_file))
    {
      include ("$g_header_file");
    }
    // ----- Include généric header
    else
    {
      echo "<HTML><head>";
      echo "<title>$v_title</title>";

      echo "</head>";
      echo "<body bgcolor=$g_text_bg link=$g_text_bg vlink=$g_text_bg alink=$g_text_bg>";
    }

?>
<script language="JavaScript1.2" src="script/phpzip.menu.js"></script>
<script language="JavaScript1.2" src="pcsexplorer/pcsexplorer.js.php"></script>
<script language="JavaScript1.2" src="script/pcsaction.js"></script>


<script language="JavaScript1.2">

var PcjsZipTrick;
var PcjsZipSelectedFile;
var PcjsZipSelectedFileIsDir=0;

var g_pcjs_zipfile_list = new Array();

// -----------------------------------------------------------------------------
// Function : PcjsZipFileDeclare()
// Description :
//   Create a PcjsMenu.
// -----------------------------------------------------------------------------
function PcjsZipFileDeclare(index, name, archive, is_dir)
{
  v_i = g_pcjs_zipfile_list.length;
  g_pcjs_zipfile_list[v_i] = new Array();
  g_pcjs_zipfile_list[v_i]['index'] = index;
  g_pcjs_zipfile_list[v_i]['name'] = name;
  g_pcjs_zipfile_list[v_i]['archive'] = archive;
  g_pcjs_zipfile_list[v_i]['is_dir'] = is_dir;
  
  // ----- Create the callback function for the menu
  v_str = 'function PcjsZipFileOpenPopup_'+v_i+'(event){PcjsZipFileOpenPopup(event, \''+v_i+'\');}';
  //alert(v_str);
  eval(v_str);
  
  // ----- associate the callback function to the layer
  v_str = 'document.getElementById(\'link_'+index+'\').onclick = PcjsZipFileOpenPopup_'+v_i+';';
  //alert(v_str);
  eval(v_str);
}
// -----------------------------------------------------------------------------


// ----- Open File Popup action window
function PcjsZipFileOpenPopup(event, p_i)
{  
  index = g_pcjs_zipfile_list[p_i]['index'];
  name = g_pcjs_zipfile_list[p_i]['name'];
  archive = g_pcjs_zipfile_list[p_i]['archive'];
  is_dir = g_pcjs_zipfile_list[p_i]['is_dir'];
  
  // ----- Close menu popup (if any open)
  PcjsMenuClosePopup();

  // ----- Store the selected file
  PcjsZipSelectedFile = name;
  PcjsZipSelectedFileIsDir = is_dir;

  // ----- Update the links
  document.getElementById('link_extract').href="<?php echo $v_url; ?>?a_action=extract_index&a_archive="+archive+"&a_index="+index;
  document.getElementById('link_extract_plus').href="<?php echo $v_url;  ?>?a_action=extract_index_ask&a_archive="+archive+"&a_index="+index;
  document.getElementById('link_delete').href="<?php echo $v_url; ?>?a_action=del_file_do&a_archive="+archive+"&a_file['index']="+name+"&a_index="+index;
<?php
  if (AppPhpzipArchiveType($p_archive)!='zip')
  {
?>
  document.getElementById('link_update').href="<?php echo $v_url; ?>?a_action=update_index&a_archive="+archive+"&a_file['index']="+name;
<?php
  }
?>

  // ----- Select the ilayer
//  var obj = document.all['PcjsZipFile'];
  var obj = document.getElementById('PcjsZipFile');

  // ----- Set the popup position
  obj.style.left = document.body.scrollLeft+event.clientX;
  obj.style.top  = document.body.scrollTop+event.clientY;

  // ----- Close the ilayer
  obj.style.visibility = "visible";
  PcjsZipTrick=1;
  document.body.onclick=PcjsZipFileClosePopup;
}

// ----- Close File Popup action window
function PcjsZipFileClosePopup()
{
  //alert('Call to PcjsZipFileClosePopup');
  if (!PcjsZipTrick)
  {
    // ----- Reset the selected file
    PcjsZipSelectedFile = "";

    // ----- Select the ilayer
//    var obj = document.all['PcjsZipFile'];
    var obj = document.getElementById('PcjsZipFile');

    // ----- Close the ilayer
    obj.style.visibility = "hidden";
    //document.body.onclick=PcjsMenuClosePopup;
    PcjsMenuClosePopup();
  }
  else
  {
    PcjsZipTrick=0;
  }
}

// ----- Confirm Extract action
function PcjsZipFileConfirmExtract()
{
  // ----- Reset the selected file
  if (PcjsZipSelectedFile == "")
  {
    alert('<?php echo Translate("Aucun fichier sélectionné.")."\\n".Translate("Extration abandonnée."); ?>');
    return false;
  }
  else
  {
    return confirm("<?php echo Translate("Confirmer l'extraction de"); ?> '"+PcjsZipSelectedFile+"'");
  }
}

// ----- Confirm Delete action
function PcjsZipFileConfirmDelete()
{
  // ----- Reset the selected file
  if (PcjsZipSelectedFile == "")
  {
    alert('<?php echo Translate("Aucun fichier sélectionné.")."\\n".Translate("Suppression abandonnée."); ?>');
    return false;
  }
  else
  {
    return confirm("<?php echo Translate("Confirmer la suppression de"); ?> '"+PcjsZipSelectedFile+"'");
  }
}

// ----- Confirm Update action
function PcjsZipFileConfirmUpdate()
{
  // ----- Reset the selected file
  if (PcjsZipSelectedFile == "")
  {
    alert('<?php echo Translate("Aucun fichier sélectionné.")."\\n".Translate("Mise à jour abandonnée."); ?>');
    return false;
  }
  else
  {
    return confirm("<?php echo Translate("Confirmer la mise à jour de"); ?> '"+PcjsZipSelectedFile+"'");
  }
}

// ----- Popup window creator
function PcjsZipFileGeneratePopup()
{
  // ----- Reset the selected file
  PcjsZipSelectedFile = "";

  // ----- Generate the div tag
  document.write("<div id=PcjsZipFile style='position:absolute; left:118px; top:214px; z-index:100; visibility:hidden; background-color: <?php echo $g_title_color; ?>; border: 1px none #000000'> ");
  document.write("<table border=0 cellspacing=0 bgcolor=<?php echo $g_title_bg; ?>>");
  document.write("<tr height=5><td colspan=3></td></tr>");
  document.write("<tr><td width=2></td><td>");
  document.write('<?php echo "<font face=$g_font size=$g_title_size><a id=link_extract class=head href=$v_url?a_action=list&a_archive=$p_archive onClick=\\'if (PcjsZipFileConfirmExtract()) { PcjsActionWindow(document.getElementById(\"link_extract\").href,\"\"); } return false;\\'>".Translate("Extraire")."</a></font><br>";?>');
  document.write('<?php echo "<font face=$g_font size=$g_title_size><a id=link_extract_plus class=head href=$v_url?a_action=list&a_archive=$p_archive onClick=\\'PcjsActionWindow(document.getElementById(\"link_extract_plus\").href,\"\"); return false;\\'>".Translate("Extraire")." ...</a></font><br>"; ?>');
  document.write('<?php echo "<font face=$g_font size=$g_title_size><a id=link_delete class=head href=$v_url?a_action=list&a_archive=$p_archive onClick=\\'if (PcjsZipFileConfirmDelete()) { PcjsActionWindow(document.getElementById(\"link_delete\").href,\"\"); } return false;\\'>".Translate("Supprimer")."</a></font><br>"; ?>');
<?php
  if (AppPhpzipArchiveType($p_archive)!='zip')
  {
?>
  document.write('<?php echo "<font face=$g_font size=$g_title_size><a id=link_update class=head href=$v_url?a_action=list&a_archive=$p_archive onClick=\\'PcjsActionWindow(document.getElementById(\"link_update\").href,\"\"); return false;\\'>".Translate("Mettre à jour")."</a></font><br>";?>');
<?php
  }
?>
  document.write("</td><td width=5></td></tr>");
  document.write("<tr height=5><td colspan=3></td></tr>");
  document.write("</table></div>");
}

document.body.onclick=PcjsZipFileClosePopup;
PcjsZipFileGeneratePopup();

</script>

<style type=text/css>
<?php

    // ----- Feuille de style pour PhpZip
    echo "\n";
    echo "A.head         { color: $g_title_color; background: $g_title_bg; font-weight: bold; text-decoration: none }\n";
    echo "A.head:link    { color: $g_title_color; background: $g_title_bg; font-weight: bold; text-decoration: none }\n";
    echo "A.head:visited { color: $g_title_color; background: $g_title_bg; font-weight: bold; text-decoration: none }\n";
    echo "A.head:hover   { color: $g_title_bg; background: $g_title_color; font-weight: bold; text-decoration: none }\n";

    echo "A.foot         { color: $g_title_color; font-weight: bold; text-decoration: none }\n";
    echo "A.foot:link    { color: $g_title_color; font-weight: bold; text-decoration: none }\n";
    echo "A.foot:visited { color: $g_title_color; font-weight: bold; text-decoration: none }\n";
    echo "A.foot:hover   { color: $g_title_color; font-weight: bold; text-decoration: underline }\n";

    echo "A.text:link    { color: $g_text_link; font-weight: bold; text-decoration: none }\n";
    echo "A.text:visited { color: $g_text_link; font-weight: bold; text-decoration: none }\n";
    echo "A.text:hover   { color: $g_text_link; font-weight: bold; text-decoration: underline }\n";

    echo "A.dir:link     { color: $g_text_link; text-decoration: none }\n";
    echo "A.dir:visited  { color: $g_text_link; text-decoration: none }\n";
    echo "A.dir:hover    { color: $g_text_link; font-weight: bold; text-decoration: none }\n";

    echo "A.file:link    { color: $g_text_link; text-decoration: none }\n";
    echo "A.file:visited { color: $g_text_link; text-decoration: none }\n";
    echo "A.file:hover   { color: $g_text_link; font-weight: bold; text-decoration: none }\n";
    echo "\n";

?>
</style>

<div id='menu_archive' style='position:absolute; left:150px; top:150px; z-index:100; visibility:hidden; background-color: blue; border: 1px none #000000'>
<table border=0 cellspacing=0 bgcolor=blue>
<tr height=5><td colspan=3></td></tr>
<tr>
  <td width=2></td>
  <td>
<?php
  echo "<font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=new>".Translate("Nouveau")." ...</a></font><br>";
  echo "<font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=read ";
  echo "onClick='PcjsExplorer(\"target_type=url\", \"target=".dirname($_SERVER['PATH_INFO']).'/'.$v_url."?a_action=list&a_archive=".'$$RESULT$$'."\", \"target_encoding=url\", \"select_type=file\", \"result_ref_dir=".dirname($_SERVER['PATH_INFO'])."\", \"start_dir=.\", \"filter_extensions=tar,tgz,gz,piz,zip\");return false;'";
  echo ">".Translate("Ouvrir")." ...</a></font><br>";
  if ($p_archive=="") {
    echo "<font face=$g_font size=$g_title_size color=$g_title_color><i>".Translate("Détruire")."</i></font><br>";
    echo "<font face=$g_font size=$g_title_size color=$g_title_color><i>".Translate("Télécharger")."</i></font><br>";
  }
  else {
    echo "<font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=erase&a_archive=$p_archive>".Translate("Détruire")."</a></font><br>";
    echo "<font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=download&a_archive=$p_archive target=_blank>".Translate("Télécharger")."</a></font><br>";
  }
  echo "<font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=new>".Translate("Quitter")."</a></font><br>";
?>  
  </td>
  <td width=5></td>
</tr>
<tr height=5><td colspan=3></td></tr>
</table></div>


<div id='menu_files' style='position:absolute; left:150px; top:150px; z-index:100; visibility:hidden; background-color: blue; border: 1px none #000000'>
<table border=0 cellspacing=0 bgcolor=blue>
<tr height=5><td colspan=3></td></tr>
<tr>
  <td width=2></td>
  <td>
<?php

  // ----- Contents for menu Files
  if ($p_archive=="")
  {
    echo "<font face=$g_font size=$g_title_size color=$g_title_color><i>".Translate("Lister")."</i></font><br>";
    echo "<font face=$g_font size=$g_title_size color=$g_title_color><i>".Translate("Ajouter")." ...</i></font><br>";
    echo "<font face=$g_font size=$g_title_size color=$g_title_color><i>".Translate("Mettre à jour")." ...</i></font><br>";
    echo "<font face=$g_font size=$g_title_size color=$g_title_color><i>".Translate("Extraire")." ...</i></font><br>";
    echo "<font face=$g_font size=$g_title_size color=$g_title_color><i>".Translate("Supprimer fichiers")." ...</i></font><br>";
  }
  else
  {
    echo "<font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=list&a_archive=$p_archive>".Translate("Lister")."</a></font><br>";
    echo "<font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=add&a_archive=$p_archive>".Translate("Ajouter")." ...</a></font><br>";
    if (AppPhpzipArchiveType($p_archive) != 'zip')
    {
      if ($v_archive_type == "phpzip")
        echo "<font face=$g_font size=$g_title_size color=$g_title_color><i>".Translate("Mettre à jour")." ...</i></font><br>\n";
      else
        echo "<font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=update&a_archive=$p_archive>".Translate("Mettre à jour")." ...</a></font><br>\n";
      echo "<font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=unzip&a_archive=$p_archive>".Translate("Extraire")." ...</a></font><br>\n";
      if ($v_archive_type == "phpzip")
        echo "<font face=$g_font size=$g_title_size color=$g_title_color><i>".Translate("Supprimer fichiers")." ...</i></font><br>\n";
      else
        echo "<font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=del_file&a_archive=$p_archive>".Translate("Supprimer fichiers")." ...</a></font><br>\n";
    }
  }


?>  

  </td>
  <td width=5></td>
</tr>
<tr height=5><td colspan=3></td></tr>
</table></div>

<div id='menu_view' style='position:absolute; left:150px; top:150px; z-index:100; visibility:hidden; background-color: blue; border: 1px none #000000'>
<table border=0 cellspacing=0 bgcolor=blue>
<tr height=5><td colspan=3></td></tr>
<tr>
  <td width=2></td>
  <td>
<?php

  // ----- Contents for menu View
  echo "<font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=option&a_archive=$p_archive>".Translate("Options")."</a></font><br>\n";


?>  

  </td>
  <td width=5></td>
</tr>
<tr height=5><td colspan=3></td></tr>
</table></div>

<div id='menu_help' style='position:absolute; left:150px; top:150px; z-index:100; visibility:hidden; background-color: blue; border: 1px none #000000'>
<table border=0 cellspacing=0 bgcolor=blue>
<tr height=5><td colspan=3></td></tr>
<tr>
  <td width=2></td>
  <td>
<?php

  //Contents for menu 4
  echo "<font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=help&a_archive=$p_archive&a_topic=$v_help_ext>".Translate("Aide")."</a></font><br>\n";
  echo "<font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=about>".Translate("Au sujet de")." ...</a></font><br>\n";

?>  

  </td>
  <td width=5></td>
</tr>
<tr height=5><td colspan=3></td></tr>
</table></div>

<?php

    // ----- Start of PhpZip table
    echo "<table width=90% border=1 cellspacing=0 cellpadding=0 bordercolorlight=$g_title_bg bordercolordark=$g_title_bg align=center>";
    echo "  <tr bgcolor=$g_title_bg>";
    echo "  <td>";
    echo "    <font face=$g_font size=$g_title_size color=$g_title_color><img src='$g_images_dir/file-piz-16.gif' border='0' hspace='0' vspace='0'> <b>PhpZip $g_phpzip_app_version - </b>$p_archive_name</font>";
    echo "  </td>";
    echo "  </tr>";
    echo "  <tr>";
    echo "    <td>";
    echo "      <table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "        <tr bgcolor=$g_title_bg>";
    echo "          <td width=10>";
    echo "          </td>";

    // ----- First menu : Archive menu
    echo "<td width=\"80\">
          <span id=\"layer_menu_archive\"><font face=$g_font size=$g_title_size><b><a class=head href=\"javascript:void(0);\">".Translate("Archive")."</a></b></font>
          </span>
          </td>";
    echo "          <td width=5>";
    echo "          </td>";

    // ----- Second menu : Files menu
    echo "<td width=80>
          <span id=\"layer_menu_files\"><font face=$g_font size=$g_title_size><b><a class=head href=\"javascript:void(0);\">".Translate("Fichiers")."</a></b></font>
          </span>
          </td>";
    echo "          <td width=5>";
    echo "          </td>";

    // ----- Third menu : View menu
    echo "<td width=80>
          <span id=\"layer_menu_view\"><font face=$g_font size=$g_title_size><b><a class=head href=\"javascript:void(0);\">".Translate("Visualiser")."</a></b></font>
          </span>
          </td>";
    echo "          <td>&nbsp;";
    echo "          </td>";

    // ----- Fourth menu : help menu
    echo "<td width=5>
          <span id=\"layer_menu_help\"><font face=$g_font size=$g_title_size><b><a class=head href=\"javascript:void(0);\"> ? </a></b></font>
          </span>
          </td>";

    echo "          <td width=5>";
    echo "          </td>";

    echo "        </tr>";
    echo "      </table>";
    echo "    </td>";
    echo "  </tr>";
    echo "  <tr>";
    echo "    <td height=11>";
    echo "      <table width=100% border=0 cellspacing=0 cellpadding=0><tr bgcolor=$g_text_bg><td width=10></td><td>";
    echo "      <font face=$g_font size=$g_text_size color=$g_text_color><img src='$v_image' border='0' hspace='0' vspace='0' align='absmiddle'>$p_archive_name</font></td></tr></table>";
    echo "    </td>";
    echo "  </tr>";
    echo "  <tr bgcolor=$g_text_bg>";
    echo "    <td>";

?>

<script language="JavaScript1.2">
PcjsMenuDeclare('menu_archive', 'layer_menu_archive');
PcjsMenuDeclare('menu_files', 'layer_menu_files');
PcjsMenuDeclare('menu_view', 'layer_menu_view');
PcjsMenuDeclare('menu_help', 'layer_menu_help');
//document.body.onClick = PcjsMenuClosePopup;
</script>

<?
    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipFooter()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipFooter($p_message)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipFooter", $p_message);

    // ----- Look for empty message
    if ($p_message == "")
      $p_message = "&nbsp;";

    // ----- Include the PhpZip Footer
    echo "</td><table width=90% border=1 cellspacing=0 cellpadding=0 bordercolorlight=$g_title_bg bordercolordark=$g_title_bg align=center>";
    echo "<tr bgcolor=$g_title_bg>";
    echo "<td><div align=left><font face=$g_font size=$g_footer_size color=$g_title_color>$p_message</font></div></td>";
    echo "<td><div align=right><font face=$g_font size=$g_footer_size color=$g_title_color>by <i><b><a class=foot href=http://www.phpconcept.net>PhpConcept</a></font></i></b></div></td>";
    echo "</tr></table>";
    echo "</tr></table>";

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipHeader()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipActionHeader($p_archive, $p_title="")
  {
    global $g_phpzip_app_version;
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipActionHeader", $p_archive);

    // ----- Look for empty
    if ($p_archive == "")
      $p_archive_name = Translate("Aucune archive");
    else
      $p_archive_name = $p_archive;

    // ----- Compose help extension
    if ($_REQUEST['a_action'] == "")
      $v_help_ext = "start";
    else
      $v_help_ext = $_REQUEST['a_action'];

    // ----- Recuperate the current script name.
//    $v_url = basename($PATH_TRANSLATED);
    $v_url = "phpzip.php";

    // ----- Compose the title
    $v_title = "PhpZip $g_phpzip_app_version - $p_archive_name - PHP Archiveur";

    // ----- Select the image
    if ($p_archive == "")
      $v_image = $g_images_dir."/file-piz-16.gif";
    else if (($v_archive_type = AppPhpzipArchiveType($p_archive)) == "phpzip")
      $v_image = $g_images_dir."/file-piz-16.gif";
    else if ($v_archive_type = "tar")
      $v_image = $g_images_dir."/file-tar-16.gif";
    else if ($v_archive_type = "tgz")
      $v_image = $g_images_dir."/file-zip-16.gif";
    else
      $v_image = $g_images_dir."/file-piz-16.gif";

    // ----- HTML Header
    echo "<HTML><head>";
    echo "<title>$v_title</title>";
?>
<script language="JavaScript1.2" src="pcsexplorer/pcsexplorer.js.php"></script>
<?php
    echo "</head>";
    echo "<body LEFTMARGIN=0 TOPMARGIN=0 bgcolor=$g_text_bg link=$g_text_bg vlink=$g_text_bg alink=$g_text_bg>";

    // ----- Start of PhpZip table
    echo "<table width=100% height=400 border=1 cellspacing=0 cellpadding=0 bordercolorlight=$g_title_bg bordercolordark=$g_title_bg align=center>";
    echo "  <tr bgcolor=$g_title_bg height=20>";
    echo "  <td align=center>";
    echo "    <font face=$g_font size=$g_title_size color=$g_title_color><b>$p_title</b></font>";
    echo "  </td>";
    echo "  </tr>";
    echo "  <tr bgcolor=$g_text_bg >";
    echo "    <td  valign=top>";

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipActionFooter()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipActionFooter($p_message)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipActionFooter", $p_message);

    // ----- Look for empty message
    if ($p_message == "")
      $p_message = "&nbsp;";

    // ----- Include the PhpZip Footer
    echo "</td></tr>";
    echo "<tr height=10><td><table width=100% height=10 border=1 cellspacing=0 cellpadding=0 bordercolorlight=$g_title_bg bordercolordark=$g_title_bg align=center>";
    echo "<tr bgcolor=$g_title_bg>";
    echo "<td><div align=left><font face=$g_font size=$g_footer_size color=$g_title_color>$p_message</font></div></td>";
    echo "<td><div align=right><font face=$g_font size=$g_footer_size color=$g_title_color>by <i><b><a class=foot href=http://www.phpconcept.net>PhpConcept</a></font></i></b></div></td>";
    echo "</tr></table>";
    echo "</tr></table>";

    echo "</BODY></HTML>";

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipAlternateMenu()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipAlternateMenu($p_archive)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipAlternateMenu", $p_archive);

    // ----- Display HTML header
    AppPhpzipHeader($p_archive);

    // ----- Display request for name.
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_title_bg>";
    echo "<td><div align=center><b><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Menu")."</font></b></div></td>";
    echo "</tr>";
    echo "<tr bgcolor=$g_text_bg><td>";

    // ----- Alternate menu
    echo "<p><font face=$g_font size=$g_text_size color=$g_title_color>";
    echo "<table border=0 cellspacing=0 cellpadding=2 align=center>";
    echo "<tr><td colspan=7>&nbsp;</td></tr>";
    echo "<tr bgcolor=$g_title_bg><td><b><font face=$g_font size=$g_text_size color=$g_title_color>".Translate("Archive")."</font><b></td><td width=10>&nbsp;</td>";
    echo "<td><b><font face=$g_font size=$g_text_size color=$g_title_color>".Translate("Fichiers")."</font><b></td><td width=10>&nbsp;</td>";
    echo "<td><b><font face=$g_font size=$g_text_size color=$g_title_color>".Translate("Visualiser")."</font><b></td><td width=10>&nbsp;</td>";
    echo "<td><b><font face=$g_font size=$g_text_size color=$g_title_color>?</font><b></td></tr>";
    echo "<tr><td colspan=7></td></tr>";

    echo "<tr bgcolor=$g_title_bg><td><font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=new>".Translate("Nouveau")." ...</a></font></td><td width=10>&nbsp;</td>";
    echo "<td><font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=list&a_archive=$p_archive>".Translate("Lister")."</a></font></td><td width=10>&nbsp;</td>";
    echo "<td><font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=option&a_archive=$p_archive>".Translate("Options")."</a></font></td><td width=10>&nbsp;</td>";
    echo "<td><font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=help&a_archive=$p_archive&a_topic=$v_help_ext>".Translate("Aide")."</a></font></td></tr>";

    echo "<tr bgcolor=$g_title_bg><td><font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=read>".Translate("Ouvrir")." ...</a></font></td><td width=10>&nbsp;</td>";
    echo "<td><font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=add&a_archive=$p_archive>".Translate("Ajouter")." ...</a></font></td><td width=10>&nbsp;</td>";
    echo "<td></td><td width=10>&nbsp;</td>";
    echo "<td><font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=about>".Translate("Au sujet de")." ...</a></font></td></tr>";

    echo "<tr bgcolor=$g_title_bg><td><font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=erase&a_archive=$p_archive>".Translate("Détruire")."</a></font></td><td width=10>&nbsp;</td>";
    echo "<td><font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=update&a_archive=$p_archive>".Translate("Mettre à jour")." ...</a></font></td><td width=10>&nbsp;</td>";
    echo "<td></td><td width=10>&nbsp;</td>";
    echo "<td></td></tr>";

    echo "<tr bgcolor=$g_title_bg><td><font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=download&a_archive=$p_archive target=_blank>".Translate("Télécharger")."</a></font></td><td width=10>&nbsp;</td>";
    echo "<td><font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=unzip&a_archive=$p_archive>".Translate("Extraire")." ...</a></font></td><td width=10>&nbsp;</td>";
    echo "<td></td><td width=10>&nbsp;</td>";
    echo "<td></td></tr>";

    echo "<tr bgcolor=$g_title_bg><td><font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=new>".Translate("Quitter")."</a></font></td><td width=10>&nbsp;</td>";
    echo "<td><font face=$g_font size=$g_title_size><a class=head href=$v_url?a_action=del_file&a_archive=$p_archive>".Translate("Supprimer fichiers")." ...</a></font></td><td width=10>&nbsp;</td>";
    echo "<td></td><td width=10>&nbsp;</td>";
    echo "<td></td></tr>";
    echo "<tr><td colspan=7>&nbsp;</td></tr>";
    echo "</table></font></p>";

    echo "</td></tr>";
    echo "</table>";

    // ----- Display HTML footer
    if ($p_message == "")
      $p_message = Translate("Créez ou Ouvrez une archive PhpZip.");
    AppPhpzipFooter($p_message);

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipStart()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipStart($p_message="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipStart", $p_message);

    // ----- Display HTML header
    AppPhpzipHeader("");

    // ----- Display request for name.
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_title_bg>";
    echo "<td><div align=center><b><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Bienvenue")." !</font></b></div></td>";
    echo "</tr>";
    echo "<tr bgcolor=$g_text_bg><td>";
    echo "<font face=$g_font color=$g_text_color><p>&nbsp;<br><div align=center>".Translate("Pour continuer vous devez soit")." <a class=text href=?a_action=new>".Translate("créer")."</a> ".Translate("une archive").", <a class=text href=?a_action=read>".Translate("ouvrir")."</a> ".Translate("une archive").", ".Translate("soit")." ".Translate("demander de l'")."<a class=text href=?a_action=help&a_topic=start>".Translate("aide")."</a></div></p></font>";
    echo "</td></tr>";
    echo "</table>";

    // ----- Display HTML footer
    if ($p_message == "")
      $p_message = Translate("Créez ou Ouvrez une archive PhpZip.");
    AppPhpzipFooter($p_message);

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipStatus()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipStatus($p_commande, $p_archive, $p_status, $p_message, $p_footer="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipStatus", "$p_commande, $p_archive, $p_status, $p_message, $p_footer");

    // ----- Display HTML header
    AppPhpzipHeader($p_archive);

    // ----- Table header.
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";

    // ----- Look for status
    if ($p_status == "OK")
    {
      echo "<tr bgcolor=$g_title_bg>";
      echo "<td><div align=center><b><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("La commande")." \"".Translate($p_commande)."\" ".Translate("a réussie")."</font></b></div></td>";
      echo "</tr>";
      echo "<tr bgcolor=$g_text_bg><td>";
      echo "<font face=$g_font size=$g_text_size color=$g_text_color><p>&nbsp;<br><div align=center>$p_message</div></p>";

      $v_url = __FILE__;

      // ----- Look for specific propositions
      switch ($p_commande) {
        case "Détruire" :
          echo "<p>&nbsp;<br><div align=center>".Translate("Pour continuer vous pouvez")." <a class=text href=$v_url?a_action=new>".Translate("créer")."</a> ".Translate("ou")." <a class=text href=$v_url?a_action=read>".Translate("ouvrir")."</a> ".Translate("une archive").".</div></p>";
        break;
        case "Créer" :
          echo "<p>&nbsp;<br><div align=center>".Translate("Pour continuer vous pouvez")." <a class=text href=$v_url?a_action=add&a_archive=$p_archive>".Translate("ajouter")."</a> ".Translate("un fichier")." ".Translate("ou")." ".Translate("un dossier")." ".Translate("à")." ".Translate("l'archive").".</div></p>";
        break;
        default :
          echo "<p>&nbsp;<br><div align=center>".Translate("Pour continuer vous pouvez")." <a class=text href=$v_url?a_action=list&a_archive=$p_archive>".Translate("lister")."</a> ".Translate("le contenu de l'archive").".</div></p>";
      }
      echo "</font></td></tr>";
    }
    else
    {
      echo "<tr bgcolor=$g_error_bg>";
      echo "<td><div align=center><b><font face=$g_font size=$g_error_size color=$g_error_color>".Translate("La commande")." \"".Translate($p_commande)."\" ".Translate("a échouée")."</font></b></div></td>";
      echo "</tr>";
      echo "<tr bgcolor=$g_text_bg><td>";
      echo "<font face=$g_font size=$g_text_size color=$g_text_color><p>&nbsp;<br><div align=center>".Translate("Détails de l'erreur")." :</div></p>";
      echo "<p><div align=center>$p_message</div></p></font>";
      echo "</td></tr>";
    }

    // ----- Table footer
    echo "</table>";

    // ----- Display HTML footer
    AppPhpzipFooter($p_footer);

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipRead()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipRead($p_dir)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipRead", "$p_dir");

    // ----- Display HTML header
    AppPhpzipHeader("");

    // ----- Look for empty directory
    if ($p_dir == "")
      $p_dir = ".";

    // ----- Header of action
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_title_bg><td width=5></td>";
    echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color><b>".Translate("Ouvrir une archive PhpZip")."</b></font></div></td>";
    echo "</tr>";

    echo "<tr bgcolor=$g_text_bg><td width=5></td>";
    echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Dossier courant")." : $p_dir</font></td>";
    echo "</tr>";
    echo "</table>";

    // ----- File / directory table
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_title_bg><td width=10></td>";
    echo "<td><div><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Fichier/Dossier")."</font></div></td>";
    echo "</tr>";

    // ----- Scan the current directory
    $v_hdir = opendir($p_dir);
    $v_file = readdir($v_hdir); // '.' directory

    for ($i=0; $v_file = readdir($v_hdir); $i++)
    {
      if ($v_file == "..")
      {
        // ----- Go back in path
        if (substr($p_dir, 0, 2) == "..")
          $v_file_full = "../".$p_dir;
        else if ($p_dir != ".")
        {
          $temp = strrchr($p_dir, "/");
          $v_file_full = substr($p_dir, 0, strlen($p_dir)-strlen($temp));
          unset($temp);
        }
        else
          $v_file_full = "..";

        TrFctMessage(__FILE__, __LINE__, 3, "Parent directory is [$v_file_full]");

        // ----- Look if the file is a file parent directory (indirect check of open_basedir restriction)
        if (($v_test_hdir = @opendir($v_file_full)) != 0)
        {
          TrFctMessage(__FILE__, __LINE__, 3, "Parent directory [$v_file_full] is readable");

          // ----- Close the temporary handle
          closedir($v_test_hdir);

          // ----- Display the name in the table without checkbox
          echo "<tr bgcolor=$g_text_bg><td width=10></td>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><A class=dir href=?a_action=read&a_dir=$v_file_full><img src='$g_images_dir/folder02-16.gif' border='0' hspace='0' vspace='0'> [".Translate("Dossier parent")."]</A></td><td></td>";
          echo "</font></tr>";
        }
        else
        {
          TrFctMessage(__FILE__, __LINE__, 3, "Parent directory [$v_file_full] not readable");

          // ----- Display the name in the table without checkbox
          echo "<tr bgcolor=$g_text_bg><td width=10></td>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><img src='$g_images_dir/folder02-16.gif' border='0' hspace='0' vspace='0'> [".Translate("Aucun dossier parent")."]</td><td></td>";
          echo "</font></tr>";
        }

      }
      else
      {
        // ----- Compose the full name
        $v_file_full = $p_dir."/".$v_file;

        // ----- Look for readable directory
        if (is_dir($v_file_full))
        {
          if (is_readable($v_file_full))
          {
            echo "<tr bgcolor=$g_text_bg><td width=10></td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><A class=dir href=?a_action=read&a_dir=$v_file_full><img src='$g_images_dir/folder01-16.gif' border='0' hspace='0' vspace='0'> $v_file</A></font></td>";
            echo "</tr>";
          }
        }

        // ----- Look for readable files
        else
        {
          $v_archive_format = AppPhpzipArchiveType($v_file_full);

          // ----- Look for PhpZip files
          if ($v_archive_format == "phpzip")
          {
            echo "<tr bgcolor=$g_text_bg><td width=10></td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><A class=file href=?a_archive=$v_file_full&a_action=list><img src='$g_images_dir/file-piz-16.gif' border='0' hspace='0' vspace='0'> $v_file</A></font></td>";
            echo "</tr>";
          }
          else if ($v_archive_format == "tar")
          {
            echo "<tr bgcolor=$g_text_bg><td width=10></td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><A class=file href=?a_archive=$v_file_full&a_action=list><img src='$g_images_dir/file-tar.gif' border='0' hspace='0' vspace='0'> $v_file</A></font></td>";
            echo "</tr>";
          }
          else if ($v_archive_format == "tgz")
          {
            echo "<tr bgcolor=$g_text_bg><td width=10></td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><A class=file href=?a_archive=$v_file_full&a_action=list><img src='$g_images_dir/file-zip-16.gif' border='0' hspace='0' vspace='0'> $v_file</A></font></td>";
            echo "</tr>";
          }
        }
      }
    }

    echo "</table>";

    // ----- Close the directory
    closedir($v_hdir);

    // ----- Display HTML footer
    if ($p_message == "")
      $p_message = Translate("Selectionnez l'archive à ouvrir.");
    AppPhpzipFooter($p_message);

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipAskCreate()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipAskCreate($p_message="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipAskCreate", "$p_message");

    // ----- Display HTML header
    AppPhpzipHeader("");

    // ----- Display request for name.
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_title_bg>";
    echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color><b>".Translate("Créer une archive PhpZip")."</b></font></div></td>";
    echo "</tr>";
    echo "</table>";

    echo "<script language='javascript' src='pcsexplorer/pcsexplorer.js.php'></script>";

    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_text_bg><td width=10></td><td>";
    echo "<form name=formulaire method=post action=\"\">";

    echo "<p><font face=$g_font size=$g_text_size color=$g_text_color>";
    echo "<table border=0 cellspacing=0 cellpadding=0 align=center>";
    echo "<tr><td colspan=2>&nbsp;</td>";
    echo "<tr><td>".Translate("Type d'archive")." : </td>";
    echo "<td><input type=radio name=a_type value=zip checked>Zip archive</td></tr>";
    echo "<tr><td>&nbsp;</td><td><input type=radio name=a_type value=tgz>Gzip Gnu Tar</td></tr>";
    echo "<tr><td>&nbsp;</td><td><input type=radio name=a_type value=tar>Gnu Tar</td></tr>";
    echo "<tr><td>&nbsp;</td><td><input type=radio name=a_type value=phpzip>PhpZip</td></tr>";
    echo "<tr><td>&nbsp;</td><td><input type=radio name=a_type value=phpautounzip>PhpZip Auto-Unzip</td></tr>";
    echo "<tr><td>".Translate("Archive à créer")." : </td>";
    echo "<td><input type=text name=a_archive size=40 maxlength=60 value=archive.zip></td></tr>";
    echo "<tr><td>".Translate("Dans le dossier")." : </td>";
    echo "<td><input type=text id=\"a_dir\" name=a_dir size=40 maxlength=200 value='./'>";
    global $PATH_INFO;
    echo " <INPUT TYPE=button name=bt value='".Translate('Parcourir')." ...' ";
//    echo "onClick='PcjsOpenExplorer(\"pcsexplorer.php\", \"forms.formulaire.a_dir.value\", \"type=dir\", \"calling_dir=".dirname($PATH_INFO)."\", \"start_dir=.\")'";
    echo "onClick='PcjsExplorer(\"target=a_dir\", \"type=dir\", \"result_ref_dir=".dirname($PATH_INFO)."\", \"start_dir=.\")'";
    echo ">";
    echo "</td></tr>";
    echo "<tr><td>".Translate("Fichier d'auto-démarrage")." : </td>";
    echo "<td><input type=text name=a_startfile size=40 maxlength=60 value=></td>";
    echo "</table></font>";
    echo "</p><p><div align=center><input type=hidden name=a_action value=new_do><input type=submit name=a_submit value=".Translate("Créer")."></div>";
    echo "</p></form>";
    echo "</td></tr></table>";

    // ----- Display HTML footer
    if ($p_message == "")
      $p_message = Translate("Veuillez indiquer un nom d'archive à créer ou cliquez sur Ouvrir.");
    AppPhpzipFooter($p_message);

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipReplace()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipReplace($p_archive, $p_type, $p_startfile)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipReplace", "$p_archive, $p_type, $p_startfile");

    @unlink($p_archive);
    $v_result = AppPhpzipCreate($p_archive, $p_type, $p_startfile);

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipCreate()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipCreate($p_archive, $p_type, $p_startfile, $p_dir="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipCreate", "archive='$p_archive', type='$p_type', $p_startfile, dir='$p_dir'");

    // ----- Check the type
    if (($p_type!="phpzip")&&($p_type!="phpautounzip")&&($p_type!="tar")&&($p_type!="tgz")&&($p_type!="zip"))
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Le type d'archive")." \"$p_type\" ".Translate("est inconnu.");

      // ----- Reset the archive name
      $p_archive = "";

      // ----- Display status
      AppPhpzipStatus("Créer", $p_archive, "NOK", $v_message, "Erreur.");

      // ----- Return
      TrFctEnd(__FILE__, __LINE__, $v_result);
      return $v_result;
    }

    // ----- Change archive path
    if ($p_dir != '')
    {
      if (substr($p_dir, -1) == '/')
        $p_archive = $p_dir.$p_archive;
      else
        $p_archive = $p_dir.'/'.$p_archive;
    }

    // ----- Check the file extension
    if (($p_type=="tar") || ($p_type=="tgz"))
    {
      // ----- Recuperate the Tar mode
      if (($v_check_type = PclTarHandleExtension($p_archive)) != $p_type)
      {
        TrFctMessage(__FILE__, __LINE__, 2, "Add extension .$p_type to archive $p_archive");

        // ----- Change the tar mode
        $p_archive .= ".$p_type";
      }
    }

    // ----- Check the file
    if (!is_file($p_archive))
    {
      // ----- Switch on archive type
      if (($p_type=="tar") || ($p_type=="tgz"))
      {
        // ----- Create the tar archive
        if (($v_result = PclTarCreate($p_archive)) == 1)
        {
          // ----- List the "empty" archive
          AppPhpzipList($p_archive);
        }
        else
        {
          // ----- Set an error string for the footer
          /*
          if ($v_result == -1)
            $v_message = Translate("Syntaxe incorrecte pour le nom de l'archive PhpZip")." [$p_archive]";
          else if ($v_result == -5)
            $v_message = Translate("Impossible de trouver le dossier")." [".dirname($p_archive)."] ".Translate("de l'archive PhpZip")." [".basename($p_archive)."]";
          else if ($v_result == -6)
            $v_message = Translate("Impossible d'écrire dans le dossier")." [".dirname($p_archive)."] ".Translate("de l'archive PhpZip")." [".basename($p_archive)."]";
          else
          */
            $v_message = Translate("Erreur lors de la création de l'archive PhpZip")." [$p_archive]";

          // ----- Reset the archive name
          $p_archive = "";

          // ----- Display HTML page
          AppPhpzipStatus("Créer", $p_archive, "NOK", $v_message);
        }
      }
      else if ($p_type=="zip")
      {
        $v_zip = new PclZip($p_archive);
        $v_list = $v_zip->create("");
        if (!is_array($v_list))
        {
          TrFctMessage(__FILE__, __LINE__, 2, "Archive is not created");
        }
        else
        {
          TrFctMessage(__FILE__, __LINE__, 2, "Archive is created");

          // ----- List the "empty" archive
          AppPhpzipList($p_archive);
        }
      }
      else
      {
        // ----- Create the archive
        if (($v_result = PhpzipCreate($p_archive, 0, $p_startfile, $p_type)) == 1)
        {
          // ----- List the "empty" archive
          AppPhpzipList($p_archive);
        }
        else
        {
          // ----- Set an error string for the footer
          if ($v_result == -1)
            $v_message = Translate("Syntaxe incorrecte pour le nom de l'archive PhpZip")." [$p_archive]";
          else if ($v_result == -5)
            $v_message = Translate("Impossible de trouver le dossier")." [".dirname($p_archive)."] ".Translate("de l'archive PhpZip")." [".basename($p_archive)."]";
          else if ($v_result == -6)
            $v_message = Translate("Impossible d'écrire dans le dossier")." [".dirname($p_archive)."] ".Translate("de l'archive PhpZip")." [".basename($p_archive)."]";
          else
            $v_message = Translate("Erreur lors de la création de l'archive PhpZip")." [$p_archive]";

          // ----- Reset the archive name
          $p_archive = "";

          // ----- Display HTML page
          AppPhpzipStatus("Créer", $p_archive, "NOK", $v_message);
        }
      }
    }
    else
    {
    /*
      // ----- Set an error string for the footer
      $v_message = Translate("Une archive PhpZip avec le nom")." \"$p_archive\" ".Translate("existe déjà.");

      // ----- Reset the archive name
      $p_archive = "";

      // ----- Display status
      AppPhpzipStatus("Créer", $p_archive, "NOK", $v_message, "Erreur.");
      */

    // ----- Display HTML header
    AppPhpzipHeader("");

    // ----- Display request for name.
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_title_bg>";
    echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color><b>".Translate("Créer une archive PhpZip")."</b></font></div></td>";
    echo "</tr>";
    echo "</table>";

    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_text_bg><td width=10></td><td>";
    echo "<form method=post action=\"\">";
    echo "<p align=center><br><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Une archive PhpZip avec le nom")." \"$p_archive\" ".Translate("existe déjà.");
    echo "</font></p>";
    echo "<p align=center><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Voulez-vous la remplacer ?")."</font>";
    echo "</p><p><div align=center>";
    echo "<input type=submit name=a_submit value=".Translate("Oui").">&nbsp";
    echo "<input type=submit name=a_submit value=".Translate("Non")."></div>";
    echo "<input type=hidden name=a_action value=new_replace>";
    echo "<input type=hidden name=a_archive value=$p_archive>";
    echo "<input type=hidden name=a_type value=$p_type>";
    echo "<input type=hidden name=a_startfile value=$p_startfile>";
    echo "</p></form>";
    echo "</td></tr></table>";

    // ----- Display HTML footer
    if ((!isset($v_message)) || ($v_message == ""))
      $v_message = Translate("Vous pouvez remplacer l'archive existante.");
    AppPhpzipFooter($v_message);
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipAdd()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipAdd($p_archive, $p_filename, $p_type)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipAdd", "$p_archive, $p_filename, $p_type");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Look for file
      if (is_file($p_filename))
      {
        // ----- Recuperate the file list
        if (($v_result = PhpzipAdd($p_archive, $p_filename, $p_type)) == 1)
        {
          // ----- Set an error string for the footer
          $v_message = Translate("Le fichier")." \"$p_filename\" ".Translate("a bien été ajouté dans l'archive.");

          // ----- Display status
          AppPhpzipStatus("Ajouter", $p_archive, "OK", $v_message, Translate("Ajout terminé."));
        }
        else
        {
          // ----- Set an error string for the footer
          $v_message = Translate("Erreur lors de l'ajout du fichier")." \"$p_filename\" ".Translate("dans l'archive PhpZip")." \"$p_archive\".";

          // ----- Display status
          AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
        }
      }

      // ----- Look for directory
      else if (is_dir($p_filename))
      {
        // ----- Recuperate the file list
        if (($v_result = PhpzipAddDir($p_archive, $p_filename, $p_type)) == 1)
        {
          // ----- Set an error string for the footer
          $v_message = Translate("Le dossier")." \"$p_filename\" ".Translate("a bien été ajouté dans l'archive.");

          // ----- Display status
          AppPhpzipStatus("Ajouter", $p_archive, "OK", $v_message, Translate("Ajout terminé."));
        }
        else
        {
          // ----- Set an error string for the footer
          $v_message = Translate("Erreur lors de l'ajout du dossier")." \"$p_filename\" ".Translate("dans l'archive PhpZip")." \"$p_archive\".";

          // ----- Display HTML page
          AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
        }
      }

      else
      {
        // ----- Set an error string for the footer
        $v_message = "\"$p_filename\" ".Translate("n'est pas un nom de fichier ou dossier valide.");

        // ----- Display HTML page
        AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }
    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipList()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipList($p_archive, $p_message="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipList", "$p_archive, $p_message");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Recuperate the file list
      if (($v_result = PhpzipList($p_archive, $v_list, $v_list_detail)) == 1)
      {
        // ----- Display HTML header
        AppPhpzipHeader($p_archive);

        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>N°</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Fichier")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Taille")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Compression")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Dossier")."</font></div></td>";
        echo "</tr>";

        // ----- List the files
        for ($i=0; $i<sizeof($v_list); $i++)
        {
          // ----- Explode to get the properties
          $v_token = explode(":", $v_list_detail[$i]);

          // ----- Calculate the compression
          if ($v_token[1] != 0)
            $v_percent = (((integer)$v_token[1]-(integer)$v_token[2])*100)/((integer)$v_token[1]);
          else
            $v_percent = 0;

          // ----- Display
          echo "<tr bgcolor=$g_text_bg>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>$i</div></font></td>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".$v_token[0]."</font></td>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".$v_token[1]."</div></font></td>";
          if ($v_token[4] == "C")
            printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".Translate("Oui")." : %3d%%</div></font></td>", $v_percent);
          else
            printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".Translate("Non")."</div></font></td>");
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".$v_token[3]."</font></td>";
          echo "</tr>";
        }

        // ----- Look for empty list
        if (sizeof($v_list) == 0)
        {
          echo "</table>";
          echo "<table width=100% border=0 cellspacing=0 cellpadding=0><tr><td>&nbsp</td></tr><tr bgcolor=$g_text_bg>";
          echo "<td><div align=center><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Aucun fichier archivé")."</font></div></td>";
          echo "</tr><tr><td>&nbsp</td></tr>";
        }

        // ----- Display HTML footer
        echo "</table>";
        if ($p_message == "")
          $p_message = Translate("Contenu de l'archive ").$p_archive.".";
        AppPhpzipFooter($p_message);
      }
      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Erreur lors de la lecture de l'archive PhpZip")." \"$p_archive\"";

        // ----- Reset the archive name
        $p_archive = "";

        // ----- Display HTML page
        AppPhpzipStatus("Lire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }

    // ----- Look for ZIP format
    else if ($v_archive_type == "zip")
    {
      // ----- Recuperate the file list
      if ($p_message == "")
        $p_message = Translate("Contenu de l'archive ").$p_archive.".";
      if (AppPhpzipZipList($p_archive, $p_message) != 1)
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Erreur lors de la lecture de l'archive PhpZip")." \"$p_archive\"";

        // ----- Reset the archive name
        $p_archive = "";

        // ----- Display HTML page
        AppPhpzipStatus("Lire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }

    // ----- Look for TAR format
    else if ($v_archive_type != "")
    {
      // ----- Recuperate the file list
      $v_list = PclTarList($p_archive);
      if (is_array($v_list))
      {
        if ($p_message == "")
          $p_message = Translate("Contenu de l'archive ").$p_archive.".";
        AppPhpzipListTar($p_archive, $v_list, $p_message);
      }
      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Erreur lors de la lecture de l'archive PhpZip")." \"$p_archive\"";

        // ----- Reset the archive name
        $p_archive = "";

        // ----- Display HTML page
        AppPhpzipStatus("Lire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }
    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Reset the archive name
      $p_archive = "";

      // ----- Display HTML page
      AppPhpzipStatus("Lire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipListTar()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipListTar($p_archive, $v_list, $p_message="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipListTar", "$p_archive, $v_list");

    // ----- Look for flat or tree view
    if ($g_view_archive)
    {
      $v_result = AppPhpzipListFlatTar($p_archive, $v_list, $p_message);
    }
    else
    {
      $v_result = AppPhpzipListTreeTar($p_archive, $v_list, $p_message);
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipListTar()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipListFlatTar($p_archive, $v_list, $p_message="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipListFlatTar", "$p_archive, $p_list");

        // ----- Display HTML header
        AppPhpzipHeader($p_archive);

        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center>&nbsp</div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Fichier")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Taille")."</font></div></td>";
        //echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Compression")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Dossier")."</font></div></td>";
        echo "</tr>";

        // ----- List the files
        for ($i=0; $i<sizeof($v_list); $i++)
        {
          if ($v_list[$i]['typeflag'] == 5)
            $v_image = "folder02-16.gif";
          else
            $v_image = AppPhpzipExtensionImage($v_list[$i]['filename']);

          // ----- Display
          echo "<tr bgcolor=$g_text_bg>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center><img src='$g_images_dir/$v_image' border='0' width='16' height='16' align='absmiddle'></div></font></td>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".basename($v_list[$i]['filename'])."</font></td>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".$v_list[$i]['size']."</div></font></td>";
          //if ($v_tar_ext == "tgz")
          //  printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".Translate("Oui")." : %3d%%</div></font></td>", $v_percent);
          //else
          //  printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".Translate("Non")."</div></font></td>");
          $v_dirname = dirname($v_list[$i]['filename']);
          if ($v_dirname == $v_list[$i]['filename'])
            $v_dirname == "";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".$v_dirname."</font></td>";
          echo "</tr>";
        }

        // ----- Look for empty list
        if (sizeof($v_list) == 0)
        {
          echo "</table>";
          echo "<table width=100% border=0 cellspacing=0 cellpadding=0><tr><td>&nbsp</td></tr><tr bgcolor=$g_text_bg>";
          echo "<td><div align=center><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Aucun fichier archivé")."</font></div></td>";
          echo "</tr><tr><td>&nbsp</td></tr>";
        }

        // ----- Display HTML footer
        echo "</table>";
        if ($p_message == "")
          $p_message = Translate("Contenu de l'archive ").$p_archive.".";
        AppPhpzipFooter($p_message);

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------


  // --------------------------------------------------------------------------------
  // Function : AppPhpzipListTreeTar()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipListTreeTar($p_archive, $v_list, $p_message="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipListTreeTar", "$p_archive, $v_list");

    // ----- Compose a tree
    $v_tree = array();
    $n = sizeof($v_list);
    for ($i=0, $j=0; $i<$n; $i++, $j++)
    {
      TrFctMessage(__FILE__, __LINE__, 4, "Adding '".$v_list[$i]['filename']."' as a root of the tree");
      $v_tree[$j]=AppPhpzipComposeTree($v_list, $i);
    }

        // ----- Display HTML header
        AppPhpzipHeader($p_archive);

        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=left><img src='$g_images_dir/folder-link00-16.gif' border='0' width='16' height='16' align='absmiddle'><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Fichier")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Taille")."</font></div></td>";
        echo "</tr>";

        // ----- Display the tree
        for ($i=0; $i<sizeof($v_tree); $i++)
        {
          AppPhpzipListTreeItemTar($p_archive, $v_list, $v_tree[$i], "", 1, 1);
        }

        // ----- Look for empty list
        if (sizeof($v_list) == 0)
        {
          echo "</table>";
          echo "<table width=100% border=0 cellspacing=0 cellpadding=0><tr><td>&nbsp</td></tr><tr bgcolor=$g_text_bg>";
          echo "<td><div align=center><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Aucun fichier archivé")."</font></div></td>";
          echo "</tr><tr><td>&nbsp</td></tr>";
        }

        // ----- Display HTML footer
        echo "</table>";
        if ($p_message == "")
          $p_message = Translate("Contenu de l'archive ").$p_archive.".";
        AppPhpzipFooter($p_message);

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipComposeTree()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipComposeTree(&$p_list, &$p_index, $p_parent_index=-1)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipComposeTree", "item='".$p_list[$p_index]['filename']."', index='$p_index', parent_index='$p_parent_index'");

    $v_list_size = sizeof($p_list);

    // ----- Set the default index value
    $p_list[$p_index]['index'] = $p_index;

    // ----- Memorize info for futur use
    $v_futur_parent = $p_index;
    $v_calling_index = $p_index;
    if ($p_list[$p_index]['typeflag']==5)
      $v_look_for_child=1;
    else
      $v_look_for_child=0;

    // ----- Look for root
    if (($p_parent_index==-1) ||
        (($p_list[$p_index]['typeflag']!=5) && ($p_list[$p_parent_index]['filename'] != dirname($p_list[$p_index]['filename']))))
    {
      TrFctMessage(__FILE__, __LINE__, 4, "Looking for new tree '".$p_list[$p_index]['filename']."'");

      // ----- Explode path of the item
      $p_path=explode("/", $p_list[$p_index]['filename']);
      $n = sizeof($p_path);
      // ----- Remove empty string, when directory finish by a single '/'
      if ($p_path[$n-1]=="")
        $n--;

      if ($p_parent_index>-1)
      {
        $p_path_parent=explode("/", $p_list[$p_parent_index]['filename']);
        $n_parent = sizeof($p_path_parent);
        if ($p_path_parent[$n_parent-1]=="")
          $n_parent--;
      }
      else
      {
        $n_parent=0;
      }

      TrFctMessage(__FILE__, __LINE__, 4, "'$n' sub-dir detected ('$n_parent' parent sub-dir)");

      // ----- Look for more than one element in the path
      // If there is several item, we must creates artificial nodes to have a true view of the tree
      if ($n > 1)
      {
        $v_futur_parent = 0;

        // ----- The returned index will be the one of the first created header
        $n1 = sizeof($p_list);
        $v_calling_index = $n1;

        TrFctMessage(__FILE__, __LINE__, 4, "Path with '$n1' sub-dir detected");

        // ----- Create a header for each sub-directory
        for ($i=$n_parent; $i<$n-1; $i++, $n1++)
        {
          TrFctMessage(__FILE__, __LINE__, 4, "Looking for path '".$p_path[$i]."', creating node index '$n1'");
          $p_list[$n1]['child_list'] = array();
          $p_list[$n1]['filename'] = "";
          $p_list[$n1]['size'] = 0;
          $p_list[$n1]['index'] = $p_index;
          $p_list[$n1]['typeflag'] = 5;
          for ($j=0; $j<=$i; $j++)
          {
            if ($j!=$i)
              $p_list[$n1]['filename'] .= $p_path[$j]."/";
            else
              $p_list[$n1]['filename'] .= $p_path[$j];
          }
          TrFctMessage(__FILE__, __LINE__, 4, "Calculated path for new node '".$p_list[$n1]['filename']."'");

          // ----- Daisy chain the created nodes (but not the first one)
          if ($i!=$n_parent)
            $p_list[$v_futur_parent]['child_list'][0] = $n1;

          $v_futur_parent = $n1;
        }

        $v_look_for_child=1;
        $p_index--;
      }
    }

    // ----- Default childs and brother
    $p_list[$p_index]['child_list'] = array();

    // ----- Look if it is a folder
    if ($v_look_for_child)
    {
      TrFctMessage(__FILE__, __LINE__, 4, "Looking for child for the node $p_list[$v_futur_parent]['filename']");

      // ----- Go through the entries
      $p_index++;
      $v_size_parent = strlen($p_list[$v_futur_parent]['filename']);
      TrFctMessage(__FILE__, __LINE__, 4, "Starting loop at node index '$p_index'");
      $v_nb_childs = sizeof($p_list[$v_futur_parent]['child_list']);
      while (($p_index < $v_list_size) && (substr($p_list[$p_index]['filename'], 0, $v_size_parent) == $p_list[$v_futur_parent]['filename']) && ($v_size_parent != strlen($p_list[$p_index]['filename'])))
      {
        TrFctMessage(__FILE__, __LINE__, 4, "Adding node index '$p_index' as a child");
        $p_list[$v_futur_parent]['child_list'][$v_nb_childs++] = AppPhpzipComposeTree($p_list, $p_index, $v_futur_parent);
        $p_index++;
      }
      $p_index--;
    }

    TrFctMessage(__FILE__, __LINE__, 4, "Node '".$p_list[$v_calling_index]['filename']."' has ".sizeof($p_list[$v_calling_index]['child_list'])." childs'");

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_calling_index);
    return $v_calling_index;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipListTreeItemTar()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipListTreeItemTar($p_archive, $p_list, $p_index, $p_prefix="", $p_last=0, $p_root=0)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipListTreeItemTar", "archive='$p_archive', index='".$p_index."' (".$p_list[$p_index]['filename']."), last=$p_last");

    // ----- Get the image associated with the file type
    if ($p_list[$p_index]['typeflag']==5)
    {
      $v_image = "folder02-16.gif";
    }
    else
    {
      $v_image = AppPhpzipExtensionImage($p_list[$p_index]['filename']);
    }

    // ----- Display
    echo "<tr bgcolor=$g_text_bg>";
    echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=left>";
    echo "<img src='$g_images_dir/blanc-9-9.gif' border='0' width='16' height='16' align='absmiddle'>";

    // ----- Display received prefix
    if (!$p_root)
    {
      echo $p_prefix;

      // ----- Display local prefix
      if ($p_last)
      {
        echo "<img src='$g_images_dir/folder-link03-16.gif' border='0' width='16' height='16' align='absmiddle'>";
      }
      else
      {
        echo "<img src='$g_images_dir/folder-link02-16.gif' border='0' width='16' height='16' align='absmiddle'>";
      }
    }

    // ----- Compose description string of file/directory
    $v_description_string = Translate(($p_list[$p_index]['typeflag']==5?"Dossier":"Fichier"))." : ".basename($p_list[$p_index]['filename'])."\n";
    if (($v_string = dirname($p_list[$p_index]['filename'])) != "")
      $v_description_string .= Translate("Chemin")." : ".$v_string."\n";
    if ($p_list[$p_index]['typeflag']!=5)
      $v_description_string .= Translate("Taille")." : ".$p_list[$p_index]['size']." ".Translate("octet")."s\n";
    $v_description_string .= Translate("Dernière modification")." : ".date("d/m/Y H:i:s", $p_list[$p_index]['mtime'])."";

    // ----- Calculate the range index for a directory
    if ($p_list[$p_index]['typeflag']==5)
    {
      $v_index_string = "".$p_list[$p_index]['index']."";

      // ----- Look for index last child of last child of last child etc ... of the folder
      if (($n=sizeof($p_list[$p_index]['child_list']))!=0)
      {
        $v_index_folder = $p_list[$p_index]['child_list'][$n-1];
        while (($n=sizeof($p_list[$v_index_folder]['child_list']))!=0)
        {
          $v_index_folder = $p_list[$v_index_folder]['child_list'][$n-1];
        }
        $v_index_string .= "-".$p_list[$v_index_folder]['index'];
      }
    }
    else
    {
      $v_index_string = $p_list[$p_index]['index'];
    }
    TrFctMessage(__FILE__, __LINE__, 4, "Index is '$v_index_string'");

    //echo "<a href='' title='coucou !' onClick='window.alert(\"Un click\");return false;' onDblClick='window.alert(\"Un double click\");return false;'>";
    echo "<img src='$g_images_dir/$v_image' border='0' width='16' height='16' align='absmiddle'>";
    //echo "</a>";
    echo " <font face=$g_font size=$g_text_size color=$g_text_color>";
//    echo "<a href='' class='file' title=\"".$v_description_string."\" onClick='PcjsZipFileOpenPopup(\"".$v_index_string."\",\"".$p_list[$p_index]['filename']."\",\"".$p_archive."\",".($p_list[$p_index]['typeflag']==5)."); return false;'>";
//    echo "<a href='' class='file' title=\"".$v_description_string."\" onClick='g_pcjszip_index=\"".$v_index_string."\";g_pcjszip_name=\"".$p_list[$p_index]['filename']."\";g_pcjszip_archive=\"".$p_archive."\";g_pcjszip_is_dir=".($p_list[$p_index]['typeflag']==5).";PcjsZipFileOpenPopup(); return false;'>";
    echo "<a href='javascript:void(0);' id='link_".$v_index_string."' class='file' title=\"".$v_description_string."\" href='javascript:void(0);'>";
    echo basename($p_list[$p_index]['filename']);
    echo "</a>";
    echo "</font></div></font></td>";
    echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".$p_list[$p_index]['size']."</div></font></td>";
    echo "</tr>";

?>
<script language="JavaScript1.2">
PcjsZipFileDeclare('<?php echo $v_index_string; ?>', '<?php echo $p_list[$p_index]['filename']; ?>', '<?php echo $p_archive; ?>', '<?php echo ($p_list[$p_index]['typeflag']==5); ?>');
</script>

<?php
    // ----- Display the childs
    $v_nb_childs = sizeof($p_list[$p_index]['child_list']);
    TrFctMessage(__FILE__, __LINE__, 4, "Node '".$p_list[$p_index]['filename']."' has $v_nb_childs childs");
    for ($i=0; $i<$v_nb_childs; $i++)
    {
      TrFctMessage(__FILE__, __LINE__, 4, "Looking for child '".$p_list[$p_index]['child_list'][$i]."' of '".$p_list[$p_index]['filename']."'");

      if (!$p_root)
      {
        TrFctMessage(__FILE__, __LINE__, 4, "Father is not root");
        if ($p_last)
          $v_prefix = $p_prefix."<img src='$g_images_dir/folder-link00-16.gif' border='0' width='16' height='16' align='absmiddle'>";
        else
          $v_prefix = $p_prefix."<img src='$g_images_dir/folder-link01-16.gif' border='0' width='16' height='16' align='absmiddle'>";
      }
      else
        $v_prefix = "";

      AppPhpzipListTreeItemTar($p_archive, $p_list, $p_list[$p_index]['child_list'][$i], $v_prefix, $i==($v_nb_childs-1));
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------


  // --------------------------------------------------------------------------------
  // Function : AppPhpzipListTreeItemTar()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipListTreeItemTar2($p_archive, $p_list, $p_index, $p_prefix="", $p_root=0)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipListTreeItemTar", "archive='$p_archive', index='".$p_index."' (".$p_list[$p_index]['filename'].")");

    // ----- Get the image associated with the file type
    if ($p_list[$p_index]['typeflag']==5)
    {
      $v_image = "folder02-16.gif";
    }
    else
    {
      $v_image = AppPhpzipExtensionImage($p_list[$p_index]['filename']);
    }

    // ----- Display
    echo "<tr bgcolor=$g_text_bg>";
    echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=left>";
    echo "<img src='$g_images_dir/blanc-9-9.gif' border='0' width='16' height='16' align='absmiddle'>";

    // ----- Display received prefix
    if (!$p_root)
    {
      echo $p_prefix;

      // ----- Display local prefix
      if ($p_list[$p_index]['brother']==0)
      {
        echo "<img src='$g_images_dir/folder-link03-16.gif' border='0' width='16' height='16' align='absmiddle'>";
      }
      else
      {
        echo "<img src='$g_images_dir/folder-link02-16.gif' border='0' width='16' height='16' align='absmiddle'>";
      }
    }

    //echo "<a href='' title='coucou !' onClick='window.alert(\"Un click\");return false;' onDblClick='window.alert(\"Un double click\");return false;'>";
    echo "<img src='$g_images_dir/$v_image' border='0' width='16' height='16' align='absmiddle'>";
    //echo "</a>";
    echo " <font face=$g_font size=$g_text_size color=$g_text_color>";
//    echo "<a href='' class=file title='coucou !' onClick='PcjsZipFileOpenPopup(".$p_index.",\"".$p_archive."\"); return false;'>";
    echo "<a href='' id='link_".$p_index."' class='file' title=\"coucou !\" href='javascript:void(0);'>";
    echo basename($p_list[$p_index]['filename']);
    echo "</a>";
    echo "</font></div></font></td>";
    echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".$p_list[$p_index]['size']."</div></font></td>";
    echo "</tr>";

?>
<script language="JavaScript1.2">
PcjsZipFileDeclare('<?php echo $p_index; ?>', '<?php echo $p_list[$p_index]['filename']; ?>', '<?php echo $p_archive; ?>', '<?php echo ($p_list[$p_index]['typeflag']==5); ?>');
</script>

<?php

    // ----- Display the childs
    $p_index_child = $p_list[$p_index]['childs'];
    TrFctMessage(__FILE__, __LINE__, 4, "Node '".$p_list[$p_index]['filename']."' has $p_index_child childs");
    while ($p_index_child != 0)
    {
      TrFctMessage(__FILE__, __LINE__, 4, "Looking for child '".$p_list[$p_index_child]['filename']."' of '".$p_list[$p_index]['filename']."'");

      if (!$p_root)
      {
        TrFctMessage(__FILE__, __LINE__, 4, "Father is not root");
        if ($p_list[$p_index]['brother']!=0)
          $v_prefix = $p_prefix."<img src='$g_images_dir/folder-link01-16.gif' border='0' width='16' height='16' align='absmiddle'>";
        else
          $v_prefix = $p_prefix."<img src='$g_images_dir/folder-link00-16.gif' border='0' width='16' height='16' align='absmiddle'>";
      }
      else
        $v_prefix = "";

      AppPhpzipListTreeItemTar($p_archive, $p_list, $p_index_child, $v_prefix);
      $p_index_child = $p_list[$p_index_child]['brother'];
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------


  // --------------------------------------------------------------------------------
  // Function : AppPhpzipAskUnzip()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipAskUnzip($p_archive, $p_message="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipAskUnzip", "$p_archive, $p_message");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Recuperate the file list
      if (($v_result = PhpzipList($p_archive, $v_list, $v_list_detail)) == 1)
      {
        // ----- Display HTML header
        AppPhpzipHeader($p_archive);

        // ----- Header of action
        echo "<form method=post action=\"\">";
        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center><b><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Sélection des fichiers à extraire")."</font></b></div></td>";
        echo "</tr>";
        echo "<tr height=1 bgcolor=$g_text_bg><td></td></tr>";
        echo "</table>";

        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Extraire")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Fichier")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Taille")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Compression")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Dossier")."</font></div></td>";
        echo "</tr>";

        // ----- Look for empty list
        if (sizeof($v_list) == 0)
        {
          echo "</table>";
          echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
          echo "<tr bgcolor=$g_text_bg>";
          echo "<td><div align=center><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Aucun fichier archivé")."</font></div></td>";
          echo "</tr>";
        }
        else
        {
          // ----- List the files
          for ($i=0; $i<sizeof($v_list); $i++)
          {
            // ----- Explode to get the properties
            $v_token = explode(":", $v_list_detail[$i]);

            // ----- Calculate the compression
            if ($v_token[1] != 0)
              $v_percent = (((integer)$v_token[1]-(integer)$v_token[2])*100)/((integer)$v_token[1]);
            else
              $v_percent = 0;

            // ----- Display each file in the archive
            echo "<tr>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center><INPUT TYPE='CHECKBOX'  name=a_file[a".$i."] value=\"".$v_list[$i]."\" align='middle'></div></font></td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".$v_token[0]."</font></td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".$v_token[1]."</div></font></td>";
            if ($v_token[4] == "C")
              printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".Translate("Oui")." : %3d%%</div></font></td>", $v_percent);
            else
              printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".Translate("Non")."</div></font></td>");
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".$v_token[3]."</font></td>";
            echo "</tr>";
          }

          // ----- Ask for all archive extraction
          echo "<tr height=10><td></td><td></td><td></td><td></td><td></td></tr>";
          echo "<tr bgcolor=$g_title_bg height=1><td></td><td></td><td></td><td></td><td></td></tr>";
          echo "<tr><td><div align=center><INPUT TYPE='CHECKBOX'  name='a_extractall' value=TRUE align='middle'></div></td>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Extraire toute l'archive")."</font></td>";
          echo "<td></td><td></td><td></td></tr>";
        }

        echo "<tr bgcolor=$g_title_bg height=1><td></td><td></td><td></td><td></td><td></td></tr>";
        echo "</table>";
        echo "</p>";

        // ----- Ask for complementary informations
        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";

        // ----- Ask for extraction directory
        echo "<tr bgcolor=$g_text_bg><td width=10></td><td><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Décompresser dans le dossier")." : </font>";
        echo "<input type=text name=a_path size=40 maxlength=60 value=./></td></tr>";
        echo "</table>";

        // ----- Hidden informations : next action and archive name
        echo "<input type=hidden name=a_action value=unzip_do>";
        echo "<input type=hidden name=a_archive value=$p_archive>";

        // ----- Extract button
        echo "<p align=center><input type=submit value=\"".Translate("Extraire")."\"></p>";
        echo "</form>";

        // ----- Display HTML footer
        if ($p_message == "")
          $p_message = Translate("Selectionner les fichiers à extraire de l'archive ").$p_archive.".";
        AppPhpzipFooter($p_message);
      }
      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Erreur lors de la lecture de l'archive PhpZip")." \"$p_archive\"";

        // ----- Reset the archive name
        $p_archive = "";

        // ----- Display HTML page
        AppPhpzipStatus("Lire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }

    // ----- Look for tar format
    else if ($v_archive_type != "")
    {
      $v_list = PclTarList($p_archive);

      // ----- Recuperate the file list
      if (sizeof($v_list) != 0)
      {
        // ----- Display HTML header
        AppPhpzipHeader($p_archive);

        // ----- Header of action
        echo "<form method=post action=\"\">";
        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center><b><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Sélection des fichiers à extraire")."</font></b></div></td>";
        echo "</tr>";
        echo "<tr height=1 bgcolor=$g_text_bg><td></td></tr>";
        echo "</table>";

        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Extraire")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Fichier")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Taille")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Compression")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Dossier")."</font></div></td>";
        echo "</tr>";

        // ----- Look for empty list
        if (sizeof($v_list) == 0)
        {
          echo "</table>";
          echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
          echo "<tr bgcolor=$g_text_bg>";
          echo "<td><div align=center><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Aucun fichier archivé")."</font></div></td>";
          echo "</tr>";
        }
        else
        {
          // ----- List the files
          for ($i=0; $i<sizeof($v_list); $i++)
          {
            if ($v_list[$i]['typeflag'] == 5)
              $v_image = "folder02-16.gif";
            else
              $v_image = AppPhpzipExtensionImage($v_list[$i]['filename']);

            // ----- Display each file in the archive
            echo "<tr>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center><INPUT TYPE='CHECKBOX'  name=a_file[a".$i."] value=\"".$v_list[$i]['filename'].($v_list[$i]['typeflag'] == 5?"/":"")."\" align='middle'></div></font></td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>";
            echo "<img src='$g_images_dir/$v_image' align='absmiddle'> ";
            echo basename($v_list[$i]['filename'])."</font></td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".$v_list[$i]['size']."</div></font></td>";
            if ($v_archive_type == "tgz")
              printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".Translate("Oui")."</div></font></td>");
            else
              printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".Translate("Non")."</div></font></td>");
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".dirname($v_list[$i]['filename'])."</font></td>";
            echo "</tr>";
          }

          // ----- Ask for all archive extraction
          echo "<tr height=10><td></td><td></td><td></td><td></td><td></td></tr>";
          echo "<tr bgcolor=$g_title_bg height=1><td></td><td></td><td></td><td></td><td></td></tr>";
          echo "<tr><td><div align=center><INPUT TYPE='CHECKBOX'  name='a_extractall' value=TRUE align='middle'></div></td>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Extraire toute l'archive")."</font></td>";
          echo "<td></td><td></td><td></td></tr>";
        }

        echo "<tr bgcolor=$g_title_bg height=1><td></td><td></td><td></td><td></td><td></td></tr>";
        echo "</table>";
        echo "</p>";

        // ----- Ask for complementary informations
        echo "<blockquote>";

        // ----- Ask for extraction directory
        echo "<font face=$g_font size=$g_text_size color=$g_text_color>";
        echo Translate("Modification des chemins d'extraction")." :";
        echo "<br>".Translate("Décompresser dans le dossier")." : ";
        echo "<input type=text name=a_path size=40 maxlength=60 value=./></font>";

        // ----- Ask for remove of directory
        echo "<br><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Chemin à ignorer")." : ";
        echo "<input type=text name=a_remove_dir size=46 maxlength=60 value=></font>";

        echo "</blockquote>";


        // ----- Hidden informations : next action and archive name
        echo "<input type=hidden name=a_action value=unzip_do>";
        echo "<input type=hidden name=a_archive value=$p_archive>";

        // ----- Extract button
        echo "<p align=center><input type=submit value=\"".Translate("Extraire")."\"></p>";
        echo "</form>";

        // ----- Display HTML footer
        if ($p_message == "")
          $p_message = Translate("Selectionner les fichiers à extraire de l'archive ").$p_archive.".";
        AppPhpzipFooter($p_message);
      }
      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Erreur lors de la lecture de l'archive PhpZip")." \"$p_archive\"";

        // ----- Reset the archive name
        $p_archive = "";

        // ----- Display HTML page
        AppPhpzipStatus("Lire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Reset the archive name
      $p_archive = "";

      // ----- Display HTML page
      AppPhpzipStatus("Lire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipUnzip()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipUnzip($p_archive, $p_path, $p_remove_dir)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipUnzip", "archive='$p_archive', path='$p_path', remove_dir='$p_remove_dir'");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Init
      $v_startfile = "";

      // ----- Recuperate the file list
      if (($v_result = PhpzipUnzipStart($p_archive, $v_startfile, $p_path)) == 1)
      {
        // ----- Display HTML header
        AppPhpzipHeader($p_archive);

        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center><font color=$g_title_color face=$g_font>".Translate("Décompression PhpZip")."</font></div></td>";
        echo "</tr>";
        echo "<tr bgcolor=$g_text_bg><td><font face=$g_font size=$g_text_size color=$g_text_color><p><br><div align=center>".Translate("Opération terminée")."</div></p></font>";
        if ($v_startfile != "")
        {
          echo "<font face=$g_font size=$g_text_size color=$g_text_color><p><div align=center>".Translate("L'archive")." $p_archive ".Translate("contient un fichier d'auto-start.")."<br>";
          echo Translate("Cliquez dessus pour le lancer")." : <a class=text href=$v_startfile>$v_startfile</a></div></p></font>";
        }
        echo "</td></tr>";

        // ----- Display HTML footer
        echo "</table>";
        AppPhpzipFooter(Translate("Décompression PhpZip OK."));
      }
      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Erreur lors de la décompression de l'archive PhpZip")." \"$p_archive\"";

        // ----- Reset the archive name
        $p_archive = "";

        // ----- Display HTML page
        AppPhpzipStatus("Extraire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
     }
    }

    // ----- Look for tar format
    else if ($v_archive_type != "")
    {
      // ----- Extract the files
      $v_list_result = PclTarExtract($p_archive, $p_path, $p_remove_dir);
      if (sizeof($v_list_result) != 0)
      {
        // ----- Display HTML header
        AppPhpzipHeader($p_archive);

        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center><font color=$g_title_color face=$g_font>".Translate("Décompression PhpZip")."</font></div></td>";
        echo "</tr>";
        echo "<tr bgcolor=$g_text_bg><td><font face=$g_font size=$g_text_size color=$g_text_color><p><br><div align=center>".Translate("Opération terminée")."</div></p></font>";
        echo "</td></tr>";

        // ----- Display HTML footer
        echo "</table>";
        AppPhpzipFooter(Translate("Décompression PhpZip OK."));
      }
      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Erreur lors de la décompression de l'archive PhpZip")." \"$p_archive\"";

        // ----- Reset the archive name
        $p_archive = "";

        // ----- Display HTML page
        AppPhpzipStatus("Extraire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
     }
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Reset the archive name
      $p_archive = "";

      // ----- Display HTML page
      AppPhpzipStatus("Extraire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipUnzipList()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipUnzipList($p_archive, $p_file, $p_path, $p_remove_dir)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipUnzipList", "$p_archive, p_file(0..".sizeof($p_file)."), path='$p_path', remove_dir='$p_remove_dir'");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Look for file
      if (sizeof($p_file))
      {
        // ----- Compose the file list
        for (reset($p_file), $i=0; $key=key($p_file); $key=next($p_file))
        {
          TrFctMessage(__FILE__, __LINE__, 2, "$i . key=[$key], p_file[$key]=[$p_file[$key]]");
          $v_list[$i++] = $p_file[$key];
        }

        // ----- Unzip the files
        if (($v_result = PhpzipUnzipList($p_archive, $v_list, $p_path)) == 1)
        {
          // ----- Display HTML header
          AppPhpzipHeader($p_archive);

          echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
          echo "<tr bgcolor=$g_title_bg>";
          echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Décompression PhpZip")."</font></div></td>";
          echo "</tr>";
          echo "<tr bgcolor=$g_text_bg><td><font face=$g_font size=$g_text_size color=$g_text_color><p><br><div align=center>".Translate("La liste de fichiers a bien été extraite de l'archive.")."</div></p></font>";
          echo "</td></tr>";
          echo "</table>";

          // ----- Display the list of extracted files
          echo "<table width=100% border=0 cellspacing=0 cellpadding=0><tr bgcolor=$g_text_bg><td>&nbsp</td></tr>";
          for ($i=0; $i<sizeof($v_list); $i++)
          {
            echo "<tr bgcolor=$g_text_bg><td><div align=center>";
            echo "<font face=$g_font size=$g_text_size color=$g_text_color>$v_list[$i]</font>";
            echo "<div></td></tr>";
          }
          echo "<tr bgcolor=$g_text_bg><td>&nbsp</td></tr></table>";

          // ----- Display HTML footer
          AppPhpzipFooter(Translate("Extraction terminée."));
        }
        else
        {
          // ----- Set an error string for the footer
          $v_message = Translate("Erreur lors de l'extraction d'une liste de fichiers de l'archive PhpZip")." \"$p_archive\".";

          // ----- Look for error messages
          if ($v_result == -4)
            $v_message = Translate("L'un des fichiers de la liste existe déjà et est protégé en écriture");

          // ----- Display status
          AppPhpzipStatus("Extraire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
        }

        // ----- Clean
        unset($v_list);
      }

      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Impossible de lire la liste des fichiers.");

        // ----- Display HTML page
        AppPhpzipStatus("Extraire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }


    // ----- Look for tar (tar.gz) format
    else if ($v_archive_type != "")
    {
      // ----- Look for file
      if (sizeof($p_file))
      {
        // ----- Compose the file list
        for (reset($p_file), $i=0; $key=key($p_file); $key=next($p_file))
        {
          TrFctMessage(__FILE__, __LINE__, 2, "$i . key=[$key], p_file[$key]=[$p_file[$key]]");
          $v_list[$i++] = $p_file[$key];
        }

        // ----- Extract the files
        $v_list_result = PclTarExtractList($p_archive, $v_list, $p_path, $p_remove_dir);
        if (sizeof($v_list_result) != 0)
        {
          // ----- Display HTML header
          AppPhpzipHeader($p_archive);

          echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
          echo "<tr bgcolor=$g_title_bg>";
          echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Décompression PhpZip")."</font></div></td>";
          echo "</tr>";
          echo "<tr bgcolor=$g_text_bg><td><font face=$g_font size=$g_text_size color=$g_text_color><p><br><div align=center>".Translate("La liste de fichiers a bien été extraite de l'archive.")."</div></p></font>";
          echo "</td></tr>";
          echo "</table>";

          $v_message = array();
          $v_message['ok']="OK";
          $v_message['added']=Translate("Ajouté");
          $v_message['updated']=Translate("Mis à jour");
          $v_message['not_updated']=Translate("Non mis à jour");
          $v_message['already_a_directory']=Translate("Un dossier utilise déjà ce nom");
          $v_message['write_protected']=Translate("Le fichier est protégé en écriture");
          $v_message['newer_exist']=Translate("Un fichier plus récent existe");
          $v_message['path_creation_fail']=Translate("Impossible de créer le dossier du fichier");
          $v_message['write_error']=Translate("Problème d'écriture du fichier");

          // ----- Display the list of extracted files
          echo "<table width=100% border=0 cellspacing=0 cellpadding=0><tr bgcolor=$g_text_bg><td>&nbsp</td></tr>";
          for ($i=0; $i<sizeof($v_list_result); $i++)
          {
            echo "<tr bgcolor=$g_text_bg><td><div align=center>";
            echo "<font face=$g_font size=$g_text_size color=$g_text_color>".$v_list_result[$i]['filename']." -> ";
            if ($v_list_result[$i]['status']=="ok")
              echo "Ok";
            else
              echo "</font><font face=$g_font size=$g_error_size color=red>Error : '".$v_message[$v_list_result[$i]['status']]."'";
            echo "</font>";
            echo "<div></td></tr>";
          }
          echo "<tr bgcolor=$g_text_bg><td>&nbsp</td></tr></table>";

          // ----- Display HTML footer
          AppPhpzipFooter(Translate("Extraction terminée."));
        }
        else
        {
          // ----- Set an error string for the footer
          $v_message = Translate("Erreur lors de l'extraction d'une liste de fichiers de l'archive PhpZip")." \"$p_archive\".";

          // ----- Display status
          AppPhpzipStatus("Extraire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
        }

        // ----- Clean
        unset($v_list);
      }

      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Impossible de lire la liste des fichiers.");

        // ----- Display HTML page
        AppPhpzipStatus("Extraire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Extraire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipAskDeleteFile()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipAskDeleteFile($p_archive, $p_message="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipAskDeleteFile", "archive='$p_archive', message='$p_message'");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Not supported yet

      // ----- Set an error string for the footer
      $v_message = Translate("Fonction non supportée pour ce type d'archive");

      // ----- Display HTML page
      AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Look for Zip format
    else if ($v_archive_type == "zip")
    {
      // ----- Not supported yet

      // ----- Set an error string for the footer
      $v_message = Translate("Fonction non supportée pour ce type d'archive");

      // ----- Display HTML page
      AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Look for tar format
    else if ($v_archive_type != "")
    {
      $v_list = PclTarList($p_archive);

      // ----- Recuperate the file list
      if (sizeof($v_list) != 0)
      {
        // ----- Display HTML header
        AppPhpzipHeader($p_archive);

        // ----- Header of action
        echo "<form method=post action=\"\" target=PhpZipAction>";
        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center><b><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Sélection des fichiers à supprimer")."</font></b></div></td>";
        echo "</tr>";
        echo "<tr height=1 bgcolor=$g_text_bg><td></td></tr>";
        echo "</table>";

        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Supprimer")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Fichier")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Taille")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Compression")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Dossier")."</font></div></td>";
        echo "</tr>";

        // ----- Look for empty list
        if (sizeof($v_list) == 0)
        {
          echo "</table>";
          echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
          echo "<tr bgcolor=$g_text_bg>";
          echo "<td><div align=center><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Aucun fichier archivé")."</font></div></td>";
          echo "</tr>";
        }
        else
        {
          // ----- List the files
          for ($i=0; $i<sizeof($v_list); $i++)
          {
            if ($v_list[$i]['typeflag'] == 5)
              $v_image = "folder02-16.gif";
            else
              $v_image = AppPhpzipExtensionImage($v_list[$i]['filename']);

            // ----- Display each file in the archive
            echo "<tr>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center><INPUT TYPE='CHECKBOX'  name=a_file[a".$i."] value=\"".$v_list[$i]['filename']."\" align='middle'></div></font></td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>";
            echo "<img src='$g_images_dir/$v_image' align='absmiddle'> ";
            echo basename($v_list[$i]['filename'])."</font></td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".$v_list[$i]['size']."</div></font></td>";
            if ($v_archive_type == "tgz")
              printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".Translate("Oui")."</div></font></td>");
            else
              printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".Translate("Non")."</div></font></td>");
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".dirname($v_list[$i]['filename'])."</font></td>";
            echo "</tr>";
          }
        }

        echo "<tr bgcolor=$g_title_bg height=1><td></td><td></td><td></td><td></td><td></td></tr>";
        echo "</table>";
        echo "</p>";

        // ----- Hidden informations : next action and archive name
        echo "<input type=hidden name=a_action value=del_file_do>";
        echo "<input type=hidden name=a_archive value=$p_archive>";

        // ----- Extract button
        echo "<p align=center><input type=submit value=\"".Translate("Supprimer")."\" onClick='PcjsActionWindow(\"\",\"\");'></p>";
        echo "</form>";

        // ----- Display HTML footer
        if ($p_message == "")
          $p_message = Translate("Selectionner les fichiers à supprimer de l'archive")." '".$p_archive."'.";
        AppPhpzipFooter($p_message);
      }
      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Erreur lors de la lecture de l'archive PhpZip")." \"$p_archive\"";

        // ----- Reset the archive name
        $p_archive = "";

        // ----- Display HTML page
        AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Reset the archive name
      $p_archive = "";

      // ----- Display HTML page
      AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipAskDeleteFile()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipAskDeleteFile_old($p_archive, $p_message="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipAskDeleteFile", "archive='$p_archive', message='$p_message'");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Not supported yet

      // ----- Set an error string for the footer
      $v_message = Translate("Fonction non supportée pour ce type d'archive");

      // ----- Display HTML page
      AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Look for tar format
    else if ($v_archive_type != "")
    {
      $v_list = PclTarList($p_archive);

      // ----- Recuperate the file list
      if (sizeof($v_list) != 0)
      {
        // ----- Display HTML header
        AppPhpzipHeader($p_archive);

        // ----- Header of action
        echo "<form method=post action=\"\">";
        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center><b><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Sélection des fichiers à supprimer")."</font></b></div></td>";
        echo "</tr>";
        echo "<tr height=1 bgcolor=$g_text_bg><td></td></tr>";
        echo "</table>";

        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Supprimer")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Fichier")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Taille")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Compression")."</font></div></td>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Dossier")."</font></div></td>";
        echo "</tr>";

        // ----- Look for empty list
        if (sizeof($v_list) == 0)
        {
          echo "</table>";
          echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
          echo "<tr bgcolor=$g_text_bg>";
          echo "<td><div align=center><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Aucun fichier archivé")."</font></div></td>";
          echo "</tr>";
        }
        else
        {
          // ----- List the files
          for ($i=0; $i<sizeof($v_list); $i++)
          {
            if ($v_list[$i]['typeflag'] == 5)
              $v_image = "folder02-16.gif";
            else
              $v_image = AppPhpzipExtensionImage($v_list[$i]['filename']);

            // ----- Display each file in the archive
            echo "<tr>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center><INPUT TYPE='CHECKBOX'  name=a_file[a".$i."] value=\"".$v_list[$i]['filename']."\" align='middle'></div></font></td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>";
            echo "<img src='$g_images_dir/$v_image' align='absmiddle'> ";
            echo basename($v_list[$i]['filename'])."</font></td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".$v_list[$i]['size']."</div></font></td>";
            if ($v_archive_type == "tgz")
              printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".Translate("Oui")."</div></font></td>");
            else
              printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".Translate("Non")."</div></font></td>");
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".dirname($v_list[$i]['filename'])."</font></td>";
            echo "</tr>";
          }
        }

        echo "<tr bgcolor=$g_title_bg height=1><td></td><td></td><td></td><td></td><td></td></tr>";
        echo "</table>";
        echo "</p>";

        // ----- Hidden informations : next action and archive name
        echo "<input type=hidden name=a_action value=del_file_do>";
        echo "<input type=hidden name=a_archive value=$p_archive>";

        // ----- Extract button
        echo "<p align=center><input type=submit value=\"".Translate("Supprimer")."\"></p>";
        echo "</form>";

        // ----- Display HTML footer
        if ($p_message == "")
          $p_message = Translate("Selectionner les fichiers à supprimer de l'archive")." '".$p_archive."'.";
        AppPhpzipFooter($p_message);
      }
      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Erreur lors de la lecture de l'archive PhpZip")." \"$p_archive\"";

        // ----- Reset the archive name
        $p_archive = "";

        // ----- Display HTML page
        AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Reset the archive name
      $p_archive = "";

      // ----- Display HTML page
      AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipDeleteFileList()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipDeleteFileList($p_archive, $p_file)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipDeleteFileList", "$p_archive, p_file(0..".sizeof($p_file).")");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Not supported yet

      // ----- Set an error string for the footer
      $v_message = Translate("Fonction non supportée pour ce type d'archive");

      // ----- Display HTML page
      AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }


    // ----- Look for Zip format
    else if ($v_archive_type == "zip")
    {
      AppPhpzipZipDeleteByIndex($p_archive, $p_file);
    }

    // ----- Look for tar (tar.gz) format
    else if ($v_archive_type != "")
    {
      // ----- Look for file
      if (sizeof($p_file))
      {
        // ----- Compose the file list
        for (reset($p_file), $i=0; $key=key($p_file); $key=next($p_file))
        {
          TrFctMessage(__FILE__, __LINE__, 2, "$i . key=[$key], p_file[$key]=[$p_file[$key]]");
          $v_list[$i++] = $p_file[$key];
        }

        // ----- Extract the files
        $v_list_result = PclTarDelete($p_archive, $v_list);
        if (sizeof($v_list_result) != 0)
        {
//          if ($p_message == "")
//            $p_message = Translate("Suppression terminée.");
//          AppPhpzipListTar($p_archive, $v_list_result, $p_message);


          // ----- Display HTML header
          AppPhpzipActionHeader($p_archive,  Translate("Etat de la suppression des fichiers"));

          echo "<table border=0 cellspacing=0 cellpadding=0 align=center>";
          echo "<tr bgcolor=$g_text_bg height=10><td colspan=7></td></tr>";
          echo "<tr bgcolor=$g_text_bg><td>";
          echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Etat")." : </font>";
          echo "</td><td width=10></td><td align=right>";
          echo "<font face=$g_font size=$g_text_size color=$g_text_color></font>";
          echo "</td><td width=10></td><td>";
          echo "<img src='$g_images_dir/file-tar-delete.gif' border='0' width='16' height='16' align='absmiddle'>";
          echo "</td><td width=10></td><td>";
          echo "<font face=$g_font size=$g_text_size color=$g_text_color>";
          echo Translate("fichiers supprimés")."</font>";
          echo "</td></tr>";
          echo "</table>";

?>
<div id="Layer2" style="position:absolute; width:300px; height:232px; z-index:1; overflow: auto; left: 20px; top: 85px; background-color: #CCCCCC; border: 1px none #CCCCCC">
<?

          // ----- Display the list of extracted files
          echo "<table border=0 cellspacing=0 cellpadding=0 align=left>";
          for ($i=0; $i<sizeof($v_list); $i++)
          {
            echo "<tr bgcolor=CCCCCC><td width=5>&nbsp</td><td>";

            echo "<img src='$g_images_dir/file-tar-delete.gif' border='0' width='16' height='16' align='absmiddle'>";

            echo "</td><td width=10>&nbsp</td><td>";
            echo "<font face=$g_font size=$g_text_size color=$g_text_color>";
            echo $v_list[$i];
            echo "</font></td>";
            echo "<td width=5>&nbsp</td></tr>";
          }
          echo "</table>";

?>
</div>
<?

          echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
          echo "<br><form method=post action=\"?\">";
          echo "<div align=center><font face=$g_font size=$g_text_size color=$g_text_color>";
          echo "<input type=submit value=\"";
          echo Translate("Fermer");
          echo "\" onClick='window.opener.focus(); window.close(); return false;'>";
          echo "</font></div>";
          echo "</form>";
          echo "<script language=\"Javascript\">window.onLoad=window.opener.open(\"?a_archive=$p_archive&a_action=list\", window.opener.name);</script>";

          // ----- Display HTML footer
          AppPhpzipActionFooter(Translate("Suppression terminée."));





        }
        else
        {
          // ----- Set an error string for the footer
          $v_message = Translate("Erreur lors de l'extraction d'une liste de fichiers de l'archive PhpZip")." \"$p_archive\".";

          // ----- Display status
          AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
        }

        // ----- Clean
        unset($v_list);
      }

      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Impossible de lire la liste des fichiers.");

        // ----- Display HTML page
        AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipDeleteFileByIndex()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipDeleteFileByIndex($p_archive, $p_index)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipDeleteFileByIndex", "archive=$p_archive, index=$p_index");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Not supported yet

      // ----- Set an error string for the footer
      $v_message = Translate("Fonction non supportée pour ce type d'archive");

      // ----- Display HTML page
      AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }


    // ----- Look for Zip format
    else if ($v_archive_type == "zip")
    {
      AppPhpzipZipDeleteByIndex($p_archive, $p_index);
    }

    // ----- Look for tar (tar.gz) format
    else if ($v_archive_type != "")
    {
      // ----- Not supported yet

      // ----- Set an error string for the footer
      $v_message = Translate("Fonction non supportée pour ce type d'archive");

      // ----- Display HTML page
      AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipDeleteFileList()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipDeleteFileList_old($p_archive, $p_file)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipDeleteFileList", "$p_archive, p_file(0..".sizeof($p_file).")");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Not supported yet

      // ----- Set an error string for the footer
      $v_message = Translate("Fonction non supportée pour ce type d'archive");

      // ----- Display HTML page
      AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }


    // ----- Look for tar (tar.gz) format
    else if ($v_archive_type != "")
    {
      // ----- Look for file
      if (sizeof($p_file))
      {
        // ----- Compose the file list
        for (reset($p_file), $i=0; $key=key($p_file); $key=next($p_file))
        {
          TrFctMessage(__FILE__, __LINE__, 2, "$i . key=[$key], p_file[$key]=[$p_file[$key]]");
          $v_list[$i++] = $p_file[$key];
        }

        // ----- Extract the files
        $v_list_result = PclTarDelete($p_archive, $v_list);
        if (sizeof($v_list_result) != 0)
        {
          if ($p_message == "")
            $p_message = Translate("Suppression terminée.");
          AppPhpzipListTar($p_archive, $v_list_result, $p_message);
        }
        else
        {
          // ----- Set an error string for the footer
          $v_message = Translate("Erreur lors de l'extraction d'une liste de fichiers de l'archive PhpZip")." \"$p_archive\".";

          // ----- Display status
          AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
        }

        // ----- Clean
        unset($v_list);
      }

      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Impossible de lire la liste des fichiers.");

        // ----- Display HTML page
        AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Supprimer fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipAskErase()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipAskErase($p_archive)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipAskErase", "$p_archive");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for valid format
    if ($v_archive_type != "")
    {
      // ----- Display HTML header
      AppPhpzipHeader($p_archive);

      // ----- Display request for name.
      echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
      echo "<tr bgcolor=$g_title_bg>";
      echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color><b>".Translate("Confirmer destruction d'une archive PhpZip")."</b></font></div></td>";
      echo "</tr>";
      echo "<tr bgcolor=$g_text_bg><td>";
      echo "<form method=post action=\"\">";
      echo "<p><br><div align=center><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Voulez-vous détruire")." <b>\"$p_archive\"</b> ?</font></div>";
      echo "<input type=hidden name=a_action value=erase_do>";
      echo "<input type=hidden name=a_archive value=$p_archive>";
      echo "<br><div align=center><input type=submit name=a_submit value=".Translate("Supprimer")."> <input type=submit name=a_submit value=".Translate("Conserver")."></div>";
      echo "</p></form>";
      echo "</td></tr></table>";

      // ----- Display HTML footer
      $p_message = Translate("Confirmez la suppression.");
      AppPhpzipFooter($p_message);
    }
    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Détruire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipErase()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipErase($p_archive)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipErase", "$p_archive");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for valid format
    if ($v_archive_type != "")
    {
      // ----- Detruire le fichier
      @unlink($p_archive);

      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip")." \"$p_archive\" ".Translate("détruite");

      // ----- Reset the archive name
      $p_archive = "";

      // ----- Display HTML page
      AppPhpzipStatus("Détruire", $p_archive, "OK", $v_message, Translate("Archive détruite."));
    }
    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Reset the archive name
      $p_archive = "";

      // ----- Display HTML page
      AppPhpzipStatus("Détruire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipHelp()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipHelp($p_archive, $p_topic)
  {
    $v_result = 1;
    $v_message = "";
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipHelp", "$p_archive, $p_topic");

    // ----- HTML header
    AppPhpzipHeader($p_archive);

    // ----- Help text
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_title_bg>";
    echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color><b>".Translate("Aide")."</b></font></div></td>";
    echo "</tr><tr bgcolor=$g_text_bg><td>";
    echo "<p><br><div align=center><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Le manuel d'utilisation est disponible en ligne")." : <a class=text href=http://www.phpconcept.net/phpzip-manuel.php>PhpConcept</a></font></div>";
    echo "</p></td></tr></table>";

    // ----- HTML Footer
    AppPhpzipFooter(Translate("Aide PhpZip."));

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipAbout()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipAbout($p_archive)
  {
    $v_result = 1;
    $v_message = "";
    global $g_config_file; include ($g_config_file);
    global $g_phpzip_app_version;
    global $g_pcltar_version;
    global $g_phpzip_version;
    global $g_pclzip_version;

    TrFctStart(__FILE__, __LINE__, "AppPhpzipAbout", "$p_archive");

    // ----- HTML header
    AppPhpzipHeader($p_archive);

    // ----- Help text
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_title_bg>";
    echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color><b>".Translate("Au sujet de")."</b></font></div></td>";
    echo "</tr><tr bgcolor=$g_text_bg><td>";
    echo "<p><br>";
    echo "<div align=center><font face=$g_font size=".($g_title_size+2)." color=$g_title_bg><b>PhpZip ".$g_phpzip_app_version."</b></font></div>";
    echo "<br><div align=center><font face=$g_font size=$g_text_size color=$g_text_color>© Copyright 2001-2005 - <a class=text href=http://www.phpconcept.net target=_blank>PhpConcept</a></font></div>";
    echo "</p>";
    echo "<p><div align=center><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Auteur")." : <a class=text href=mailto:vincent@phpconcept.net>Vincent Blavet</a></font></div>";
    echo "</p>";
    echo "<p><div align=left><blockquote><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("PhpZip utilise les ressources suivantes")." :</font>";
    echo "<blockquote>";

    echo "<table>";
    echo "<tr>";
    echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Librairies de compression")." :</font></td>";
    echo "<td><font face=$g_font size=$g_text_size color=$g_text_color> <a class=text href='http://www.phpconcept.net/phpziplib-manuel.php' target=_blank>PhpZipLib ".$g_phpzip_version."</a></font></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>&nbsp;</td>";
    echo "<td><font face=$g_font size=$g_text_size color=$g_text_color> <a class=text href='http://www.phpconcept.net/pcltar-index.php' target=_blank>PclTar ".$g_pcltar_version."</a></font></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>&nbsp;</td>";
    echo "<td><font face=$g_font size=$g_text_size color=$g_text_color> <a class=text href='http://www.phpconcept.net/pclzip/index.php' target=_blank>PclZip ".$g_pclzip_version."</a></font></td>";
    echo "</tr>";
    echo "</table>";

    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Librairie de gestion des erreurs")." : PclError 1.0</font>";
    echo "<br><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Librairie de gestion des traces")." : PclTrace 1.0</font>";
    echo "</blockquote></blockquote></div>";
    echo "</p>";
    echo "<p><div align=left><blockquote><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Avec l'aide de")." :</font>";
    echo "<blockquote>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color><a class=text href=mailto:webmaster@subnet.it>Piero Mannelli</a> ".Translate("pour la traduction Italienne").",</font>";
    echo "<br><font face=$g_font size=$g_text_size color=$g_text_color><a class=text href=mailto:pmcweb@cybersound.com>Markus Pfeifenberger</a> ".Translate("pour la traduction Allemande")."</font>";
    echo "<br><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("et tous ceux qui ont rapportés les bugs")."</font>";
    echo "</blockquote></blockquote></div>";
    echo "</p>";
    echo "</td></tr></table>";

    // ----- HTML Footer
    AppPhpzipFooter(Translate("Au sujet de")." PhpZip.");

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipExplorer()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipExplorer($p_archive, $p_request, $p_dir="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipExplorer", "archive=$p_archive, request=$p_request, dir=$p_dir");

    // ----- Display HTML header
    AppPhpzipHeader($p_archive);

    // ----- Look for empty directory
    if ($p_dir == "")
      $p_dir = ".";

    // ----- Header of action
    echo "<form method=post action=\"\">";
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_title_bg><td width=5></td>";
    echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color><b>";
    if ($p_request=="add")
      echo Translate("Sélection des fichiers et/ou dossiers à archiver");
    else
      echo Translate("Sélection des fichiers et/ou dossiers à mettre à jour");
    echo "</b></font></div></td>";
    echo "</tr>";

    echo "<tr bgcolor=$g_text_bg><td width=5></td>";
    echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Dossier courant")." : $p_dir<font></td>";
    echo "</tr>";
    echo "</table>";

    // ----- File / directory table
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_title_bg>";
    echo "<td width=2%></td>";
    echo "<td></td>";
    echo "<td width=90%><div><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Fichier/Dossier")."</font></div></td>";
    echo "<td><div><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Taille")."</font></div></td>";
    echo "<td width=8%></td></tr>";

    // ----- Scan the current directory
    $v_hdir = opendir($p_dir);
    $v_file = readdir($v_hdir); // '.' directory

    for ($i=0; $v_file = readdir($v_hdir); $i++)
    {
      if ($v_file == "..")
      {
        // ----- Go back in path
        if (substr($p_dir, 0, 2) == "..")
          $v_file_full = "../".$p_dir;
        else if ($p_dir != ".")
        {
          $temp = strrchr($p_dir, "/");
          $v_file_full = substr($p_dir, 0, strlen($p_dir)-strlen($temp));
          unset($temp);
        }
        else
          $v_file_full = "..";

        // ----- Look if the file is a file parent directory (indirect check of open_basedir restriction)
        if ($v_test_hdir = @opendir($v_file_full))
        {
          // ----- Close the temporary handle
          closedir($v_test_hdir);

          // ----- Display the name in the table without checkbox
          echo "<tr bgcolor=$g_text_bg><td width=10></td>";
          echo "<td></td>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><A class=dir href=?a_archive=$p_archive&a_action=add&a_dir=$v_file_full><img src='$g_images_dir/folder02-16.gif' border='0' hspace='0' vspace='0'> [".Translate("Dossier parent")."]</A></td><td></td>";
          echo "<td></td></font></tr>";
        }
        else
        {
          // ----- Display the name in the table without checkbox
          echo "<tr bgcolor=$g_text_bg><td width=10></td>";
          echo "<td></td>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><img src='$g_images_dir/folder02-16.gif' border='0' hspace='0' vspace='0'> [".Translate("Aucun dossier parent")."]</td><td></td><td></td>";
          echo "</font></tr>";
        }

      }
      else
      {
        // ----- Compose the full name
        $v_file_full = $p_dir."/".$v_file;

        // ----- Display
        echo "<tr bgcolor=$g_text_bg><td width=10></td>";

        // ----- Look for readable directory
        if (is_dir($v_file_full))
        {
          if (is_readable($v_file_full))
          {
            echo "<td><INPUT TYPE='CHECKBOX'  name='a_file[a$i]' value='$v_file_full' align='middle'></td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><A class=dir href=?a_archive=$p_archive&a_action=add&a_dir=$v_file_full><img src='$g_images_dir/folder01-16.gif' border='0' hspace='0' vspace='0'> $v_file</A></font></td>";
            echo "<td>-</td><td></td>";
          }
        }

        // ----- Look for readable files
        else
        {
          // ----- Select icon depending on file extension
          $v_icon = AppPhpzipExtensionImage($v_file_full);

          if (is_readable($v_file_full))
            echo "<td><INPUT TYPE='CHECKBOX'  name='a_file[a$i]' value='$v_file_full' align='middle'></td>";
          else
            echo "<td></td>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><A class=file><img src='$g_images_dir/$v_icon' border='0' hspace='0' vspace='0'> $v_file</file></font></td>";
          echo "<td>".filesize($v_file_full)."</td><td></td>";
        }
        echo "</tr>";
      }

    }

    echo "</table>";

    // ----- Get type
    $v_type = AppPhpzipArchiveType($p_archive);

    // ----- Ask for compression type with PhpZip archives
    if ($v_type == "phpzip")
    {
      echo "<p align=center><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Type de compression")." : ";
      echo "<input type=radio name=a_type value=A checked> ".Translate("Automatique")." , <input type=radio name=a_type value=C> ".Translate("Compressé")." , <input type=radio name=a_type value=N> ".Translate("Non compressé");
      echo "</font></p>";
    }

    // ----- Ask for add and remove directory with PclZip archives
    else if ($v_type == "zip")
    {
      echo "<blockquote><font face=$g_font size=$g_text_size color=$g_text_color>";
      echo Translate("Chemin d'accès mémorisé")." : ";
      echo "<INPUT TYPE='TEXT' name='a_stored_dir' value=\"$p_dir/\" size='60' maxlength='120'>";
      echo "</font></blockquote>";
    }

    // ----- Ask for add and remove directory with PclTar archives
    else if (($v_tar_ext = PclTarHandleExtension($p_archive))!="")
    {
      echo "<blockquote><font face=$g_font size=$g_text_size color=$g_text_color>";
      echo Translate("Chemin d'accès mémorisé")." : ";
      echo "<INPUT TYPE='TEXT' name='a_stored_dir' value=\"$p_dir/\" size='60' maxlength='120'>";
      echo "</font></blockquote>";
    }

    // ----- End of form
    if ($p_request=="add")
      echo "<input type=hidden name=a_action value=add_list>";
    else
      echo "<input type=hidden name=a_action value=update_list>";
    echo "<input type=hidden name=a_archive value=$p_archive>";
    echo "<input type=hidden name=a_dir value=$p_dir>";
    echo "<p align=center><font face=$g_font size=$g_text_size color=$g_text_color><input type=submit value=\"";
    if ($p_request=="add")
      echo Translate("Ajouter à l'archive");
    else
      echo Translate("Mettre à jour dans l'archive");
    echo "\"></font></p>";
    echo "</form>";

    // ----- Close the directory handle
    closedir($v_hdir);

    // ----- Display HTML footer
    if ((!isset($p_message)) || ($p_message == ""))
      $p_message = Translate("Indiquez un nom de fichier ou de dossier à ajouter.");
    AppPhpzipFooter($p_message);

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipArchiveType()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipArchiveType($p_archive)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipArchiveType", "archive='$p_archive'");

    // ----- Get extension
    $v_extension = substr($p_archive, -4);

    // ----- Look for archive format
    if ((substr($p_archive, -7) == ".tar.gz")
        || ($v_extension == ".tgz")
        || ($v_extension == ".taz"))
    {
      TrFctMessage(__FILE__, __LINE__, 3, "Archive is in gzip tar format");
      $v_result = "tgz";
    }
    else if ($v_extension == ".tar")
    {
      TrFctMessage(__FILE__, __LINE__, 3, "Archive is in tar format");
      $v_result = "tar";
    }
    // ---- TBC : Should be an archive structure check ?
    else if ($v_extension == ".zip")
    {
      TrFctMessage(__FILE__, __LINE__, 3, "Archive is in zip format");
      $v_result = "zip";
    }
    // ----- Look for PhpZip archive format
    else if (IsPhpzipArchive($p_archive))
    {
      TrFctMessage(__FILE__, __LINE__, 3, "Archive is in PhpZip format");
      $v_result = "phpzip";
    }
    // ----- Look for file extension
    else
    {
      TrFctMessage(__FILE__, __LINE__, 3, "Archive has unknown format");
      $v_result = "";
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipExtensionImage()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipExtensionImage($p_file)
  {
    $v_image = "file01-16.gif";
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipExtensionImage", "file='$p_file'");

    // ----- Get extension
    switch ($v_extension = substr(strrchr($p_file, '.'), 1)) {
      case "php" :
      case "php3" :
        $v_image = "file-php2-16.gif";
      break;
      case "htm" :
      case "html" :
        $v_image = "file-ie-16.gif";
      break;
      case "tar" :
      case "tgz" :
        $v_image = "file-tar-16.gif";
      break;
      case "zip" :
      case "gz" :
        $v_image = "file-zip-16.gif";
      break;
      case "piz" :
        $v_image = "file-piz-16.gif";
      break;
      case "gif" :
        $v_image = "file-gif-16.gif";
      break;
      case "bmp" :
      case "png" :
      case "jpg" :
        $v_image = "file-bmp-16.gif";
      break;
      case "txt" :
        $v_image = "file-txt-16.gif";
      break;
      default :
      $v_image = "file01-16.gif";
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_image);
    return $v_image;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipExtensionImageTar()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipExtensionImageTar($p_header)
  {
    $v_image = "file01-16.gif";
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipExtensionImageTar", "header='".$p_header['filename']."'");

    // ----- Look for directory
    if ($p_header['typeflag']==5)
    {
        $v_image = "folder01-16.gif";
    }
    else
    {
    // ----- Get extension
    switch ($v_extension = substr(strrchr($p_header['filename'], '.'), 1)) {
      case "php" :
      case "php3" :
        $v_image = "file-php2-16.gif";
      break;
      case "htm" :
      case "html" :
        $v_image = "file-ie-16.gif";
      break;
      case "tar" :
      case "tgz" :
        $v_image = "file-tar-16.gif";
      break;
      case "zip" :
      case "gz" :
        $v_image = "file-zip-16.gif";
      break;
      case "piz" :
        $v_image = "file-piz-16.gif";
      break;
      case "gif" :
        $v_image = "file-gif-16.gif";
      break;
      case "bmp" :
      case "png" :
      case "jpg" :
        $v_image = "file-bmp-16.gif";
      break;
      case "txt" :
        $v_image = "file-txt-16.gif";
      break;
      default :
      $v_image = "file01-16.gif";
    }
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_image);
    return $v_image;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipAddList()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipAddList($p_archive, $p_file, $p_type, $p_dir, $p_stored_dir)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipAddList", "$p_archive, $p_file, $p_type, dir='$p_dir', stored_dir='$p_stored_dir'");

    // ----- Get archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Check for file
    if (IsPhpzipArchive($p_archive))
    {
      // ----- Look for file
      if (is_array($p_file))
      {
        // ----- Compose the file list
        for (reset($p_file), $i=0; $key=key($p_file); $key=next($p_file))
        {
          $v_list[$i++] = $p_file[$key];
        }

        // ----- Recuperate the file list
        if (($v_result = PhpzipAddList($p_archive, $v_list, $p_type)) == 1)
        {
          // ----- Set an error string for the footer
          $v_message = Translate("La liste de fichiers a bien été ajouté dans l'archive.");

          // ----- Display status
          AppPhpzipStatus("Ajouter", $p_archive, "OK", $v_message, Translate("Ajout terminé."));
        }
        else
        {
          // ----- Set an error string for the footer
          $v_message = Translate("Erreur lors de l'ajout de la liste dans l'archive PhpZip")." \"$p_archive\".";

          // ----- Display status
          AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
        }

        // ----- Clean
        unset($v_list);
      }

      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Impossible de lire la liste des fichiers.");

        // ----- Display HTML page
        AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }

    // ----- Look for ZIP format
    else if ($v_archive_type == 'zip')
    {
      $v_result = AppPhpzipZipAddList($p_archive, $p_file, $p_type, $p_dir, $p_stored_dir);
    }

    // ----- Look for TAR format
    else if (($v_tar_ext = PclTarHandleExtension($p_archive))!="")
    {
      // ----- Look for file
      if (is_array($p_file))
      {
        // ----- Compose the file list
        for (reset($p_file), $i=0; $key=key($p_file); $key=next($p_file))
        {
          $v_list[$i++] = $p_file[$key];
        }

        // ----- Compose the add and/or remove dir
        if ($p_stored_dir != $p_dir)
        {
          $v_add_dir = $p_stored_dir;
          $v_remove_dir = $p_dir;
        }
        else
        {
          $v_add_dir = "";
          $v_remove_dir = "";
        }

        // ----- Add current directory tag
        if (($v_add_dir != "") && (substr($v_add_dir,0,2) != "./"))
          $v_add_dir = "./".$v_add_dir;
        if (($v_remove_dir != "") && (substr($v_remove_dir,0,2) != "./"))
          $v_remove_dir = "./".$v_remove_dir;

        // ----- Recuperate the file list
        $v_list_result = PclTarAddList($p_archive, $v_list, $v_add_dir, $v_remove_dir);
        if (sizeof($v_list_result) != 0)
        {
          $v_nb_added = 0;

          // ----- Display HTML header
          AppPhpzipHeader($p_archive);

          echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
          echo "<tr bgcolor=$g_title_bg>";
          echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Ajouter")."</font></div></td>";
          echo "</tr>";
          echo "<tr bgcolor=$g_text_bg><td><font face=$g_font size=$g_text_size color=$g_text_color><p><br><div align=center>";
          echo Translate("Etat de l'ajout des fichiers");
          echo " :</div></p></font>";
          echo "</td></tr>";
          echo "</table>";

          $v_message = array();
          $v_message['ok']="OK";
          $v_message['added']=Translate("Ajouté");
          $v_message['updated']=Translate("Mis à jour");
          $v_message['not_updated']=Translate("Non mis à jour");
          $v_message['already_a_directory']=Translate("Un dossier utilise déjà ce nom");
          $v_message['write_protected']=Translate("Le fichier est protégé en écriture");
          $v_message['newer_exist']=Translate("Un fichier plus récent existe");
          $v_message['path_creation_fail']=Translate("Impossible de créer le dossier du fichier");
          $v_message['write_error']=Translate("Problème d'écriture du fichier");

          // ----- Display the list of added files
          echo "<table border=0 cellspacing=0 cellpadding=0 align=center>";
          echo "<tr bgcolor=$g_title_bg><td width=1 bgcolor=$g_title_bg></td>";
          echo "<td width=10>&nbsp</td><td><font face=$g_font size=$g_text_size color=$g_title_color>File</font></td>";
          echo "<td width=10>&nbsp</td><td><font face=$g_font size=$g_text_size color=$g_title_color>Status</font></td>";
          echo "<td width=10>&nbsp</td><td width=1 bgcolor=$g_title_bg></td></tr>";
          for ($i=0; $i<sizeof($v_list_result); $i++)
          {
            if  ($v_list_result[$i]['status']!="ok")
            {
            echo "<tr bgcolor=$g_text_bg><td width=1 bgcolor=$g_title_bg></td><td width=10>&nbsp</td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".$v_list_result[$i]['filename']."</font></td>";
            echo "<td width=10>&nbsp</td><td><font face=$g_font size=$g_text_size color=$g_text_color>";
            if ($v_list_result[$i]['status']=="ok")
            {
              echo "Ok";
            }
            else if ($v_list_result[$i]['status']=="added")
            {
              echo $v_message[$v_list_result[$i]['status']];
              $v_nb_added++;
            }
            else
              echo "</font><font face=$g_font size=$g_error_size color=red>Error : '".$v_message[$v_list_result[$i]['status']]."'";
            echo "</font>";
            echo "</td><td width=10>&nbsp</td><td width=1 bgcolor=$g_title_bg></td></tr>";
            }
          }
          echo "<tr height=1 bgcolor=$g_title_bg><td colspan=7></td></tr>";
          echo "<tr><td width=1 bgcolor=$g_title_bg></td>";
          echo "<td width=10>&nbsp</td><td colspan=3><font face=$g_font size=$g_text_size color=$g_text_color>$v_nb_added ".Translate("fichiers ajoutés")."</font></td>";
          echo "<td width=10>&nbsp</td><td width=1 bgcolor=$g_title_bg></td></tr>";
          echo "<tr height=1 bgcolor=$g_title_bg><td colspan=7></td></tr>";
          echo "<tr><td colspan=7>&nbsp</td></tr></table>";

          // ----- Display HTML footer
          AppPhpzipFooter(Translate("Ajout terminé."));
        }
        else
        {
          // ----- Set an error string for the footer
          $v_message = Translate("Erreur lors de l'ajout de la liste dans l'archive PhpZip")." \"$p_archive\".";

          // ----- Display status
          AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
        }

        // ----- Clean
        unset($v_list);
      }

      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Impossible de lire la liste des fichiers.");

        // ----- Display HTML page
        AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }
    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipComposeDirList()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipComposeDirList($p_dir, &$p_list)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipComposeDirList", "$p_dir, size(list)=".sizeof($p_list));

    // ----- Recuperate the current size
    $v_nb = sizeof($p_list);

    // ----- Read the directory for files and sub-directories
    $v_hdir = opendir($p_dir);
    $v_hitem = readdir($v_hdir); // '.' directory
    $v_hitem = readdir($v_hdir); // '..' directory
    while ($v_hitem = readdir($v_hdir))
    {
      TrFctMessage(__FILE__, __LINE__, 4, "Reading '$v_hitem' in directory '$p_dir'");
      if (is_dir($p_dir."/".$v_hitem))
      {
        TrFctMessage(__FILE__, __LINE__, 4, "'$p_dir/$v_hitem' in a directory (found at index $v_nb)");
        AppPhpzipComposeDirList($p_dir."/".$v_hitem, $p_list);
        $v_nb = sizeof($p_list);
      }
      else
      {
        TrFctMessage(__FILE__, __LINE__, 4, "'$p_dir/$v_hitem' in a file (add at index $v_nb)");
        $p_list[$v_nb++]=$p_dir."/".$v_hitem;
      }
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipUpdate()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipUpdate($p_archive, $p_file, $p_type="", $p_dir=".", $p_stored_dir="./")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipUpdate", "archive='$p_archive', file='0..".sizeof($p_file)."', type='$p_type', dir='$p_dir', stored_dir='$p_stored_dir'");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Not supported yet

      // ----- Set an error string for the footer
      $v_message = Translate("Fonction non supportée pour ce type d'archive");

      // ----- Display HTML page
      AppPhpzipStatus("Mettre à jour fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }


    // ----- Look for tar (tar.gz) format
    else if ($v_archive_type != "")
    {
      // ----- Look for file
      if (sizeof($p_file))
      {
        $v_list = array();

        // ----- Compose the file list
        for (reset($p_file), $i=0; $key=key($p_file); $key=next($p_file))
        {
          TrFctMessage(__FILE__, __LINE__, 2, "$i . key=[$key], p_file[$key]=[$p_file[$key]]");

          // ----- Look if file is a directory
          if (file_exists($p_file[$key]))
          {
            if (is_dir($p_file[$key]))
            {
              AppPhpzipComposeDirList($p_file[$key], $v_list);
              $i=sizeof($v_list);
            }
            else if (is_file($p_file[$key]))
              $v_list[$i++] = $p_file[$key];
            }
          }

        // ----- Display HTML header
        //AppPhpzipHeader($p_archive);
        AppPhpzipActionHeader($p_archive, Translate("Mettre à jour fichiers"));

        echo "<form method=post action=\"?\">";
        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_text_bg><td><font face=$g_font size=$g_text_size color=$g_text_color><p><br><div align=center>".Translate("Confirmer que PhpZip doit maintenant essayer de mettre à jour les fichiers suivants")." :</div></p></font>";
        echo "</td></tr>";
        echo "</table>";

?>
<div id="Layer2" style="position:absolute; width:300px; height:252px; z-index:1; overflow: auto; left: 20px; top: 75px; background-color: #CCCCCC; border: 1px none #CCCCCC">
<?
        // ----- Display the list of extracted files
        echo "<table border=0 cellspacing=0 cellpadding=0 align=left><tr bgcolor=CCCCCC><td>&nbsp</td></tr>";
        for ($i=0; $i<sizeof($v_list); $i++)
        {
          echo "<tr bgcolor=CCCCCC><td>";
          echo "<font face=$g_font size=$g_text_size color=$g_text_color>$v_list[$i]</font>";
          echo "<INPUT TYPE='hidden'  name='a_file[a$i]' value='$v_list[$i]'>";
          echo "</td></tr>\n\r";
        }
        echo "<tr bgcolor=CCCCCC><td>&nbsp</td></tr></table>";

        //echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center><tr bgcolor=$g_text_bg><td>&nbsp</td></tr>";
        //echo "<tr bgcolor=$g_text_bg><td>";
        //echo "<blockquote><font face=$g_font size=$g_text_size color=$g_text_color>";
        //echo Translate("Chemin d'accès mémorisé")." : ";
        //echo "<INPUT TYPE='TEXT' name='a_stored_dir' value=\"$p_stored_dir\" size='60' maxlength='120'>";
        //echo "</font></blockquote></td></tr>";
        //echo "<tr bgcolor=$g_text_bg><td>&nbsp</td></tr></table>";
?>
</div>
<?

        // ----- End of form
        echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
        echo "<input type=hidden name=a_action value=update_list_do>";
        echo "<input type=hidden name=a_archive value=$p_archive>";
        echo "<input type=hidden name=a_dir value=$p_dir>";
        echo "<div align=center><font face=$g_font size=$g_text_size color=$g_text_color>";
        //echo "<input type=submit value=\"";
        //echo Translate("Mettre à jour dans l'archive");
        //echo "\">";
        echo "<input type=submit value=\"";
        echo Translate("Confirmer");
        echo "\"> ";
        echo "<input type=submit value=\"";
        echo Translate("Annuler");
        echo "\" onClick='window.close();'>";
        echo "</font></div>";
        echo "</form>";

        // ----- Display HTML footer
        AppPhpzipActionFooter(Translate("Mettre à jour fichiers"));

        unset($v_list);
      }

      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Impossible de lire la liste des fichiers.");

        // ----- Display HTML page
        AppPhpzipStatus("Mettre à jour fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Mettre à jour fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipUpdate()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipUpdate_old($p_archive, $p_file, $p_type="", $p_dir=".", $p_stored_dir="./")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipUpdate", "archive='$p_archive', file='0..".sizeof($p_file)."', type='$p_type', dir='$p_dir', stored_dir='$p_stored_dir'");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Not supported yet

      // ----- Set an error string for the footer
      $v_message = Translate("Fonction non supportée pour ce type d'archive");

      // ----- Display HTML page
      AppPhpzipStatus("Mettre à jour fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }


    // ----- Look for tar (tar.gz) format
    else if ($v_archive_type != "")
    {
      // ----- Look for file
      if (sizeof($p_file))
      {
        $v_list = array();

        // ----- Compose the file list
        for (reset($p_file), $i=0; $key=key($p_file); $key=next($p_file))
        {
          TrFctMessage(__FILE__, __LINE__, 2, "$i . key=[$key], p_file[$key]=[$p_file[$key]]");

          // ----- Look if file is a directory
          if (file_exists($p_file[$key]))
          {
            if (is_dir($p_file[$key]))
            {
              AppPhpzipComposeDirList($p_file[$key], $v_list);
              $i=sizeof($v_list);
            }
            else if (is_file($p_file[$key]))
              $v_list[$i++] = $p_file[$key];
            }
          }

        // ----- Display HTML header
        //AppPhpzipHeader($p_archive);
        AppPhpzipActionHeader($p_archive);

        echo "<form method=post action=\"?\">";
        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Mettre à jour fichiers")."</font></div></td>";
        echo "</tr>";
        echo "<tr bgcolor=$g_text_bg><td><font face=$g_font size=$g_text_size color=$g_text_color><p><br><div align=center>".Translate("Confirmer que PhpZip doit maintenant essayer de mettre à jour les fichiers suivants")." :</div></p></font>";
        echo "</td></tr>";
        echo "</table>";

        // ----- Display the list of extracted files
        echo "<table border=0 cellspacing=0 cellpadding=0 align=center><tr bgcolor=$g_text_bg><td>&nbsp</td></tr>";
        for ($i=0; $i<sizeof($v_list); $i++)
        {
          echo "<tr bgcolor=$g_text_bg><td>";
          echo "<font face=$g_font size=$g_text_size color=$g_text_color>$v_list[$i]</font>";
          echo "<INPUT TYPE='hidden'  name='a_file[a$i]' value='$v_list[$i]'>";
          echo "</td></tr>\n\r";
        }
        echo "<tr bgcolor=$g_text_bg><td>&nbsp</td></tr></table>";
        echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center><tr bgcolor=$g_text_bg><td>&nbsp</td></tr>";
        echo "<tr bgcolor=$g_text_bg><td>";
        echo "<blockquote><font face=$g_font size=$g_text_size color=$g_text_color>";
        echo Translate("Chemin d'accès mémorisé")." : ";
        echo "<INPUT TYPE='TEXT' name='a_stored_dir' value=\"$p_stored_dir\" size='60' maxlength='120'>";
        echo "</font></blockquote></td></tr>";
        echo "<tr bgcolor=$g_text_bg><td>&nbsp</td></tr></table>";

        // ----- End of form
        echo "<input type=hidden name=a_action value=update_list_do>";
        echo "<input type=hidden name=a_archive value=$p_archive>";
        echo "<input type=hidden name=a_dir value=$p_dir>";
        echo "<p align=center><font face=$g_font size=$g_text_size color=$g_text_color><input type=submit value=\"";
        echo Translate("Mettre à jour dans l'archive");
        echo "\"></font></p>";
        echo "</form>";

        // ----- Display HTML footer
        AppPhpzipActionFooter(Translate("Mettre à jour fichiers"));

        unset($v_list);
      }

      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Impossible de lire la liste des fichiers.");

        // ----- Display HTML page
        AppPhpzipStatus("Mettre à jour fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Mettre à jour fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipUpdateList()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipUpdateList($p_archive, $p_file, $p_type="", $p_dir=".", $p_stored_dir="./")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipUpdateList", "archive='$p_archive', file='0..".sizeof($p_file)."', type='$p_type', dir='$p_dir', stored_dir='$p_stored_dir'");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Not supported yet

      // ----- Set an error string for the footer
      $v_message = Translate("Fonction non supportée pour ce type d'archive");

      // ----- Display HTML page
      AppPhpzipStatus("Mettre à jour fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Look for TAR format
    else if ($v_archive_type != "")
    {
      $v_tar_ext = $v_archive_type;

      // ----- Look for file
      if (is_array($p_file))
      {
        // ----- Compose the file list
        for (reset($p_file), $i=0; $key=key($p_file); $key=next($p_file))
        {
          TrFctMessage(__FILE__, __LINE__, 2, "$i . key=[$key], p_file[$key]=[$p_file[$key]]");
          $v_list[$i++] = $p_file[$key];
        }

        // ----- Compose the add and/or remove dir
        if ($p_stored_dir != $p_dir)
        {
          $v_add_dir = $p_stored_dir;
          $v_remove_dir = $p_dir;
        }
        else
        {
          $v_add_dir = "";
          $v_remove_dir = "";
        }

        // ----- Add current directory tag
        if (($v_add_dir != "") && (substr($v_add_dir,0,2) != "./"))
          $v_add_dir = "./".$v_add_dir;
        if (($v_remove_dir != "") && (substr($v_remove_dir,0,2) != "./"))
          $v_remove_dir = "./".$v_remove_dir;

        // ----- Recuperate the file list
        $v_list_result = PclTarUpdate($p_archive, $v_list, "", $v_add_dir, $v_remove_dir);
        if ((is_array($v_list_result))&&(sizeof($v_list_result) != 0))
        {
          $v_nb_updated = 0;
          $v_nb_not_updated = 0;
          $v_nb_added = 0;

          for ($i=0; $i<sizeof($v_list_result); $i++)
          {
            if ($v_list_result[$i]['status']=="added")
            {
              $v_nb_added++;
            }
            else if ($v_list_result[$i]['status']=="updated")
            {
              $v_nb_updated++;
            }
            else if ($v_list_result[$i]['status']=="not_updated")
            {
              $v_nb_not_updated++;
            }
          }

          // ----- Display HTML header
          AppPhpzipActionHeader($p_archive,  Translate("Etat de la mise à jour des fichiers"));

          $v_message = array();
          $v_message['ok']="OK";
          $v_message['added']=Translate("Ajouté");
          $v_message['updated']=Translate("Mis à jour");
          $v_message['not_updated']=Translate("Non mis à jour");
          $v_message['already_a_directory']=Translate("Un dossier utilise déjà ce nom");
          $v_message['write_protected']=Translate("Le fichier est protégé en écriture");
          $v_message['newer_exist']=Translate("Un fichier plus récent existe");
          $v_message['path_creation_fail']=Translate("Impossible de créer le dossier du fichier");
          $v_message['write_error']=Translate("Problème d'écriture du fichier");

          echo "<table border=0 cellspacing=0 cellpadding=0 align=center>";
          echo "<tr bgcolor=$g_text_bg height=10><td colspan=7></td></tr>";
          echo "<tr bgcolor=$g_text_bg><td>";
          echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Etat")." : </font>";
          echo "</td><td width=10></td><td align=right>";
          echo "<font face=$g_font size=$g_text_size color=$g_text_color>$v_nb_updated</font>";
          echo "</td><td width=10></td><td>";
          echo "<img src='$g_images_dir/file-tar-update.gif' border='0' width='16' height='16' align='absmiddle'>";
          echo "</td><td width=10></td><td>";
          echo "<font face=$g_font size=$g_text_size color=$g_text_color>";
          echo Translate("fichiers mis à jour")."</font>";
          echo "</td></tr>";
          echo "<tr bgcolor=$g_text_bg><td colspan=2></td><td align=right>";
          echo "<font face=$g_font size=$g_text_size color=$g_text_color>$v_nb_added</font>";
          echo "</td><td width=10></td><td>";
          echo "<img src='$g_images_dir/file-tar-add.gif' border='0' width='16' height='16' align='absmiddle'>";
          echo "</td><td width=10></td><td>";
          echo "<font face=$g_font size=$g_text_size color=$g_text_color>";
          echo " ".Translate("fichiers ajoutés")."</font>";
          echo "</td></tr>";
          echo "<tr bgcolor=$g_text_bg><td colspan=2></td><td align=right>";
          echo "<font face=$g_font size=$g_text_size color=$g_text_color>$v_nb_not_updated</font>";
          echo "</td><td width=10></td><td>";
          echo "<img src='$g_images_dir/file-tar-uptodate.gif' border='0' width='16' height='16' align='absmiddle'>";
          echo "</td><td width=10></td><td>";
          echo "<font face=$g_font size=$g_text_size color=$g_text_color>";
          echo " ".Translate("fichiers non modifiés")."</font>";
          echo "</td></tr>";
          echo "</table>";

?>
<div id="Layer2" style="position:absolute; width:300px; height:232px; z-index:1; overflow: auto; left: 20px; top: 85px; background-color: #CCCCCC; border: 1px none #CCCCCC">
<?

          // ----- Display the list of extracted files
          echo "<table border=0 cellspacing=0 cellpadding=0 align=left>";
          for ($i=0; $i<sizeof($v_list_result); $i++)
          {
            if  ($v_list_result[$i]['status']!="ok")
            {
            echo "<tr bgcolor=CCCCCC><td width=5>&nbsp</td><td>";
            if ($v_list_result[$i]['status']=="added")
            {
              echo "<img src='$g_images_dir/file-tar-add.gif' border='0' width='16' height='16' align='absmiddle'>";
            }
            else if ($v_list_result[$i]['status']=="updated")
            {
              echo "<img src='$g_images_dir/file-tar-update.gif' border='0' width='16' height='16' align='absmiddle'>";
            }
            else if ($v_list_result[$i]['status']=="not_updated")
            {
              echo "<img src='$g_images_dir/file-tar-uptodate.gif' border='0' width='16' height='16' align='absmiddle'>";
            }
            else
            {
              echo "<img src='$g_images_dir/file01-16.gif' border='0' width='16' height='16' align='absmiddle'>";
            }

            echo "</td><td width=10>&nbsp</td><td>";
            echo "<font face=$g_font size=$g_text_size color=$g_text_color>";
            echo "<a title=\"".$v_message[$v_list_result[$i]['status']]."\">";
            echo $v_list_result[$i]['filename'];
            echo "</a>";
            echo "</font></td>";
            echo "<td width=5>&nbsp</td></tr>";
            }
          }
          echo "</table>";

?>
</div>
<?

          echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
          echo "<br><form method=post action=\"?\">";
          echo "<div align=center><font face=$g_font size=$g_text_size color=$g_text_color>";
          echo "<input type=submit value=\"";
          echo Translate("Fermer");
          echo "\" onClick='window.opener.focus(); window.close(); return false;'>";
          echo "</font></div>";
          echo "</form>";
          echo "<script language=\"Javascript\">window.onLoad=window.opener.open(\"?a_archive=$p_archive&a_action=list\", window.opener.name);</script>";

          // ----- Display HTML footer
          AppPhpzipActionFooter(Translate("Mise à jour terminée."));
        }
        else
        {
          // ----- Set an error string for the footer
          $v_message = Translate("Erreur lors de l'ajout de la liste dans l'archive PhpZip")." \"$p_archive\".";

          // ----- Display status
          AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
        }

        // ----- Clean
        unset($v_list);
      }

      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Impossible de lire la liste des fichiers.");

        // ----- Display HTML page
        AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }
    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipUpdateList()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipUpdateList_old($p_archive, $p_file, $p_type="", $p_dir=".", $p_stored_dir="./")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipUpdateList", "archive='$p_archive', file='0..".sizeof($p_file)."', type='$p_type', dir='$p_dir', stored_dir='$p_stored_dir'");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Not supported yet

      // ----- Set an error string for the footer
      $v_message = Translate("Fonction non supportée pour ce type d'archive");

      // ----- Display HTML page
      AppPhpzipStatus("Mettre à jour fichiers", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Look for TAR format
    else if ($v_archive_type != "")
    //else if (($v_tar_ext = PclTarHandleExtension($p_archive))!="")
    {
      $v_tar_ext = $v_archive_type;

      // ----- Look for file
      if (is_array($p_file))
      {
        // ----- Compose the file list
        for (reset($p_file), $i=0; $key=key($p_file); $key=next($p_file))
        {
          TrFctMessage(__FILE__, __LINE__, 2, "$i . key=[$key], p_file[$key]=[$p_file[$key]]");
          $v_list[$i++] = $p_file[$key];
        }

        // ----- Compose the add and/or remove dir
        if ($p_stored_dir != $p_dir)
        {
          $v_add_dir = $p_stored_dir;
          $v_remove_dir = $p_dir;
        }
        else
        {
          $v_add_dir = "";
          $v_remove_dir = "";
        }

        // ----- Add current directory tag
        if (($v_add_dir != "") && (substr($v_add_dir,0,2) != "./"))
          $v_add_dir = "./".$v_add_dir;
        if (($v_remove_dir != "") && (substr($v_remove_dir,0,2) != "./"))
          $v_remove_dir = "./".$v_remove_dir;

        // ----- Recuperate the file list
        $v_list_result = PclTarUpdate($p_archive, $v_list, "", $v_add_dir, $v_remove_dir);
        if ((is_array($v_list_result))&&(sizeof($v_list_result) != 0))
        {
          $v_nb_updated = 0;
          $v_nb_not_updated = 0;
          $v_nb_added = 0;

          // ----- Display HTML header
          AppPhpzipHeader($p_archive);

          echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
          echo "<tr bgcolor=$g_title_bg>";
          echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>";
          echo Translate("Ajouter")."</font></div></td>";
          echo "</tr>";
          echo "<tr bgcolor=$g_text_bg><td><font face=$g_font size=$g_text_size color=$g_text_color><p><br><div align=center>";
          echo Translate("Etat de la mise à jour des fichiers");
          echo " :</div></p></font>";
          echo "</td></tr>";
          echo "</table>";

          $v_message = array();
          $v_message['ok']="OK";
          $v_message['added']=Translate("Ajouté");
          $v_message['updated']=Translate("Mis à jour");
          $v_message['not_updated']=Translate("Non mis à jour");
          $v_message['already_a_directory']=Translate("Un dossier utilise déjà ce nom");
          $v_message['write_protected']=Translate("Le fichier est protégé en écriture");
          $v_message['newer_exist']=Translate("Un fichier plus récent existe");
          $v_message['path_creation_fail']=Translate("Impossible de créer le dossier du fichier");
          $v_message['write_error']=Translate("Problème d'écriture du fichier");

          // ----- Display the list of extracted files
          echo "<table border=0 cellspacing=0 cellpadding=0 align=center>";
          echo "<tr bgcolor=$g_title_bg><td width=1 bgcolor=$g_title_bg></td>";
          echo "<td width=10>&nbsp</td><td><font face=$g_font size=$g_text_size color=$g_title_color>File</font></td>";
          echo "<td width=10>&nbsp</td><td><font face=$g_font size=$g_text_size color=$g_title_color>Status</font></td>";
          echo "<td width=10>&nbsp</td><td width=1 bgcolor=$g_title_bg></td></tr>";
          for ($i=0; $i<sizeof($v_list_result); $i++)
          {
            if  ($v_list_result[$i]['status']!="ok")
            {
            echo "<tr bgcolor=$g_text_bg><td width=1 bgcolor=$g_title_bg></td><td width=10>&nbsp</td>";
            echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".$v_list_result[$i]['filename']."</font></td>";
            echo "<td width=10>&nbsp</td><td><font face=$g_font size=$g_text_size color=$g_text_color>";
            if ($v_list_result[$i]['status']=="added")
            {
              echo $v_message[$v_list_result[$i]['status']];
              $v_nb_added++;
            }
            else if ($v_list_result[$i]['status']=="updated")
            {
              echo $v_message[$v_list_result[$i]['status']];
              $v_nb_updated++;
            }
            else if ($v_list_result[$i]['status']=="not_updated")
            {
              echo $v_message[$v_list_result[$i]['status']];
              $v_nb_not_updated++;
            }
            else
              echo "</font><font face=$g_font size=$g_error_size color=red>Error : '".$v_message[$v_list_result[$i]['status']]."'";
            echo "</font>";
            echo "</td><td width=10>&nbsp</td><td width=1 bgcolor=$g_title_bg></td></tr>";
            }
          }
          echo "<tr height=1 bgcolor=$g_title_bg><td colspan=7></td></tr>";
          echo "<tr><td width=1 bgcolor=$g_title_bg></td>";
          echo "<td width=10>&nbsp</td><td colspan=3><font face=$g_font size=$g_text_size color=$g_text_color>$v_nb_updated ".Translate("fichiers mis à jour")."</font></td>";
          echo "<td width=10>&nbsp</td><td width=1 bgcolor=$g_title_bg></td></tr>";
          echo "<tr><td width=1 bgcolor=$g_title_bg></td>";
          echo "<td width=10>&nbsp</td><td colspan=3><font face=$g_font size=$g_text_size color=$g_text_color>$v_nb_not_updated ".Translate("fichiers non modifiés")."</font></td>";
          echo "<td width=10>&nbsp</td><td width=1 bgcolor=$g_title_bg></td></tr>";
          echo "<tr><td width=1 bgcolor=$g_title_bg></td>";
          echo "<td width=10>&nbsp</td><td colspan=3><font face=$g_font size=$g_text_size color=$g_text_color>$v_nb_added ".Translate("fichiers ajoutés")."</font></td>";
          echo "<td width=10>&nbsp</td><td width=1 bgcolor=$g_title_bg></td></tr>";
          echo "<tr height=1 bgcolor=$g_title_bg><td colspan=7></td></tr>";
          echo "<tr><td colspan=7>&nbsp</td></tr></table>";

          // ----- Display HTML footer
          AppPhpzipFooter(Translate("Mise à jour terminée."));
        }
        else
        {
          // ----- Set an error string for the footer
          $v_message = Translate("Erreur lors de l'ajout de la liste dans l'archive PhpZip")." \"$p_archive\".";

          // ----- Display status
          AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
        }

        // ----- Clean
        unset($v_list);
      }

      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Impossible de lire la liste des fichiers.");

        // ----- Display HTML page
        AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }
    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Ajouter", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipConfiguration()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipConfiguration($p_archive)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipConfiguration", "$p_archive");

    // ----- HTML header
    AppPhpzipHeader($p_archive);

    // ----- Add PCJS Color Chooser script
    echo "<script language='JavaScript' src='script/pcjscolorchooser.js'></script>";

    // ----- Section header and title
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_title_bg><td width=10></td>";
    echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color><b>".Translate("Configuration")."</b></font></div></td>";
    echo "<td width=10></td></tr><tr><td width=10></td><td>";

    // ----- Display the form
    echo "<FORM  method=POST name=a_config>";

    // ----- General parameters section
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";
/*
    echo "<tr>";
    echo "  <td width=10></td><td><font face=$g_font size=$g_subtitle_size color=$g_title_bg><b>".Translate("Paramètres généraux")."</b></font></td><td width=10></td>";
    echo "</tr>";
    echo "<tr>";
    echo "  <td width=10 height=1></td><td height=1 bgcolor=$g_title_bg></td><td width=10 height=1></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";

    // ----- Home directory
    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Dossier de démarrage")." : <INPUT TYPE='TEXT' name='a_home_dir' value=\"$g_home_dir\" size='40' maxlength='120'></font>";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";
*/

    // ----- Langue Section
    echo "<tr>";
    echo "  <td width=10></td><td><font face=$g_font size=$g_subtitle_size color=$g_title_bg><b>".Translate("Langue")."</b></font></td><td width=10></td>";
    echo "</tr>";
    echo "<tr>";
    echo "  <td width=10 height=1></td><td height=1 bgcolor=$g_title_bg></td><td width=10 height=1></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";

    // ----- Language
    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Langue")." : <SELECT name=a_lang size=1>";
    $v_dir = opendir($g_language_dir);
    while (($v_file = readdir($v_dir)))
    {
      // ----- Look for language file
      if ( (ereg("(^lang-)([[:alnum:]]+)(\.inc\.php$)", $v_file, $v_items)) )
      {
        //echo "<option value=>$v_items[1]; $v_items[2]; $v_items[3]</option>";
        if ($v_items[2] == $g_language)
          echo "<option value=$v_items[2] selected>$v_items[2]</option>";
        else if ($v_items[2] != "template")
          echo "<option value=$v_items[2]>$v_items[2]</option>";
      }
    }
    echo "</SELECT></font>";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";

    // ----- View Section
    echo "<tr>";
    echo "  <td width=10></td><td><font face=$g_font size=$g_subtitle_size color=$g_title_bg><b>".Translate("Visualisations")."</b></font></td><td width=10></td>";
    echo "</tr>";
    echo "<tr>";
    echo "  <td width=10 height=1></td><td height=1 bgcolor=$g_title_bg></td><td width=10 height=1></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";

    // ----- Views
    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Visualisation par défaut d'une archive")." : ";
    echo "<INPUT TYPE=RADIO name=a_view_archive value=1 ".($g_view_archive?"checked":"").">".Translate("Plane");
    echo " <INPUT TYPE=RADIO  name=a_view_archive value=0 ".(!$g_view_archive?"checked":"").">".Translate("Arborescente")."</font>";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=5></td></tr>";

    // ----- Font and colors Section
    echo "<tr>";
    echo "  <td width=10></td><td><font face=$g_font size=$g_subtitle_size color=$g_title_bg><b>".Translate("Police de Caractères et Couleurs")."</b></font></td><td width=10></td>";
    echo "</tr>";
    echo "<tr>";
    echo "  <td width=10 height=1></td><td height=1 bgcolor=$g_title_bg></td><td width=10 height=1></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";

    // ----- Font face
    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Police de caractères")." : <INPUT TYPE='TEXT' name='a_font' value=\"$g_font_type\" size='40' maxlength='80'></font>";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";

    // ----- Normal text color
    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>";
    echo Translate("Couleur du texte normal")." : ";
    echo "<INPUT TYPE='TEXT' id='a_text_color' name='a_text_color' value=\"$g_text_color\" size='8' maxlength='15'>";
    echo "<img id='img1' src='images/button-sel.jpg' border='0' width='16' height='18' hspace='0' vspace='0' align='absmiddle'  onClick=\"PcjsColorChooser('img1', 'a_text_color','value')\">";
    echo ", ".Translate("Arrière plan")." : ";
    echo "<INPUT TYPE='TEXT' id='a_text_bg' name='a_text_bg' value=\"$g_text_bg\" size='8' maxlength='15'>";
    echo "<img id='img2' src='images/button-sel.jpg' border='0' width='16' height='18' hspace='0' vspace='0' align='absmiddle'  onClick=\"PcjsColorChooser('img2', 'a_text_bg','value')\">";
    echo ", ".Translate("Taille")." : ";
    echo "<INPUT TYPE='TEXT' name='a_text_size' value=\"$g_text_size\" size='3' maxlength='15'></font>";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=5></td></tr>";
    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Couleur des liens")." : ";
    echo "<INPUT TYPE='TEXT' id='a_text_link' name='a_text_link' value=\"$g_text_link\" size='8' maxlength='15'>";
    echo "<img id='img3' src='images/button-sel.jpg' border='0' width='16' height='18' hspace='0' vspace='0' align='absmiddle'  onClick=\"PcjsColorChooser('img3', 'a_text_link','value')\">";
    echo "</font>";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=5></td></tr>";
    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Couleur des titres")." : ";
    echo "<INPUT TYPE='TEXT' id='a_title_color' name='a_title_color' value=\"$g_title_color\" size='8' maxlength='15'>";
    echo "<img id='img4' src='images/button-sel.jpg' border='0' width='16' height='18' hspace='0' vspace='0' align='absmiddle'  onClick=\"PcjsColorChooser('img4', 'a_title_color','value')\">";
    echo ", ".Translate("Arrière plan")." : ";
    echo "<INPUT TYPE='TEXT' id='a_title_bg' name='a_title_bg' value=\"$g_title_bg\" size='8' maxlength='15'>";
    echo "<img id='img5' src='images/button-sel.jpg' border='0' width='16' height='18' hspace='0' vspace='0' align='absmiddle'  onClick=\"PcjsColorChooser('img5', 'a_title_bg','value')\">";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=5></td></tr>";
    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Couleur des erreurs")." : ";
    echo "<INPUT TYPE='TEXT' id='a_error_color' name='a_error_color' value=\"$g_error_color\" size='8' maxlength='15'>";
    echo "<img id='img6' src='images/button-sel.jpg' border='0' width='16' height='18' hspace='0' vspace='0' align='absmiddle'  onClick=\"PcjsColorChooser('img6', 'a_error_color','value')\">";
    echo ", ".Translate("Arrière plan")." : ";
    echo "<INPUT TYPE='TEXT' id='a_error_bg' name='a_error_bg' value=\"$g_error_bg\" size='8' maxlength='15'>";
    echo "<img id='img7' src='images/button-sel.jpg' border='0' width='16' height='18' hspace='0' vspace='0' align='absmiddle'  onClick=\"PcjsColorChooser('img7', 'a_error_bg','value')\">";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";

    // ----- Size Section
    echo "<tr>";
    echo "  <td width=10></td><td><font face=$g_font size=$g_subtitle_size color=$g_title_bg><b>".Translate("Tailles des Textes")."</b></font></td><td width=10></td>";
    echo "</tr>";
    echo "<tr>";
    echo "  <td width=10 height=1></td><td height=1 bgcolor=$g_title_bg></td><td width=10 height=1></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";

    // ----- Sizes
    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Texte normal")." : <INPUT TYPE='TEXT' name='a_text_size' value=\"$g_text_size\" size='3' maxlength='15'>";
    echo ", ".Translate("Erreurs")." : <INPUT TYPE='TEXT' name='a_error_size' value=\"$g_error_size\" size='3' maxlength='15'></font>";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=5></td></tr>";
    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Titres")." : <INPUT TYPE='TEXT' name='a_title_size' value=\"$g_title_size\" size='3' maxlength='15'>";
    echo ", ".Translate("Sous-titres")." : <INPUT TYPE='TEXT' name='a_subtitle_size' value=\"$g_subtitle_size\" size='3' maxlength='15'>";
    echo ", ".Translate("Bas de page")." : <INPUT TYPE='TEXT' name='a_footer_size' value=\"$g_footer_size\" size='3' maxlength='15'></font>";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=5></td></tr>";

    // ----- Advance configuration Section
    echo "<tr>";
    echo "  <td width=10></td><td><font face=$g_font size=$g_subtitle_size color=$g_title_bg><b>".Translate("Configuration avancée")."</b></font></td><td width=10></td>";
    echo "</tr>";
    echo "<tr>";
    echo "  <td width=10 height=1></td><td height=1 bgcolor=$g_title_bg></td><td width=10 height=1></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";

    // ----- Header adn Footer filenames
    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Fichier d'entête")." : <INPUT TYPE='TEXT' name='a_header' value=\"$g_header_file\" size='40' maxlength='80'></font>";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=5></td></tr>";
    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Fichier de pied de page")." : <INPUT TYPE='TEXT' name='a_footer' value=\"$g_footer_file\" size='40' maxlength='80'></font>";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";

    // ----- Trace configuration Section
    echo "<tr>";
    echo "  <td width=10></td><td><font face=$g_font size=$g_subtitle_size color=$g_title_bg><b>".Translate("Configuration de la trace")."</b></font></td><td width=10></td>";
    echo "</tr>";
    echo "<tr>";
    echo "  <td width=10 height=1></td><td height=1 bgcolor=$g_title_bg></td><td width=10 height=1></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";

    // ----- Trace parameters
    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Trace")." : ";
    if ($g_trace > 0)
    {
      echo "<INPUT TYPE=RADIO name=a_trace value=1 checked>".Translate("Allumer");
      echo " <INPUT TYPE=RADIO  name=a_trace value=0>".Translate("Eteindre")."</font>";
    }
    else
    {
      echo "<INPUT TYPE=RADIO name=a_trace value=1>".Translate("Allumer");
      echo " <INPUT TYPE=RADIO  name=a_trace value=0 checked>".Translate("Eteindre")."</font>";
    }
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=5></td></tr>";

    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Affichage de la trace")." : ";
    echo "<INPUT TYPE=RADIO name=a_trace_mode value=memory ".($g_trace_mode=="memory"?"checked":"").">".Translate("A la fin");
    echo " <INPUT TYPE=RADIO name=a_trace_mode value=normal ".($g_trace_mode=="normal"?"checked":"").">".Translate("Pas à pas");
// ----- No ready in PclTrace library
//    echo " <INPUT TYPE=RADIO name=a_trace_mode value=log ".($g_trace_mode=="log"?"checked":"").">".Translate("Dans un fichier");
//    echo " : <INPUT TYPE='TEXT' name='a_trace_filename' value=\"$g_trace_filename\" size='20' maxlength='40'>";
    echo "</font>";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=5></td></tr>";

    echo "<tr>";
    echo "  <td width=10></td><td>";
    echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Niveau de trace")." : <SELECT  name=a_trace_level>";
    echo "<option value=1 ".($g_trace==1?"selected":"").">".Translate("Appels fonctions")."</option>";
    echo "<option value=2 ".($g_trace==2?"selected":"").">".Translate("+ actions")."</option>";
    echo "<option value=3 ".($g_trace==3?"selected":"").">".Translate("+ détails")."</option>";
    echo "<option value=4 ".($g_trace==4?"selected":"").">".Translate("+ détails fins")."</option>";
    echo "<option value=5 ".($g_trace==5?"selected":"").">".Translate("+ détails trés fin")."</option>";
    echo "</SELECT></font>";
    echo "  </td><td width=10></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";

    // ----- End of sections table
    echo "<tr>";
    echo "  <td width=10 height=1></td><td height=1 bgcolor=$g_title_bg></td><td width=10 height=1></td>";
    echo "</tr>";
    echo "<tr><td width=10></td><td>&nbsp</td><td width=10></td></tr>";
    echo "</table>";

    // ----- End of form
    echo "<input type='hidden' name=a_archive value=$p_archive>";
    echo "<input type='hidden' name=a_action value=option_do>";
    echo "<div align=center><INPUT TYPE='SUBMIT'  value='".Translate("Appliquer")."'></div>";
    echo "</FORM>";

    // ----- Section footer
    echo "</td><td width=10></td></tr></table>";

    // ----- HTML Footer
    AppPhpzipFooter(Translate("Configuration").".");

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipConfigurationChange()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipConfigurationChange($p_archive, $p_lang, $p_font, $p_header, $p_footer, $p_trace, $p_trace_level, $p_trace_mode, $p_trace_filename,
                                        $p_text_bg, $p_text_color, $p_text_link, $p_title_bg, $p_title_color, $p_error_bg, $p_error_color,
                                        $p_text_size, $p_title_size, $p_subtitle_size, $p_error_size, $p_footer_size, $p_home_dir, $p_view_archive)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipConfigurationChange", "$p_archive, $p_lang, $p_font, $p_header, $p_footer, $p_trace, $p_trace_level, $p_trace_mode, $p_trace_filename, $p_view_archive");

    // ----- Read the configuration file
    $v_file_lines = file($g_config_file);

    // ----- Rename configuration file in backup file
    if (!@copy($g_config_file, $g_config_file.".bak"))
    {
      TrFctMessage(__FILE__, __LINE__, 3, "Unable to copy configuration file ....");

      // ----- Set an error string for the footer
      $v_message = Translate("Impossible de sauvegarder le fichier de configuration en .bak");

      // ----- Display HTML page
      AppPhpzipStatus("Configurer", $p_archive, "NOK", $v_message, Translate("Erreur").".");

      // ----- Return
      TrFctEnd(__FILE__, __LINE__, $v_result);
      return($v_result);
    }

    // ----- Write a new dynamic configuration file
    if (!($v_file=@fopen($g_config_file, "w")))
    {
      TrFctMessage(__FILE__, __LINE__, 3, "Fail to open new configuration file '".$g_config_file."'");

      // ----- Set an error string for the footer
      $v_message = Translate("Impossible d'ouvrir en écriture le fichier de configuration")." \"".$g_config_file."\"";

      // ----- Display HTML page
      AppPhpzipStatus("Configurer", $p_archive, "NOK", $v_message, Translate("Erreur").".");

      // ----- Return
      TrFctEnd(__FILE__, __LINE__, $v_result);
      return($v_result);
    }

    TrFctMessage(__FILE__, __LINE__, 3, "p_lang = $p_lang p_font = $p_font");

    // ----- Scan all the lines
    for ($i=0; $i<sizeof($v_file_lines); $i++)
    {
      // ----- Get the line
      $v_line = $v_file_lines[$i];

      TrFctMessage(__FILE__, __LINE__, 3, "line      : $v_file_lines[$i]");

      // ----- Look for variable definition
      if ( (ereg("([ ]+)(\\\$)([[:alnum:]_]+)([ ]*=[ ]*)(.*)(;[ \n\r]*$)", $v_file_lines[$i], $v_item)) )
      {
        TrFctMessage(__FILE__, __LINE__, 3, "found 1:[$v_item[1]] 2:[$v_item[2]] 3:[$v_item[3]] 4:[$v_item[4]] 5:[$v_item[5]] 6:[$v_item[6]]");

        // ----- Look for known configuration variable
        switch ($v_item[3]) {
          case "g_language" :
            // ----- Modify the line
            $v_item[5] = "\"".$p_lang."\"";
          break;
          case "g_font_type" :
            // ----- Modify the line
            $v_item[5] = "\"".$p_font."\"";
          break;
          case "g_header_file" :
            // ----- Modify the line
            $v_item[5] = "\"".$p_header."\"";
          break;
          case "g_footer_file" :
            // ----- Modify the line
            $v_item[5] = "\"".$p_footer."\"";
          break;
          case "g_trace" :
            // ----- Modify the line
            if ($p_trace)
              $v_item[5] = $p_trace_level;
            else
              $v_item[5] = 0;
          break;
          case "g_trace_mode" :
            // ----- Modify the line
            if ($p_trace)
              $v_item[5] = "\"".$p_trace_mode."\"";
          break;
          case "g_trace_filename" :
            // ----- Modify the line
            if ($p_trace)
              $v_item[5] = "\"".$p_trace_filename."\"";
          break;
          case "g_text_bg" :
            $v_item[5] = "\"".$p_text_bg."\"";
          break;
          case "g_text_color" :
            $v_item[5] = "\"".$p_text_color."\"";
          break;
          case "g_text_link" :
            $v_item[5] = "\"".$p_text_link."\"";
          break;
          case "g_title_bg" :
            $v_item[5] = "\"".$p_title_bg."\"";
          break;
          case "g_title_color" :
            $v_item[5] = "\"".$p_title_color."\"";
          break;
          case "g_error_bg" :
            $v_item[5] = "\"".$p_error_bg."\"";
          break;
          case "g_error_color" :
            $v_item[5] = "\"".$p_error_color."\"";
          break;
          case "g_text_size" :
            $v_item[5] = $p_text_size;
          break;
          case "g_title_size" :
            $v_item[5] = $p_title_size;
          break;
          case "g_subtitle_size" :
            $v_item[5] = $p_subtitle_size;
          break;
          case "g_error_size" :
            $v_item[5] = $p_error_size;
          break;
          case "g_footer_size" :
            $v_item[5] = $p_footer_size;
          break;
          case "g_home_dir" :
            $v_item[5] = "\"".$p_home_dir."\"";
          break;
          case "g_view_archive" :
            $v_item[5] = $p_view_archive;
          break;
          default :
            // TBC : Error unknown config variable
        }

        // ----- Compose the resulting line
        $v_line = $v_item[1].$v_item[2].$v_item[3].$v_item[4].$v_item[5].$v_item[6];
      }
      else {
        TrFctMessage(__FILE__, __LINE__, 3, "Line with no attribute");
      }

      TrFctMessage(__FILE__, __LINE__, 3, "change by : $v_line");

      // ----- Write the line
      fputs($v_file, $v_line);
    }

    // ----- Close the file
    fclose($v_file);

    // ---- Delete the backup file
    if (!@unlink($g_config_file.".bak"))
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Warning : Impossible de détruire le fichier temporaire")." \"".$g_config_file.".bak\"";

      // ----- Display HTML page
      AppPhpzipStatus("Configurer", $p_archive, "NOK", $v_message, Translate("Erreur").".");

      // ----- Return
      TrFctEnd(__FILE__, __LINE__, $v_result);
      return($v_result);
    }

    // ----- Refresh instruction
    die("<HTML><HEADER><META HTTP-EQUIV=refresh CONTENT=0></HEADER><BODY></BODY></HTML>");

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipDownload()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipDownload($p_archive)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipDownload", "$p_archive");

    // ----- Check for security, does not download other files than archives
    if (AppPhpzipArchiveType($p_archive) == '')
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Impossible de trouver l'archive")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Télécharger", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }
    else
    // ----- Open and send the file
    if ($fp = @fopen($p_archive, "r"))
    {
      header("Content-disposition: filename=".basename($p_archive));
      header('Content-Length: '.filesize($p_archive));
      header("Content-type: application/octetstream");
      header("Pragma: no-cache");
      header("Expires: 0");
      fpassthru($fp);
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Impossible de trouver l'archive")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Télécharger", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipExtractByIndex()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipExtractByIndex($p_archive, $p_index, $p_path="./", $p_remove_dir="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipExtractByIndex", "archive='$p_archive', indext='$p_index', path='$p_path', remove_dir='$p_remove_dir'");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for zip format
    if ($v_archive_type == 'zip')
    {
      $v_result = AppPhpzipZipExtractByIndex($p_archive, $p_index, $p_path, $p_remove_dir);
    }

    // ----- Look for tar format
    else if (($v_archive_type != "phpzip") && ($v_archive_type != ""))
    {
      // ----- Extract the files
      $v_list_result = PclTarExtractIndex($p_archive, $p_index, $p_path, $p_remove_dir, $v_archive_type);
      if ((is_array($v_list_result)) && (sizeof($v_list_result) != 0))
      {
        // ----- Display HTML header
//        AppPhpzipHeader($p_archive);
        AppPhpzipActionHeader($p_archive, Translate("Décompression PhpZip"));

        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_text_bg><td><font face=$g_font size=$g_text_size color=$g_text_color><p><br><br><div align=center>".Translate("Opération terminée")."</div></p></font>";
        echo "</td></tr>";

        // ----- Display HTML footer
        echo "</table>";

        // ----- Close button
        echo "<form method=post action=\"?\">";
        echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
        echo "<div align=center><font face=$g_font size=$g_text_size color=$g_text_color>";
        echo "<input type=submit value=\"";
        echo Translate("Fermer");
        echo "\" onClick='window.close();'>";
        echo "</font></div>";
        echo "</form>";

        AppPhpzipActionFooter(Translate("Décompression PhpZip OK."));
      }
      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Erreur lors de la décompression de l'archive PhpZip")." \"$p_archive\"";

        // ----- Reset the archive name
        $p_archive = "";

        // ----- Display HTML page
        AppPhpzipStatus("Extraire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
     }
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Reset the archive name
      $p_archive = "";

      // ----- Display HTML page
      AppPhpzipStatus("Extraire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipExtractByIndexAsk()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipExtractByIndexAsk($p_archive, $p_index)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipExtractByIndexAsk", "archive='$p_archive', indext='$p_index'");

    // ----- Get the archive type
    $v_archive_type = AppPhpzipArchiveType($p_archive);

    // ----- Look for PhpZip format
    if ($v_archive_type == "phpzip")
    {
      // ----- Not supported yet

      // ----- Set an error string for the footer
      $v_message = Translate("Fonction non supportée pour ce type d'archive");

      // ----- Display HTML page
      AppPhpzipStatus("Extraire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }


    // ----- Look for zip format
    else if ($v_archive_type == "zip")
    {
      $v_result = AppPhpzipZipExtractByIndexAsk($p_archive, $p_index);
    }

    // ----- Look for tar (tar.gz) format
    else if ($v_archive_type != "")
    {
      // ----- Get list of file
      $v_list = PclTarList($p_archive, $v_archive_type);

      // ----- Parse index
      $v_token = explode('-', $p_index);
      if (sizeof($v_token)==1)
        $v_token[1]=$v_token[0];
      TrFctMessage(__FILE__, __LINE__, 2, "Parsing index '$v_token[0]' and '$v_token[1]'");

      // ----- Look for file
      if (sizeof($v_list))
      {
        // ----- Display HTML header
        AppPhpzipActionHeader($p_archive, Translate("Extraire"));

        echo "<form name=formulaire method=post action=\"?\">";
        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_text_bg><td><font face=$g_font size=$g_text_size color=$g_text_color><p><br><div align=center>";
        echo Translate("Confirmer l'extraction de")." :</div></p></font>";
        echo "</td></tr>";
        echo "</table>";

?>
<div id="Layer2" style="position:absolute; width:300px; height:200px; z-index:1; overflow: auto; left: 20px; top: 60px; background-color: #CCCCCC; border: 1px none #CCCCCC">
<?
        // ----- Display the list of extracted files
        echo "<table border=0 cellspacing=0 cellpadding=0 align=left><tr bgcolor=CCCCCC><td colspan=3>&nbsp</td></tr>";
        // ----- Compose the file list
        for ($i=0; $i<sizeof($v_list); $i++)
        {
          // ----- Look for scope
          if (($i>=$v_token[0]) && ($i <= $v_token[1]))
          {
            echo "<tr bgcolor=CCCCCC><td>";
            echo "<img src='$g_images_dir/".AppPhpzipExtensionImageTar($v_list[$i])."' border='0' width='16' height='16' align='absmiddle'>";
            echo "</td><td width=5></td><td>";
            echo "<font face=$g_font size=".$g_text_size." color=$g_text_color>";
            echo $v_list[$i]['filename']."</font>";
            echo "</td></tr>\n\r";
          }
        }
        echo "<tr bgcolor=CCCCCC><td>&nbsp</td></tr></table>";

?>
</div>
<?

        // ----- End of form
        echo "<br><br><br><br><br><br><br><br><br><br><br><br>";

        // ----- Ask for extraction directory
        echo '<div align=center>';
        echo "<font face=$g_font size=$g_text_size color=$g_text_color>";
        echo "<br>".Translate("Extraire dans")." : ";
        echo "<input type=text id=\"a_path\" name=a_path size=15 maxlength=200 value=./>";
        //echo " <INPUT TYPE='BUTTON'  value='".Translate('Parcourir')." ...'></font>";

        echo "<script language='javascript' src='pcsexplorer/pcsexplorer.js.php'></script>";

        // ----- Here I should use something like the last opened directory ...
        // TBC
        global $PATH_INFO;
        echo " <INPUT TYPE=button name=bt value='".Translate('Parcourir')." ...' ";
//        echo "onClick='PcjsOpenExplorer(\"pcsexplorer.php\", \"forms.formulaire.a_path.value\", \"type=dir\", \"calling_dir=".dirname($PATH_INFO)."\", \"start_dir=.\")'";
        echo "onClick='PcjsExplorer(\"target=a_path\", \"type=dir\", \"result_ref_dir=".dirname($PATH_INFO)."\", \"start_dir=.\")'";
        echo ">";

        // ----- Ask for remove of directory
        $v_subpath = explode("/", $v_list[$v_token[0]]['filename']);
        $v_nb = sizeof($v_subpath);
        if ($v_list[$v_token[0]]['typeflag']!=5)
          $v_nb--;
        if ($v_nb != 0)
        {
          echo "<br><font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Chemin à ignorer")." : ";
          echo "<SELECT  name='a_remove_dir'>";
          echo "<OPTION value=''></OPTION>";
          for ($i=0, $v_str=''; $i<$v_nb; $i++)
          {
            if ($i==0)
              $v_str = $v_subpath[$i];
            else
              $v_str = $v_str.'/'.$v_subpath[$i];
            echo "<OPTION value='$v_str'>$v_str</OPTION>";
          }
          echo "</SELECT>";
        }
        else
          echo "<br>&nbsp;";
        echo '</div><br>';


        echo "<input type=hidden name=a_action value=extract_index>";
        echo "<input type=hidden name=a_index value=$p_index>";
        echo "<input type=hidden name=a_archive value=$p_archive>";
        echo "<div align=center><font face=$g_font size=$g_text_size color=$g_text_color>";
        echo "<input type=submit value=\"";
        echo Translate("Confirmer");
        echo "\"> ";
        echo "<input type=submit value=\"";
        echo Translate("Annuler");
        echo "\" onClick='window.close();'>";
        echo "</font></div>";
        echo "</form>";

        // ----- Display HTML footer
        AppPhpzipActionFooter(Translate("Extraire"));

        unset($v_list);
      }

      else
      {
        // ----- Set an error string for the footer
        $v_message = Translate("Impossible de lire la liste des fichiers.");

        // ----- Display HTML page
        AppPhpzipStatus("Extraire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
      }
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Archive PhpZip inconnue")." \"$p_archive\"";

      // ----- Display HTML page
      AppPhpzipStatus("Extraire", $p_archive, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------


  // ---------------------------------------------------------------------------
  // Main part
  // ---------------------------------------------------------------------------
  
  TrFctMessage(__FILE__, __LINE__, 2, "action='".$_REQUEST['a_action']."'");

  $v_message_window = 0;

  // ----- Check for no a_action argument
  if (!isset($_REQUEST['a_action']))
    $_REQUEST['a_action'] = "";

  // ----- Main loop
  switch ($_REQUEST['a_action']) {
    case "start" :
      AppPhpzipStart();
    break;

    case "new" :
      AppPhpzipAskCreate();
    break;

    case "new_do" :
      AppPhpzipCreate($_REQUEST['a_archive'], $_REQUEST['a_type'],
                      $_REQUEST['a_startfile'], $_REQUEST['a_dir']);
    break;

    case "new_replace" :
      if ($_REQUEST['a_submit']==Translate("Oui"))
        AppPhpzipReplace($_REQUEST['a_archive'], $_REQUEST['a_type'],
                         $_REQUEST['a_startfile']);
      else
        AppPhpzipAskCreate();
    break;

    case "read" :
      AppPhpzipRead($_REQUEST['a_dir']);
    break;

    case "add" :
      if ($_REQUEST['a_archive'] == "")
        AppPhpzipRead($_REQUEST['a_dir']);
      else
        AppPhpzipExplorer($_REQUEST['a_archive'], "add",
                          (isset($_REQUEST['a_dir'])?$_REQUEST['a_dir']:''));
    break;

    case "add_list" :
      if ($_REQUEST['a_archive'] == "")
        AppPhpzipRead($_REQUEST['a_dir']);
      else
      {
        AppPhpzipAddList($_REQUEST['a_archive'], $_REQUEST['a_file'],
                         (isset($_REQUEST['a_type'])?$_REQUEST['a_type']:''),
		                     $_REQUEST['a_dir'], $_REQUEST['a_stored_dir']);
      }
    break;

    case "update" :
      if ($_REQUEST['a_archive'] == "")
        AppPhpzipRead($_REQUEST['a_dir']);
      else
        AppPhpzipExplorer($_REQUEST['a_archive'], "update", $_REQUEST['a_dir']);
    break;

    case "update_list" :
      if ($_REQUEST['a_archive'] == "")
        AppPhpzipRead($_REQUEST['a_dir']);
      else
      {
        AppPhpzipUpdate($_REQUEST['a_archive'], $_REQUEST['a_file'],
                        $_REQUEST['a_type'], $_REQUEST['a_dir'],
                        $_REQUEST['a_stored_dir']);
      }
    break;

    case "update_list_do" :
      if ($_REQUEST['a_archive'] == "")
        AppPhpzipRead($_REQUEST['a_dir']);
      else
      {
        AppPhpzipUpdateList($_REQUEST['a_archive'], $_REQUEST['a_file'],
                            $_REQUEST['a_type'], $_REQUEST['a_dir'],
                            $_REQUEST['a_stored_dir']);
        $v_message_window = 1;
      }
    break;

    case "erase" :
      if ($_REQUEST['a_archive'] == "")
        AppPhpzipRead($_REQUEST['a_dir']);
      else
        AppPhpzipAskErase($_REQUEST['a_archive']);
    break;

    case "download" :
      if ($_REQUEST['a_archive'] == "")
        AppPhpzipRead($_REQUEST['a_dir']);
      else
        AppPhpzipDownload($_REQUEST['a_archive']);
    break;

    case "erase_do" :
      if ($_REQUEST['a_submit'] == Translate("Supprimer"))
        AppPhpzipErase($_REQUEST['a_archive']);
      else
        AppPhpzipList($_REQUEST['a_archive']);
    break;

    case "unzip" :
      if ($_REQUEST['a_archive'] == "")
        AppPhpzipRead($_REQUEST['a_dir']);
      else
        AppPhpzipAskUnzip($_REQUEST['a_archive']);
    break;

    case "unzip_do" :
      if ($_REQUEST['a_archive'] == "")
        AppPhpzipRead($_REQUEST['a_dir']);
      else
        if ((isset($_REQUEST['a_extractall'])) && ($_REQUEST['a_extractall']))
          AppPhpzipUnzip($_REQUEST['a_archive'], $_REQUEST['a_path'],
                         $_REQUEST['a_remove_dir']);
        else
        {
          AppPhpzipUnzipList($_REQUEST['a_archive'], $_REQUEST['a_file'],
                             $_REQUEST['a_path'], $_REQUEST['a_remove_dir']);
        }
    break;

    case "del_file" :
      if ($_REQUEST['a_archive'] == "")
        AppPhpzipRead($_REQUEST['a_dir']);
      else
        AppPhpzipAskDeleteFile($_REQUEST['a_archive']);
    break;

    case "del_file_do" :
      if ($_REQUEST['a_archive'] == "")
        AppPhpzipRead($_REQUEST['a_dir']);
      else
      {
        if (AppPhpzipArchiveType($_REQUEST['a_archive'])=="zip")
          AppPhpzipDeleteFileByIndex($_REQUEST['a_archive'],
                                     $_REQUEST['a_index']);
        else
          AppPhpzipDeleteFileList($_REQUEST['a_archive'], $_REQUEST['a_file']);
        $v_message_window = 1;
      }
    break;

    case "list" :
      if ($_REQUEST['a_archive'] == "")
        AppPhpzipRead($_REQUEST['a_dir']);
      else
        AppPhpzipList($_REQUEST['a_archive']);
    break;

    case "help" :
        AppPhpzipHelp($_REQUEST['a_archive'], $_REQUEST['a_topic']);
    break;

    case "about" :
        AppPhpzipAbout((isset($_REQUEST['a_archive'])?$_REQUEST['a_archive']:''));
    break;

    case "option" :
      AppPhpzipConfiguration($_REQUEST['a_archive']);
    break;

    case "option_do" :
      AppPhpzipConfigurationChange($_REQUEST['a_archive'], $_REQUEST['a_lang'],
                                   $_REQUEST['a_font'],
                                   $_REQUEST['a_header'], $_REQUEST['a_footer'],
                                   $_REQUEST['a_trace'],
                                   $_REQUEST['a_trace_level'],
                                   $_REQUEST['a_trace_mode'], 
                                   '' /*$_REQUEST['a_trace_filename']*/,
                                   $_REQUEST['a_text_bg'],
                                   $_REQUEST['a_text_color'],
                                   $_REQUEST['a_text_link'],
                                   $_REQUEST['a_title_bg'],
                                   $_REQUEST['a_title_color'],
                                   $_REQUEST['a_error_bg'],
                                   $_REQUEST['a_error_color'],
                                   $_REQUEST['a_text_size'],
                                   $_REQUEST['a_title_size'],
                                   $_REQUEST['a_subtitle_size'],
                                   $_REQUEST['a_error_size'],
                                   $_REQUEST['a_footer_size'],
                                   '' /*$_REQUEST['a_home_dir']*/,
                                   $_REQUEST['a_view_archive']);
    break;

    case "alternate" :
      AppPhpzipAlternateMenu($_REQUEST['a_archive']);
    break;

    case "extract_index_ask" :
      AppPhpzipExtractByIndexAsk($_REQUEST['a_archive'], $_REQUEST['a_index']);
      $v_message_window = 1;
    break;

    case "extract_index" :
      AppPhpzipExtractByIndex($_REQUEST['a_archive'], $_REQUEST['a_index'],
                              $_REQUEST['a_path'], $_REQUEST['a_remove_dir']);
      $v_message_window = 1;
    break;

    case "update_index" :
      if ($_REQUEST['a_archive'] == "")
        AppPhpzipRead($_REQUEST['a_dir']);
      else
      {
        AppPhpzipUpdate($_REQUEST['a_archive'], $_REQUEST['a_file']);
        $v_message_window = 1;
      }
    break;

    case "test" :
      AppPhpzipActionHeader($_REQUEST['a_archive']);
      AppPhpzipActionFooter("Test");
      exit;
    break;

    default :
      AppPhpzipStart();
  }

  // ----- Display trace (if enable)
  echo "<p>";
  TrDisplay();
  echo "</p>";

  // ----- Display the custom footer
  if (@is_file($g_footer_file))
  {
    include ("$g_footer_file");
  }
  // ----- Finish the HTML page
  else
  {
    echo "</BODY></HTML>";
  }

  // ----- End of file
?>
