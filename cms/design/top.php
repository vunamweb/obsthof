<?php
	include("nogo/nav_".$lan.".inc");
	if($sn2_id) { $nav = preg_replace(array("/hn".$hn_id."/", "/hn".$cid."/", "/dd".$cid."/", "/s".$cid."/", "/n".$hn_id."/"), array("active", "active", "active", "active", "active"), $nav); }
	else { $nav = preg_replace(array("/hn".$hn_id."/", "/dd".$cid."/", "/n".$hn_id."/"), array("active", "active", "active"), $nav); }



?>

<header>
	<div class="side-navbar <?php echo $mobile || $ipad ? '' : ' active-nav'; ?> d-flex justify-content-between flex-wrap flex-column" id="sidebar">
		<ul class="nav flex-column text-white w-100">
			<li class="heim"><a href="<?php echo $dir; ?>" class="nav-link h3 text-white my-2"><img src="<?php echo $dir; ?>images/Home_Melanie_Kuehl.svg" alt="Psychotherapie und Diagnostik Home" class="home"></a></li>
		
			<?php 
				echo $nav; 
			?>
					
			<li class="nav-item kontakt"><a href="<?php echo getUrl(6); ?>" class="btn btn-success">Kontakt</a></li>
		</ul>
		
	</div>
</header>

<main class="my-container<?php echo $mobile || $ipad ? '' : ' active-cont'; ?>">
	<!-- Top Nav -->
	<nav class="navbar navbar-light ">		
		<button class="navbar-toggler" id="menu-btn" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"  aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
	</nav>
	
	<div class="logo"><a href="<?php echo $dir; ?>"><img src="<?php echo $dir; ?>images/Logo_Melanie_Kuehl.svg" alt="Psychotherapie und Diagnostik für Kinder & Jugendliche und Coaching für Eltern"  class="img-fluid" /></a></div>

