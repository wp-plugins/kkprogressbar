<?php

require('../../../../wp-blog-header.php');

global $wpdb;

$id = $_POST['id'];

$table_name = $wpdb->prefix."kkprogressbar";

$sql = "DELETE FROM $table_name WHERE `id` = '$id' LIMIT 1";

$wynik = $wpdb->query($sql);

if($wynik) {
    echo '<div class="kkpb-ok postbox">Progress bar deleted successfully.</div>';
}else {
    echo '<div class="kkpb-error postbox">Progress bar could not be deleted. Please try again.</div>';
}

?>
