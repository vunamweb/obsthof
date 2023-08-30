<?php
/* pixel-dusche.de */

global $fileID, $lastUsedTemplateID, $anker, $class, $farbe, $tabstand, $anzahlOffenerDIV, $templateIsClosed, $text_rechts;

$fileID = basename(__FILE__, '.php');
$lastUsedTemplateID = $fileID;
$templateIsClosed=1;

if(!isset($_SESSION["kat"])) $_SESSION["kat"]=0;

// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + +
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + +
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + +
// TEMPLATE

// print_r($_GET);
// print_r($_SESSION);

$blog = isset($_GET["nid"]) ? $_GET["nid"] : 0;
$kat = isset($_GET["kat"]) ? $_GET["kat"] : 0;
	
if($blog) {
	$kat = $_SESSION["kat"];
} else if($kat) {
	$_SESSION["kat"] = $kat;	
} else if (!$blog && !$kat) {
	$kat = 0;
	$_SESSION["kat"] = $kat;	
}

$n = 0;

// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
// list of all categories
$sql = "SELECT * FROM morp_blog_kat_assign t1, morp_blog_kat t2 WHERE t1.fBlogKatID=t2.fBlogKatID GROUP BY fKat ORDER BY fKat";
$res = safe_query($sql);

$liste = '<li><a href="'.$dir.$navID[5].'"'.(!$kat ? ' class="active"' : '').'>Alle</a></li>';
while ($row = mysqli_fetch_object($res)) {
	$liste .= '<li><a href="'.$dir.$navID[5].eliminiere(strtolower($row->fKat)).'+'.$row->fBlogKatID.'/#blog"'.($row->fBlogKatID==$kat ? ' class="active"' : '').'>'.$row->fKat.'</a></li>';
}	
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 

// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
// last or selected blog
if($kat) {
	$sql = "SELECT * FROM morp_blog_kat_assign t1, morp_blog t2 WHERE t1.fBlogID=t2.fBlogID AND t1.fBlogKatID=$kat GROUP BY t1.fBlogID ORDER BY fDatum DESC";
	$res = safe_query($sql);	
} else {
	$sql = "SELECT * FROM morp_blog WHERE 1 ORDER BY fDatum DESC";
	$res = safe_query($sql);
}
$x = 0;
$seiten = '';
while ($row = mysqli_fetch_object($res)) {
	$x++;
	$fBlogID = $row->fBlogID;
	
	if(!$blog && $x == 1) $blog_text = '
		<h1>'.$row->fTitle.'</h1>
		'.$row->fText.'';
	else if($blog && $blog == $fBlogID) $blog_text = '
		<h1>'.$row->fTitle.'</h1>
		'.$row->fText.'';
		
	$seiten .= '<li><a href="'.$dir.$navID[5].'thema/'.eliminiere(strtolower($row->fTitle)).'+'.$row->fBlogID.'/#blog"'.($row->fBlogID==$blog ? ' class="active"' : '').'">'.$x.'</a><div class="vl"></div></li>';
}	
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 


$template = '
	<section id="blog">
		<div class="container">
    		<div class="row">
				<div class="new-1">
					<div class="row">
						<ul class="kat">
						'.$liste.'
						</ul>							
					</div>
				</div>

				<div class="new-2 blog">
					'.$blog_text.'
				
					<ul class="pages">
						'.$seiten.'					
					</ul>
				</div>
			</div>
		</div>
	</section>
';



// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + +
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + +
// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + +
// END TEMPLATE



$anzahlOffenerDIV = 0;

$class = '';
$farbe = '';
$grIMG = '';
$text_rechts = '';

