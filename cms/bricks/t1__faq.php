<?php
/* pixel-dusche.de */

global $wiki, $wiki_nav;

$table 		= 'morp_faq';
$nameField 	= "name";
$sortField 	= 'reihenfolge';



$sql = "SELECT * FROM `$table` WHERE 1 ORDER BY $sortField ASC";
$res = safe_query($sql);
$anz = mysqli_num_rows($res);
$x = 0;


while ($row = mysqli_fetch_object($res)) {
	$x++;
	$output .= '
<div class="box_content_items FAQ">
	<div class="row">
		<div class="col-xs-12 col-md-3 col-lg-4">
			<h2>'.$x.'. '.$row->name.'</h2>
		</div>
		<div class="col-xs-12 col-md-9 col-lg-8">
			'.$row->faq.'
		</div>
	</div>
</div>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "QAPage",
  "mainEntity": {
	"@type": "Question",
	"name": "'.$row->name.'",
	"text": "'.$row->faq.'",
  }
}
</script>


';


}

$wiki .= '</div>';

$morp = "FAQ Modul";

