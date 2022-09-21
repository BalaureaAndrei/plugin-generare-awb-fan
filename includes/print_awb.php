<?php

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.selfawb.ro/view_awb_integrat_pdf.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array('username' => 'clienttest', 'client_id' => '7032158', 'user_pass' => 'testing', 'nr' => '2264200120135', 'language' => 'ro'),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;