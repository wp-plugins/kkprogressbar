jQuery(document).ready(function(){
	jQuery('.kkpb-proj-info').qtip({
		content: {
	      	text		:	function(api) {
		      	return jQuery(this).parents('.kkpb-proj-box').find('.kkpb-proj-desc').clone();
	      	}
	   	},
	   	position: {
	   		my			:	'bottom center',
	   		at			:	'top center'
	    },
	    style: {
	        classes		: 	'ui-tooltip-shadow ui-tooltip-dark'
	     }
	});

	jQuery('.kkpb-rozwijarka').click(function(){
		if(jQuery(this).parents('.kkpb-proj-box').find('.kkpb-proj-prog-bar').is(':visible')){
			jQuery(this).parents('.kkpb-proj-box').find('.kkpb-proj-prog-bar').slideUp('normal');
			jQuery(this).removeClass('kkpb-minus').addClass('kkpb-plus');
		}else{
			jQuery(this).parents('.kkpb-proj-box').find('.kkpb-proj-prog-bar').slideDown('normal');
			jQuery(this).removeClass('kkpb-plus').addClass('kkpb-minus');
		}
		return false;
	});
});