<?php

require('../../../../wp-blog-header.php');

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
?>
