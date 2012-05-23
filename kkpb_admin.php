<?php
/*
  Plugin Name: KKProgressbar2 Free
  Plugin URI: http://krzysztof-furtak.pl/kk-progress-bar-2/
  Description: Plugin shows/indicates progress that has been made on projects. KKProgressbar2 is a totally new uncover of my plugin. This plugin allows you to show the progress made on your projects. The new version (2.x) segregates projects and tasks so that you can see which tasks need to be completed to finish a project. The number of projects and tasks that can be added is unlimited. Users of the old plugin (1.1.x Free and 1.3.x Premium) do not need to be worried as I took care of them as well. New plugin will detect his older friend and convert the data to the new version under the condition that user allows for it. 
  Version: 1.1.4.2
  Author: Krzysztof Furtak
  Author URI: http://krzysztof-furtak.pl
 */

add_action('init', 'kkpb_load_translation');
require_once('kkpb_prezentacja.php');

function kkpb_load_translation() {
    $lang = get_locale();
    if (!empty($lang)) {
        $moFile = dirname(plugin_basename(__FILE__)) . "/lang";
        $moKat = dirname(plugin_basename(__FILE__));

        load_plugin_textdomain("lang-kkprogressbar", false, $moFile);
    }
}

function kkpb_admin_enqueue_scripts(){
	
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
  	
  	wp_register_script('kk-ui-widget-js', WP_PLUGIN_URL .'/kkprogressbar/js/jquery-ui-1.8.16.custom.js', array('jquery-ui-core'), '1.0.0');
	wp_enqueue_script('kk-ui-widget-js');
	
	wp_enqueue_style('kk-pb-css', WP_PLUGIN_URL .'/kkprogressbar/css/kkpb.css');
	wp_enqueue_style('kk-pb-css', WP_PLUGIN_URL .'/kkprogressbar/css/kkpb-front.css');
	wp_register_script('kk-pb-js', WP_PLUGIN_URL .'/kkprogressbar/js/kkpb.js', array('jquery'), '2.0.1');
	wp_enqueue_script('kk-pb-js');
    
    /* ============= CHECKBOX PLUGIN ============= */
    wp_register_script('kk-checkbox', WP_PLUGIN_URL .'/kkprogressbar/js/iphone-style-checkboxes.js');
    wp_enqueue_script('kk-checkbox');
    wp_enqueue_style('kk-checkbox-css', WP_PLUGIN_URL .'/kkprogressbar/css/iphone-style-checkboxes-css.css');
    
    /* ============= ColorPicker PLUGIN ============= */
    wp_enqueue_style('kk-admin-css-color', WP_PLUGIN_URL .'/kkprogressbar/css/colorpicker.css');
    wp_register_script('kk-admin-color', WP_PLUGIN_URL .'/kkprogressbar/js/colorpicker.js', array('jquery'), '1.0.0');
    wp_enqueue_script('kk-admin-color');
    
    /* ============= Validator PLUGIN ============= */
    wp_register_script('kk-admin-val', WP_PLUGIN_URL .'/kkprogressbar/js/jquery.validate.min.js', array('jquery'), '1.0.0');
    wp_enqueue_script('kk-admin-val');
    wp_register_script('kk-admin-val-met', WP_PLUGIN_URL .'/kkprogressbar/js/additional-methods.js', array('jquery'), '1.0.0');
    wp_enqueue_script('kk-admin-val-met');
    
    wp_enqueue_style('kk-jquery-ui-css', WP_PLUGIN_URL .'/kkprogressbar/css/black-tie/jquery-ui-1.8.16.custom.css');
}

function kkpb_enqueue_scripts(){
   	wp_enqueue_script('jquery');
   	
	wp_enqueue_style('kkpb-css', WP_PLUGIN_URL .'/kkprogressbar/css/kkpb-front.css');
	wp_register_script('kkpb-js', WP_PLUGIN_URL .'/kkprogressbar/js/kkpb-front.js', array('jquery'), '1.0.0');
	wp_enqueue_script('kkpb-js');
	
	/* ============= Tooltip PLUGIN ============= */
	wp_enqueue_style('kkpb-css-tip', WP_PLUGIN_URL .'/kkprogressbar/css/jquery.qtip.css');
	wp_register_script('kkpb-tip', WP_PLUGIN_URL .'/kkprogressbar/js/jquery.qtip.min.js', array('jquery'), '1.0.0');
	wp_enqueue_script('kkpb-tip');
}

add_action('init', 'kkpb_enqueue_scripts');
add_action('admin_enqueue_scripts', 'kkpb_admin_enqueue_scripts');

/* instalacja */

function kkpb_install() {
	
	add_option('kkpbsettings');
	
    global $wpdb;
	$table_name_new = $wpdb->prefix . "kkpb_projekt";
    $table_name_new_a = $wpdb->prefix . "kkpb_progress";
    
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name_new'") != $table_name_new) {
    	$sql = "CREATE TABLE  " . $table_name_new . " (
		 	`id` INT NOT NULL AUTO_INCREMENT ,
		  	`id_post` INT NULL DEFAULT NULL ,
		  	`nazwa` VARCHAR(255) NULL ,
		  	`opis` TEXT NULL ,
			`link` VARCHAR(255) NULL ,
		  	`typ` INT NULL ,
		  	PRIMARY KEY (`id`) )
			ENGINE = InnoDB;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        $insert = "INSERT INTO  " . $table_name_new . " (
			`id` ,
			`id_post` ,
			`nazwa` ,
			`opis` ,
			`link`,
			`typ`
			)
			VALUES (
			NULL , NULL ,  'Test',  'Test of description.', NULL,  '2'
			)";
        $results = $wpdb->query($insert);
    }
	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name_new_a'") != $table_name_new_a) {
    	$sql = "CREATE TABLE  " . $table_name_new_a . " (
		 	 	`idprogress` INT NOT NULL AUTO_INCREMENT ,
				`idprojekt` INT NOT NULL ,
				`nazwa` VARCHAR(255) NULL ,
				`typ` INT NULL DEFAULT 1 ,
				`procent` INT NULL DEFAULT 0 ,
				`aktualna_wartosc` INT NULL DEFAULT 0 ,
				`docelowa_wartosc` INT NULL DEFAULT 0 ,
				`status` INT NULL DEFAULT 1 ,
				PRIMARY KEY (`idprogress`) )
				ENGINE = InnoDB;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        $insert = "INSERT INTO  " . $table_name_new_a . " (
			`idprogress` ,
			`idprojekt` ,
			`nazwa` ,
			`typ` ,
			`procent` ,
			`aktualna_wartosc` ,
			`docelowa_wartosc` ,
			`status`
			)
			VALUES (
			NULL ,  '1',  'Progressbar test',  '1',  '84',  '0',  '0',  '1'
			)";
        $results = $wpdb->query($insert);
    }    
    
}

register_activation_hook(__FILE__, 'kkpb_install');
/* koniec instalacja */
    
if (is_admin ()) {
	require 'admin-interface.php';
}
/* koniec admin */
require_once ('db.php');
require_once ('ajax.php');

?>
