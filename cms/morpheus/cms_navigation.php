<?php
error_reporting (0);
//error_reporting(E_ALL & ~E_DEPRECATED);
# # # # # # # # # # # # # # # # # # # # # # # # # # #
# www.pixel-dusche.de                               #
# bj&ouml;rn t. knetter                                  #
# start 12/2003                                     #
#                                                   #
# post@pixel-dusche.de                              #
# frankfurt am main, germany                        #
# # # # # # # # # # # # # # # # # # # # # # # # # # #
/*
$auths_arr = array(
20=>"Redaktion",
30=>"Dokumente / Uploads",
40=>"Dokumente / Uploads Gremien",
50=>"Mitglieder",
60=>"Mitarbeiter",
);
*/
# navigation
?>

<div class="row">
    <!-- uncomment code for absolute positioning tweek see top comment in css -->
    <!-- <div class="absolute-wrapper"> </div> -->
    <!-- Menu -->
    <div class="side-menu">

    <nav class="navbar navbar-default" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <div class="brand-wrapper">
            <!-- Hamburger -->
            <button type="button" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Brand -->
            <div class="brand-name-wrapper">
                <a class="navbar-brand" href="index.php">
                    <img src="images/Logo-Morpheus.svg" class="logo" alt="" />
                </a>
            </div>
        </div>
    </div>

    <!-- Main Menu -->
    <div class="side-menu-container">
        <ul class="nav navbar-nav">
				<?php
				if(in_array(10,$auths) || $admin) { ?>

					<li></li>

					<li class="panel panel-default <?php global $redaktion_in; echo $redaktion_in; ?>">
					    <a data-toggle="collapse" href="#dropdown-lvl1">
					        <i class="fa fa-sitemap "></i> Redaktion Text <span class="caret"></span>
					    </a>
					    <div id="dropdown-lvl1" class="panel-collapse collapse <?php global $redaktion_in; echo $redaktion_in; ?>">
					        <div class="panel-body">
					            <ul class="nav navbar-nav">
									<li<?php echo $nav_active; ?>><a href="navigation.php" title="Links verwalten und Content zuweisen"><i class="fa fa-navicon "></i> Seitenverwaltung</a></li>
									<li<?php echo $news_active; ?>><a href="news.php?formatSelect" title="news verwalten"><i class="fa fa-rss-square "></i> News</a></li>
									<!-- <li><a href="news.php?formatSelect=2,3,4" title="news verwalten"><i class="fa fa-rss-square "></i> Startseite Links</a></li> -->
 									<li<?php echo $vorl_active; ?>><a href="template.php" title="Text-Vorlagen verwalten"><i class="fa fa-puzzle-piece "></i> Text Vorlagen</a></li> 									 
<!-- 									<li><a href="_keywcreate.php" title="backup morpheus"><i class="fa fa-search "></i> Suchindex erstellen</a></li> -->
					            </ul>
					        </div>
					    </div>
					</li>

					<li class="panel panel-default <?php global $images_in; echo $images_in; ?>">
						<a data-toggle="collapse" href="#dropdown-lvl6">
							<i class="fa fa-camera-retro "></i> Bilder <span class="caret"></span>
						</a>
						<div id="dropdown-lvl6" class="panel-collapse collapse <?php global $images_in; echo $images_in; ?>">
							<div class="panel-body">
								<ul class="nav navbar-nav">
									<li><a href="image.php" title="Bilder / Fotos verwalten"><i class="fa fa-camera-retro "></i> Bildarchiv</a>
<!--
									<li<?php global $ms_active; echo $ms_active; ?>><a href="morp_masterslider.php" title="Master Slider verwalten"<?php global $ms_active; echo $ms_active; ?>><i class="fa fa-camera-retro "></i> Master Slider</a>
-->
									<li><a href="bild_galerie.php?ggid=1&name=<?php echo $morpheus["GaleryPath"]; ?>&db=morp_cms_galerie_name" title="Galerien verwalten"><i class="fa fa-th"></i> Bilder Galerien</a></li>
								</ul>
							</div>
						</div>
					</li>

				<?php	} ?>
				<?php if(in_array(21,$auths) || $admin) { ?>
				 	<li><a href="formular.php" title="Formulare verwalten"><i class="fa fa-tasks "></i> Formulare</a></li>
					 <li><a href="morp_sortenlexikon.php" title="Sortenlexikon verwalten"><i class="fa fa-tasks "></i> Sortenlexikon</a></li>
					 <li><a href="morp_faq.php" title="FAQ"><i class="fa fa-tasks "></i> FAQ</a></li>
				<?php	} ?>


				<?php if(in_array(30,$auths) || $admin) { ?>
					<li><a href="pdf_group.php?sec=off" title="PDF\'s verwalten"><i class="fa fa-file-pdf-o "></i> Downloads</a></li>	
					
					<li class="panel panel-default">
					    <a data-toggle="collapse" href="#dropdown-lvl4">
					        <i class="fa fa-cog "></i> Config <span class="caret"></span>
					    </a>
					    <div id="dropdown-lvl4" class="panel-collapse collapse">
					        <div class="panel-body">
					            <ul class="nav navbar-nav">
									<li><a href="sprachdatei.php"><i class="fa fa-bullhorn"></i> Text Templates</a></li>
									<li><a href="morp_settings.php" title="settings kunden"><i class="fa fa-users "></i> Einstellungen Kunde</a></li>
									<li><a href="morp_settings_css.php" title="settings css"><i class="fa fa-paint-brush "></i> Einstellungen CSS</a></li>
									<li><a href="config_css.php?datei=../css/css.css" title="config morpheus"><i class="fa fa-users "></i> CSS stylesheet </a></li>
									<li><a href="morp_settings_fonts.php" title="settings fonts"><i class="fa fa-tags "></i> Einstellungen Fonts</a></li>
									<li><a href="morp_icons.php" class="nav" title="Icons konfigurieren"><i class="fa fa-star-half-o"></i> Icons konfigurieren</a></li>
									<li><a href="morp_colors.php" class="nav" title="Icons konfigurieren"><i class="fa fa-eyedropper"></i> Farben konfigurieren</a></li>
					            </ul>
					        </div>
					    </div>
					</li>

				<?php	} ?>

				<?php if($admin) { ?>
				<!-- 	<li><a href="user.php" title="Assets" style=""><i class="fa fa-lock"></i> Zugänge</a></li>-->
				<!--	<li><a href="sitemap_erstelle.php" title="index erstellen"><i class="fa fa-sort-alpha-asc"></i> Sitemap/Index erstellen</a></li>-->

					
										
					<li class="panel panel-default">
						<a data-toggle="collapse" href="#dropdown-lvl2">
							<i class="fa fa-rocket "></i> Admin <span class="caret"></span>
						</a>
						<div id="dropdown-lvl2" class="panel-collapse collapse">
							<div class="panel-body">
								<ul class="nav navbar-nav">
									<li><a href="user.php" title="backup morpheus"><i class="fa fa-users "></i> Userverwaltung</a></li>
									<!-- <li><a href="user_intranet.php" title="backup morpheus"><i class="fa fa-users "></i> User Register</a></li> -->
									<li><a href="config.php?datei=../nogo/config.php" title="config morpheus"><i class="fa fa-users "></i> Config Editor</a></li>
								</ul>
							</div>
						</div>
					</li>

				<?php	} ?>

				<li><a href="backup_morpheus.php" title="backup morpheus"><i class="fa fa-cloud-download "></i> Backup Daten</a></li>
				<li><a href="logout.php" title="logout morpheus"> Logout</a></li>
        </ul>
    </div><!-- /.navbar-collapse -->
</nav>

    </div>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="side-body">



<?php

/*

	            <li class="panel panel-default">
                <a data-toggle="collapse" href="#dropdown-lvl1">
                    <span class="glyphicon glyphicon-user"></span> Sub Level <span class="caret"></span>
                </a>

                <!-- Dropdown level 1 -->
                <div id="dropdown-lvl1" class="panel-collapse collapse">
                    <div class="panel-body">
                        <ul class="nav navbar-nav">
                            <li><a href="#">Link</a></li>
                            <li><a href="#">Link</a></li>
                            <li><a href="#">Link</a></li>

                            <!-- Dropdown level 2 -->
                            <li class="panel panel-default">
                                <a data-toggle="collapse" href="#dropdown-lvl2">
                                    <span class="glyphicon glyphicon-off"></span> Sub Level <span class="caret"></span>
                                </a>
                                <div id="dropdown-lvl2" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="nav navbar-nav">
                                            <li><a href="#">Link</a></li>
                                            <li><a href="#">Link</a></li>
                                            <li><a href="#">Link</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>

*/