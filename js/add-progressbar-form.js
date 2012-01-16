var kkpbAddForm = {
	
	valid: '',
	progressFormVal: null,
	progressbarTab:	[],
	progressbarCount: 0,
	count:	0,
	isEdit: false,
	
	initialize : function(options){
		_this = this;
        this.options = options;
        
        if(this.options['editID'].val() != ''){
        	this.isEdit = true;
        }
        
        if(jQuery('#kkpb-box .kkpb-progressbar-box:visible') != undefined && jQuery('#kkpb-box .kkpb-progressbar-box:visible').length > 0){
			_this.count = jQuery('#kkpb-box .kkpb-progressbar-box:visible').length;
		}
        
		this.validationInit();
		this.disabledInputs('#kkpb_name, #kkpb_val_all, #kkpb_val_now, #kkpb_name_dialog, #kkpb_val_all_dialog, #kkpb_val_now_dialog');
		
        this.options['openFormButton'].click(function(){
			_this.openFormBox(_this.options['formBox'], _this.options['progressbarManual']);
			_this.enabledInputs('#kkpb_name');
			return false;
    	});
        
		jQuery('.kkpb-auto').live('change',function(){
			if(jQuery(this).is(':checked')){
				jQuery(this).parents('#kkpb-add-bar-form').find('.kkpb-auto-row').fadeOut("fast",function(){
					jQuery(this).parents('#kkpb-add-bar-form').find('.kkpb-progress-row').fadeIn("fast");
					_this.disabledInputs('#kkpb_val_all, #kkpb_val_now');
					_this.enabledInputs('#kkpb_procent');
				});
			}else{
				jQuery(this).parents('#kkpb-add-bar-form').find('.kkpb-progress-row').fadeOut("fast",function(){
					jQuery(this).parents('#kkpb-add-bar-form').find('.kkpb-auto-row').fadeIn("fast");
					_this.enabledInputs('#kkpb_val_all, #kkpb_val_now');
					_this.disabledInputs('#kkpb_procent');
				});
			}
		});
		
		jQuery('#kkpb-auto-dialog').live('change',function(){
			if(jQuery(this).is(':checked')){
				jQuery(this).parents('#kkpb-edit-progressbar-dialog').find('.kkpb-auto-row').fadeOut("fast",function(){
					jQuery(this).parents('#kkpb-edit-progressbar-dialog').find('.kkpb-progress-row').fadeIn("fast");
					_this.disabledInputs('#kkpb_val_all_dialog, #kkpb_val_now_dialog');
					_this.enabledInputs('#kkpb_procent_dialog');
				});
			}else{
				jQuery(this).parents('#kkpb-edit-progressbar-dialog').find('.kkpb-progress-row').fadeOut("fast",function(){
					jQuery(this).parents('#kkpb-edit-progressbar-dialog').find('.kkpb-auto-row').fadeIn("fast");
					_this.enabledInputs('#kkpb_val_all_dialog, #kkpb_val_now_dialog');
					_this.disabledInputs('#kkpb_procent_dialog');
				});
			}
		});
		
        this.options['closeFormButton'].click(function(){
        	_this.closeFormBox(_this.options['formBox']);
			_this.disabledInputs('#kkpb_name, #kkpb_val_all, #kkpb_val_now');
        	return false;
        });
        
        this.options['addProgressButton'].click(function(){
			
			if(_this.options['progressbarName'].is(':enabled')){
				var valA = _this.options['progressbarName'].valid();
			}else{
				var valA = true;
			}
			
			if(_this.options['progressbarPercent'].is(':enabled')){
				var valD = _this.options['progressbarPercent'].valid();
			}else{
				var valD = true;
			}
			
			if(_this.options['progressbarVallAll'].is(':enabled')){
				var valB = _this.options['progressbarVallAll'].valid();
			}else{
				var valB = true;
			}
			
			if(_this.options['progressbarVallNow'].is(':enabled')){
				var valC = _this.options['progressbarVallNow'].valid();
			}else{
				var valC = true;
			}
			
			if(!(valA && valB && valC && valD)){
				return false;
			}
		
        	if(_this.isEdit){
        		_this.addProgressbarBox(_this.options['editID'].val());
        	}else{
        		_this.addProgressbarBox('');
        	}
        	return false;
        });
        
        this.options['statusHendler'].live('click', function(){
        	var myObj = jQuery(this);
        	_this.changeStatusIMG(myObj);
        	if(_this.isEdit){
        		_this.updateStatusAjax(myObj);
        	}
        	return false;
        });
        
        this.options['deleteHendler'].live('click', function(){
        	_this.deleteProgressbar(jQuery(this));
        	return false;
        });
        
        this.options['editHendler'].live('click', function(){
        	//TODO ogarnąć tą funkcję
        	var myObj = jQuery(this);
        	var perc = 0;
        	
        	var nazwa = myObj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputName'] +'"]').val();
        	var manual = myObj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputManual'] +'"]').val();
        	var procent = myObj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputPercent'] +'"]').val();
        	var all = myObj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputVallAll'] +'"]').val();
        	var now = myObj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputVallNow'] +'"]').val();
        	
        	if(manual == '1'){
        		_this.options['progressbarManualDialog'].attr({'checked' : 'checked'});
        	}else if(manual == '2'){
        		_this.options['progressbarManualDialog'].attr({'checked' : false});
        	}
        	
        	_this.options['progressbarNameDialog'].val(nazwa);
        	_this.options['progressbarPercentDialog'].val(procent);
        	_this.options['progressbarVallAllDialog'].val(all);
        	_this.options['progressbarVallNowDialog'].val(now);
        	
        	_this.options['dialogBox'].dialog("destroy");
        	_this.options['dialogBox'].dialog({
    			height: 300,
    			width: 500,
    			modal: true,
    			open: function(){
    				_this.options['progressbarManualDialog'].change();
					_this.enabledInputs('#kkpb_name_dialog');
    				
    				_this.options['sliderClass'].slider({
    					min: 0,
    			        max: 100,
    			        step: 1,
    			        value: procent
    				});
    				
    				_this.refreshCheckboxPlugin(_this.options['progressbarManualDialog']);
    			},
    			buttons: { 
    				Save : function(){
					
						if(_this.options['progressbarNameDialog'].is(':enabled')){
							var valA = _this.options['progressbarNameDialog'].valid();
						}else{
							var valA = true;
						}
						
						if(_this.options['progressbarPercentDialog'].is(':enabled')){
							var valD = _this.options['progressbarPercentDialog'].valid();
						}else{
							var valD = true;
						}
						
						if(_this.options['progressbarVallAllDialog'].is(':enabled')){
							var valB = _this.options['progressbarVallAllDialog'].valid();
						}else{
							var valB = true;
						}
						
						if(_this.options['progressbarVallNowDialog'].is(':enabled')){
							var valC = _this.options['progressbarVallNowDialog'].valid();
						}else{
							var valC = true;
						}
						
						if(!(valA && valB && valC && valD)){
							return false;
						}
					
    					var id = myObj.attr('href');
    					id = id.split('#');
    					id = id[1];
    					
    					if(_this.options['progressbarManualDialog'].is(':checked')){
    		        		manual = 1;
    		        		perc = _this.options['progressbarPercentDialog'].val();
    		        	}else{
    		        		manual = 2;
    		        		perc = (parseInt(_this.options['progressbarVallNowDialog'].val()) / _this.options['progressbarVallAllDialog'].val()) * 100;
    		        	}

    					if(parseInt(perc) > 100){
    		        		var percBar = 100;
    		        	}else{
    		        		var percBar = parseInt(perc);
    		        	}
    					
    					myObj.parents('.kkpb-progressbar-box').find(_this.options['nameClass']).html(_this.options['progressbarNameDialog'].val());
    					myObj.parents('.kkpb-progressbar-box').find(_this.options['percentInfo']).attr({'title' : perc + '%'});
    					myObj.parents('.kkpb-progressbar-box').find(_this.options['progressWidth']).css({'width' : percBar + '%'});
    					
    					myObj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputName'] +'"]').val(_this.options['progressbarNameDialog'].val());
    		        	myObj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputManual'] +'"]').val(manual);
    		        	myObj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputPercent'] +'"]').val(_this.options['progressbarPercentDialog'].val());
    		        	myObj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputVallAll'] +'"]').val(_this.options['progressbarVallAllDialog'].val());
    		        	myObj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputVallNow'] +'"]').val(_this.options['progressbarVallNowDialog'].val());
    		        	
    		        	if(_this.isEdit){
	    		        	var wiadomosc = {
	    		    	            action 	: 	'update_bar_kkpb',
	    		    	            id		:	id,
	    		    	            nazwa	:	_this.options['progressbarNameDialog'].val(),
	    		    	            link	:	_this.options['progressbarNameLinkDialog'].val(),
	    		    	            manual	:	manual,
	    		    	            procent	:	_this.options['progressbarPercentDialog'].val(),
	    		    	            valNow	:	_this.options['progressbarVallNowDialog'].val(),
	    		    	            valAll	:	_this.options['progressbarVallAllDialog'].val()
	    		    	        };
    		        		
	    		        	_this.ajaxLoading();
			    	        jQuery.post(ajaxurl,wiadomosc,function(html){ 
			    	        	_this.ajaxLoading();
			    	        	
			    	        	var html = jQuery.parseJSON(html);
			    	        	
			    	        	if(html.hasError){
			    	        		_this.ajaxError();
			    	        	}else{
			    	        		_this.ajaxOK();
			    	        	}
			    	        	
			    	        	_this.options['dialogBox'].dialog("close"); 
			    	        });
    		        	}
						_this.options['dialogBox'].dialog("close"); 
    				},
    				Cancel : function(){ jQuery(this).dialog("close"); }
    			}
    		});
        });
        
	},
	
	validationInit: function(){
		var validator = jQuery('#kkpb-add-form').validate({
			rules: {
				kkpb_project_name: {
					required: true
				},
				kkpb_project_link: {
					required: false
				},
				kkpb_name: {
					required: true
				},
				kkpb_val_all: {
					required: true,
					onlyDigits: true,
					moreThenZero: true
				},
				kkpb_val_now: {
					required: true,
					onlyDigits: true,
					moreThenZero: true,
					mniejszaOd:	"#kkpb_val_all"
				},
				kkpb_procent: {
					required: true,
					onlyDigits: true
				}
			},
			errorPlacement: function(error, element) { 
				element.parent().append('<div class="clear"></div>');
				var $el_name = element.attr("name"); 
				error.attr("id", $el_name+"_error");
				error.appendTo( element.parent() ); 
			}
		});
		
		var validatorDialog = jQuery('#kkpb-edit-progress-box').validate({
			rules: {
				kkpb_name_dialog: {
					required: true
				},
				kkpb_val_all_dialog: {
					required: true,
					onlyDigits: true,
					moreThenZero: true
				},
				kkpb_val_now_dialog: {
					required: true,
					onlyDigits: true,
					moreThenZero: true,
					mniejszaOd:	"#kkpb_val_all_dialog"
				},
				kkpb_procent_dialog: {
					required: true,
					onlyDigits: true
				}
			},
			errorPlacement: function(error, element) { 
				element.parent().append('<div class="clear"></div>');
				var $el_name = element.attr("name"); 
				error.attr("id", $el_name+"_error");
				error.appendTo( element.parent() ); 
			}
		});
	},
	
	disabledInputs: function(inputsID){
		jQuery(inputsID).attr({'disabled' : 'disabled'});
	},
	
	enabledInputs: function(inputsID){
		jQuery(inputsID).attr({'disabled' : false});
	},
	
	updateStatusAjax: function(obj){
		var status = obj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputStatus'] +'"]').val();
		var id = obj.parents('.kkpb-progressbar-box').find(_this.options['progressBoxOptions']).attr('href');
		id = id.split('#');
		id = id[1];

		var wiadomosc = {
	            action 	: 	'zmiana_statusu_kkpb',
	            id		:	id,
	            status	:	status
	        };

		_this.ajaxLoading();
        jQuery.post(ajaxurl,wiadomosc,function(html){
        	_this.ajaxLoading();
        	
        	var html = jQuery.parseJSON(html);
        	
        	if(html.hasError){
        		_this.ajaxError();
        		return false;
        	}
        });
	},
	
	changeStatusIMG: function(obj){
		var cls = obj.attr('class');
		
		if(cls == 'kkpb-status-aktywny'){
			obj.removeClass().addClass('kkpb-status-wstrzymany');
			obj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputStatus'] +'"]').val('2');
		}else if(cls == 'kkpb-status-wstrzymany'){
			obj.removeClass().addClass('kkpb-status-nieaktywny');
			obj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputStatus'] +'"]').val('3');
		}else if(cls == 'kkpb-status-nieaktywny'){
			obj.removeClass().addClass('kkpb-status-aktywny');
			obj.parents('.kkpb-progressbar-box').find('input[name="'+ _this.options['inputStatus'] +'"]').val('1');
		}
		
	},
	
	ajaxLoading: function(){
		//TODO zrobić automatyczne zamykanie komunikatów
		jQuery('.kkpb-ajax-ok').hide();
		jQuery('.kkpb-ajax-error').hide();
		
		if(jQuery('.kkpb-ajax-loading').is(':visible')){
			jQuery('.kkpb-ajax-loading').hide();
		}else{
			jQuery('.kkpb-ajax-loading').show();
		}
	},
	
	ajaxError: function(){
		jQuery('.kkpb-ajax-loading').hide();
		jQuery('.kkpb-ajax-ok').hide();
		jQuery('.kkpb-ajax-error').show();
	},
	
	ajaxOK: function(){
		jQuery('.kkpb-ajax-loading').hide();
		jQuery('.kkpb-ajax-error').hide();
		jQuery('.kkpb-ajax-ok').show();
	},
	
	deleteProgressbar: function(obj){
		var id = obj.attr('href');
		id = id.split('#');
		id = id[1];
		
		if(_this.isEdit){
			_this.deleteProgressbarAjax(id);
		}
		
		_this.removeBox(obj.parents(_this.options['progressbarBoxClass']));
	},
	
	deleteProgressbarAjax: function(id){
		var wiadomosc = {
	            action 	: 	'del_bar_kkpb',
	            id		:	id
	        };
		
		_this.ajaxLoading();
        jQuery.post(ajaxurl,wiadomosc,function(html){
        	_this.ajaxLoading();
        	
        	var html = jQuery.parseJSON(html);
        	
        	if(html.hasError){
        		_this.ajaxError();
        		return false;
        	}else{
        		_this.ajaxOK();
        	}
        });
	},
	
	removeBox: function(obj){
		obj.fadeOut('normal',function(){
			obj.remove();
			_this.count--;
		});
	},
	
	addProgressbarBox: function(id){
		var box, perc, manualAjax;
		_this.progressbarTab = [];
		
		_this.getFormValues();
		_this.progressbarTab.push(_this.progressFormVal);
		
		if(_this.progressFormVal.manual){
			//TODO dodać zabezpieczenie przed wartością 0 > xxx > 100
			perc = (parseInt(_this.progressFormVal.manualNow) / parseInt(_this.progressFormVal.manualAll)) * 100;
			manualAjax = 2;
		}else{
			perc = _this.progressFormVal.percentage;
			manualAjax = 1;
		}
		
		if(_this.count >= 2 || _this.count < 0){
			alert('In the free version you can add two tasks for each project. Feel free to purchase the professional version.');
			return false;
		}else{
			_this.count++;
		}
		
		if(id != ''){
			var wiadomosc = {
	            action 	: 	'add_bar_kkpb',
	            nazwa 	: 	_this.progressFormVal.name,
	            manual 	: 	manualAjax,
	            procent :	_this.progressFormVal.percentage,
	            valAll	:	_this.progressFormVal.manualAll,
	            valNow	:	_this.progressFormVal.manualNow,
	            status	:	'1',
	            proId	:	id
	        };

	        _this.ajaxLoading();
	        jQuery.post(ajaxurl,wiadomosc,function(html){
	        	_this.ajaxLoading();
	        	
	        	var html = jQuery.parseJSON(html);
	        	
	        	if(html.hasError){
	        		_this.ajaxError();
	        		return;
	        	}else{
	        		_this.ajaxOK();
	        	}
	        	
	        	_this.progressbarCount = html.id;
	        	
	        	if(parseInt(perc) > 100){
	        		var percBar = 100;
	        	}else{
	        		var percBar = parseInt(perc);
	        	}
	        	
	            //TODO wewalić to poniżej do funkcji bo syf
	            box = _this.options['progressbarBoxPrototype'].clone();
				box.attr({'id' : ''})
					.find(_this.options['nameClass']).html(_this.progressFormVal.name);
				box.find(_this.options['percentInfo']).attr({'title' : perc + '%'});
				box.find(_this.options['progressWidth']).css({'width' : percBar + '%'});
				box.find(_this.options['progressBoxOptions']).attr({'href' : '#' + _this.progressbarCount});
				
				box.find('input[name="'+ _this.options['inputName'] +'"]').val(_this.progressFormVal.name);
				box.find('input[name="'+ _this.options['inputManual'] +'"]').val(_this.progressFormVal.manual);
				box.find('input[name="'+ _this.options['inputPercent'] +'"]').val(_this.progressFormVal.percentage);
				box.find('input[name="'+ _this.options['inputVallAll'] +'"]').val(_this.progressFormVal.manualAll);
				box.find('input[name="'+ _this.options['inputVallNow'] +'"]').val(_this.progressFormVal.manualNow);
				
				_this.progressbarCount++;
				_this.options['progressbarBoxBox'].append(box.fadeIn());
	        });
		}else{
		
			if(parseInt(perc) > 100){
        		var percBar = 100;
        	}else{
        		var percBar = parseInt(perc);
        	}
			
			box = _this.options['progressbarBoxPrototype'].clone();
			box.attr({'id' : ''})
				.find(_this.options['nameClass']).html(_this.progressFormVal.name);
			box.find(_this.options['percentInfo']).attr({'title' : perc + '%'});
			box.find(_this.options['progressWidth']).css({'width' : percBar + '%'});
			box.find(_this.options['progressBoxOptions']).attr({'href' : '#' + _this.progressbarCount});
			
			box.find('input[name="'+ _this.options['inputName'] +'"]').val(_this.progressFormVal.name);
			box.find('input[name="'+ _this.options['inputManual'] +'"]').val(_this.progressFormVal.manual);
			box.find('input[name="'+ _this.options['inputPercent'] +'"]').val(_this.progressFormVal.percentage);
			box.find('input[name="'+ _this.options['inputVallAll'] +'"]').val(_this.progressFormVal.manualAll);
			box.find('input[name="'+ _this.options['inputVallNow'] +'"]').val(_this.progressFormVal.manualNow);
			
			_this.progressbarCount++;
			_this.options['progressbarBoxBox'].append(box.fadeIn());
		}
	},
	
	getFormValues: function(){
		var manual, perc, manualAll, manualNow;
		
		if(!_this.options['progressbarManual'].is(':checked')){
			manual = true;
			perc = 0;
			manualAll = _this.options['progressbarVallAll'].val();
			manualNow = _this.options['progressbarVallNow'].val();
		}else{
			manual = false;
			perc = _this.options['progressbarPercent'].val();
			manualAll = 0;
			manualNow = 0;
		}
		
		_this.progressFormVal = {
			'name'			:	_this.options['progressbarName'].val(),
			'manual'		:	manual,
			'percentage'	:	perc,
			'manualAll'		:	manualAll,
			'manualNow'		:	manualNow
		};
	},
	
	openFormBox: function(obj, check){
		obj.slideDown('normal', function(){
			_this.refreshCheckboxPlugin(check);
		});
	},
	
	closeFormBox: function(obj){
		obj.slideUp('normal');
	},
	
	refreshCheckboxPlugin: function(obj){
		obj.iphoneStyle();
	}
		
};

function kkpbAddFormConstr(){
	this.kkpbAddForm = kkpbAddForm;
}