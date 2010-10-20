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
        $sql = "CREATE TABLE  " . $table_settings . " (
`idkkpbsettings` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) NOT NULL ,
`value` VARCHAR( 255 ) NOT NULL
) ENGINE = INNODB";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $insert = "INSERT INTO  " . $table_settings . " (
`idkkpbsettings` ,
`name` ,
`value`
)
VALUES (
'1',  'kol_aktywny',  '008ADD'
), (
'2',  'kol_nieaktywny',  'cccccc'
), (
'3',  'textura_a',  '0'
)";
        $results = $wpdb->query($insert);
    }
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
        echo '<div id="icon-edit-pages" class="icon32"></div><h2>KK Progress Bar - ' . __("Lista/Edycja", "lang-kkprogressbar") . '</h2>';

        echo '
            <hr style="margin-top: 30px; margin-bottom: 20px;" />

            <div class="postbox" style="-moz-border-radius:4px; background: #fdffe1; border: 1px #ffe0a6 solid; font-size: 11px;">
                <div style="margin:10px;">
                    KK Progress Bar - ' . __('Aktualna wersja:', 'lang-kkprogressbar') . ' <strong>1.1.2</strong>
                </div>
            </div>

            <div id="info"></div>

            <div id="kkpb-add" class="postbox" style="display: none;">
            <div style="border-bottom:1px #ddd solid; color: #ccc; font-size: 14px; font-weight: bold;">
                    <div style="float:left; padding: 10px 15px;">' . __('Dodaj nowy pasek postępu', 'lang-kkprogressbar') . '</div>
                    <div style="float:right;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/close.png" onclick="kkpbCloseDiv(\'kkpb-add\'); return false;" style="vertical-align: middle; margin: 10px; cursor:pointer;" alt="X" /></div>
                    <div class="kkpb-clear-div"></div>
            </div>
            <div class="inside">
                    <div style="margin: 10px 15px;">
                        <form action="">
                        <div class="kk-naglowek">' . __('Wybierz typ projektu:', 'lang-kkprogressbar') . '</div>
                            <div class="kk-row">
                                <select id="kkpb-typ-projektu">
                                    <option value="0">-- ' . __('WYBIERZ', 'lang-kkprogressbar') . ' --</option>
                                    <option value="1">' . __('Artykuł', 'lang-kkprogressbar') . '</option>
                                    <option value="2">' . __('Projekt', 'lang-kkprogressbar') . '</option>
                                </select> >>
                                <span id="add-loading" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Czekaj..." /></span>
                                <span id="kkpb-projekt-a" style="display:none;">' . __('Nazwa projeku', 'lang-kkprogressbar') . ': <span id="kkpb-projekt-a-wew"></span></span>
                                <span id="kkpb-projekt-b" style="display:none;">' . __('Wybierz artykuł nad którym pracujesz', 'lang-kkprogressbar') . ': <span id="kkpb-projekt-b-wew"></span></span>
                            </div>
                            <div class="kk-naglowek">' . __('Krótki opis:', 'lang-kkprogressbar') . '</div>
                            <div class="kk-row">
                                <textarea style="width:100%; height: 100px;" id="kkpb-opis"></textarea>
                            </div>
                            <div class="kk-naglowek">' . __('Aktualny procent zaawansowania:', 'lang-kkprogressbar') . '</div>
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
                                <a href="#" class="button" onclick="kkpbSaveProgress(); return false;" /><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Zapisz', 'lang-kkprogressbar') . '</a><span id="save-loading" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Czekaj..." /></span>
                            </div>
                        </form>
                    </div>
            </div>
            </div>

            <div id="kkpb-edit" class="postbox" style="display: none;">
            <div style="border-bottom:1px #ddd solid; color: #ccc; font-size: 14px; font-weight: bold;">
                    <div style="float:left; padding: 10px 15px;">' . __('Edycja paseka postępu', 'lang-kkprogressbar') . '</div>
                    <div style="float:right;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/close.png" onclick="kkpbCloseDiv(\'kkpb-edit\'); return false;" style="vertical-align: middle; margin: 10px; cursor:pointer;" alt="X" /></div>
                    <div class="kkpb-clear-div"></div>
            </div>
            <div class="inside">
                    <div style="margin: 10px 15px;">
                        <form action="">
                        <input type="hidden" id="kkpb-idprogress" />
                        <div class="kk-naglowek">' . __('Wybierz typ projektu:', 'lang-kkprogressbar') . '</div>
                            <div class="kk-row">
                                <select id="kkpb-typ-projektu-edit">
                                    <option value="0">-- ' . __('WYBIERZ', 'lang-kkprogressbar') . ' --</option>
                                    <option value="1">' . __('Artykuł', 'lang-kkprogressbar') . '</option>
                                    <option value="2">' . __('Projekt', 'lang-kkprogressbar') . '</option>
                                </select> >>
                                <span id="add-loading-edit" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Czekaj..." /></span>
                                <span id="kkpb-projekt-a-edit" style="display:none;">' . __('Nazwa projeku', 'lang-kkprogressbar') . ': <span id="kkpb-projekt-a-wew-edit"></span></span>
                                <span id="kkpb-projekt-b-edit" style="display:none;">' . __('Wybierz artykuł nad którym pracujesz', 'lang-kkprogressbar') . ': <span id="kkpb-projekt-b-wew-edit"></span></span>
                            </div>
                            <div class="kk-naglowek">' . __('Krótki opis:', 'lang-kkprogressbar') . '</div>
                            <div class="kk-row">
                                <textarea style="width:100%; height: 100px;" id="kkpb-opis-edit"></textarea>
                            </div>
                            <div class="kk-naglowek">' . __('Aktualny procent zaawansowania:', 'lang-kkprogressbar') . '</div>
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
                                <a href="#" class="button" onclick="kkpbSaveEditProgress(); return false;" /><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Zapisz', 'lang-kkprogressbar') . '</a><span id="save-loading-edit" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Czekaj..." /></span>
                            </div>
                        </form>
                    </div>
            </div>
            </div>

            ';
        echo '
            <div style="float:left; width: 74%;">
            <div style="text-align:right; margin-top:20px;"><a href="#" class="button add-new-h2" onclick="addProgressBarDiv(); return false;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/add.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Dodaj', 'lang-kkprogressbar') . '</a></div>';

        echo '<table id="kkpb-table" class="widefat fixed" cellspacing="0" style="margin-top: 20px;">';
        echo '<thead><tr class="thead">
            <th style="width: 35px;">ID:</th>
            <th style="width: 150px;">' . __('Nazwa', 'lang-kkprogressbar') . ':</th>
            <th>' . __('Opis:', 'lang-kkprogressbar') . '</th>
            <th style="width: 150px;">' . __('Postęp', 'lang-kkprogressbar') . ':</th>
            <th style="width: 60px;">' . __('Status', 'lang-kkprogressbar') . ':</th>
            <th colspan="2" style="width: 150px;">' . __('Opcje', 'lang-kkprogressbar') . ':</th>
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
                <td><a href="#" onclick="editKKPB(\'' . $row->id . '\',\'' . $row->nazwa . '\',\'' . $row->opis . '\',\'' . $row->procent . '\',\'' . $row->typ . '\',\'' . $row->status . '\',\'' . $row->id_post . '\'); return false;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/edit.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Edytuj', 'lang-kkprogressbar') . '</a></td>
                <td><a href="#" onclick="delKKPB(\'' . $row->id . '\'); return false;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/delete.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Usuń', 'lang-kkprogressbar') . '</a></td>
                </tr>';
        }

        echo '</table></div>';

        echo '
        <div class="metabox-holder" style="float:right; width: 25%; margin-right:5px;">
            <div class="postbox gdrgrid frontright">
                <h3 class="hndle" style="cursor:default;"><span>Info:</span></h3>
                <div class="inside">
                    <div style="margin: 10px 15px;">
                        <div style="margin: 10px 0px;"><span class="kkc-small-text"><strong>' . __('Autor', 'lang-kkprogressbar') . ':</strong></span> <br /><a href="http://krzysztof-furtak.pl" target="_blank" >Krzysztof Furtak</a> <span class="kkc-small-text">Web Developer</span></div>
                        <div style="margin: 10px 0px;"><span class="kkc-small-text"><strong>' . __('Zgłoś błąd', 'lang-kkprogressbar') . ':</strong></span><br /> <a href="http://krzysztof-furtak.pl/2010/06/wp-kk-progressbar-plugin/" target="_blank" >' . __('Strona wtyczki', 'lang-kkprogressbar') . '</a></div>
                        <hr />
                        <div style="margin: 10px 0px; font-size: 10px;">
                            <h4>' . __('Legenda', 'lang-kkprogressbar') . ':</h4>
                            <img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/aktywny.png" alt="Yes" style="vertical-align:middle;" /> - ' . __('Aktywny', 'lang-kkprogressbar') . '<br />
                            <img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/wstrzymany.png" alt="Yes" style="vertical-align:middle;" /> - ' . __('Prace wstrzymane', 'lang-kkprogressbar') . '<br />
                            <img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/nieaktywny.png" alt="Yes" style="vertical-align:middle;" /> - ' . __('Nieaktywny (nie jest wyświetlany)', 'lang-kkprogressbar') . '<br />
                        </div>
                    </div>
                </div>
            </div>

            <div class="postbox gdrgrid frontright">
                <h3 class="hndle" style="cursor:default;"><span>' . __('Dotacja:', 'lang-kkprogressbar') . '</span></h3>
                <div class="inside">
                    <div style="margin: 10px 15px;">
                            
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                            <input type="hidden" name="cmd" value="_s-xclick">
                            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
                            ">
                            <input type="image" src="http://krzysztof-furtak.pl/upload/buy_coffee.png" border="0" name="submit" alt="PayPal — Płać wygodnie i bezpiecznie">
                            <img alt="" border="0" src="https://www.paypal.com/pl_PL/i/scr/pixel.gif" width="1" height="1">
                            </form>


                        </div>
                    </div>
                </div>
            </div>
         </div>
            ';
        echo '</div>';
    }

    function kkpb_settings() {

        global $wpdb;
        $table_name = $wpdb->prefix . "kkpbsettings";

        $rows = $wpdb->get_results("SELECT * FROM $table_name");


        echo '<div class="wrap">';
        echo '<div id="icon-options-general" class="icon32"></div><h2>KK Progress Bar - ' . __("Ustawienia:", "lang-kkprogressbar") . '</h2>';
        echo '<hr style="margin-top: 30px; margin-bottom: 20px;" />';

        if ($rows[2]->value == 1) {
            $checked = 'checked="checked"';
        } else {
            $checked = '';
        }

        echo '
        <div id="info"></div>
        <fieldset style="padding: 20px; border: 1px #ccc dashed;"><legend>' . __('Wygląd paska postępu:', 'lang-kkprogressbar') . '</legend>

            <form action="">
                <span class="kkpb-label" style="width: 250px;">' . __('Kolor aktywnego paseka postępu:', 'lang-kkprogressbar') . ' </span><span class="kkpb-input">#<input type="text" id="kkpb-kolor-aktywny" value="' . $rows[0]->value . '" /></span><br />
                <span class="kkpb-label" style="width: 250px;">' . __('Kolor nieaktywnego paseka postępu:', 'lang-kkprogressbar') . ' </span><span class="kkpb-input">#<input type="text" id="kkpb-kolor-nieaktywny" value="' . $rows[1]->value . '" /></span><br />
                <span class="kkpb-label" style="width: 250px;">' . __('Textura na paseku postępu:', 'lang-kkprogressbar') . ' </span><span class="kkpb-input"><input type="checkbox" ' . $checked . ' id="kkpb-cien" /></span><br />
                <div style="margin: 20px 0px;">
                    <a href="#" class="button" onclick="kkpbSaveSettingsBar(); return false;" /><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Zapisz', 'lang-kkprogressbar') . '</a><span id="save-loading" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Czekaj..." /></span>
                </div>
            </form>

        </fieldset>
        ';

        echo '</div>';
    }

}
/* koniec admin */

require_once('ajax.php');

?>
