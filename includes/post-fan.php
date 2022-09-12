<?php

add_action('wp_ajax_post_fan', 'post_fan');    //execute when wp logged in
add_action('wp_ajax_nopriv_post_fan', 'post_fan'); //execute when logged out

function post_fan()
{
  $options = get_option('fan_options');
  $user = $options['user'];
  $password = $options['password'];
  $id = $options['ID'];
  $linkFisier = $_POST["csv"];
  $curl = curl_init();

  $functie_post = curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.selfawb.ro/import_awb_integrat.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array('username' => $user, 'client_id' => $id, 'user_pass' => $password, 'fisier' => new CURLFILE($linkFisier)),
  ));

  $response = curl_exec($curl);

  $string_array = explode(",", $response);
  for ($i = 0; $i < sizeof($string_array); $i++) {
    if ($i == 2) {
      $awb_id = $string_array[$i];
    }
  }
  echo $awb_id;
?>


<?php

  curl_close($curl);



  die();
}