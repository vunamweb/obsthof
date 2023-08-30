  <script src="tinymce/tinymce.min.js"></script>
  <script type="text/javascript">
  tinymce.init({
    selector: '.summernote',
	width: '100%',
	max_width: '100%',
	menubar: false,
	statusbar: false,
	toolbar: false,
	//inline: 'true',
	 skin: 'tinymce-5-dark',
    // content_css: '/psychotherapie/css/styles.css',
	toolbar_mode: 'floating',
	// plugins: 'autoresize advlist link image lists',
	plugins: 'autoresize link lists charmap table code ',
	toolbar: 
    	 'undo redo ' +
    	 'styles ' +
    	 'bold italic underline | link | hr ' +
    	 'bullist numlist | table | charmap | removeformat | selectall | code ', 
    
	
//		outdent indent |   
//  	{ name: 'alignment', items: [ 'alignleft', 'aligncenter', 'alignright', 'alignjustify' ] },
  });
  </script>
