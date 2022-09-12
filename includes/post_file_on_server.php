<?php

add_action( 'wp_ajax_post_file_on_server', 'post_file_on_server' );    //execute when wp logged in
add_action( 'wp_ajax_nopriv_post_file_on_server', 'post_file_on_server'); //execute when logged out

function post_file_on_server() {

    $obj = json_decode(stripslashes($_POST['file']));
    $filename = $_POST['filename'];
    
    $target_dir = "csv_fan/";
    $target_file = $target_dir .  $filename;

     // open csv file for writing
    $f = fopen($target_dir .  $filename , 'w');

    if ($f === false) {
        die('Error opening the file ' . $filename);
    }

    // write each row at a time to a file
    foreach ($obj as $row) {
        fputcsv($f, $row);
    }

    // close the file
    fclose($f);
    

    die();
}