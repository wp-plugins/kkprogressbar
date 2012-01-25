var advencedEditor = [["grpEdit", "Edit", 
						 ["Undo", "Redo", "Search", "SpellCheck", "ClearAll", "BRK", "Cut", "Copy",
						 "Paste","PasteWord", "PasteText", "RemoveFormat", "Print", "Preview", 
						 "FullScreen", "XHTMLSource"]],
						 ["grpFont", "Font", ["FontName", "FontSize", "StyleAndFormatting", "ForeColor", "BackColor", "BRK", "Bold", 
						 "Italic", "Underline","Strikethrough","Superscript",
						 "Subscript"]],
						 ["grpPara", "Paragraph", ["Paragraph", "Indent", "Outdent", "Characters", "Line",
						 "BRK", "JustifyLeft", "JustifyCenter",
						 "JustifyRight","JustifyFull", "Numbering",
						 "Bullets"]],
						 ["grpObjects", "Objects", ["Image", "Flash","Media", "Form", "BRK", "Hyperlink", "Bookmark", "Table", "Guidelines"]]
						];
var simpleEditor = [["grpFont", "Font", ["FontSize", "Bold", 
						 "Italic", "Underline","Strikethrough"]],
						 ["grpPara", "Paragraph", ["JustifyLeft", "JustifyCenter",
						 "JustifyRight","JustifyFull", "Numbering", "Bullets"]],
						 ["grpObjects", "Objects", ["Image", "Hyperlink"]]];


function alignCenter(progbar){
	
		var width_parent = parseInt(progbar.css('width'));
		var width_child = parseInt(progbar.find('table').css('width'));
		var roznica = Math.round((width_parent - width_child)/2);
		
		progbar.find('.div-cloud').css({'left': roznica+'px'});
	
}

function updateDB(){
    jQuery.post(ajaxurl,{
        action : 'kkpb_db_update',
        beforeSend	:	function(){
        	jQuery('#kkpb-db-update-start').hide();
        	jQuery('#kkpb-db-update-loader').show();
        }
    },function(html){

         var dane = html.split('|||');
         jQuery('#kkpb-db-update-text').html(dane[1]);
         setTimeout(function(){window.location.reload();},3000);
    });
}

jQuery(document).ready(function(){
	
	jQuery('.kknewcheckbox').iphoneStyle();
	
	jQuery('.kkadmin-radio-ui').buttonset();
	
	jQuery('#kkpb-db-update-start').click(function(){
		updateDB();
		return false;
	});
	
    jQuery('.kkadmin-radio-prev-box input[type="radio"]:checked').parents('.kkadmin-radio-prev-box').addClass('kkadmin-active');
    
    jQuery('.kkadmin-radio-prev-box input[type="radio"]').live('click', function(){
    	jQuery(this).parents('.kkadmin-selectbox').find('.kkadmin-radio-prev-box').removeClass('kkadmin-active');
    	jQuery(this).parents('.kkadmin-radio-prev-box').addClass('kkadmin-active');
    });

    jQuery('.kkpb-color-pick').ColorPicker({
    	'color'			:	jQuery(this).val(),
    	'flat'			:	false,
    	'livePreview'	:	true,
		onSubmit: function(hsb, hex, rgb, el) {
    		jQuery(el).val(hex);
    		jQuery(el).ColorPickerHide();
    		jQuery(el).change();
    	},
    	onHide: function(hsb, hex, rgb, el) {
    		jQuery(el).val(hex);
    	},
    }).bind('keyup', function(){
    	jQuery(this).ColorPickerSetColor(this.value);
    });
    
	jQuery('.kk-tooltip').qtip({
		content: {
			title		:	'Info:',
	      	attr		: 	'title'
	   	},
	   	position: {
	   		my			:	'left center',
	   		at			:	'right center'
	    },
	    style: {
	        classes		: 	'ui-tooltip-shadow ui-tooltip-dark'
	     }
	});
	
	jQuery('.kkpb-progressbar-content').qtip({
		content: {
			title		:	'Progress:',
	      	attr		: 	'title'
	   	},
	   	position: {
	   		my			:	'bottom center',
	   		at			:	'top center'
	    },
	    style: {
	        classes		: 	'ui-tooltip-shadow ui-tooltip-dark'
	     }
	});
	
	jQuery(".slider-edit").slider({
        min: 0,
        max: 100,
        step: 1,
        slide: function(event, ui) {
            jQuery(".kkpb_procent").val(ui.value);
            var procent = jQuery('.kkpb_procent').val();
        }

    });

});