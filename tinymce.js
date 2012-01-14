jQuery(document).ready(function($) {
	
	$("#insert").click(function() {
	
		var code = '';
		var error = false;
		
		var id = $('#kkpb_shortcode .tabs .current').attr('id');
		
		if( id == 'mail_tab' ) {
		
			var project = $("#kkpb_short_type").val();
			var project_task = $('input[name="kkpb_short_project_with_task"]:checked').val();
			var idproject = $("#kkpb_short_project").val();
			var idtask = $("#kkpb_short_task").val();
		
			code += '[kkpb';
			code += ' project="' + project + '"';
			if( project != '' && project == 'true' ){
				code += ' task="' + project_task + '"';
				code += ' idproject="' + idproject + '"';
			}else{
				code += ' idtask="' + idtask + '"';
			}
			code += ' /]';
		}
		
		if( error == false ) {
			if(window.tinyMCE ) {
				//TODO: For QTranslate we should use here 'qtrans_textarea_content' instead 'content'
				window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, code);
				//Peforms a clean up of the current editor HTML. 
				//tinyMCEPopup.editor.execCommand('mceCleanup');
				//Repaints the editor. Sometimes the browser has graphic glitches. 
				tinyMCEPopup.editor.execCommand('mceRepaint');
				tinyMCEPopup.close();
			}
		}
		else {
			$(".mceActionPanel .error").text('An error is occured. ');
		}
		
		return false;
	});
});
