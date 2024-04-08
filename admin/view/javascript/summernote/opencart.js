$(document).ready(function() {
	// Override summernotes image manager
	$('[data-toggle=\'summernote\']').each(function() {
		var element = this;
		
		if ($(this).attr('data-lang')) {
			$('head').append('<script type="text/javascript" src="view/javascript/summernote/lang/summernote-' + $(this).attr('data-lang') + '.js"></script>');
		}

		$(element).summernote({
			lang: $(this).attr('data-lang'),
			disableDragAndDrop: true,
			height: 300,
			emptyPara: '',
			codemirror: { // codemirror options
				mode: 'text/html',
				htmlMode: true,
				lineNumbers: true,
				theme: 'monokai'
			},			
			fontsize: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '30', '36', '48' , '64'],
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'underline', 'italic', 'clear']],
				// ['fontname', ['fontname']],
				// ['fontsize', ['fontsize']],
				// ['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link']],
				// ['insert', ['link', 'image', 'video']],
				['view', ['fullscreen', 'codeview', 'help']]
			],
			popover: {
           		image: [
					['custom', ['imageAttributes']],
					['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
					['float', ['floatLeft', 'floatRight', 'floatNone']],
					['remove', ['removeMedia']]
				],
			},
			// cleaner:{
			// 	  action: 'both', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
			// 	  newline: '<br>', // Summernote's default is to use '<p><br></p>'
			// 	  notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
			// 	  icon: '<i class="note-icon">delete</i>',
			// 	  keepHtml: false, // Remove all Html formats
			// 	  keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>'], // If keepHtml is true, remove all tags except these
			// 	  keepClasses: false, // Remove Classes
			// 	  badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
			// 	  badAttributes: ['style', 'start'], // Remove attributes from remaining tags
			// 	  limitChars: false, // 0/false|# 0/false disables option
			// 	  limitDisplay: 'both', // text|html|both
			// 	  limitStop: false // true/false
			// },			
			buttons: {
    			image: function() {
					var ui = $.summernote.ui;
							
					// create button
					var button = ui.button({
						contents: '<i class="note-icon-picture" />',
						tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
						click: function () {
							$('#modal-image').remove();
							
							$.ajax({
								url: 'index.php?route=common/filemanager&user_token=' + getURLVar('user_token'),
								dataType: 'html',
								beforeSend: function() {
									$('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
									$('#button-image').prop('disabled', true);
								},
								complete: function() {
									$('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
									$('#button-image').prop('disabled', false);
								},
								success: function(html) {
									$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
									
									$('#modal-image').modal('show');
									
									$('#modal-image').delegate('a.thumbnail', 'click', function(e) {
										e.preventDefault();
										
										$(element).summernote('insertImage', $(this).attr('href'));
																	
										$('#modal-image').modal('hide');
									});
								}
							});						
						}
					});
				
					return button.render();
				}
  			}
		});
	});
});
