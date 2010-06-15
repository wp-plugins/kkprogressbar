<?php

require('../../../../wp-blog-header.php');

global $wpdb;

$kol_aktywny = $_POST['kol_aktywny'];
$kol_nieaktywny = $_POST['kol_nieaktywny'];
$textura = $_POST['textura'];

$table_name = $wpdb->prefix . "kkpbsettings";

$sql = "UPDATE  " . $table_name . " SET  `value` =  '" . $kol_aktywny . "' WHERE  `idkkpbsettings` = 1 LIMIT 1";
$wynik = $wpdb->query($sql);

$sql = "UPDATE  " . $table_name . " SET  `value` =  '" . $kol_nieaktywny . "' WHERE  `idkkpbsettings` = 2 LIMIT 1";
$wynik_a = $wpdb->query($sql);

$sql = "UPDATE  " . $table_name . " SET  `value` =  '" . $textura . "' WHERE  `idkkpbsettings` = 3 LIMIT 1";
$wynik_b = $wpdb->query($sql);


echo '<div class="kkpb-ok postbox">Changes saved successfully.</div>';
?>
