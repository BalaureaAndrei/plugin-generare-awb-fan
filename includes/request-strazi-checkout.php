<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include_once $path . '/wp-config.php';
$host     = DB_HOST;
$user     = DB_USER;
$pass     = DB_PASSWORD;
$dbname   = DB_NAME;
$connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

$judet = 'Bucuresti';
$localitate = 'Bucuresti';
$strada ="Topli";

$colection = $connection->query("SELECT DISTINCT strada,de_la,pana_la FROM `wpmh_fancourier_cities` WHERE `judet` = '$judet' AND `localitate` = '$localitate' AND `strada` LIKE '%$strada%' LIMIT 200");

foreach($colection as $array){
    var_dump($array['strada'] . ' ' . $array['de_la'] . '-' .$array['pana_la']);
}
