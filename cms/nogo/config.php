<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de / björn t. knetter / post@pixel-dusche.de / frankfurt am main, germany
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
global $morpheus, $sign_gruppe, $lan;
$morpheus = array();

include("#*Z-z9xX/fingerweg.php");
$morpheus["code"]			= "pd?xVx!";
$morpheus["url"]			= "http://localhost/obsthof/cms/";
$morpheus["shopurl"]		= "https://www.morpheus-cms.de/shop/";
$morpheus["subFolder"] 		= '/obsthof/cms/';
$morpheus["dfile"]			= "morpheus_db.sql";
$morpheus["search_ID"]		= array("de"=>52, "en"=>"200" );

$morpheus["multilang"]		= 0;
$morpheus["kontaktID"]		= 6;
$morpheus["dfile"]			= "morpheus_db.sql";
$morpheus["home_ID"]		= array("de"=>1, "en"=>70 );
$morpheus["lan_arr"]		= array(1=>"de",  2=>"en" );
$morpheus["lan_nm_arr"]		= array("de"=>"Deutsch", "en"=>"English", );

$morpheus["img_size_news"]		= 450;
$morpheus["img_size_news_tn"]	= 120;
$morpheus["img_size_tn"]	= 35;
$morpheus["img_size_full"]	= 600;
$morpheus["img_size"]		= 600;
$morpheus["page-topic"]		= "";
$morpheus["publisher"]		= "";
$morpheus["foto"]			= 0;
$morpheus["imageName"]		= "stiftung_";
$morpheus["GaleryPath"]		= "stiftung";

	
$morpheus["imageFolder"] 	= 'image/';

$morpheus["mail_start"]		= '<style Type="text/css">
body { background-color: #ffffff; } p, h1, h2, h3, td, a { font-family:	Arial, Verdana; font-size:	12px; line-height: 	16px; color: #666666; margin: 0 0 1px 0; padding: 0; } p.small { font-size:	10px; line-height: 	14px; color: #888888; } td { padding:6px 6px 6px 0;	margin:0; border:none !important; } h1, h2  { font-weight: 	bold; margin: 0 0 10px 0; } h1  { font-size: 16px; font-weight: normal; line-height: 20px; margin: 0 0 12px 0; } table { width: 100%; border:none !important; margin-bottom:20px;} table td table td table td table { width:auto !important; } table td table td table td table td { width:auto !important; padding:6px 6px 0 0; } colgroup { display: none; }
</style>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td align="center">
	<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width: 600px">
		<tr><td align="center" style="background: #fff;" colspan="2">
			<img src="'.$morpheus["url"].'images/Logo_Melanie_Kuehl.svg" width="200" style="width:200px;height:auto;margin:10px auto 10px;" /></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>
';

$morpheus["mail_end"]		= '</td></tr>
<tr><td> 
<br/><br/>
<hr>
<br/><br/>
<p class="small">

#ADDRESS#

</p>
</td></tr>
</table></td></tr></table></center>';

// NEW array for SEARCH PAGE
// Array Lang-ID => navID *********************************************

