<?php

/**
 * Ogólne ustawienia:
 */
require_once 'db.php';

global $kkplugin, $wersja_plugin, $wpdb;

if(isset($_POST['action']) && $_POST['action'] == 'save-project'){
	$return = kkpb::updateProject($_POST['id'], $_POST['kkpb_project_name'], $_POST['kkpb_project_description'], $_POST['kkpb_project_link']);
}else{
	$return = false;
}

if((isset($_POST['action']) && $_POST['action'] == 'edit-project') || (isset($_POST['action']) && $_POST['action'] == 'save-project')){
	$project = kkpb::getProjectWithProgressbars($_POST['id']);
	$act = '/wp-admin/admin.php?page=kkpb-add-project';
	$edit = true;
}else{
	$project = null;
	$act = '/wp-admin/admin.php?page=kkpb-menu';
	$edit = false;
}
	
?>
<div class="wrap">
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/js/add-progressbar-form-conf.js"></script>
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/js/add-progressbar-form-min.js"></script>

	<script type="text/javascript">
		jQuery(document).ready(function(){
			formConfig = new kkProgressbarFormConfig();
			form = new kkpbAddFormConstr;
			form.kkpbAddForm.initialize(formConfig.options);
		});
	</script>
	<div id="kkadmin-head">
		<img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/logo-kkadmin.png" alt="KKAdmin Logo" />
		<span id="kkadmin-head-plugin" style="left: 60px;"><a href="http://krzysztof-furtak.pl/" target="_blank">Krzysztof Furtak</a> - <?php echo $kkplugin; ?> v<?php echo $wersja_plugin; ?></span>
		<div id="icon-options-general" class="icon32"></div>
		<div id="kkadmin-slug">BRAVE NEW WORLD</div>
	</div>
	
	<div id="kkadmin-menu">
		<ul id="kkadmin-menu-ul">
				<li><a href="/wp-admin/admin.php?page=kkpb-menu"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('List of projects','lang-kkprogressbar'); ?></a></li>
				<li><a href="/wp-admin/admin.php?page=kkpb-add-project" class="active"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog_up.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('Add/Edit Project','lang-kkprogressbar'); ?></a></li>
				<li><a href="/wp-admin/admin.php?page=kkpb-settings"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('Settings','lang-kkprogressbar'); ?></a></li>
				<li><a href="/wp-admin/admin.php?page=kkpb-menu-documentation"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('Documentation','lang-kkprogressbar'); ?></a></li>
				<li><a href="/wp-admin/admin.php?page=kkpb-menu-changelog"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> Changelog</a></li>
		</ul>
		<?php include 'kkpb_sidebar.php'; ?>
	</div>
	<div id="kkadmin-tresc">
		<div id="kkadmin-tresc-wew">
			<form action="<?php echo $act ?>" id="kkpb-add-form" method="post">
			<input type="hidden" name="action" value="save-project" />
			<input type="hidden" name="id" id="idEdit" value="<?php if(isset($project->id)) echo $project->id; ?>" />
			
			<div id="kkadmin-tresc-" class="kkadmin-tresc">
				<div class="kkpb-alert kkpb-alert-ok kkpb-ajax-ok" style="display: none;"><?php echo __('Data saved correctly.','lang-kkprogressbar'); ?></div>
				<div class="kkpb-alert kkpb-alert-error kkpb-ajax-error" style="display: none;"><?php echo __('Error: An error occurred when saving data. Please try again or contact the author of the plugin.','lang-kkprogressbar'); ?></div>
				<?php if($return): ?>
					<div class="kkpb-alert kkpb-alert-ok"><?php echo __('The project saved correctly.','lang-kkprogressbar'); ?></div>
					<script type="text/javascript">
					jQuery(document).ready(function(){
						setTimeout(function(){
							jQuery('.kkpb-alert').fadeOut('fast',function(){
								jQuery(this).remove();
							});
						}, 5000);
					});
					</script>
				<?php endif;?>
			
				<table class="kkadmin-option-content">
					
					<tr>
						<td class="kk-admin-info">
							<div class="kkadmin-info kk-tooltip" title="Project name.">
							<img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/ico_info.png" alt="Info" style="vertical-align: middle;" />
						</div>
						</td>
						<td class="kk-admin-label">
							<label for="kkpb_project_name"><?php echo __('Project name:','lang-kkprogressbar'); ?> </label>
						</td>
						<td class="kk-admin-settings-val">
							<div class="kk-admin-input">
								<input type="text" name="kkpb_project_name" id="kkpb_project_name" value="<?php if(isset($project->nazwa)) echo $project->nazwa; ?>" />
							</div>
						</td>
					</tr>
					<tr>
						<td class="kk-admin-info">
							<div class="kkadmin-info kk-tooltip" title="Link to project site.">
							<img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/ico_info.png" alt="Info" style="vertical-align: middle;" />
						</div>
						</td>
						<td class="kk-admin-label">
							<label for="kkpb_project_name"><?php echo __('Link to project site:','lang-kkprogressbar'); ?> </label>
						</td>
						<td class="kk-admin-settings-val">
							<div class="kk-admin-input">
								<input type="text" name="kkpb_project_link" id="kkpb_project_link" value="<?php if(isset($project->link)) echo $project->link; ?>" />
							</div>
						</td>
					</tr>
					<tr>
						<td class="kk-admin-info">
							<div class="kkadmin-info kk-tooltip" title="Project description.">
							<img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/ico_info.png" alt="Info" style="vertical-align: middle;" />
						</div>
						</td>
						<td class="kk-admin-label" colspan="2" style="width: auto;">
							<label for="kkpb_project_description"><?php echo __('Project description:','lang-kkprogressbar'); ?> </label>
							<div class="kk-admin-editor">
								<?php 
								if(isset($project->opis)){
									$content = stripslashes($project->opis);
								}
								if(floatval(get_bloginfo('version')) >= 3.3){
									wp_editor( $content, "kkpb_project_description", $settings = array() ); 
								}else{
								?>
									<textarea name="kkpb_project_description" id="kkpb_project_description" style="width: 100%; height: 150px;"><?php echo $content; ?></textarea>
								<?php } ?>
							</div>
						</td>
					</tr>
					<?php if($edit):?>
					<tr>
						<td class="kk-admin-label" colspan="3"><input type="submit" value="Save project" class="button-primary kk-button" style="float: right;" /></td>
					</tr>
					<?php endif; ?>
					<tr>
						<td class="kk-admin-info">
							<div class="kkadmin-info kk-tooltip" title="<?php echo $value['tooltip']; ?>">
							<img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/ico_info.png" alt="Info" style="vertical-align: middle;" />
						</div>
						</td>
						<td class="kk-admin-label" id="kkpb-box" colspan="2" style="width: auto;">
							<label for="<?php echo $key; ?>"><?php echo __('Tasks:','lang-kkprogressbar'); ?> </label> <a href="#" class="button-primary kk-button" id="kkpb-add-new-progress"><?php echo __('Add new task', 'lang-kkprogressbar'); ?></a>
							<div id="kkpb-add-bar-form" style="display: none;">
								<table style="width: 100%;">
									<tr><td class="kkpb-small-label">
										<span><?php echo __('Name', 'lang-kkprogressbar'); ?>: </span></td><td><input type="text" class="kkpb-long-input" id="kkpb_name" name="kkpb_name" />
									</td></tr>
									<tr><td class="kkpb-small-label">
										<span><?php echo __('Manual percentage', 'lang-kkprogressbar'); ?>: </span></td><td><input type="checkbox" class="kkpb-auto" id="kkpb-auto" name="kkpb-auto" checked="checked" />
									</td></tr>
									<tr class="kkpb-progress-row"><td class="kkpb-small-label">
										<?php echo __('Progress', 'lang-kkprogressbar'); ?>:
									</td><td>
					             		<div class="slider-input"><input type="text" size="2" class="kkpb_procent" name="kkpb_procent" id="kkpb_procent" value="0" /> %</div>
					             		<div class="slider"><div class="slider-edit"></div></div>
					             	</td></tr>
					             	<tr class="kkpb-auto-row" style="display: none;"><td class="kkpb-small-label">
					             		<span><?php echo __('The target value', 'lang-kkprogressbar'); ?>: </span></td><td><input type="text" class="kkpb-long-input" id="kkpb_val_all" name="kkpb_val_all" />
					             	</td></tr>
					             	<tr class="kkpb-auto-row" style="display: none;"><td class="kkpb-small-label">
					             		<span><?php echo __('The current value', 'lang-kkprogressbar'); ?>: </span></td><td><input type="text" class="kkpb-long-input" id="kkpb_val_now" name="kkpb_val_now" />
					             	</td></tr>
					             	<tr>
										<td colspan="2" style="text-align: right; padding: 20px 0;">
											<span id="loading-box" style="display: none;"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/loading.gif" alt="" style="vertical-align: middle;" /> <span style="vertical-align: middle;">Zapisuję ...</span></span>
											<a href="#" class="button-primary kk-button" id="kkpb-progressbar-save" style="float: none;"><?php echo __('Save task', 'lang-kkprogressbar'); ?></a>
											<a href="#" id="kkpb-cancel-form" style="float: none;" class="button kk-button"><?php echo __('Cancel', 'lang-kkprogressbar'); ?></a>
										</td>
									</tr>
					            </table>
					       	</div>
					       	<div id="kkpb-progressbarsbox-box"></div>
					       	
					       	<div class="kkpb-progressbar-box" id="kkpb-progressbar-prototype">
					       		<input type="hidden" name="kkpb-input-name[]" />
					       		<input type="hidden" name="kkpb-input-auto[]" />
					       		<input type="hidden" name="kkpb-input-progress[]" />
					       		<input type="hidden" name="kkpb-input-all[]" />
					       		<input type="hidden" name="kkpb-input-now[]" />
					       		<input type="hidden" name="kkpb-input-status[]" value="1" />
					       		
					       		<table style="width: 100%;"><tr>
					       			<td class="kkpb-progressbar-name"><strong></strong></td>
					       			<td class="kkpb-progressbar-progress" style="padding: 0 10px;">
					       				<div class="kkpb-progressbar-content kkpb-progressbar-content-dark" title="">
				                            <div class="kkpb-progressbar-bar" style="height: 20px; width: 0%;"></div>
				                        </div>
									</td>
					       			<td class="kkpb-progressbar-status">
					       				<a href="#" class="kkpb-status-aktywny"></a>
					       			</td>
					       			<td class="kkpb-progressbar-opcje">
					       				<a href="#ID" class="kkpb-edit-hendler"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/edit.png" alt="Edit" /></a>
					       				<a href="#ID" class="kkpb-delete-hendler"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/delete.png" alt="Delete" /></a>
					       			</td>
					       		</tr></table>
					       	</div>
					       <?php 
					       if( isset($project->progressbars) && (count($project->progressbars) > 0)) :
								
							foreach ($project->progressbars as &$dane){
							
					       		if($dane->typ == 1){
					       			$procent = $dane->procent;
					       		}elseif ($dane->typ == 2){
					       			$procent = ($dane->aktualna_wartosc / $dane->docelowa_wartosc) * 100;
					       		}
					       		
					       		if($procent > 100){
					       			$procentBar = 100;
					       		}else{
					       			$procentBar = $procent;
					       		}
					       		
					       		if($dane->status == 1){
					       			$class = 'kkpb-status-aktywny';
					       		}else if($dane->status == 2){
					       			$class = 'kkpb-status-wstrzymany';
					       		}else if($dane->status == 3){
					       			$class = 'kkpb-status-nieaktywny';
					       		}else{
					       			$class = 'kkpb-status-aktywny';
					       		}
							?>
							<div class="kkpb-progressbar-box">
							<input type="hidden" name="kkpb-input-name[]" value="<?php echo $dane->nazwa; ?>" />
							<input type="hidden" name="kkpb-input-auto[]" value="<?php echo $dane->typ; ?>" />
							<input type="hidden" name="kkpb-input-progress[]" value="<?php echo $dane->procent; ?>" />
							<input type="hidden" name="kkpb-input-all[]" value="<?php echo $dane->docelowa_wartosc; ?>" />
							<input type="hidden" name="kkpb-input-now[]" value="<?php echo $dane->aktualna_wartosc; ?>" />
							<input type="hidden" name="kkpb-input-status[]" value="<?php echo $dane->status; ?>" />
							
							<table style="width: 100%;"><tr>
							<td class="kkpb-progressbar-name"><strong><?php echo $dane->nazwa; ?></strong></td>
							<td class="kkpb-progressbar-progress" style="padding: 0 10px;">
							<div class="kkpb-progressbar-content kkpb-progressbar-content-dark" title="<?php echo $procent; ?>%">
							<div class="kkpb-progressbar-bar" style="height: 20px; width: <?php echo $procentBar; ?>%;"></div>
							</div>
							</td>
							<td class="kkpb-progressbar-status">
							<a href="#" class="<?php echo $class; ?>"></a>
							</td>
							<td class="kkpb-progressbar-opcje">
							<a href="#<?php echo $dane->idprogress; ?>" class="kkpb-edit-hendler"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/edit.png" alt="Edit" /></a>
							<a href="#<?php echo $dane->idprogress; ?>" class="kkpb-delete-hendler"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/delete.png" alt="Delete" /></a>
							</td>
							</tr></table>
							</div>
									
							<?php
								}
					       	endif;
							?>
						</td>
					</tr>
					<?php if(!$edit): ?>
					<tr>
						<td class="kk-admin-label" colspan="3"><input type="submit" value="<?php echo __('Save project', 'lang-kkprogressbar'); ?>" class="button-primary kk-button" style="float: right;" /></td>
					</tr>
					<?php endif; ?>
				</table>
			</div>
			</form>
			<div style="display: none;">
				<div id="kkpb-edit-progressbar-dialog">
                    <form id="kkpb-edit-progress-box">
					<table style="width: 100%;">
						<tr><td class="kkpb-small-label">
							<span><?php echo __('Name', 'lang-kkprogressbar'); ?>: </span></td><td><input type="text" class="kkpb-long-input" id="kkpb_name_dialog" name="kkpb_name_dialog" />
						</td></tr>
						<tr><td class="kkpb-small-label">
							<span><?php echo __('Manual percentage', 'lang-kkprogressbar'); ?>: </span></td><td><input type="checkbox" class="kkpb-auto" id="kkpb-auto-dialog" name="kkpb-auto" checked="checked" />
						</td></tr>
						<tr class="kkpb-progress-row"><td class="kkpb-small-label">
							<?php echo __('Progress', 'lang-kkprogressbar'); ?>:
						</td><td>
		             		<div class="slider-input"><input type="text" size="2" class="kkpb_procent" name="kkpb_procent_dialog" id="kkpb_procent_dialog" /> %</div>
		             		<div class="slider"><div class="slider-edit"></div></div>
		             	</td></tr>
		             	<tr class="kkpb-auto-row" style="display: none;"><td class="kkpb-small-label">
		             		<span><?php echo __('The target value', 'lang-kkprogressbar'); ?>: </span></td><td><input type="text" class="kkpb-long-input" id="kkpb_val_all_dialog" name="kkpb_val_all_dialog" />
		             	</td></tr>
		             	<tr class="kkpb-auto-row" style="display: none;"><td class="kkpb-small-label">
		             		<span><?php echo __('The current value', 'lang-kkprogressbar'); ?>: </span></td><td><input type="text" class="kkpb-long-input" id="kkpb_val_now_dialog" name="kkpb_val_now_dialog" />
		             	</td></tr>
		            </table>
                    </form>
				</div>
			</div>
		</div>
	</div>
 </div>
<div class="kkpb-ajax-info kkpb-ajax-loading"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/loader.gif" alt="Loading" style="vertical-align: middle;" /> <span style="vertical-align: middle;"> <?php echo __('In progress', 'lang-kkprogressbar'); ?> ...</span></div>
