<?php

/**
 * Ogólne ustawienia:
 */
require_once 'db.php';

global $kkplugin, $wersja_plugin, $temat, $opis;
$kkplugin = 'KKProgressbar2 Free';
$wersja_plugin = '1.1.4.1';

function kkpb_admin_content(){
	global $wpdb, $kkplugin, $wersja_plugin, $options;
	
?>
<div class="wrap">

	<div id="kkadmin-head">
		<img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/logo-kkadmin.png" alt="KKAdmin Logo" />
		<span id="kkadmin-head-plugin" style="left: 60px;"><a href="http://krzysztof-furtak.pl/" target="_blank">Krzysztof Furtak</a> - <?php echo $kkplugin; ?> v<?php echo $wersja_plugin; ?></span>
		<div id="icon-options-general" class="icon32"></div>
		<div id="kkadmin-slug">BRAVE NEW WORLD</div>
	</div>
	<?php 
	global $wpdb, $table_name;
	$table_name_old = $wpdb->prefix . "kkprogressbar";
	$table_name = $wpdb->prefix . "kkpb_projekt";
	
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name_old'") == $table_name_old):
	?>
	<div id="kkpb-db-update">
    	<p>
    		<?php echo __('Your database is out of date. Please upgrade the database, to do this, press the button below.','lang-kkprogressbar'); ?>
    	</p>
    	<div id="kkpb-db-update-text">
    		<a href="#" id="kkpb-db-update-start" class="button-primary"><?php echo __('Update','lang-kkprogressbar'); ?></a>
    		<img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/loader.gif" style="vertical-align: middle; display: none;" id="kkpb-db-update-loader" alt="Wait..." />
    	</div>
    </div>
    <?php else: ?>
	<div id="kkadmin-menu">
		<ul id="kkadmin-menu-ul">
				<li><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=kkpb-menu" class="active"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog_up.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('List of projects','lang-kkprogressbar'); ?></a></li>
				<li><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=kkpb-add-project"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('Add/Edit Project','lang-kkprogressbar'); ?></a></li>
				<li><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=kkpb-settings"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('Settings','lang-kkprogressbar'); ?></a></li>
				<li><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=kkpb-menu-documentation"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('Documentation','lang-kkprogressbar'); ?></a></li>
				<li><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=kkpb-menu-changelog"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> Changelog</a></li>
		</ul>
		<?php include 'kkpb_sidebar.php'; ?>
	</div>
	<div id="kkadmin-tresc">
		<div id="kkadmin-tresc-wew">
			<div class="kkadmin-tresc">
				<?php 
				$wp_options = get_option('kkpbsettings');
				if(empty($wp_options['use_gradient']) || $wp_options['use_gradient'] == null){
				?>	
					<div class="kkpb-alert kkpb-alert-error"><?php echo __('<strong>Plugin is not configured. It may not work correctly.</strong> Go to settings, make your selection and save settings.','lang-kkprogressbar'); ?></div>
				<?php 	
				}else{
				?>
					<script type="text/javascript">
					jQuery(document).ready(function(){
						setTimeout(function(){
							jQuery('.kkpb-alert').fadeOut('fast',function(){
								jQuery(this).remove();
							});
						}, 5000);
					});
					</script>
				<?php
				}
				
				if(isset($_POST['action']) && $_POST['action'] == 'save-project'){
					
					$id = kkpb::saveNewProject($_POST['kkpb_project_name'], $_POST['kkpb_project_description'], $_POST['kkpb_project_link']);
					if($id != null){
						?>
						<div class="kkpb-alert kkpb-alert-ok"><?php echo __('The project saved correctly.','lang-kkprogressbar'); ?></div>
						<?php
						for($i = 0, $max = count($_POST['kkpb-input-name']); $i < $max; $i++){
							if($_POST['kkpb-input-name'][$i] != ""){
								if($_POST['kkpb-input-auto'][$i] == 'true'){
									$typ = 2;
								}else{
									$typ = 1;
								}
								$idprogress = kkpb::saveNewProgressbar($id, $_POST['kkpb-input-name'][$i], $typ, $_POST['kkpb-input-progress'][$i], $_POST['kkpb-input-now'][$i], $_POST['kkpb-input-all'][$i], $_POST['kkpb-input-status'][$i]);
								if($idprogress != null){
									?>
									<div class="kkpb-alert kkpb-alert-ok"><?php echo __('Task','lang-kkprogressbar'); ?> <strong><?php echo $_POST['kkpb-input-name'][$i]; ?></strong> <?php echo __('saved correctly','lang-kkprogressbar'); ?>.</div>
									<?php
								}elseif($i >= 2){
									?>
									<div class="kkpb-alert kkpb-alert-error"><?php echo __('Task','lang-kkprogressbar'); ?> <strong><?php echo $_POST['kkpb-input-name'][$i]; ?></strong> <?php echo __('not saved. In the free version you can add two tasks for each project. Feel free to purchase the professional version.','lang-kkprogressbar'); ?>.</div>
									<?php
								}else{
									?>
									<div class="kkpb-alert kkpb-alert-error"><?php echo __('Task','lang-kkprogressbar'); ?> <strong><?php echo $_POST['kkpb-input-name'][$i]; ?></strong> <?php echo __('not saved. Please contact the plugin author','lang-kkprogressbar'); ?>.</div>
									<?php
								}
							}
						}
					}else{
						?>
						<div class="kkpb-alert kkpb-alert-error"><?php echo __('The project has not been saved. Please contact the author of the plugin.','lang-kkprogressbar'); ?></div>
						<?php
					}
					 
				}else if(isset($_POST['action']) && $_POST['action'] == 'delete-project'){
					$result = kkpb::deleteProject($_POST['id']);
					if ($result){ ?>
						<div class="kkpb-alert kkpb-alert-ok"><?php echo __('The project has been deleted.','lang-kkprogressbar'); ?></div>
					<?php }else{ ?>
						<div class="kkpb-alert kkpb-alert-error"><?php echo __('The project has not been removed. Please contact the author of the plugin.','lang-kkprogressbar'); ?></div>
					<?php }
				}
				?>
				
				<h2><?php echo __('List of projects', 'lang-kkprogressbar'); ?>:</h2>
				<table id="kkpb-table" class="widefat fixed" cellspacing="0">
			        <thead><tr class="thead">
			            <th style="width: 35px;">ID:</th>
			            <th style="width: 150px;"><?php echo __('Name', 'lang-kkprogressbar'); ?>:</th>
			            <th><?php echo __('Description', 'lang-kkprogressbar'); ?>:</th>
			            <th style="width: 25px;"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/aktywny.png" alt="" /></th>
			            <th style="width: 25px;"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/wstrzymany.png" alt="" /></th>
			            <th style="width: 25px;"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/nieaktywny.png" alt="" /></th>
			            <th colspan="2" style="width: 150px; text-align: center;"><?php echo __('Options', 'lang-kkprogressbar'); ?>:</th>
			            </tr></thead>
					<?php 
					$rows = kkpb::getAllProjects();
					
			        foreach ($rows as $row) {
						
			            ?>
			            <tr class="alternate" id="kkpb-row-<?php echo $row->id; ?>">
			                <td><?php echo  $row->id; ?></td>
			                <td><?php echo stripslashes($row->nazwa); ?></td>
			                <td><?php echo strip_tags(stripslashes($row->opis)); ?></td>
			                <td><?php echo count(kkpb::getProgressbarsOnStatus($row->id, '1')); ?></td>
			                <td><?php echo count(kkpb::getProgressbarsOnStatus($row->id, '2')); ?></td>
			                <td><?php echo count(kkpb::getProgressbarsOnStatus($row->id, '3')); ?></td>
			       			<td><form action="<?php echo home_url(); ?>/wp-admin/admin.php?page=kkpb-add-project" method="post"><input type="hidden" name="action" value="edit-project" /><input type="hidden" name="id" value="<?php echo $row->id; ?>" /><input type="submit" class="button kkpb-button" value="<?php echo __('Edit', 'lang-kkprogressbar'); ?>" /></form></td>
			                <td><form action="<?php echo home_url(); ?>/wp-admin/admin.php?page=kkpb-menu" method="post"><input type="hidden" name="action" value="delete-project" /><input type="hidden" name="id" value="<?php echo $row->id; ?>" /><input type="submit" class="button kkpb-button" value="<?php echo __('Delete', 'lang-kkprogressbar'); ?>" onclick="javascript: return confirm('<?php echo __('Please confirm the removal of the project along with all progressbarami assigned to him.', 'lang-kkprogressbar'); ?>');" /></form></td>
			            </tr>
			        <?php } ?>
			
			    </table>
			    <div id="kkpb-info-box">
					<div class="kkpb-info-widget" style="font-size: 10px;">
	                    <h2><?php echo __('Legend', 'lang-kkprogressbar'); ?>:</h2>
	                    <img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/aktywny.png" alt="Yes" style="vertical-align:middle;" /> - <?php echo __('Active', 'lang-kkprogressbar'); ?><br />
	                    <img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/wstrzymany.png" alt="Yes" style="vertical-align:middle;" /> - <?php echo __('Works suspended', 'lang-kkprogressbar'); ?><br />
	                    <img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/nieaktywny.png" alt="Yes" style="vertical-align:middle;" /> - <?php echo __('Inactive (not displayed)', 'lang-kkprogressbar'); ?><br />
	                </div>
	                <div class="kkpb-info-widget">
	                </div>
	                <div class="clear"></div>
                </div>
                <div class="updated" style="margin-top: 10px;">
					<p>If you are willing to support my work or you are looking for more options, feel free to purchase professional version of the plugin <a href="http://codecanyon.net/item/kk-progressbar-2/916225?ref=KrzysztofF" target="_blank">here</a>.</p>
				</div>
			</div>
		</div>
	</div>
	<?php endif ?>
 </div>

<?php
}

function kkpb_admin_settings(){

	global $options;
	
	$strony = get_pages();
	
	$gradient = array(
		__('Blue','lang-kkprogressbar') 	=> 	'kkpb-grad-blue'
	);
	
	$kol_grad = array(
		__('Gragient','lang-kkprogressbar')	=>	'gradient'
	);
	
	$kol_tla = array(
		__('Dark','lang-kkprogressbar')		=>	'kkpb-progressbar-content-dark',
		__('Light','lang-kkprogressbar')	=>	'kkpb-progressbar-content-light'
	);

	$options = array(
	// ==== GENERAL SETTINGS ====
	array(	'title'		=>	__('General Settings','lang-kkprogressbar'),
							'alias'		=>	'general-settings',
							'icon'		=>	WP_PLUGIN_URL . '/images/global.png',
						  	'content'	=>	array(
						  					'title_hr_1'	=>	array('type' => 'title-hr',
						  									'default'	=>	 __('Progress bar settings:','lang-kkprogressbar'),
						  									'class'		=>	'kkpb-settings-title-break'
											),
											'use_gradient'=>array(	'type'		=>	'radio-ui',
														   		'default'	=>	'gradient',
														   		'title'		=>	__('Use a solid color or gradient?','lang-kkprogressbar'),
													       		'tooltip'	=>	__('','lang-kkprogressbar'),
													       		'values'	=>	$kol_grad,
													       		'class'		=>	''
											),
											'progress_gradient'=>array(	'type'		=>	'radio-class',
																	   		'default'	=>	'kkpb-grad-blue',
																	   		'title'		=>	__('Progressbar gradient','lang-kkprogressbar'),
																       		'tooltip'	=>	__('','lang-kkprogressbar'),
																       		'values'	=> $gradient,
																			'class'		=>	'kkpb-tr-gradient'
											),
											'title_hr_2'	=>	array('type' => 'title-hr',
						  									'default'	=>	 __('Background progress bar settings:','lang-kkprogressbar'),
						  									'class'		=>	'kkpb-settings-title-break'
											),
											'progress_bg'=>array(	'type'		=>	'radio-class',
																		   		'default'	=>	'kkpb-progressbar-content-dark',
																		   		'title'		=>	__('Progressbar background','lang-kkprogressbar'),
																	       		'tooltip'	=>	__('','lang-kkprogressbar'),
																	       		'values'	=> $kol_tla,
																				'class'		=>	''
											),
											'border_color'=>array(	'type'		=>	'color-pick',
																	   		'default'	=>	'000000',
																	   		'title'		=>	__('Border color','lang-kkprogressbar'),
																       		'tooltip'	=>	__('','lang-kkprogressbar'),
																			'class'		=>	''
											),
											'title_hr_3'	=>	array('type' => 'title-hr',
						  									'default'	=>	 __('General settings:','lang-kkprogressbar'),
						  									'class'		=>	'kkpb-settings-title-break'
											),
											'project_info'=>array(	'type'		=>	'checkbox',
															   		'default'	=>	'enabled',
															   		'title'		=>	__('Show information about the project?','lang-kkprogressbar'),
														       		'tooltip'	=>	__('Do you want to view information icon? After moving the cursor on the icon, the user will see a cloud with information about the project.','lang-kkprogressbar'),
																	'class'		=>	''
											),
											'global_progress'=>array(	'type'		=>	'checkbox',
																   		'default'	=>	'enabled',
																   		'title'		=>	__('Show project progressbar?','lang-kkprogressbar'),
															       		'tooltip'	=>	__('Do you want to display a progress bar that summarizes the general progress of work on the project. It is calculated based on the average values ​​of all tasks belonging to the project.','lang-kkprogressbar'),
																		'class'		=>	''
											),
											'project_perc'=>array(	'type'		=>	'checkbox',
																	   		'default'	=>	'enabled',
																	   		'title'		=>	__('Display the percentage of completion?','lang-kkprogressbar'),
																       		'tooltip'	=>	__('Do you want to display a numeric percentage progress on the project.','lang-kkprogressbar'),
																			'class'		=>	''
											)
											
	)
	));
}

function kkpb_settings(){
	
	global $options;
	
	kkpb_admin_settings();
	
	if ( isset($_GET['page']) ) {
		
		if( $_GET['page'] == 'kkpb-settings' ) {
			
			$options_array = array();
			if ( isset($_POST['action']) && $_POST['action'] == 'save' ) {
				
				foreach ($options as $value) {
					foreach( $value['content'] as $key => $val ) {
						if( $key != 'custom_sidebar' && $key != 'title_hr_1' && $key != 'title_hr_2' && $key != 'title_hr_3' ) {
							if($_REQUEST[$key] == ''){
								$_REQUEST[$key] = null;
							}
							$options_array[$key] = $_REQUEST[$key];
						}
					}
				}
				update_option( 'kkpbsettings', $options_array );
			}
				
		}
	}
	include 'settings.php';
}

function kkpb_changelog(){
	include 'changelog.php';
}

function kkpb_add_project(){
	include 'add_progressbar.php';
}

function kkpb_documentation(){
	include 'documentation.php';
}

function kkpb_menu() {
	global $kkplugin, $wersja_plugin;
		
	add_menu_page($kkplugin, $kkplugin, 'administrator', 'kkpb-menu', 'kkpb_admin_content', WP_PLUGIN_URL.'/kkprogressbar/images/kkpb-ico.jpg');
	
	global $wpdb, $table_name;
	$table_name_old = $wpdb->prefix . "kkprogressbar";
	$table_name = $wpdb->prefix . "kkpb_projekt";
	
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name_old'") != $table_name_old){
		add_submenu_page('kkpb-menu', $kkplugin, 'Add/Edit Project', 'administrator', 'kkpb-add-project', 'kkpb_add_project');
		add_submenu_page('kkpb-menu', $kkplugin, 'Settings', 'administrator', 'kkpb-settings', 'kkpb_settings');
		add_submenu_page('kkpb-menu', $kkplugin, 'Documentation', 'administrator', 'kkpb-menu-documentation', 'kkpb_documentation');
		add_submenu_page('kkpb-menu', $kkplugin, 'Changelog', 'administrator', 'kkpb-menu-changelog', 'kkpb_changelog');
	}
}

add_action('admin_menu', 'kkpb_menu');
