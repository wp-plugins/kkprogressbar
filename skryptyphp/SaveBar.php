<?php
require('../../../../wp-blog-header.php');

global $wpdb;

$update = $_GET['u'];

$typ_projektu = $_POST['typ_projektu'];
$projekt = $_POST['projekt'];
$opis = $_POST['opis'];
$procent = $_POST['procent'];
$data = time();

if(empty($procent)) {
    $procent = 0;
}

$table_name = $wpdb->prefix."kkprogressbar";

if($update == 0) {
    if($typ_projektu == 1) {
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
    }else if($typ_projektu == 2) {
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
}else if($update == 1) {

    $idprogress = $_POST['idprogress'];

    if($typ_projektu == 1) {

        $sql = "UPDATE  $table_name SET
`id_post` =  '$projekt',
`nazwa` =  NULL,
`opis` =  '$opis',
`procent` =  '$procent',
`typ` =  '$typ_projektu' WHERE `id` = '$idprogress' LIMIT 1";

    }else if($typ_projektu == 2) {
        $sql = "UPDATE  $table_name SET
`id_post` =  NULL,
`nazwa` =  '$projekt',
`opis` =  '$opis',
`procent` =  '$procent',
`typ` =  '$typ_projektu' WHERE `id` = '$idprogress' LIMIT 1";
    }

}

$wynik = $wpdb->query($sql);

if($wynik) {
    echo '<div class="kkpb-ok postbox">Changes saved successfully.</div>';
}else {
    echo '<div class="kkpb-error postbox">Changes have not been saved. Please try again.</div>';
}

?>
