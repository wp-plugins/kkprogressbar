<?php

// Bootstrap file for getting the ABSPATH constant to wp-load.php
$wp_include = "../wp-load.php";
$i = 0;
while (!file_exists($wp_include) && $i++ < 10) {
  $wp_include = "../$wp_include";
}

// let's load WordPress
require($wp_include);

// check for rights
if ( !is_user_logged_in() || !current_user_can('edit_posts') ) 
	wp_die(__("You are not allowed to be here"));
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>KKProgressbar Shortcodes</title>
	<meta charset="UTF-8" /> 
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/js/jquery-1.6.js"></script>
	<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script type="text/javascript" src="<?php bloginfo('wpurl'); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/js/tinymce.js?v=1.0"></script>
	<style>
		.required { color: red }
		.error { color: red; clear: both; margin-top: 10px }
		input[type=text] { padding: 4px; width: 400px }
	</style>
	<base target="_self" />
	<script type="text/javascript">

		function changeProjectType(){
			if(jQuery('#kkpb_short_type').val() === 'true'){
				jQuery('.kkpb_short_task_choose').hide();
				jQuery('.kkpb_short_project_choose').show();
			}else{
				jQuery('.kkpb_short_task_choose').show();
				jQuery('.kkpb_short_project_choose').hide();
			}
		}
	
		jQuery(document).ready(function(){
			changeProjectType();
			jQuery('#kkpb_short_type').live('change', function(){
				changeProjectType();
			});
		});
	</script>
</head>
<body id="link" onload="document.body.style.display='';document.getElementById('category_panel').focus();" style="display: none">
	<form name="kkpb_shortcode" id="kkpb_shortcode" action="#">
		<div class="tabs">
			<ul>
				<li id="mail_tab" class="current"><span><a href="javascript:mcTabs.displayTab('mail_tab','mail_panel');" onmousedown="javascript: return false;">KKProgresbar</a></span></li>
			</ul>
		</div>
		
		<div class="panel_wrapper" style="height: 310px; overflow: auto">
			
			<div id="category_panel" class="panel current">
				<fieldset>
					<legend>Options: </legend>
					<table border="0" cellpadding="0" cellspacing="2">
						<tr>
							<td><label for="kkpb_short_type">Type: </label></td>
							<td>
								<select name="kkpb_short_type" id="kkpb_short_type">
									<option value="true">Project</option>
									<option value="false">Task</option>
								</select>
							</td>
						</tr>
						<tr class="kkpb_short_task_choose">
							<td><label for="kkpb_short_task">Task: </label></td>
							<td>
								<select name="kkpb_short_task" id="kkpb_short_task">
								<?php 
									$prog = kkpb::getAllProgressbars();
									foreach ($prog as $progressbar){
										?>
										<option value="<?php echo $progressbar->idprogress; ?>"><?php echo kkpb::getProjectName($progressbar->idprojekt); ?> - <?php echo $progressbar->nazwa; ?></option>
										<?php 
									}
								?>
								</select>
							</td>
						</tr>
						<tr class="kkpb_short_project_choose">
							<td><label for="category_panel_link">Project with task? </label></td>
							<td>
								<input type="radio" id="kkpb_short_project_with_task_yes" name="kkpb_short_project_with_task" value="true" checked="checked" /><label for="kkpb_short_project_with_task_yes">Yes</label>
								<input type="radio" id="kkpb_short_project_with_task_no" name="kkpb_short_project_with_task" value="false" /><label for="kkpb_short_project_with_task_no">No</label>
							</td>
						</tr>
						<tr class="kkpb_short_project_choose">
							<td><label for="kkpb_short_project">Project: </label></td>
							<td>
								<select name="kkpb_short_project" id="kkpb_short_project">
								<?php 
									$prog = kkpb::getAllProjects();
									foreach ($prog as $project){
										?>
										<option value="<?php echo $project->id; ?>"><?php echo $project->nazwa; ?></option>
										<?php 
									}
								?>
								</select>
							</td>
						</tr>
					</table>
				</fieldset>
			</div>
		</div>

		<div class="mceActionPanel">
			<div style="float: left">
				<input type="button" id="cancel" name="cancel" value="<?php echo "Cancel"; ?>" onclick="tinyMCEPopup.close();" />
			</div>

			<div style="float: right">
				<input type="submit" id="insert" name="insert" value="<?php echo "Insert"; ?>" />
			</div>
			<p class="error"></p>
		</div>
	</form>
</body>
</html>
