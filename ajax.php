<?php

add_action('wp_ajax_add_bar_kkpb', 'addBarAjaxKKPB');

function addBarAjaxKKPB() {

    $edit = $_POST['e'];
    $idpost = $_POST['id'];

    global $wpdb;

    $table_name = $wpdb->prefix . "posts";

    $sql = "SELECT ID, post_title FROM $table_name WHERE post_type = 'post' AND post_status = 'draft'";
    $wyniki = $wpdb->get_results($sql, ARRAY_A);

    if ($edit == 1) {
        echo '<select id="kkpb-nazwa-projektu-post-edit">';
    } else {
        echo '<select id="kkpb-nazwa-projektu-post">';
    }

    foreach ($wyniki as $wynik) {

        if ($edit == 1 && $wynik['ID'] == $idpost) {
            echo '<option value="' . $wynik['ID'] . '" selected="selected">' . $wynik['post_title'] . '</option>';
        } else {
            echo '<option value="' . $wynik['ID'] . '">' . $wynik['post_title'] . '</option>';
        }
    }

    echo '</select>';
}

add_action('wp_ajax_del_bar_kkpb', 'delBarAjaxKKPB');

function delBarAjaxKKPB() {

    global $wpdb;

    $id = $_POST['id'];

    $table_name = $wpdb->prefix . "kkprogressbar";

    $sql = "DELETE FROM $table_name WHERE `id` = '$id' LIMIT 1";

    $wynik = $wpdb->query($sql);

    if ($wynik) {
        echo '1|||<div class="kkpb-ok">'.__('Progress bar deleted successfully.','lang-kkprogressbar') .'</div>|||';
    } else {
        echo '0|||<div class="kkpb-error">'.__('Progress bar could not be deleted. Please try again.','lang-kkprogressbar') .'</div>|||';
    }
}

add_action('wp_ajax_save_bar_kkpb', 'saveBarAjaxKKPB');

function saveBarAjaxKKPB() {

    global $wpdb;

    $update = $_POST['u'];

    $typ_projektu = $_POST['typ_projektu'];
    $projekt = $_POST['projekt'];
    $opis = $_POST['opis'];
    $procent = $_POST['procent'];
    $data = time();

    if (empty($procent)) {
        $procent = 0;
    }

    $table_name = $wpdb->prefix . "kkprogressbar";

    if ($update == 0) {
        if ($typ_projektu == 1) {
            $sql = "INSERT INTO  $table_name (
`id` ,
`id_post` ,
`nazwa` ,
`opis` ,
`procent` ,
`data_dodania` ,
`status` ,
`typ`
)
VALUES (
NULL ,  '$projekt',  NULL,  '$opis',  '$procent',  '$data',  '1',  '$typ_projektu'
)";
        } else if ($typ_projektu == 2) {
            $sql = "INSERT INTO  $table_name (
`id` ,
`id_post` ,
`nazwa` ,
`opis` ,
`procent` ,
`data_dodania` ,
`status` ,
`typ`
)
VALUES (
NULL ,  NULL,  '$projekt',  '$opis',  '$procent',  '$data',  '1',  '$typ_projektu'
)";
        }
    } else if ($update == 1) {

        $idprogress = $_POST['idprogress'];

        if ($typ_projektu == 1) {

            $sql = "UPDATE  $table_name SET
`id_post` =  '$projekt',
`nazwa` =  NULL,
`opis` =  '$opis',
`procent` =  '$procent',
`typ` =  '$typ_projektu' WHERE `id` = '$idprogress' LIMIT 1";
        } else if ($typ_projektu == 2) {
            $sql = "UPDATE  $table_name SET
`id_post` =  NULL,
`nazwa` =  '$projekt',
`opis` =  '$opis',
`procent` =  '$procent',
`typ` =  '$typ_projektu' WHERE `id` = '$idprogress' LIMIT 1";
        }
    }

    $wynik = $wpdb->query($sql);
    $id_last = mysql_insert_id();

    if($id_last == 0){
        $id_last = $idprogress;
    }

    if ($wynik) {
        echo '1|||<div class="kkpb-ok">'.__('Changes saved successfully.','lang-kkprogressbar') .'</div>|||'.$id_last.'|||
         <tr class="alternate" id="kkpb-row-' . $id_last . '">
                <td>' . $id_last . '</td>
                <td>' . $projekt . '</td>
                <td>' . $opis . '</td>
                <td>' . $procent . '%</td>
                <td><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/aktywny.png" id="kkpb-status-' . $id_last . '" onclick="zmienStatusKKPB(\'' . $id_last . '\'); return false;" alt="Yes" style="display:inline-block; vertical-align:middle; cursor: pointer;" /> <span id="loader-status-' . $id_last . '" style="display:none;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/small-loader.gif" alt="..." style="display:inline-block; vertical-align:middle;" /><span></td>
                <td><a href="#" onclick="editKKPB(\'' . $id_last . '\',\'' . $projekt . '\',\'' . $opis . '\',\'' . $procent . '\',\'' . $typ_projektu . '\',\'1\',\'' . $id_last . '\'); return false;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/edit.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Edit', 'lang-kkprogressbar') . '</a></td>
                <td><a href="#" onclick="delKKPB(\'' . $id_last . '\'); return false;"><img src="' . WP_PLUGIN_URL . '/kkprogressbar/images/delete.png" alt="+" style="display:inline-block; vertical-align:middle;" /> ' . __('Delete', 'lang-kkprogressbar') . '</a></td>
                </tr>|||
        ';
    } else {
        echo '0|||<div class="kkpb-error">'.__('Changes have not been saved. Please try again.','lang-kkprogressbar') .'</div>|||'.$id_last."|||";
    }
}

add_action('wp_ajax_bar_settings_kkpb', 'saveBarSettingsAjaxKKPB');

function saveBarSettingsAjaxKKPB() {

    $kol_aktywny = $_POST['kol_aktywny'];
    $kol_nieaktywny = $_POST['kol_nieaktywny'];
    $textura = $_POST['textura'];
    $cloud = $_POST['cloud'];
	$kol_cloud = $_POST['kol_cloud'];
	$cloud_width = $_POST['cloud_width'];
	$cloud_skin = $_POST['cloud_skin'];
    
    update_option('kkpb-kol-aktywny', $kol_aktywny);
	update_option('kkpb-kol-nieaktywny', $kol_nieaktywny);
	update_option('kkpb-textura', $textura);
	update_option('kkpb-cloud', $cloud);
	update_option('kkpb-kol-cloud', $kol_cloud);
	update_option('kkpb-cloud-width', $cloud_width);
	update_option('kkpb-cloud-skin', $cloud_skin);
    
    echo '0|||<div class="kkpb-ok">'.__('Changes saved successfully.','lang-kkprogressbar') .'</div>|||';
}

add_action('wp_ajax_zmiana_statusu_kkpb', 'zmienStatusAjaxKKPB');

function zmienStatusAjaxKKPB() {
    
    global $wpdb;
    $id = $_POST['id'];

    $table_name = $wpdb->prefix . "kkprogressbar";

    $sql = "SELECT status FROM $table_name WHERE id = '$id' LIMIT 1";
    $dane = $wpdb->get_row($sql, ARRAY_A);

    switch ($dane['status']) {
        case 2:
            $sqla = "UPDATE " . $table_name . " SET status = '0' WHERE id = '$id' LIMIT 1";
            $wynika = $wpdb->query($sqla);
            echo 0;
            break;
        case 1:
            $sqla = "UPDATE " . $table_name . " SET status = '2' WHERE id = '$id' LIMIT 1";
            $wynika = $wpdb->query($sqla);
            echo 2;
            break;
        case 0:
            $sqla = "UPDATE " . $table_name . " SET status = '1' WHERE id = '$id' LIMIT 1";
            $wynika = $wpdb->query($sqla);
            echo 1;
            break;
    }
}