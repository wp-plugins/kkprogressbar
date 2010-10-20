<?php
/*
  Plugin Name: KK ProgressBar
  Plugin URI: http://krzysztof-furtak.pl/2010/06/wp-kk-progressbar-plugin/
  Description: Plugin shows/indicates progress that has been made on projects or articles.
  Version: 1.1.2
  Author: Krzysztof Furtak
  Author URI: http://krzysztof-furtak.pl
 */
add_action('init', 'kkpb_addJavaScript');
add_action('admin_init', 'kkpb_admin_init');
add_action('init', 'kkpb_load_translation');

require_once('kkpb_prezentacja.php');

function kkpb_admin_init() {
    /* Register our stylesheet. */
    wp_register_style('kkpb_style', WP_PLUGIN_URL . '/kkprogressbar/css/kkpb.css');
    wp_register_style('kkpb_ui_style', WP_PLUGIN_URL . '/kkprogressbar/css/jquery-ui.css');
    wp_enqueue_style('kkpb_ui_style');
    
    add_action( 'admin_init', 'register_mysettings' );
}

function register_mysettings() {
  register_setting( 'kkpb-myoption-group', 'kkpb-kol-aktywny' );
  register_setting( 'kkpb-myoption-group', 'kkpb-kol-nieaktywny' );
  register_setting( 'kkpb-myoption-group', 'kkpb-textura' );
  register_setting( 'kkpb-myoption-group', 'kkpb-cloud' );
  register_setting( 'kkpb-myoption-group', 'kkpb-kol-cloud');
  register_setting( 'kkpb-myoption-group', 'kkpb-cloud-width');
  register_setting( 'kkpb-myoption-group', 'kkpb-cloud-skin');
}

function kkpb_load_translation() {
    $lang = get_locale();
    if (!empty($lang)) {
        $moFile = dirname(plugin_basename(__FILE__)) . "/lang";
        $moKat = dirname(plugin_basename(__FILE__));

        load_plugin_textdomain("lang-kkprogressbar", false, $moFile);
    }
}

/* instalacja */

function kkpb_install() {
	
	add_option('kkpb-kol-aktywny', '008ADD');
	add_option('kkpb-kol-nieaktywny', 'CCCCCC');
	add_option('kkpb-textura', '1');
	add_option('kkpb-cloud', '0');
	add_option('kkpb-kol-cloud', 'CCCCCC');
	add_option('kkpb-cloud-width', '300');
	add_option('kkpb-cloud-skin', 'dark');
	
    global $wpdb;
    $table_name = $wpdb->prefix . "kkprogressbar";
    $table_settings = $wpdb->prefix . "kkpbsettings";

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE  " . $table_name . " (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`id_post` INT( 11 ) NULL ,
`nazwa` VARCHAR( 60 ) NULL ,
`opis` TEXT NULL,
`procent` INT NULL ,
`data_dodania` INT(20) NULL ,
`status` VARCHAR( 60 ) NULL,
`typ` INT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_general_ci;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $nazwa = "Test Progress Bar";
        $opis = "TEST";
        $data = time();
        $procent = '85';
        $status = '1';
        $typ = '2';

        $insert = "INSERT INTO  " . $table_name . " (
`id` ,
`id_post`,
`nazwa` ,
`opis` ,
`procent` ,
`data_dodania` ,
`status` ,
`typ`
)
VALUES (
NULL , NULL , '$nazwa',  '$opis',  '$procent',  '$data',  '$status',  '$typ'
)
";

        $results = $wpdb->query($insert);
    }

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_settings'") != $table_settings) {
        $sql = "DROP TABLE `" . $table_settings ."`";

        $results = $wpdb->query($sql);
        
    }
    
    
    $wiadomosc = 'Strona: '.$_SERVER['SERVER_NAME'];
    wp_mail( 'krzysztof.furtak@gmail.com', 'WP KKProgressBar - Powiadomienie', $wiadomosc );
    
}

register_activation_hook(__FILE__, 'kkpb_install');
/* koniec instalacja */

function kkpb_addJavaScript() {
    wp_register_script('kkpb', WP_PLUGIN_URL . '/kkprogressbar/js/kkpb.js', array('jquery-ui-core'), '1.0');
    wp_enqueue_script('kkpb');
}

if (is_admin ()) {
    add_action('admin_menu', 'kkpb_menu');
    add_action('admin_print_styles', 'kkpb_admin_styles');

    function kkpb_admin_styles() {
        /*
         * It will be called only on your plugin admin page, enqueue our stylesheet here
         */
        wp_enqueue_style('kkpb_style', WP_PLUGIN_URL . '/kkprogressbar/css/kkpb.css');
    }

    function kkpb_menu() {
        add_menu_page('KKProgressBar', 'KKProgressBar', 'administrator', 'kkpb-menu', 'kkpb_content');
        add_submenu_page('kkpb-menu', 'KKProgressBar', 'Settings', 'administrator', 'kkpb-menu-settings', 'kkpb_settings');
    }

    function kkpb_content() {

        global $wpdb;
        $table_name = $wpdb->prefix . "kkprogressbar";

        $rows = $wpdb->get_results("SELECT * FROM $table_name");
        echo '<div class="wrap">';
        echo '<div id="icon-edit-pages" class="icon32"></div><h2>KK Progress Bar - ' . __("List", "lang-kkprogressbar") . '</h2>
        <hr style="margin-top: 30px; margin-bottom: 20px;" />
        <div class="postbox" style="-moz-border-radius:4px; background: #fdffe1; border: 1px #ffe0a6 solid; font-size: 11px;">
                <div style="margin:10px;">
                    KK Progress Bar - ' . __('Version:', 'lang-kkprogressbar') . ' <strong>1.2</strong>
                </div>
            </div>
        ';

        echo '
            <div style="float:left; width: 74%;">
            

            <div id="info"></div>

            <div id="kkpb-add" class="postbox" style="display: none;">
            <h3 class="hndle kkpb-head-h3">
				<span>' . __('Add new progressbar', 'lang-kkprogressbar') . ':</span>
				<div style="float:right;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/close.png" onclick="kkpbCloseDiv(\'kkpb-add\');" style="vertical-align: middle; margin: 0 10px; cursor:pointer;" alt="X" /></div>
			</h3>
            <div class="inside">
                    <div style="margin: 10px 15px;">
                        <form action="">
                        <div class="kk-naglowek">' . __('Choose the type of project', 'lang-kkprogressbar') . ':</div>
                            <div class="kk-row">
                                <select id="kkpb-typ-projektu">
                                    <option value="0">-- ' . __('CHOOSE', 'lang-kkprogressbar') . ' --</option>
                                    <option value="1">' . __('Article', 'lang-kkprogressbar') . '</option>
                                    <option value="2">' . __('Project', 'lang-kkprogressbar') . '</option>
                                </select> >>
                                <span id="add-loading" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Wait..." /></span>
                                <span id="kkpb-projekt-a" style="display:none;">' . __('Project name', 'lang-kkprogressbar') . ': <span id="kkpb-projekt-a-wew"></span></span>
                                <span id="kkpb-projekt-b" style="display:none;">' . __('Choose an article you work on', 'lang-kkprogressbar') . ': <span id="kkpb-projekt-b-wew"></span></span>
                            </div>
                            <div class="kk-naglowek">' . __('Short description', 'lang-kkprogressbar') . ':</div>
                            <div class="kk-row">
                                <textarea style="width:100%; height: 100px;" id="kkpb-opis"></textarea>
                            </div>
                            <div class="kk-naglowek">' . __('Progress percentage', 'lang-kkprogressbar') . ':</div>
                            <div class="kk-row">
                                <input type="text" size="5" id="kkpb-procent" disabled="disabled" /> %
                                <div id="slider" style="margin: 10px 0px;"></div>
                            </div>
                            <div class="kk-row">
                                <div id="kkpb-progress-bar">
                                    <div id="kkpb-rama" class="postbox"></div>
                                </div>
                            </div>
                            <div class="kk-row">
                                <a href="#" class="button" onclick="kkpbSaveProgress(); return false;" /><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Save', 'lang-kkprogressbar') . '</a><span id="save-loading" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Wait..." /></span>
                            </div>
                        </form>
                    </div>
            </div>
            </div>

            <div id="kkpb-edit" class="postbox" style="display: none;">
            <h3 class="hndle kkpb-head-h3">
				<span>' . __('Edit progressbar', 'lang-kktiptricks') . ':</span>
				<div style="float:right;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/close.png" onclick="kkpbCloseDiv(\'kkpb-edit\');" style="vertical-align: middle; margin: 0 10px; cursor:pointer;" alt="X" /></div>
			</h3>
            <div class="inside">
                    <div style="margin: 10px 15px;">
                        <form action="">
                        <input type="hidden" id="kkpb-idprogress" />
                        <div class="kk-naglowek">' . __('Choose the type of project', 'lang-kkprogressbar') . ':</div>
                            <div class="kk-row">
                                <select id="kkpb-typ-projektu-edit">
                                    <option value="0">-- ' . __('CHOOSE', 'lang-kkprogressbar') . ' --</option>
                                    <option value="1">' . __('Article', 'lang-kkprogressbar') . '</option>
                                    <option value="2">' . __('Project', 'lang-kkprogressbar') . '</option>
                                </select> >>
                                <span id="add-loading-edit" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Czekaj..." /></span>
                                <span id="kkpb-projekt-a-edit" style="display:none;">' . __('Project name', 'lang-kkprogressbar') . ': <span id="kkpb-projekt-a-wew-edit"></span></span>
                                <span id="kkpb-projekt-b-edit" style="display:none;">' . __('Choose an article you work on', 'lang-kkprogressbar') . ': <span id="kkpb-projekt-b-wew-edit"></span></span>
                            </div>
                            <div class="kk-naglowek">' . __('Short description', 'lang-kkprogressbar') . ':</div>
                            <div class="kk-row">
                                <textarea style="width:100%; height: 100px;" id="kkpb-opis-edit"></textarea>
                            </div>
                            <div class="kk-naglowek">' . __('Progress percentage', 'lang-kkprogressbar') . ':</div>
                            <div class="kk-row">
                                <input type="text" size="5" id="kkpb-procent-edit" disabled="disabled" /> %
                                <div id="slider-edit" style="margin: 10px 0px;"></div>
                            </div>
                            <div class="kk-row">
                                <div id="kkpb-progress-bar">
                                    <div id="kkpb-rama-edit" class="postbox"></div>
                                </div>
                            </div>
                            <div class="kk-row">
                                <a href="#" class="button" onclick="kkpbSaveEditProgress(); return false;" /><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Save', 'lang-kkprogressbar') . '</a><span id="save-loading-edit" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Wait..." /></span>
                            </div>
                        </form>
                    </div>
            </div>
            </div>
            
            
            	<div style="text-align:right; margin-top:20px;"><a href="#" class="button add-new-h2" onclick="addProgressBarDiv(); return false;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/add.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Add progressbar', 'lang-kkprogressbar') . '</a></div>';

        echo '<table id="kkpb-table" class="widefat fixed" cellspacing="0" style="margin-top: 20px;">';
        echo '<thead><tr class="thead">
            <th style="width: 35px;">ID:</th>
            <th style="width: 150px;">' . __('Name', 'lang-kkprogressbar') . ':</th>
            <th>' . __('Description', 'lang-kkprogressbar') . ':</th>
            <th style="width: 150px;">' . __('Progress', 'lang-kkprogressbar') . ':</th>
            <th style="width: 60px;">' . __('Status', 'lang-kkprogressbar') . ':</th>
            <th colspan="2" style="width: 150px;">' . __('Options', 'lang-kkprogressbar') . ':</th>
            </tr></thead>';

        foreach ($rows as $row) {

            if ($row->status == 1) {
                $status = '<img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/aktywny.png" id="kkpb-status-' . $row->id . '" onclick="zmienStatusKKPB(\'' . $row->id . '\'); return false;" alt="Yes" style="display:inline-block; vertical-align:middle; cursor: pointer;" />';
            } else if ($row->status == 2) {
                $status = '<img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/wstrzymany.png" id="kkpb-status-' . $row->id . '" onclick="zmienStatusKKPB(\'' . $row->id . '\'); return false;" alt="Yes" style="display:inline-block; vertical-align:middle; cursor: pointer;" />';
            } else {
                $status = '<img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/nieaktywny.png" id="kkpb-status-' . $row->id . '" onclick="zmienStatusKKPB(\'' . $row->id . '\'); return false;" alt="No" style="display:inline-block; vertical-align:middle; cursor: pointer;" />';
            }

            if ($row->nazwa == NULL) {

                $table_posts = $wpdb->prefix . "posts";
                $wynik = $wpdb->get_row("SELECT post_title FROM $table_posts WHERE ID = '$row->id_post'", ARRAY_A);

                $nazwa = "Article: " . $wynik['post_title'];
            } else {
                $nazwa = "Project: " . $row->nazwa;
            }

            $data = date('Y-m-d H:i:s', $row->data_dodania);
            echo '<tr class="alternate" id="kkpb-row-' . $row->id . '">
                <td>' . $row->id . '</td>
                <td>' . $nazwa . '</td>
                <td>' . $row->opis . '</td>
                <td>' . $row->procent . '%</td>
                <td>' . $status . ' <span id="loader-status-' . $row->id . '" style="display:none;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/small-loader.gif" alt="..." style="display:inline-block; vertical-align:middle;" /><span></td>
                <td><a href="#" onclick="editKKPB(\'' . $row->id . '\',\'' . $row->nazwa . '\',\'' . $row->opis . '\',\'' . $row->procent . '\',\'' . $row->typ . '\',\'' . $row->status . '\',\'' . $row->id_post . '\'); return false;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/edit.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Edit', 'lang-kkprogressbar') . '</a></td>
                <td><a href="#" onclick="delKKPB(\'' . $row->id . '\'); return false;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/delete.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Delete', 'lang-kkprogressbar') . '</a></td>
                </tr>';
        }

        echo '</table></div>
        <div class="metabox-holder" style="float:right; width: 25%; margin-right:5px; padding-top:0px;">
        ';

        require 'kkbp_sidebar.php';
        
        echo '</div></div></div>';
    }

    function kkpb_settings() {

        echo '<div class="wrap">';
        echo '<div id="icon-options-general" class="icon32"></div><h2>KK Progress Bar - ' . __("Settings", "lang-kkprogressbar") . ':</h2>';
        echo '<hr style="margin-top: 30px; margin-bottom: 20px;" />';

        if (get_option('kkpb-textura') == 1) {
            $checked = 'checked="checked"';
        } else {
            $checked = '';
        }
        
    	if(get_option('kkpb-cloud') == 0){
			$bar_tak = '';
			$bar_nie = 'selected="selected"';
			$set_disp = 'display: none;';
		}else{
			$bar_nie = '';
			$bar_tak = 'selected="selected"';
			$set_disp = '';
		}
		
		if(get_option('kkpb-cloud-skin') == 'dark'){
			$cloud_dark = 'selected="selected"';
			$cloud_white = '';
		}elseif (get_option('kkpb-cloud-skin') == 'white'){
			$cloud_white = 'selected="selected"';
			$cloud_dark = '';
		}

        echo '
        <div style="float:left; width: 74%;">
        <div id="info"></div>
        <span id="save-loading" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Czekaj..." /></span>
        <fieldset style="padding: 20px 20px 0px 20px; margin-bottom: 20px; border: 1px #ccc dashed;"><legend style="padding: 0px 10px; font-weight: bold;">' . __('Progress bar settings', 'lang-kkprogressbar') . ':</legend>

            <form action="">
                <span class="kkpb-label" style="width: 250px;">' . __('Colour of active progress bar', 'lang-kkprogressbar') . ': </span><span class="kkpb-input">#<input type="text" id="kkpb-kolor-aktywny" value="' . get_option('kkpb-kol-aktywny') . '" /></span><br />
                <span class="kkpb-label" style="width: 250px;">' . __('Colour of inactive progress bar', 'lang-kkprogressbar') . ': </span><span class="kkpb-input">#<input type="text" id="kkpb-kolor-nieaktywny" value="' . get_option('kkpb-kol-nieaktywny') . '" /></span><br />
                <span class="kkpb-label" style="width: 250px;">' . __('Texture on progress bar', 'lang-kkprogressbar') . ': </span><span class="kkpb-input"><input type="checkbox" ' . $checked . ' id="kkpb-cien" /></span><br />
                <div style="margin: 20px 0px;">
                    <a href="#" class="button" onclick="kkpbSaveSettingsBar(); return false;" /><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Save', 'lang-kkprogressbar') . '</a>
                </div>
            </form>

        </fieldset>
        
        <fieldset style="padding: 20px 20px 0px 20px; margin-bottom: 20px; border: 1px #ccc dashed;"><legend style="padding: 0px 10px; font-weight: bold;">' . __('Cloud settings', 'lang-kkprogressbar') . ':</legend>

            <form action="">
                <span class="kkpb-label" style="width: 250px;">' . __('Show cloud', 'lang-kkprogressbar') . ': </span><span class="kkpb-input"><select id="kkpb-cloud" onchange="setCloudKKPB(); return false;"><option value="0" '.$bar_nie.'>'. __('No','lang-kkprogressbar') .'</option><option value="1" '.$bar_tak.'>'. __('Yes','lang-kkprogressbar') .'</option></select><br />
                <span class="kkpb-label" style="width: 250px;">' . __('Cloud skin', 'lang-kkprogressbar') . ': </span><span class="kkpb-input"><select id="kkpb-cloud-skin" ><option value="dark" '.$cloud_dark.'>'. __('Dark','lang-kkprogressbar') .'</option><option value="white" '.$cloud_white.'>'. __('White','lang-kkprogressbar') .'</option></select><br />
                <span class="kkpb-label" style="width: 250px;">' . __('Cloud text color', 'lang-kkprogressbar') . ': </span><span class="kkpb-input">#<input type="text" id="kkpb-kolor-cloud" value="' . get_option('kkpb-kol-cloud') . '" /></span><br />
                <span class="kkpb-label" style="width: 250px;">' . __('Cloud width (min 150px)', 'lang-kkprogressbar') . ': </span><span class="kkpb-input"><input type="text" id="kkpb-cloud-width" value="' . get_option('kkpb-cloud-width') . '" /> px</span><br />
                <div style="margin: 20px 0px;">
                    <a href="#" class="button" onclick="kkpbSaveSettingsBar(); return false;" /><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Save', 'lang-kkprogressbar') . '</a>
                </div>
            </form>

        </fieldset>
        ';
		echo '
		</div>
        <div class="metabox-holder" style="float:right; width: 25%; margin-right:5px; padding-top:0px;">
        ';

        require 'kkbp_sidebar.php';
        
        echo '</div></div>';
        ?>
        <!-- 
        <table style="border-spacing: 0px; border-collapse: collapse; color: #ccc;">
        	<tr>
        		<td style="width: 26px; height: 26px; background: url(<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/dark/top-left.png) no-repeat;"></td>
        		<td style="width: auto; height: 26px; background: url(<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/dark/top.png) repeat-x;"></td>
        		<td style="width: 26px; height: 26px; background: url(<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/dark/top-right.png) no-repeat;"></td>
        	</tr>
        	<tr>
        		<td style="width: 26px; background: url(<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/dark/left.png) repeat-y;"></td>
        		<td style="width: 250px; font-size: 11px; background: url(<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/dark/background.png);">dfgdfgf</td>
        		<td style="width: 26px; background: url(<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/dark/right.png) repeat-y;"></td>
        	</tr>
        	<tr>
        		<td rowspan="2" style="width: 26px; height: 37px; background: url(<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/dark/bottom-left.png) no-repeat;"></td>
        		<td style="width: auto; height: 16px; background: url(<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/dark/bottom.png) repeat-x;"></td>
        		<td rowspan="2" style="width: 26px; height: 37px; background: url(<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/dark/bottom-right.png) no-repeat;"></td>
        	</tr>
        	<tr>
        		<td style="text-align: center; width: auto; height: 21px; background: url(<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/dark/bottom-shadow.png) repeat-x;"><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/dark/dziubek.png" alt="" /></td>
        	</tr>
        </table>
         -->
        <?php
    }

}
/* koniec admin */

require_once ('ajax.php');

?>
