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
				<li><a href="/wp-admin/admin.php?page=kkpb-menu"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('List of projects','lang-kkprogressbar'); ?></a></li>
				<li><a href="/wp-admin/admin.php?page=kkpb-add-project"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('Add/Edit Project','lang-kkprogressbar'); ?></a></li>
				<li><a href="/wp-admin/admin.php?page=kkpb-settings"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('Settings','lang-kkprogressbar'); ?></a></li>
				<li><a href="/wp-admin/admin.php?page=kkpb-menu-documentation" class="active"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog_up.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> <?php echo __('Documentation','lang-kkprogressbar'); ?></a></li>
				<li><a href="/wp-admin/admin.php?page=kkpb-menu-changelog"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/blog.png" alt="" style="vertical-align: middle; margin-right: 15px;" /> Changelog</a></li>
		</ul>
	</div>
	<div id="kkadmin-tresc">
	<div id="kkadmin-tresc-wew">
	<div id="tresc">
		<h2>Professional version of plugin:</h2>
		<p>Thank you for installing free version of KKProgressbar2 plugin. Options in that version are fairly limited as compared to professional version, however, plugin has the same possibilities as version 1.1.3 with some new updates.</p>
		<p>Please find below all the options that you can get by purchasing professional version:</p>
		<ul>
			<li>+ Unlimited number of projects that can be added</li>
			<li>+ Much more advanced option of adjusting the display of progressbar to your personal needs</li>
			<li>+ Free-of-charge support</li>
			<li>+ Free and frequent updates providing new options to the plugin.</li>
		</ul>
		<p>If you are willing to support my work or you are looking for more options, feel free to purchase professional version of the plugin <a href="http://codecanyon.net/item/kk-progressbar-2/916225?ref=KrzysztofF" target="_blank">here</a>.</p>
		<hr />
		<h2>Info:</h2>
		<p>
		Plugin shows/indicates progress that has been made on projects or articles.
		</p>	
		<p>
		KKProgressbar 2 is a totally new uncover of my plugin. This plugin allows you to show the progress made on your projects. The new version (2.x) segregates projects and tasks so that you can see which tasks need to be completed to finish a project. The number of projects and tasks that can be added is unlimited. Users of the old plugin (1.1.x Free and 1.3.x Premium) do not need to be worried as I took care of them as well. New plugin will detect his older friend and convert the data to the new version under the condition that user allows for it.
		</p>	
		<hr />
		<h2>Installation:</h2>
		<ol>
			 	<li>Unpack files from the patch you have purchased.</li>
				<li>Copy folder 'kkprogress' to a folder on your own server. To do it you might want to use programs meant for establishing connection with FTP server (personally, I would recommend <a href="http://filezilla-project.org/download.php" target="_blank">FileZilla</a> program. <a href="http://wiki.filezilla-project.org/Using" target="_blank">You can find guide on how to use it here</a>). All the data needed for establishing connection with FTP server should go together with a purchased server.</li>
				<li>Next, log in to the administrative panel of your blog.</li>
				<li>Go to the tab called 'Plugins'.</li>
				<li>Find plug-in called 'KK Progressbar' and click on 'Activate'</li>
				<li>On the left hand side, 'KK Progressbar' tab should appear. From that tab, you can manage your plug-in.</li>
		</ol>
		<div class="clear-div"></div>
		<hr />
		<h2>How to use plugin:</h2>
		
		<h2>I. How to add a project?</h2>
		<p>1. Go to 'Add/Edit Project' tab.</p>
		<p>2. Fill out a form.</p>
		<p>3. Click on 'Add new task' button if you want to add a task to a project right away.</p>
		<p>4. Continue on filling out the form. At this point you can add as many tasks to a project as you wish.</p>
		<p style="text-align: center;"><a href="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/screen1.jpg" class="zoom"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/screen1.jpg" style="width: 300px; padding: 5px; border: 1px #ccc solid;" alt="" /></a></p>
		<p>5. Click on 'Save project'.</p>

		<h2>II. How to add a task to an existing project.</h2>
		<p>1. Go to list of projects.</p>
		<p>2. Click on 'Edit' button which is next to a project that you want to edit.</p>
		<p style="text-align: center;"><a href="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/screen2.jpg" class="zoom"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/screen2.jpg" style="width: 300px; padding: 5px; border: 1px #ccc solid;" alt="" /></a></p>
		<p>3. You should be now moved to a tab of project edition.</p>
		<p>4. Click on 'Add new task'.</p>
		<p>5. Fill out a form and click on 'Add task'.</p>
		<p style="text-align: center;"><a href="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/screen3.jpg" class="zoom"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/screen3.jpg" style="width: 300px; padding: 5px; border: 1px #ccc solid;" alt="" /></a></p>
		<p>6. Task will be saved automatically. There is no need to save the entire project.</p>

		<h2>III. How to save a colour in settings?</h2>
		<p>1. Click on a random setting field to choose a colour.</p>
		<p>2. You should be prompted with a window where you can a colour from.</p>
		<p style="text-align: center;"><a href="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/screen4.jpg" class="zoom"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/screen4.jpg" style="width: 300px; padding: 5px; border: 1px #ccc solid;" alt="" /></a></p>
		<p>3. Click on a coloured icon to save the changes.</p>
		
		<hr />
		<h2>Thank you!</h2>
		<p>
			<strong>Thank you for purchasing my product!</strong> Do not hesitate to contact me in case of any further questions or concerns. 
			<br /><strong>You can reach me at:</strong> <a href="mailto:krzysztof.furtak@gmail.com" target="_blank">krzysztof.furtak@gmail.com</a>
		</p>
		<p>Regards,<br />
		<img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/PODPIS.jpg" alt="Krzysztof Furtak" />
		</p>
		<div class="clear-div"></div>
	</div>
		</div>
	</div>
</div>

