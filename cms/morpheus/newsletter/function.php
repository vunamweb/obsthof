<?php
global $no_str,$nw_str,$search_str, $dir;


# mail('post@pixel-dusche.de', 'Apothekerkammer', 'function.php aus newsletter set gestartet');

// $no_str = array("/\//", "/\+/", "/ä/", "/ö/", "/ü/", "/Ä/", "/Ö/", "/Ü/", "/ß/", "/ \& /", "/   /", "/  /", "/ /", "/\+\+\+/", "/\+\+/", "/\"/", "/\./", "/\,/", "/\´/", "/\`/", "/\'/", "/\?/");
// $nw_str = array("-", "-", "ae", "oe", "ue", "Ae", "Oe", "Ue", "ss", "-", "-", "-", "-", "-", "-", "", "", "", "", "", "");
// $search_str = ereg_replace("/", "", implode("|", $no_str));

function read_dat($name) {
	$data = fopen($name,"r");

	while (!feof($data)) {
		$val .= trim(fgets($data,4096)) ."\n";
	}

	fclose($data);

	return $val;
} // read_infobox


function save_dat($datei,$data,$art)  {
	#echo "$datei,$data,$art<br>";
	$write = fopen($datei,$art);	   		//oeffne datei zum schreiben der daten
	if ($write!=0) fwrite($write, $data);	//write data
	fclose($write);
}


function create_html_doc($text, $folder, $file) {
	global $img_pfad, $dir;

	// $img_pfad = $dir."img/".$folder."/";
	$img_pfad = $dir."img/";

	include ($file.".php");

	return $html;
}

function getImg ($id) {
	$sql = "SELECT imgname FROM image WHERE imgid=$id";
	$res = safe_query($sql);
	$row = mysqli_fetch_object($res);
	return $row->imgname;
}


function getEdmData($row, $folder, $newfolder) {
	global $dir, $files;

	$img_pfad_social = $dir."img/";
	$zip_pfad = "img/".$folder."/";
	$img_pfad = $img_pfad_social.$folder."/";

	$arr = array("nlname", "nlrubrik", "nlabstr", "nlmail", "nlmailname", "nllink", "nllink2", "text", "textweb", "text2", "text3", "nlimg1", "nlimg2", "nlimg3", "nlsign", "nlcta", "nlcta2", "soc1", "soc2", "soc3", "soc4", "soc5", "nlgruppe", "pdf1", "pdf2", "pdf3", "platzhalter", "banner", "versendet", "nlart", "nlsort",
		"img11", "bu11",
		"img12", "img13", "img14", "img15", "img16", "img17", "img18", "bu12", "bu13", "bu14", "bu15", "bu16", "bu17", "bu18",
		"img19", "bu19",
		"img20", "bu20",
		"img21", "bu21",
		"img22", "bu22",
		"img23", "bu23",
		"img24", "bu24",
		"img25", "bu25",
		"img26", "bu26",
	);

	$sql = '';
	$ct = count($arr);
	$x = 0;

	foreach($arr as $val) {
		$x++;
		$tmp = addslashes($row->$val);
		$sql .= $val."='".$tmp.($x <= $ct ? "', " : "'");
	}

	/******* IMAGES kopieren ******/
	$arr = array("nlimg1", "nlimg2", "nlimg3", "nlcta", "nlcta2", "nlsign", "img11", "img12", "img13", "img14", "img15", "img16", "img17", "img18", "img19", "img20", "img21", "img22", "img23", "img24", "img25", "img26");

	foreach($arr as $val) {
		$tmp = $row->$val;
		copy("../img/".$folder."/".$tmp, "../img/".$newfolder."/".$tmp);
	}
	/******* ************** ******/

	return $sql;
}

function getContWeb($res, $folder) {
	global $dir, $files, $extratext, $social_image, $social_desc, $social_title, $navID;

	$img_pfad_social = $dir."img/";
	$zip_pfad = "img/".$folder."/";
	$img_pfad = $img_pfad_social.$folder."/";
	$n = 0;

	while($row = mysqli_fetch_object($res)) {
		$thisid = $row->nlcid;
		$lnk = $row->nllink;
		$lnk2 = $row->nllink2;
		$texte = $row->textweb;
		$text2 = $row->text2;
		$text3 = nl2br($row->text3);
		$img1 = $row->nlimg1;
		$img2 = $row->nlimg2;
		$sign = $row->nlsign;
		$pdf = $row->nlimg3;
		$cta = $row->nlcta;
		$cta2 = $row->nlcta2;
		$s1 = $row->soc1;
		$s2 = $row->soc2;
		$s3 = $row->soc3;
		$s4 = $row->soc4;
		$s5 = $row->soc5;
		$art = $row->nlart;
		$rubrik = $row->f1name;

		$img11 = $row->img11;
		$bu11 = $row->bu11;
		$img12 = $row->img12;
		$bu12 = $row->bu12;
		$img13 = $row->img13;
		$bu13 = $row->bu13;
		$img14 = $row->img14;
		$bu14 = $row->bu14;
		$img15 = $row->img15;
		$bu15 = $row->bu15;
		$img16 = $row->img16;
		$bu16 = $row->bu16;
		$img17 = $row->img17;
		$bu17 = $row->bu17;
		$img18 = $row->img18;
		$bu18 = $row->bu18;
		$img19 = $row->img19;
		$bu19 = $row->bu19;
		$img20 = $row->img20;
		$bu20 = $row->bu20;
		$img21 = $row->img21;
		$bu21 = $row->bu21;
		$img22 = $row->img22;
		$bu22 = $row->bu22;
		$img23 = $row->img23;
		$bu23 = $row->bu23;
		$img24 = $row->img24;
		$bu24 = $row->bu24;
		$img25 = $row->img25;
		$bu25 = $row->bu25;
		$img26 = $row->img26;
		$bu26 = $row->bu26;

		$webvis = $row->webvis;

		$social_desc = strip_tags($row->text);
		$social_desc = htmlspecialchars($social_desc);
		$social_desc = preg_replace(array("/\n/"), array(" "), $social_desc);
		$social_title = htmlspecialchars($row->nlname);

		$whatsapp = preg_replace(array("/ /"), array("%20"), $social_title).'%20%20Url:%20'.$dir.$navID[132].preg_replace("/\+/", "-", eliminiere($social_title)).'/'.$thisid.'/';

		$n++;

		if($art==20 && $text2) $extratext=$text2;

		if($lnk) $lnk = '<p><a href="'.$lnk.'"><i class="fa fa-external-link-square"></i> mehr erfahren<!--'.$lnk.'--></a></p>';

		$raus = array('/#ziel#/', '/#txt#/', '/#txt2#/', '/#txt3#/', '/#img1#/', '/#tn#/', '/#img2#/', '/#sign#/', '/#rubrik#/', '/#anker#/');
		$rein = array($lnk, $texte, $text2, $text3, $img_pfad.$img1, $img_pfad.$tn, $img_pfad.$img2, '<img src="'.$img_pfad.$sign.'" class="sign" alt="">', '<a name="'.$rubrik.'"></a><a name="'.$rubrik.$n.'"></a>'.$rubrik, '<a name="'.$rubrik.'"></a><a name="'.$rubrik.$n.'"></a>');

		/* * * ZIP * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
		if($img1) $files[] = $zip_pfad.$img1;
		if($img2) $files[] = $zip_pfad.$img2;
		if($tn) $files[] = $zip_pfad.$tn;
		if($cta) $files[] = $zip_pfad.$cta;
		if($cat2) $files[] = $zip_pfad.$cta2;

		$social_image = $img_pfad.$img1;

		/* * * ____ ZIP ____ * * * * * * * * * * * * * * * * * * * * * * * * * * */

		for($i=11; $i<=26; $i++) {
			$tmp="img".$i;
			$bu = "bu".$i;
			if($row->$tmp) {
				$raus[] = '/#img'.$i.'#/';
				$rein[] = '<div style="margin: 24px 0 24px 0;font-size:10px;"><img src="'.$img_pfad.$row->$tmp.'" alt="" /><br/>'.$row->$bu.'</div>';
			}
			else {
				$raus[] = '/#img'.$i.'#/';
				$rein[] = '';
			}
		}

		if($pdf) {
			$raus[] = '/#pdf#/';
			$rein[] = '<p style="margin: 24px 0 24px 0;"><a href="'.$img_pfad.$pdf.'"><i class="fa fa-file-o"></i> PDF Download</a></p>';
		}
		else {
			$raus[] = '/#pdf#/';
			$rein[] = '';
		}

		$raus[] = '/#soc#/';
		if($webvis) $rein[] = '<p class="mobileOn"><a href="whatsapp://send?text='.$whatsapp.'" class="whatsapp"><i class="fa fa-whatsapp large" aria-hidden="true"></i></a></p>

		<div class="teilen"><a href="https://www.facebook.com/sharer/sharer.php?u='.preg_replace(array('/\//', '/:/'), array("%2F", "%3A"), $dir.$navID[132]).preg_replace("/\+/", "-", eliminiere($social_title)).'%2F'.$thisid.'%2F" target="_blank"><span class="share_text"><i class="fa fa-facebook"></i> Teilen</span></a></div>
';
		else $rein[] = '';

// 		<div class="smfb"><iframe src="//www.facebook.com/plugins/share_button.php?href='.preg_replace(array('/\//', '/:/'), array("%2F", "%3A"), $dir.$navID[132]).preg_replace("/\+/", "-", eliminiere($social_title)).'%2F'.$thisid.'%2F&amp;layout=button" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px; width: 60px; float: left; display:block; " allowTransparency="true"></iframe><br style="clear:both;" /></div>

// <!--		<div class="smfb"><a href="'.$dir.$navID[132].preg_replace("/\+/", "-", eliminiere($social_title)).'/'.$thisid.'/">.</a></div> -->


		$sql = "SELECT * FROM morp_newsletter_template WHERE nltid=".$art;
		$rs = safe_query($sql);
		$rw = mysqli_fetch_object($rs);

		$html = $rw->nlthtmlweb;

		$html = '<article>'.preg_replace($raus, $rein, $html).'</article>';

		$getHTML .= $html;
	}

	return $getHTML;
}


function getContMailing($res, $folder) {
	global $dir, $files, $extratext, $testmail;

	$img_pfad_social = $dir."img/";
	$zip_pfad = "img/".$folder."/";
	$zip_pfad = "img/";
	$img_pfad = $img_pfad_social.$folder."/";
	$img_pfad = $img_pfad_social;
	$n = 0;

	while($row = mysqli_fetch_object($res)) {
		$lnk = $row->nllink;
		$lnk2 = $row->nllink2;
		$texte = $row->text;
		$text2 = $row->text2;
		//$text3 = nl2br(($row->text3));
		$text3 = $row->text3;
		$img1 = $row->nlimg1;
		$img2 = $row->nlimg2;
		$sign = $row->nlsign;
		$pdf = $row->nlimg3;
		$cta = $row->nlcta;
		$cta2 = $row->nlcta2;
		$s1 = $row->soc1;
		$s2 = $row->soc2;
		$s3 = $row->soc3;
		$s4 = $row->soc4;
		$s5 = $row->soc5;
		$art = $row->nlart;
		$rubrik = $row->f1name;
		$n++;

		if($art==20 && $text2) $extratext=$text2;

		// if(!$lnk) $lnk = 'https://www.apothekerkammer.de/service/lak+aktuell/lak+aktuell+ausgabe-'.$row->nlid.($testmail ? '-prev' : '').'/#'.$rubrik.$n;
		if(!$lnk) $lnk = '';
		else {
			$lnk = '<p style="margin:30px 0 0;"><a href="'.$lnk.'" class="btn" style="text-decoration:none;color:#fff;background:#0072BC;padding:8px 20px;font-family:Verdana;font-size: 13px;font-weight:bold;border-radius:6px;">'.$text3.'</a></p>';
		}

		$raus = array('/#link#/', '/#txt#/', '/#txt2#/', '/#txt3#/', '/#img1#/', '/#tn#/', '/#img2#/', '/#sign#/', '/#rubrik#/', '/#anker#/', '/src="img\//');
		$rein = array($lnk, $texte, $text2, nl2br(($text3)), $img_pfad.$img1, $img_pfad.$tn, $img_pfad.$img2, '<img src="'.$img_pfad.$sign.'" class="sign" alt="">', '<a name="'.$rubrik.'"></a>'.$rubrik, '<a name="'.$rubrik.$n.'"></a>', 'src="'.$img_pfad_social);

		/* * * ZIP * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
		#if($img1) $files[] = $zip_pfad.$img1;
		#if($img2) $files[] = $zip_pfad.$img2;
		#if($tn) $files[] = $zip_pfad.$tn;
		#if($cta) $files[] = $zip_pfad.$cta;
		#if($cat2) $files[] = $zip_pfad.$cta2;
		/* * * ____ ZIP ____ * * * * * * * * * * * * * * * * * * * * * * * * * * */


		#if($pdf) {
		#	$raus[] = '/#pdf#/';
		#	$rein[] = '<p style="margin: 24px 0 24px 0;"><a href="'.$img_pfad.$pdf.'">PDF Download</a></p>';
		#}
		#else {
			$raus[] = '/#pdf#/';
			$rein[] = '';
		#}


		$sql = "SELECT * FROM morp_newsletter_template WHERE nltid=".$art;
		$rs = safe_query($sql);
		$rw = mysqli_fetch_object($rs);

		$html = $rw->nlthtml;
		$html = preg_replace($raus, $rein, $html);

		$getHTML .= $html;
	}

	return $getHTML;
}

