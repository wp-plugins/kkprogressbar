function kkProgressbarFormConfig(options){
	this.options = jQuery.extend({
		'openFormButton'			:	jQuery('#kkpb-add-new-progress'),
		'addProgressButton'			:	jQuery('#kkpb-progressbar-save'),
		'closeFormButton'			:	jQuery('#kkpb-cancel-form'),
		'formBox'					:	jQuery('#kkpb-add-bar-form'),
		'newCheckboxsHendler'		:	jQuery('.kknewcheckbox'),
		'progressbarBoxBox'			:	jQuery('#kkpb-progressbarsbox-box'),
		'progressbarBoxClass'		:	'.kkpb-progressbar-box',
		
		'progressbarBoxPrototype'	:	jQuery('#kkpb-progressbar-prototype'),
		'nameClass'					:	'.kkpb-progressbar-name strong',
		'percentInfo'				:	'.kkpb-progressbar-content',
		'progressWidth'				:	'.kkpb-progressbar-bar',
		'progressBoxOptions'		:	'.kkpb-progressbar-opcje a',
		
		'projectName'				:	jQuery('#kkpb_project_name'),
		'projectDescription'		:	'',
		
		'progressbarName'			:	jQuery('#kkpb_name'),
		'progressbarNameLink'		:	jQuery('#kkpb-link'),
		'progressbarManual'			:	jQuery('#kkpb-auto'),
		'progressbarPercent'		:	jQuery('#kkpb_procent'),
		'progressbarVallAll'		:	jQuery('#kkpb_val_all'),
		'progressbarVallNow'		:	jQuery('#kkpb_val_now'),
		
		'progressbarNameDialog'		:	jQuery('#kkpb_name_dialog'),
		'progressbarNameLinkDialog'	:	jQuery('#kkpb-link-dialog'),
		'progressbarManualDialog'	:	jQuery('#kkpb-auto-dialog'),
		'progressbarPercentDialog'	:	jQuery('#kkpb_procent_dialog'),
		'progressbarVallAllDialog'	:	jQuery('#kkpb_val_all_dialog'),
		'progressbarVallNowDialog'	:	jQuery('#kkpb_val_now_dialog'),
		
		'inputName'					:	'kkpb-input-name[]',
		'inputNameLink'				:	'kkpb-input-link[]',
		'inputManual'				:	'kkpb-input-auto[]',
		'inputPercent'				:	'kkpb-input-progress[]',
		'inputVallAll'				:	'kkpb-input-all[]',
		'inputVallNow'				:	'kkpb-input-now[]',
		'inputStatus'				:	'kkpb-input-status[]',
		
		'deleteHendler'				:	jQuery('.kkpb-delete-hendler'),
		'editHendler'				:	jQuery('.kkpb-edit-hendler'),
		'statusHendler'				:	jQuery('.kkpb-progressbar-status a'),
		'dialogBox'					:	jQuery("#kkpb-edit-progressbar-dialog"),
		
		'editID'					:	jQuery('#idEdit'),
		'sliderClass'				:	jQuery('.slider-edit')
	},options);
}