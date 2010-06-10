<?php
require('../../../../wp-blog-header.php');

$edit = $_GET['e'];
$idpost = $_GET['id'];

global $wpdb;

$table_name = $wpdb->prefix."posts";

$sql = "SELECT ID, post_title FROM $table_name WHERE post_type = 'post' AND post_status = 'draft'";
$wyniki = $wpdb->get_results($sql,ARRAY_A);

if($edit == 1) {
    echo '<select id="kkpb-nazwa-projektu-post-edit">';
}else {
    echo '<select id="kkpb-nazwa-projektu-post">';
}

foreach($wyniki as $wynik) {

    if($edit == 1 && $wynik['ID'] == $idpost) {
        echo '<option value="'.$wynik['ID'].'" selected="selected">'.$wynik['post_title'].'</option>';
    }else {
        echo '<option value="'.$wynik['ID'].'">'.$wynik['post_title'].'</option>';
    }

}

echo '</select>';

?>
