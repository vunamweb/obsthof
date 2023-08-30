	<meta name="author" content="Harald Kirschner, digitarald.de" />
	<meta name="copyright" content="Copyright 2009 Harald Kirschner" />
 	
	<script type="text/javascript" src="../js/mootools.js"></script>
	<script type="text/javascript" src="../js/Swiff.Uploader.js"></script>
	<script type="text/javascript" src="../js/Fx.ProgressBar.js"></script>
	<script type="text/javascript" src="../js/FancyUpload2.js"></script>
	<script type="text/javascript" src="<?php echo $js_dl ? '' : '../'; ?>js/m_download.js"></script>



	<!-- See style.css -->
	<style type="text/css">
		/**
 * FancyUpload Showcase
 *
 * @license		MIT License
 * @author		Harald Kirschner <mail [at] digitarald [dot] de>
 * @copyright	Authors
 */

/* CSS vs. Adblock tabs */
.swiff-uploader-box a {
	display: none !important;
}

/* .hover simulates the flash interactions */
a:hover, a.hover {
	color: #00217a;
}

#status {
	padding: 10px 15px;
	width: 420px;
	border: 1px solid #eee;
}

#status .progress {
	background: url(../assets/progress-bar/progress.gif) no-repeat;
	background-position: +50% 0;
	margin-right: 0.5em;
	vertical-align: middle;
}

#status .progress-text {
	font-size: 0.8em;
	font-weight: bold;
}

#list {
	list-style: none;
	width: 450px;
	margin: 0;
}

#list li.validation-error {
	font-size: 0.8em;
	padding-left: 44px;
	display: block;
	clear: left;
	line-height: 24px;
	color: #8a1f11;
	cursor: pointer;
	border-bottom: 1px solid #fbc2c4;
	background: #fbe3e4 url(../assets/failed.png) no-repeat 4px 4px;
}

#list li.file {
	border-bottom: 1px solid #eee;
	background: url(../assets/file.png) no-repeat 4px 4px;
	overflow: auto;
}
#list li.file.file-uploading {
	background-image: url(../assets/uploading.png);
	background-color: #D9DDE9;
}
#list li.file.file-success {
	background-image: url(../assets/success.png);
}
#list li.file.file-failed {
	background-image: url(../assets/failed.png);
}

#list li.file .file-name {
	font-size: 0.8em;
	margin-left: 44px;
	display: block;
	clear: left;
	line-height: 50px;
	height: 40px;
	font-weight: normal;
}
#list li.file .file-size {
	font-size: 0.8em;
	line-height: 18px;
	float: right;
	margin-top: 2px;
	margin-right: 6px;
}
#list li.file .file-info {
	display: block;
	margin-left: 44px;
	font-size: 0.8em;
	line-height: 20px;
	/* clear; */
}
#list li.file .file-remove {
	clear: right;
	float: right;
	line-height: 18px;
	margin-right: 6px;
}	

.overall-title, .current-title, .current-text {
font-size:12px;
}

.current-text {
font-size:14px;
margin-top:10px;
}
</style>

