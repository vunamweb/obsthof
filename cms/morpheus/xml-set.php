<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # # 
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
# edit 27.11.2006                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # # 

/*
		<main id="3" hasDivider="1" extraXOffset ="20">
			<name>Unternehmen</name>
			<link>http://www.pixel-dusche.de</link>

			<submenu>
				<sub id="0">
					<name>Profil</name>
					<link>http://www.pixel-dusche.de</link>
				</sub>
			</submenu>	
		</main>
*/
// var auslesen
//
$xml = '<?xml version="1.0" encoding="UTF-8"?>
<data>
';
$xml_name = $kun_bilder_arr[$pid];
//


// PRODUKT
$sql	= "SELECT * FROM morp_kun_produkt WHERE pid=$pid";
$res 	= safe_query($sql);
$row 	= mysqli_fetch_object($res);
$nmde	= $row->namede;
$nmen	= $row->nameen;
$txde	= $row->detailde; 
$txen	= str_replace("’", "'", $row->detailen); 

$xml 	.= '
	<copy>
		<de><![CDATA['.utf8_encode($txde).']]></de>
		<en><![CDATA['.utf8_encode($txen).']]></en>
	</copy>

	<pics>';

// GALERIE
$sql	= "SELECT * FROM morp_kun_images WHERE pid=$pid ORDER BY sortierung";
$res 	= safe_query($sql);

while ($row = mysqli_fetch_object($res)) {
	$img	= $row->image;
	if ($img) $xml 	.= '
		<item>bilder/'.$xml_name.'/'.$img.'</item>';
}

$xml 	.= '
	</pics>
	
	<tooltip>';

// TOOLTIPP - HOTSPOT
$sql	= "SELECT * FROM morp_kun_hotspot WHERE pid=$pid";
$res 	= safe_query($sql);

while ($row = mysqli_fetch_object($res)) {
	$img	= $row->image;
	$xpos	= $row->xpos;
	$ypos	= $row->ypos;
	$xposO	= $row->xposo;
	$yposO	= $row->yposo;
	$align	= $row->align;
	$textde	= $row->textde;
	$texten	= str_replace("’", "\'", $row->texten);
	$width	= $row->weite;
	$bg		= $row->bg;

	if ($img) $xml 	.= '
	<item xpos="'.$xpos.'" ypos= "'.$ypos.'" offsetX= "'.$xposO.'" offsetY = "'.$yposO.'" align= "'.$align.'" width= "'.$width.'" bg="'. ($bg ? $bg : "NONE") .'" img="bilder/'.$xml_name.'/tooltip/'.$img.'">
		<de><![CDATA['.utf8_encode($textde).']]></de>
		<en><![CDATA['.utf8_encode($texten).']]></en>
	</item>';
}


$xml 	.= '
</tooltip>

<icon>';

// TOOLTIPP - HOTSPOT
$sql	= "SELECT * FROM morp_kun_icons i, morp_kun_icons_zuord z WHERE i.item=z.item AND pid=$pid";
$res 	= safe_query($sql);

while ($row = mysqli_fetch_object($res)) {
	$item	= $row->item;

	if ($img) $xml 	.= '
	<item id="'.$item.'"></item>';
}



$xml 	.= '
</icon>
	
</data>
';

save_data("../xml/".$xml_name.".xml",$xml,"w");


?>
