<?php
// --------------------------------------------------------------------------------
// PhpZip Application
// --------------------------------------------------------------------------------
// License GNU/GPL - Vincent Blavet - Janvier 2001
// http://www.phpconcept.net & http://phpconcept.free.fr
// --------------------------------------------------------------------------------
// CVS : $Id: phpzip-zip.php,v 1.1 2005/12/22 14:27:01 vblavet Exp $
// --------------------------------------------------------------------------------


  // --------------------------------------------------------------------------------
  // Function : AppPhpzipZipList()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipZipList($p_archive, $p_message="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipZipList", "$p_archive, $p_message");

    // ----- Create the zip object
    $v_zip = new PclZip($p_archive);

    // ----- Look for flat or tree view
    if ($g_view_archive)
    {
      $v_result = AppPhpzipZipListFlat($v_zip, $p_message);
    }
    else
    {
      $v_result = AppPhpzipZipListTree($v_zip, $p_message);
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipZipListFlat()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipZipListFlat($p_zip, $p_message="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipZipListFlat", "$p_zip, $p_message");

    // ----- Do the list
    $v_list=$p_zip->listContent();
    if (!is_array($v_list))
    {
      // ----- Return
      TrFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }

    // ----- Display HTML header
    AppPhpzipHeader($p_zip->zipname);

    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_title_bg>";
    echo "<td><div align=center>&nbsp</div></td>";
    echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Fichier")."</font></div></td>";
    echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Taille")."</font></div></td>";
    echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Compression")."</font></div></td>";
    echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Dossier")."</font></div></td>";
    echo "</tr>";

    // ----- List the files
    for ($i=0; $i<sizeof($v_list); $i++)
    {
      if ($v_list[$i]['folder'])
        $v_image = "folder02-16.gif";
      else
        $v_image = AppPhpzipExtensionImage($v_list[$i]['stored_filename']);

      // ----- Display
      echo "<tr bgcolor=$g_text_bg>";
      echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center><img src='$g_images_dir/$v_image' border='0' width='16' height='16' align='absmiddle'></div></font></td>";
      echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".basename($v_list[$i]['stored_filename'])."</font></td>";
      echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".$v_list[$i]['size']."</div></font></td>";
      if ($v_list[$i]['size']!=0)
      {
        printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>%d(%d%%)</div></font></td>", $v_list[$i]['compressed_size'], (100-($v_list[$i]['compressed_size']/$v_list[$i]['size'])*100));
      }
      else
      {
        printf("<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>-</div></font></td>");
      }

      $v_dirname = dirname($v_list[$i]['stored_filename']);
      if ($v_dirname == $v_list[$i]['stored_filename'])
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
      $p_message = Translate("Contenu de l'archive ").$p_zip->zipname.".";
    AppPhpzipFooter($p_message);

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipZipListTree()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipZipListTree($p_zip, $p_message="")
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipZipListTree", "$p_zip, $p_message");

    // ----- Do the list
    $v_list = $p_zip->listContent();
    if (!is_array($v_list))
    {
      // ----- Return
      TrFctEnd(__FILE__, __LINE__, 0);
      return 0;
    }

    // ----- Compose a tree
    $v_tree = array();
    $n = sizeof($v_list);
    for ($i=0, $j=0; $i<$n; $i++, $j++)
    {
      TrFctMessage(__FILE__, __LINE__, 4, "Adding '".$v_list[$i]['filename']."' as a root of the tree");
      $v_tree[$j]=AppPhpzipZipComposeTree($v_list, $i);
    }

    // ----- Display HTML header
    AppPhpzipHeader($p_zip->zipname);

    echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
    echo "<tr bgcolor=$g_title_bg>";
    echo "<td><div align=left><img src='$g_images_dir/folder-link00-16.gif' border='0' width='16' height='16' align='absmiddle'><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Fichier")."</font></div></td>";
    echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Taille")."</font></div></td>";
    echo "</tr>";

    // ----- Display the tree
    for ($i=0; $i<sizeof($v_tree); $i++)
    {
      AppPhpzipZipListTreeItem($p_zip, $v_list, $v_tree[$i], "", 1, 1);
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
  // Function : AppPhpzipZipComposeTree()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipZipComposeTree(&$p_list, &$p_index, $p_parent_index=-1)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipZipComposeTree", "item='".$p_list[$p_index]['filename']."', index='$p_index', parent_index='$p_parent_index'");

    $v_list_size = sizeof($p_list);

    // ----- Set the default index value
    //$p_list[$p_index][index] = $p_index;

    // ----- Memorize info for futur use
    $v_futur_parent = $p_index;
    $v_calling_index = $p_index;
    if ($p_list[$p_index]['folder'])
    {
      TrFctMessage(__FILE__, __LINE__, 4, "We will look for childs");
      $v_look_for_child=1;
    }
    else
    {
      TrFctMessage(__FILE__, __LINE__, 4, "No child expected");
      $v_look_for_child=0;
    }

    // ----- Look for root
/*
    if (($p_parent_index==-1) ||
        ((!$p_list[$p_index]['folder']) && ($p_list[$p_parent_index]['filename'] != dirname($p_list[$p_index]['filename']))))
*/
    if (($p_parent_index==-1) ||
        ((!$p_list[$p_index]['folder']) && (!PhpZipPathIsIncluded($p_list[$p_index]['filename'], $p_list[$p_parent_index]['filename']))))

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
          $p_list[$n1]['folder'] = true;
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
      TrFctMessage(__FILE__, __LINE__, 4, "Looking for child for the node '".$p_list[$v_futur_parent]['filename']."'");

      // ----- Go through the entries
      $p_index++;
      $v_size_parent = strlen($p_list[$v_futur_parent]['filename']);
      TrFctMessage(__FILE__, __LINE__, 4, "Starting loop at node index '$p_index'");
      $v_nb_childs = sizeof($p_list[$v_futur_parent]['child_list']);
      while (($p_index < $v_list_size) && (substr($p_list[$p_index]['filename'], 0, $v_size_parent) == $p_list[$v_futur_parent]['filename']) && ($v_size_parent != strlen($p_list[$p_index]['filename'])))
      {
        TrFctMessage(__FILE__, __LINE__, 4, "Adding node index '$p_index' (".$p_list[$p_index]['filename'].") as a child");
        $p_list[$v_futur_parent]['child_list'][$v_nb_childs++] = AppPhpzipZipComposeTree($p_list, $p_index, $v_futur_parent);
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
  // Function : AppPhpzipZipListTreeItem()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipZipListTreeItem($p_zip, $p_list, $p_index, $p_prefix="", $p_last=0, $p_root=0)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipZipListTreeItem", "index='".$p_index."' (".$p_list[$p_index]['filename']."), last=$p_last");

    // ----- Get the image associated with the file type
    if ($p_list[$p_index]['folder'])
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
    $v_description_string = Translate(($p_list[$p_index]['folder']?"Dossier":"Fichier"))." : ".basename($p_list[$p_index]['filename'])."\n";
    if (($v_string = dirname($p_list[$p_index]['filename'])) != "")
      $v_description_string .= Translate("Chemin")." : ".$v_string;
    if (!$p_list[$p_index]['folder'])
      $v_description_string .= "\n".Translate("Taille")." : ".$p_list[$p_index]['size']." ".Translate("octet")."s";
	if (isset($p_list[$p_index]['mtime']))
    $v_description_string .= "\n".Translate("Dernière modification")." : ".date("d/m/Y H:i:s", $p_list[$p_index]['mtime'])."";

    // ----- Calculate the range index for a directory
    if ($p_list[$p_index]['folder'])
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
//    echo "<a href='' class='file' title=\"".$v_description_string."\" onClick='PcjsZipFileOpenPopup(\"".$v_index_string."\",\"".$p_list[$p_index]['filename']."\",\"".$p_zip->zipname."\",".($p_list[$p_index]['folder']==true?1:0)."); return false;'>";
    echo "<a id='link_".$v_index_string."' class='file' title=\"".$v_description_string."\" href='javascript:void(0);'>";
    echo basename($p_list[$p_index]['filename']);
    echo "</a>";
    echo "</font></div></font></td>";
    echo "<td><font face=$g_font size=$g_text_size color=$g_text_color><div align=center>".$p_list[$p_index]['size']."</div></font></td>";
    echo "</tr>";

?>
<script language="JavaScript1.2">
PcjsZipFileDeclare('<?php echo $v_index_string; ?>', '<?php echo $p_list[$p_index]['filename']; ?>', '<?php echo $p_zip->zipname; ?>', '<?php echo ($p_list[$p_index]['folder']==true?1:0); ?>');
</script>
<?php

    // ----- Display the childs
    $v_nb_childs = sizeof($p_list[$p_index]['child_list']);
    TrFctMessage(__FILE__, __LINE__, 4, "Node '".$p_list[$p_index]['filename']."' has ".$v_nb_childs." childs");
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

      AppPhpzipZipListTreeItem($p_zip, $p_list, $p_list[$p_index]['child_list'][$i], $v_prefix, $i==($v_nb_childs-1));
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipZipAddList()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipZipAddList($p_archive, $p_file, $p_type, $p_dir, $p_stored_dir)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipZipAddList", "$p_archive, $p_file, $p_type, dir='$p_dir', stored_dir='$p_stored_dir'");

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

      // ----- Create the Object
      $v_zip = new PclZip($p_archive);

      // ----- Recuperate the file list
      $v_list_result = $v_zip->add($v_list, $v_add_dir, $v_remove_dir);
      if (sizeof($v_list_result) != 0)
      {
        $v_nb_added = 0;

        // ----- Display HTML header
        AppPhpzipHeader($v_zip->zipname);

        echo "<table width=100% border=0 cellspacing=0 cellpadding=0>";
        echo "<tr bgcolor=$g_title_bg>";
        echo "<td><div align=center><font face=$g_font size=$g_title_size color=$g_title_color>".Translate("Ajouter")."</font></div></td>";
        echo "</tr>";
        echo "<tr bgcolor=$g_text_bg><td><font face=$g_font size=$g_text_size color=$g_text_color><p><br><div align=center>";
        echo Translate("Etat de l'ajout des fichiers");
        echo " :</div></p></font>";
        echo "</td></tr>";
        echo "</table>";

        // TBC ....
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
          PclTraceFctMessage(__FILE__, __LINE__, 2, "Display file : ".$v_list_result[$i]['stored_filename']);
          PclTraceFctMessage(__FILE__, __LINE__, 2, "status file : ".$v_list_result[$i]['status']);
          echo "<tr bgcolor=$g_text_bg><td width=1 bgcolor=$g_title_bg></td><td width=10>&nbsp</td>";
          echo "<td><font face=$g_font size=$g_text_size color=$g_text_color>".$v_list_result[$i]['stored_filename']."</font></td>";
          echo "<td width=10>&nbsp</td><td><font face=$g_font size=$g_text_size color=$g_text_color>";
          if ($v_list_result[$i]['status']=="ok")
          {
            echo "Ok";
            $v_nb_added++;
          }
          else
            echo "</font><font face=$g_font size=$g_error_size color=red>Error : '".$v_message[$v_list_result[$i]['status']]."'";
          echo "</font>";
          echo "</td><td width=10>&nbsp</td><td width=1 bgcolor=$g_title_bg></td></tr>";
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
        $v_message = Translate("Erreur lors de l'ajout de la liste dans l'archive PhpZip")." \"".$p_zip->zipname."\".";

        // ----- Display status
        AppPhpzipStatus("Ajouter", $v_zip->zipname, "NOK", $v_message, Translate("Erreur").".");
      }

      // ----- Clean
      unset($v_list);
    }

    else
    {
      // ----- Set an error string for the footer
      $v_message = Translate("Impossible de lire la liste des fichiers.");

      // ----- Display HTML page
      AppPhpzipStatus("Ajouter", $p_zip->zipname, "NOK", $v_message, Translate("Erreur").".");
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipZipExtractByIndexAsk()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipZipExtractByIndexAsk($p_archive, $p_index)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipZipExtractByIndexAsk", "archive='$p_archive', indext='$p_index'");

    // ----- Create the zip object
    $v_zip = new PclZip($p_archive);

    // ----- Get list of file
    $v_list = $v_zip->listContent();

    // ----- Parse index
    $v_token = explode('-', $p_index);
    if (sizeof($v_token)==1)
      $v_token[1]=$v_token[0];
    TrFctMessage(__FILE__, __LINE__, 2, "Parsing index '$v_token[0]' and '$v_token[1]'");

      // ----- Look for file
      if (sizeof($v_list))
      {
        // ----- Display HTML header
        AppPhpzipActionHeader($v_zip->zipname, Translate("Extraire"));

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
            echo "<img src='$g_images_dir/".AppPhpzipExtensionImageZip($v_list[$i])."' border='0' width='16' height='16' align='absmiddle'>";
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
        echo "<input type=text id=\"id_path\" name=a_path size=15 maxlength=60 value=./>";
        //echo " <INPUT TYPE='BUTTON'  value='".Translate('Parcourir')." ...'></font>";

        echo "<script language='javascript' src='pcsexplorer/pcsexplorer.js'></script>";

        // ----- Here I should use something like the last opened directory ...
        // TBC
        global $PATH_INFO;
        echo " <INPUT TYPE=button name=bt value='".Translate('Parcourir')." ...' ";
//        echo "onClick='PcjsOpenExplorer(\"pcsexplorer.php\", \"forms.formulaire.a_path.value\", \"type=dir\", \"calling_dir=".dirname($PATH_INFO)."\", \"start_dir=.\")'";
        echo "onClick='PcjsExplorer(\"target=id_path\", \"type=dir\", \"result_ref_dir=".dirname($PATH_INFO)."\", \"start_dir=.\")'";
        echo ">";

        // ----- Ask for remove of directory
        $v_subpath = explode("/", $v_list[$v_token[0]]['filename']);
        $v_nb = sizeof($v_subpath);
        if (!$v_list[$v_token[0]]['folder'])
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
        echo "<input type=hidden name=a_archive value=".$v_zip->zipname.">";
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
        AppPhpzipStatus("Extraire", $v_zip->zipname, "NOK", $v_message, Translate("Erreur").".");
      }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipZipExtractByIndex()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipZipExtractByIndex($p_archive, $p_index, $p_path, $p_remove_dir)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipZipExtractByIndex", "archive='$p_archive', indext='$p_index', path='$p_path', remove_dir='$p_remove_dir'");

    // ----- Create the object
    $v_zip = new PclZip($p_archive);

    // ----- Extract the files
    $v_list_result = $v_zip->extractByIndex($p_index, $p_path, $p_remove_dir);
    if ((is_array($v_list_result)) && (sizeof($v_list_result) != 0))
    {
      // ----- Display HTML header
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

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------


  // --------------------------------------------------------------------------------
  // Function : AppPhpzipZipDeleteByIndex()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipZipDeleteByIndex($p_archive, $p_index)
  {
    $v_result = 1;
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipZipDeleteByIndex", "archive='$p_archive', index='$p_index'");

    // ----- Create the object
    $v_zip = new PclZip($p_archive);

        // ----- Delete the files
        $v_list_result = $v_zip->deleteByIndex($p_index);
        if (sizeof($v_list_result) != 0)
        {
//          if ($p_message == "")
//            $p_message = Translate("Suppression terminée.");
//          AppPhpzipListTar($p_archive, $v_list_result, $p_message);


          // ----- Display HTML header
          AppPhpzipActionHeader($p_archive,  Translate("Etat de la suppression des fichiers"));

          /*
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
          */

          echo "<table border=0 cellspacing=0 cellpadding=0 align=center>";
          echo "<tr height=65><td>&nbsp;</td></tr>";
          echo "</table>";

?>
<div id="Layer2" style="position:absolute; width:300px; height:232px; z-index:1; overflow: auto; left: 20px; top: 85px; background-color: #CCCCCC; border: 1px none #CCCCCC">
<?

          echo "<table width=100% height=100% border=0 cellspacing=0 cellpadding=0 align=left>";
          echo "<tr bgcolor=CCCCCC><td align=center>";
          echo "<font face=$g_font size=$g_text_size color=$g_text_color>".Translate("Suppression terminée.")."</font>";
          echo "</td></tr>";
/*

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
          */
          echo "</table>";

?>
</div>
<?

          echo "<table border=0 cellspacing=0 cellpadding=0 align=center>";
          echo "<tr height=232><td>&nbsp;</td></tr>";
          echo "</table>";

          //echo "echo<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
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

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : AppPhpzipExtensionImageZip()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function AppPhpzipExtensionImageZip($p_header)
  {
    $v_image = "file01-16.gif";
    global $g_config_file; include ($g_config_file);

    TrFctStart(__FILE__, __LINE__, "AppPhpzipExtensionImageZip", "header='".$p_header['filename']."'");

    // ----- Look for directory
    if ($p_header['folder'])
    {
      $v_image = "folder01-16.gif";
    }
    else
    {
      $v_image = AppPhpzipExtensionImage($p_header['filename']);
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_image);
    return $v_image;
  }
  // --------------------------------------------------------------------------------


    // Check is path is included in dir (so it is a file or subdir)
  // --------------------------------------------------------------------------------
  // Function : PhpZipPathIsIncluded()
  // Description :
  //
  // --------------------------------------------------------------------------------
  function PhpZipPathIsIncluded($p_path, $p_dir)
  {
    $v_result = false;
    TrFctStart(__FILE__, __LINE__, "PhpZipPathIsIncluded", "path='".$p_path."', dir='".$p_dir."'");

    // ----- Explode path by directory names
    $v_list_path = explode('/', $p_path);
    $v_list_dir = explode('/', $p_dir);

    // ----- If the path is smaller it can not be included
    if (($v_size = sizeof($p_dir)) > sizeof($p_path))
    {
      $v_result = false;
    }
    else
    {
      // ----- Set default answer
      $v_result = true;

      // ----- Study directories from last to first
      for ($i=0; ($i<$v_size && $v_result); $i++)
      {
        if ((($v_list_path[$i]=='') || ($v_list_dir[$i]=='')) && ($i != 0)
            && ($v_list_path[$i] != $v_list_dir[$i]))
        {
          $v_result = false;
        }
      }
    }

    // ----- Return
    TrFctEnd(__FILE__, __LINE__, $v_result);
    return $v_result;
  }
  // --------------------------------------------------------------------------------



?>
