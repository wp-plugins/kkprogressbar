<?php

add_action('wp_ajax_add_bar_kkpb', 'addBarAjaxKKPB');

function addBarAjaxKKPB() {
	$ilosc = count(kkpb::getProjectProgressbars($_POST['proId']));
	if($ilosc >= 2){
		$return = json_encode(array('hasError' => true, 'response' => '', 'id' => $id_last));
	}else{
		$return = kkpb::saveNewProgressbar($_POST['proId'], $_POST['nazwa'], $_POST['manual'], $_POST['procent'], $_POST['valNow'], $_POST['valAll'], $_POST['status']);
	}
	echo $return;
	die();
}

add_action('wp_ajax_update_bar_kkpb', 'updateBarAjaxKKPB');

function updateBarAjaxKKPB() {
	$return = kkpb::updateProgressbar($_POST['id'], $_POST['nazwa'], $_POST['manual'], $_POST['procent'], $_POST['valNow'], $_POST['valAll']);
	echo $return;
	die();
}

add_action('wp_ajax_del_bar_kkpb', 'delBarAjaxKKPB');

function delBarAjaxKKPB() {
    $id = $_POST['id'];
	$return = kkpb::deleteProgressbar($id);
	echo $return;
	die();
}

add_action('wp_ajax_save_bar_kkpb', 'saveBarAjaxKKPB');

function saveBarAjaxKKPB() {

	$update = $_POST['u'];

    $typ_projektu = $_POST['typ_projektu'];
    $projekt = addslashes($_POST['projekt']);
    $opis = addslashes($_POST['opis']);

    if (empty($procent)) {
        $procent = 0;
    }
    
    kkpb::saveProjectKKPB($update,$typ_projektu,$projekt,$opis);
	
}

add_action('wp_ajax_get_all_data_kkpb', 'getAllDataKKPB');

function getAllDataKKPB() {
    $id = $_POST['id'];
    $wynik = json_encode(kkpb::getAllDataKKPB($id));
	echo $wynik;
}

add_action('wp_ajax_zmiana_statusu_kkpb', 'zmienStatusAjaxKKPB');

function zmienStatusAjaxKKPB() {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $return = kkpb::updateStatus($id, $status);
    echo $return;
    die();
}

add_action('wp_ajax_kkpb_db_update', 'updateDB');

function updateDB() {
    
    global $wpdb;
    $table_name = $wpdb->prefix . "kkprogressbar";

    $sql = "SELECT * FROM " . $table_name;
    $wyniki = $wpdb->get_results($wpdb->prepare($sql));

    if(count($wyniki) > 0){
    	$message = '1. Dane zostały prawidlowo skopiowane (skopiowanych projektów - '.count($wyniki).').<br />';
    }else{
    	$message = '1. Nie znaleziono danych do skopiowania.<br />';
    }
    
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
        $message .= '2. Nowy schemat bazy danych został poprawnie zainstalowany.<br />';
    }else{
    	$message .= '2. Nowy schemat bazy danych jest już poprawnie zainstalowany.<br />';
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
    }
    
    foreach ($wyniki as $wiersz) {
    	
    	$insert = "INSERT INTO  " . $table_name_new . " (
			`id` ,
			`id_post` ,
			`nazwa` ,
			`opis` ,
			`link` ,
			`typ`
			)
			VALUES (
			NULL , NULL ,  '".$wiersz->nazwa."',  '".$wiersz->opis."', NULL,  '".$wiersz->typ."'
			)";
        $results = $wpdb->query($insert);
        $id_last = mysql_insert_id();
        
        if($wiersz->status == '0'){
        	$status = 3;
        }else{
        	$status = $wiersz->status;
        }
        
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
			NULL ,  '".$id_last."',  '".$wiersz->nazwa."',  '1',  '".$wiersz->procent."',  '0',  '0',  '".$status."'
			)";
        $results = $wpdb->query($insert);
    	
    }
    $message .= '3. Dane zostały przeniesione na nowy schemat bazy danych.<br />';
    
    
    $sql = "DROP TABLE `" . $table_name ."`";
    $results = $wpdb->query($sql);
    $message .= '4. Stara baza danych zostala usunięta poprawnie.<br />';
    
    
    echo '1|||'.$message.'<br />Prace zakończone. Strona zostanie odświerzona za 3 sekundy.|||';
}