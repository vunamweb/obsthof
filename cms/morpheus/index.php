<?php
session_start();
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# björn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #

include("cms_include.inc");


echo '<div id=content_big class=text>

<h1>Willkommen im CMS Morpheus</h1>





<h2 style="margin:2em 0 .5em;">Direkt zu</h2>

<p><a href="navigation.php"><i class="fa fa-chevron-right"></i> &nbsp; Seitenverwaltung</a></p>
<p><a href="template.php"><i class="fa fa-chevron-right"></i> &nbsp; Text Vorlagen</a></p>
<p><a href="pdf_group.php?sec=off"><i class="fa fa-chevron-right"></i> &nbsp; Download Dokumente Vorlagen</a></p>


<p>&nbsp;</p>
<p><a href="user.php"><i class="fa fa-chevron-right"></i> &nbsp; Verwaltung CMS Login Mitarbeiter</a></p>
<p>&nbsp;</p>


<h2 style="margin:3em 0 .5em;">Download Handbuch PDF</h2>
<p><a href="Handuch Morpheus CMS IV silverton.pdf"><i class="fa fa-cloud-download"></i> &nbsp; Download</a></p>


<!--
<br>
<img src="../images/logo.png" alt="" width="192" height="44" border="0">

</p>

<p>&nbsp;</p>

<h2>Seitenverwaltung</h2>
<ul>
	<li><a href="navigation.php">Seiten bearbeiten &raquo;</a></li>
</ul>

<h2>News</h2>
<ul>
	<li><a href="content.php?edit=24&db=content&back=ebene;:;1;;p_0;:;0;;n_0;:;Hauptnavigation&sprache=2">STARTSEITE bearbeiten &raquo;</a><br>&nbsp;</li>

	<li><a href="news.php?ngid=1&sprache=1">News DEUTSCH &raquo;</a></li>
	<li><a href="news.php?ngid=3&sprache=2">News ENGLISH &raquo;</a></li>
</ul>

<h2>Referenzen Verwaltung</h2>
<ul>
	<li><a href="customer.php">Kunden / Refrenzen anlegen, editieren &raquo; </a></li>
</ul>

<h2>Handbuch PDF</h2>
<ul>
	<li><a href="handbuch_morpheus.pdf">Download &raquo; </a></li>
</ul>

<p>&nbsp;</p>

<h2>Formate</h2>
<table>
	<tr>
		<td>Teaser: <strong>225px x 90px</strong></td>
	</tr>
	<tr>
		<td><img src="../images/euroshop.jpg" width="225" height="90" alt=""><br>&nbsp;</td>
	</tr>
	<tr>
		<td nowrap>Große emotionale Fotos: <strong>484px x 310px</strong></td>
	</tr>
		<td><img src="../images/center.jpg" alt="" width="484" height="310" border="0"><br>&nbsp;</td>
	</tr>
	<tr>
		<td nowrap>Weitere Bilder nach Möglichkeit in Breite: <strong>484px</strong><br>
		<img src="../images/euroshop-foto.jpg" alt="" width="484" height="111" border="0"><br>&nbsp;</td>
	</tr>
	<tr>
		<td>Galerie: <strong>750px x 440px</strong><br>
		<img src="../images/flash.jpg" alt="" width="775" height="440" border="0"></td>
	</tr>
</table>
-->

</div>
';

?>

<?php
include("footer.php");
?>