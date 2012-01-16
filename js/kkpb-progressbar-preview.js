var kkpbProgressbarPrev  = {
	
	initialize : function(){
		_this = this;
		
		this.updateForm();
		this.progressColor();
		this.getGradientBG();
		this.setBorderSize(jQuery('#border_size'));
		this.setBorderColor(jQuery('#border_color'));
		this.setBorderRadius(jQuery('#border_radius'));
		
		jQuery('input[name="use_gradient"]').click(function(){
			_this.updateForm();
			_this.progressColor();
		});
		
		jQuery('input[name="use_texture"]').change(function(){
			_this.progressTexture();
		});
		
		jQuery('#progress_color').change(function(){
			_this.progressColor();
		});
		
		jQuery('input[name="progress_texture"]').click(function(){
			_this.progressTexture();
		});
		
		jQuery('input[name="progress_gradient"]').click(function(){
			_this.getGradient();
		});
		
		jQuery('input[name="progress_bg"]').click(function(){
			_this.getGradientBG();
		});
		
		jQuery('#border_size').change(function(){
			_this.setBorderSize(jQuery(this));
		});
		
		jQuery('#border_color').change(function(){
			_this.setBorderColor(jQuery(this));
		});
		
		jQuery('#border_radius').change(function(){
			_this.setBorderRadius(jQuery(this));
		});
	},
	
	setBorderRadius: function(obj){
		var border_radius = obj.val();
		
		jQuery('#kkpb-demo-progressbar .kkpb-progressbar-content').css({
			'border-radius' 		: parseInt(border_radius) + 'px',
			'-webkit-border-radius' : parseInt(border_radius) + 'px',
			'-khtml-border-radius' 	: parseInt(border_radius) + 'px',
			'-moz-border-radius' 	: parseInt(border_radius) + 'px'
			});
		jQuery('#kkpb-demo-progressbar #kkpb-progress-bar').css({
			'border-radius' 		: parseInt(border_radius) + 'px',
			'-webkit-border-radius' : parseInt(border_radius) + 'px',
			'-khtml-border-radius' 	: parseInt(border_radius) + 'px',
			'-moz-border-radius' 	: parseInt(border_radius) + 'px'
			});
	},
	
	setBorderColor: function(obj){
		var border_color = obj.val();
		jQuery('#kkpb-demo-progressbar .kkpb-progressbar-content').css({'border-color' : '#'+border_color});
	},
	
	setBorderSize: function(obj){
		var border_size = obj.val();
		jQuery('#kkpb-demo-progressbar .kkpb-progressbar-content').css({'border-width' : parseInt(border_size) + 'px'});
	},
	
	updateForm: function(){
		var color_type = jQuery('input[name="use_gradient"]:checked').val();
		
		if(color_type == 'color'){
			jQuery('.kkpb-tr-gradient').hide();
			jQuery('.kkpb-tr-kolor').show();
		}else if(color_type == 'gradient'){
			jQuery('.kkpb-tr-kolor').hide();
			jQuery('.kkpb-tr-gradient').show();
		}
	},
	
	progressColor: function(){
		_this = this;
		
		var color_type = jQuery('input[name="use_gradient"]:checked').val();
		
		if(color_type == 'color'){
			_this.getColor();
			_this.progressTexture();
		}else if(color_type == 'gradient'){
			_this.getGradient();
		}
	},
	
	getColor: function(){
		var color = jQuery('#progress_color').val();
		jQuery('#kkpb-demo-progressbar #kkpb-progress-bar').removeClass().css({'background-color':'#'+color});
	},
	
	getGradient: function(){
		var gradientClass = jQuery('input[name="progress_gradient"]:checked').val();
		jQuery('#kkpb-demo-progressbar #kkpb-progress-bar').removeClass().addClass(gradientClass);
	},
	
	getGradientBG: function(){
		var gradientClass = jQuery('input[name="progress_bg"]:checked').val();
		var delClass = jQuery('input[name="progress_bg"]').not(':checked').val();
		jQuery('#kkpb-demo-progressbar .kkpb-progressbar-content').removeClass(delClass).addClass(gradientClass);
	},
	
	progressTexture: function(){
		_this = this;
		
		if(jQuery('#use_texture').is(':checked')){
			var texture = jQuery('input[name="progress_texture"]:checked').val();
			jQuery('#kkpb-demo-progressbar #kkpb-progress-bar').removeClass().addClass(texture);
		}else{
			jQuery('#kkpb-demo-progressbar #kkpb-progress-bar').removeClass();
		}
	}
};

function kkpbProgressbarPrevConstr(){
	this.kkpbProgressbarPrev = kkpbProgressbarPrev;
}