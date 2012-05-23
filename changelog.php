<?php

/**
 * Ogólne ustawienia:
 */

global $kkplugin, $wersja_plugin, $wpdb;

?>
<div class="wrap">

	<div id="kkadmin-head">
		<img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/logo-kkadmin.png" alt="KKAdmin Logo" />
		<span id="kkadmin-head-plugin" style="left: 60px;"><a href="http://krzysztof-furtak.pl/" target="_blank">Krzysztof Furtak</a> - <?php echo $kkplugin; ?> v<?php echo $wersja_plugin; ?></span>
		<div id="icon-options-general" class="icon32"></div>
		<div id="kkadmin-slug">BRAVE NEW WORLD</div>
	</div>
	
	<div id="kkadmin-menu">
		<ul id="kkadmin-menu-ul">
				<li><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=kkpb-menu"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('List of projects','lang-kkprogressbar'); ?></a></li>
				<li><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=kkpb-add-project"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('Add/Edit Project','lang-kkprogressbar'); ?></a></li>
				<li><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=kkpb-settings"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('Settings','lang-kkprogressbar'); ?></a></li>
				<li><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=kkpb-menu-documentation"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('Documentation','lang-kkprogressbar'); ?></a></li>
				<li><a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=kkpb-menu-changelog" class="active"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog_up.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> Changelog</a></li>
		</ul>
		<?php include 'kkpb_sidebar.php'; ?>
	</div>
	<div id="kkadmin-tresc">
		<div id="kkadmin-tresc-wew">
			<h2>Changelog:</h2>
			<h2>= 1.1.4.1 =</h2>
			<ul>
				<li>FIX : Improved styles for jQuery UI.</li>
				<li>FIX : Improved styles for the description of project.</li>
				<li>CHANGE : Small fixes in the admin interface.</li>
			</ul>
			
			<h2>= 1.1.4 =</h2>
			<ul>
			<li>NEW: Totally new uncover of my plugin.</li>
			</ul>
			
			<h2>= 1.3.2 Hotfix =</h2>
			<ul>
			<li>FIX: Plugin has been adjusted to a new Wordpress version (v.3.1)</li>
			<li>FIX: Corrected progressbar display in edit window</li>
			</ul>
			
			<h2>= 1.3.1 Hotfix =</h2>
			<ul>
			<li>FIX: Issue with a framed cloud showing up has been fixed</li>
			<li>FIX: Russian translation has been updated</li>
			</ul>
			
			<h2>= 1.3 =</h2>
			<ul>
			<li>NEW: Tag allows you to either turn a cloud on or off</li>
			<li>NEW: Editor which allows to change progress bar description</li>
			<li>NEW: Option which allows to turn off message showing progress of project</li>
			<li>NEW: Adding translation in Russian</li>
			<li>FIX: Problem with apostrophes and quotation marks has been fixed</li>
			<li>FIX: Function centering cloud with description has been fixed</li>
			<li>FIX: Correction of Polish translation</li>
			</ul>
			
			<h2>= 1.1.2 =</h2>
			<ul>
			<li>CHANGE: Plugin is fully based on Ajax (no need to reload a page ).</li>
			<li>CHANGE: Modified view of plugin settings.</li>
			<li>FIX: Plugin bug causing issues under Wordpress 3.0</li>
			</ul>
			
			<h2>= 1.1.1 =</h2>
			<ul>
			<li>FIX: Settings save</li>
			</ul>
			
			<h2>= 1.1 =</h2>
			<ul>
			<li>NEW: Some settings for progress bar looks</li>
			</ul>
			
			<h2>= 1.0.1 =</h2>
			<ul>
			<li>FIX: Some bug in javascript functions</li>
			</ul>
			
			<h2>= 1.0 =</h2>
			<ul>
			<li>NEW: Beta release</li>
			</ul>
		</div>
	</div>
</div>

