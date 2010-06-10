<?php
/*
Plugin Name: KK ProgressBar
Plugin URI: http://krzysztof-furtak.pl/2010/06/wp-kk-progressbar-plugin/
Description: Plugin shows/indicates progress that has been made on projects or articles.
Version: 1.0
Author: Krzysztof Furtak
Author URI: http://krzysztof-furtak.pl
*/

add_action('init', 'kkpb_addJavaScript');
add_action('admin_init', 'kkpb_admin_init');

require_once('kkpb_prezentacja.php');

function kkpb_admin_init() {
    /* Register our stylesheet. */
    wp_register_style('kkpb_style', WP_PLUGIN_URL . '/kkprogressbar/css/kkpb.css');
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
}

register_activation_hook(__FILE__, 'kkpb_install');
/* koniec instalacja */

function kkpb_addJavaScript() {
    wp_enqueue_script('kkpb_jquery',WP_PLUGIN_URL . '/kkprogressbar/js/jquery.js');
    wp_enqueue_script('kkpb_admin_js_file', WP_PLUGIN_URL . '/kkprogressbar/js/kkpb.js');
//    wp_enqueue_script('mojeFunkcje',WP_PLUGIN_URL . '/kkprogressbar/js/kkpb.js');
}

if (is_admin ()) {
    add_action('admin_menu', 'kkpb_menu');
    add_action('init', 'kkpb_load_translation');
    add_action('admin_print_styles', 'kkpb_admin_styles');

    function kkpb_admin_styles() {
        /*
         * It will be called only on your plugin admin page, enqueue our stylesheet here
         */
        wp_enqueue_style('kkpb_style', WP_PLUGIN_URL . '/kkprogressbar/css/kkpb.css');
    }

    function kkpb_menu() {
        add_menu_page('KKProgressBar', 'KKProgressBar', 'administrator', 'kkpb-menu', 'kkpb_content');
    }

    function kkpb_content() {

        global $wpdb;
        $table_name = $wpdb->prefix . "kkprogressbar";

        $rows = $wpdb->get_results("SELECT * FROM $table_name");

        echo '<div id="icon-edit-pages" class="icon32"></div><h2>KK Progress Bar - ' . __("Lista/Edycja", "lang-kkprogressbar") . '</h2>';

        echo '
            <hr style="margin-top: 30px; margin-bottom: 20px;" />

            <div class="postbox" style="-moz-border-radius:4px; background: #fdffe1; border: 1px #ffe0a6 solid; font-size: 11px;">
                <div style="margin:10px;">
                    KK Progress Bar - ' . __('Aktualna wersja:', 'lang-kkprogressbar') . ' <strong>1.0</strong>
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
                                <input type="text" size="5" id="kkpb-procent" /> %
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
                                <input type="text" size="5" id="kkpb-procent-edit" /> %
                            </div>
                            <div class="kk-row">
                                <div id="kkpb-progress-bar">
                                    <div id="kkpb-rama-edit" class="postbox"></div>
                                </div>
                            </div>
                            <div class="kk-row">
                                <a href="#" class="button" onclick="kkpbSaveEditProgress(); return false;" /><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/save.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Zapisz', 'lang-kkprogressbar') . '</a><span id="save-loading" style="display: none;"><img src="' . WP_PLUGIN_URL . '/kkcountdown/images/loader.gif" style="vertical-align: middle; margin-left: 10px;" alt="Czekaj..." /></span>
                            </div>
                        </form>
                    </div>
            </div>
            </div>

            ';
        echo '
            <div style="float:left; width: 74%;">
            <div style="text-align:right; margin-top:20px;"><a href="#" class="button add-new-h2" onclick="addProgressBarDiv(); return false;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/add_count.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Dodaj', 'lang-kkprogressbar') . '</a></div>';

        echo '<table class="widefat fixed" cellspacing="0" style="margin-top: 20px;">';
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
                $status = '<img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/aktywny.png" id="kkpb-status-' . $row->id . '" onclick="zmienStatusKKPB(\'' . WP_PLUGIN_URL . '/kkprogressbar/skryptyphp/ZmienStatus.php\',\'' . $row->id . '\'); return false;" alt="Yes" style="display:inline-block; vertical-align:middle; cursor: pointer;" />';
            } else if ($row->status == 2) {
                $status = '<img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/wstrzymany.png" id="kkpb-status-' . $row->id . '" onclick="zmienStatusKKPB(\'' . WP_PLUGIN_URL . '/kkprogressbar/skryptyphp/ZmienStatus.php\',\'' . $row->id . '\'); return false;" alt="Yes" style="display:inline-block; vertical-align:middle; cursor: pointer;" />';
            } else {
                $status = '<img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/nieaktywny.png" id="kkpb-status-' . $row->id . '" onclick="zmienStatusKKPB(\'' . WP_PLUGIN_URL . '/kkprogressbar/skryptyphp/ZmienStatus.php\',\'' . $row->id . '\'); return false;" alt="No" style="display:inline-block; vertical-align:middle; cursor: pointer;" />';
            }

            if ($row->nazwa == NULL) {

                $table_posts = $wpdb->prefix . "posts";
                $wynik = $wpdb->get_row("SELECT post_title FROM $table_posts WHERE ID = '$row->id_post'", ARRAY_A);

                $nazwa = "Artykuł: " . $wynik['post_title'];
            } else {
                $nazwa = "Projekt: " . $row->nazwa;
            }

            $data = date('Y-m-d H:i:s', $row->data_dodania);
            echo '<tr class="alternate">
                <td>' . $row->id . '</td>
                <td>' . $nazwa . '</td>
                <td>' . $row->opis . '</td>
                <td>' . $row->procent . '%</td>
                <td>' . $status . ' <span id="loader-status-' . $row->id . '" style="display:none;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/small-loader.gif" alt="..." style="display:inline-block; vertical-align:middle;" /><span></td>
                <td><a href="#" onclick="editKKPB(\'' . $row->id . '\',\'' . $row->nazwa . '\',\'' . $row->opis . '\',\'' . $row->procent . '\',\'' . $row->typ . '\',\'' . $row->status . '\',\'' . $row->id_post . '\'); return false;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/edit_count.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Edytuj', 'lang-kkprogressbar') . '</a></td>
                <td><a href="#" onclick="delKKPB(\'' . $row->id . '\'); return false;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/delete_count.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Usuń', 'lang-kkprogressbar') . '</a></td>
                </tr>';
        }

        echo '</table></div>';

        echo '
        <div style="float:right; width: 25%; margin-right:5px; margin-top:55px;">
            <div class="postbox" style="float: left;">
                <div style="border-bottom:1px #ddd solid; color: #ccc; font-size: 14px; font-weight: bold;">
                    <div style="padding: 10px 15px;">INFO:</div>
                </div>
                <div class="inside">
                    <div style="margin: 10px 15px;">
                        <div style="margin: 10px 0px;"><span class="kkc-small-text"><strong>' . __('Autor', 'lang-kkprogressbar') . ':</strong></span> <a href="http://krzysztof-furtak.pl" target="_blank" >Krzysztof Furtak</a> <span class="kkc-small-text">WebDesigner</span></div>
                        <div style="margin: 10px 0px;"><strong>' . __('Zgłoś błąd', 'lang-kkprogressbar') . ':</strong> <a href="http://krzysztof-furtak.pl/2010/06/wp-kk-progressbar-plugin/" target="_blank" >' . __('Strona wtyczki', 'lang-kkprogressbar') . '</a></div>
                    </div>
                </div>
            </div>
         </div>
            ';
    }

}
/* koniec admin */
?>
