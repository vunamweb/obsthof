<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		language : "de",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

//		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

		// Theme options
		theme_advanced_buttons1 : "save,|,bold,italic,underline,|,bullist,numlist,|,styleselect,formatselect,|,cut,copy,paste",
		theme_advanced_buttons2 : "undo,redo,|,link,unlink,|,tablecontrols,|,hr,|,removeformat,cleanup,|,code",
		//theme_advanced_buttons1 : "save,|,bold,italic,underline,|,bullist,numlist,|,styleselect,formatselect,|,cut,copy,paste",
		//theme_advanced_buttons2 : "search,replace,|,undo,redo,|,link,unlink,anchor,|,tablecontrols,|,hr,removeformat,|,|,cleanup,|,code",
		//theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat",
		//theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft,visualblocks",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

        // Skin options

        // Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'strong'},
//			{title : 'Spalte rechts', inline : 'span'},
//			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Text rechtsbündig Tabelle', inline : 'span', classes : 'tabRechts'},
//			{title : 'Example 2', inline : 'span', classes : 'example2'},
//			{title : 'Table styles'},
//			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "pixeldusche",
			staffid : "1"
		}
	});
</script>
<!-- /TinyMCE -->

