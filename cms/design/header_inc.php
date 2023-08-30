<!DOCTYPE html>
<html lang="<?php echo $lan; ?>">
<?php $rand=rand(0, 9999); ?>
<?php global $socialImg; if(!$fbimage) $fbimage = $socialImg; else $fbimage = $img_pfad.$fbimage; if(!$fbimage) $fbimage = $dir.'images/Hi-Systems_Logo.svg'; ?>
<head>
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <title><?php echo $title; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo htmlspecialchars($desc); ?>">
    <meta name="keywords" content="<?php echo $keyw; ?>" />
    <?php if(!$_GET["nid"]) { ?>
	<link rel="canonical" href="<?php echo getUrl($cid); ?>" />
	<?php } ?>
	<!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="<?php echo utf8_encode($title); ?>">
    <meta itemprop="description" content="<?php echo htmlspecialchars($desc); ?>">
    <meta itemprop="image" content="<?php echo $fbimage; ?>">
    <!-- Open Graph data -->
    <meta property="og:title" content="<?php echo utf8_encode($title); ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="<?php echo substr($dir, 0, -1).$uri; ?>" />
    <?php if ($fbimage) { ?>
    <meta property="og:image" content="<?php echo $fbimage; ?>" />
    <?php } ?>
    <meta property="og:description" content="<?php echo htmlspecialchars($desc); ?>" />
    <meta property="og:site_name" content="<?php echo utf8_encode($title); ?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $dir; ?>favicon_package_v0.16/apple-touch-icon.png?v=1">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $dir; ?>favicon_package_v0.16/favicon-32x32.png?v=1">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $dir; ?>favicon_package_v0.16/favicon-16x16.png?v=1">
    <?php 
	 $size = $fbimage ? getimagesize($fbimage) : array(200,200); 
	?>
	<script type='application/ld+json' class='yoast-schema-graph yoast-schema-graph--main'>{"@context":"https://schema.org","@graph":[{"@type":"WebSite","@id":"<?php echo $dir; ?>#website","url":"<?php echo $dir; ?>","name":"<?php echo $morpheus["client"]; ?>","potentialAction":{"@type":"SearchAction","target":"<?php echo $dir; ?>?s={suche}","query-input":"required name=suche"}},{"@type":"ImageObject","@id":"<?php echo substr($dir, 0, -1).$uri; ?>#primaryimage","url":"<?php echo $fbimage; ?>","width":<?php echo $size[0]; ?>,"height":<?php echo $size[1]; ?>,"caption":"<?php echo $morpheus["client"]; ?>"},{"@type":"WebPage","@id":"<?php echo substr($dir, 0, -1).$uri; ?>#webpage","url":"<?php echo substr($dir, 0, -1).$uri; ?>","inLanguage":"de-DE","name":"<?php echo $title; ?>","isPartOf":{"@id":"<?php echo $dir; ?>#website"},"primaryImageOfPage":{"@id":"<?php echo $fbimage; ?>"},"datePublished":"2019-07-30T10:00:00+00:00","dateModified":"<?php echo $changeDat; ?>T12:00:00+00:00","description":"<?php echo $desc; ?>"}]}</script>
	<link rel="stylesheet" href="<?php echo $dir; ?>css/font-awesome.min.css?v=<?php echo $rand; ?>" type="text/css">
<?php 
	$css_imp .= file_get_contents('css/bootstrap.min.css');
	// $css_imp .= file_get_contents('css/datepicker.css');
    // $css_imp .= file_get_contents('css/swiper.min.css');
	// $css_imp .= file_get_contents('css/animate.min.css');
	// $css_imp .= file_get_contents('css/styles.css');
	// $css_imp .= file_get_contents('css/mobile.css');
	echo '<style>' . str_replace(array("url(../", "\n", "  ", "\t"), array("url(".$morpheus["subFolder"], " ", " ", " "), $css_imp) . '</style>';
?>
	<link rel="stylesheet" href="<?php echo $dir; ?>css/styles.css?v=<?php echo $rand; ?>" type="text/css">
	<link rel="stylesheet" href="<?php echo $dir; ?>css/mobile.css?v=<?php echo $rand; ?>" type="text/css">
<?php if($morpheus_edit) { ?>
	<link rel="stylesheet" href="<?php echo $dir; ?>css/edit.css?v=<?php echo $rand; ?>" type="text/css">
<?php } ?>
</head>

<body itemscope itemtype="https://schema.org/WebPage" class="pg<?php $ar = str_word_count($title, 1); echo $hn_id.' '.strtolower($ar[0]); ?> <?php echo $_SESSION["kontrast"] ? 'accessibility'.$_SESSION["kontrast"] : ''; ?> s<?php echo $_SESSION["fsize"] ? $_SESSION["fsize"] : 1; ?>" ref="<?php echo $_SESSION["fsize"] ? $_SESSION["fsize"] : 1; ?>">
    <a id="top"></a>

<?php 
//print_r($_SESSION); 
?>