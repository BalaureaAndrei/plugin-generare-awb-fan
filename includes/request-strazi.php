<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once $path . '/wp-config.php';
$host     = DB_HOST;
$user     = DB_USER;
$pass     = DB_PASSWORD;
$dbname   = DB_NAME;
$connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);


function initiate_selfawb_weekly()
{
    global $connection;
    $options = get_option('fan_options');
    $user = $options['user'];
    $password = $options['password'];
    $id = $options['ID'];
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.selfawb.ro/export_strazi_integrat.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('username' => $user, 'client_id' => $id, 'user_pass' => $password, 'judet' => ''),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $_collection = setBody($response);
    $prep = array();

    $connection->query('TRUNCATE `wpmh_fancourier_cities`'); // Clean Table

    foreach ($_collection as $insData) {
        foreach ($insData as $k => $v) {
            $prep[':' . $k] = $v;
            if ($k == 'agentie') {
                $sth = $connection->prepare("INSERT INTO wpmh_fancourier_cities ( " . implode(', ', array_keys($insData)) . ") VALUES (" . implode(', ', array_keys($prep)) . ")");
                $sth->execute($prep);
            }
        }
    }
}

function setBody($body)
{

    $body = str_replace("\r\n", "\n", $body);
    $body = str_replace("\r", "\n", $body);
    $body = explode("\n", $body);
    $body = array_filter($body);

    $keys = $data = [];
    $header = str_getcsv($body[0]);
    unset($body[0]);

    foreach ($header as $item) {
        $keys[] = trim(preg_replace("/[^a-z0-9]+/", '_', strtolower($item)), '_');
    }

    foreach ($body as $item) {
        $item = str_getcsv($item);
        $row = [];
        foreach ($keys as $i => $key) {
            $row[$key] = $item[$i] ?? null;
        }

        $data[] = $row;
    }

    return $data;
}

function update_streets_weekly()
{
    // Do stuff here
    initiate_selfawb_weekly();

    // Run next Monday
    $next_run = strtotime('next monday');

    // Clear hook, just in case
    wp_clear_scheduled_hook('update_streets_weekly');

    // Add our event
    wp_schedule_single_event($next_run, 'update_streets_weekly');
}

// Run next Monday
$first_run = strtotime('next monday');

// Add our event
wp_schedule_single_event($first_run, 'update_streets_weekly');