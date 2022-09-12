<?php

/**
 
 * @package plugin-fan-ddv
 
 */

/*
 
Plugin Name: Plugin Fan DDV
 
 
Description: Plugin for FAN AWB Generation
 
Version: 0.1(alpha)
 
Author: Balaurea Andrei
 
 
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// CUSTOM FOR FAN CURIER

/**
 * Add custom field to the checkout page
 */


include(plugin_dir_path(__FILE__) . './includes/post_file_on_server.php');
include(plugin_dir_path(__FILE__) . './includes/add_admin_menu.php');
include(plugin_dir_path(__FILE__) . './includes/post-fan.php');

$domain = get_option('fan_options')['domain'];

add_action('woocommerce_after_checkout_billing_form', 'custom_checkout_field');

function custom_checkout_field($checkout)

{

    woocommerce_form_field(
        'cod_postal',
        array(
            'type' => 'text',

            'class' => array(
                'my-field-class form-row-wide'
            ),

            'label' => __('Cod postal'),
            'placeholder' => __('Cod postal'),

        ),

        $checkout->get_value('cod_postal')
    );
}

/**

 * Checkout Process

 */

add_action('woocommerce_checkout_process', 'customised_checkout_field_process');

function customised_checkout_field_process()

{

    // Show an error message if the field is not set.

    if (!$_POST['cod_postal']) wc_add_notice(__('Va rugam introduceti codul postal'), 'error');
}

/**

 * Update the value given in custom field

 */

add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta');

function custom_checkout_field_update_order_meta($order_id)

{

    if (!empty($_POST['cod_postal'])) {

        update_post_meta($order_id, 'cod_postal', sanitize_text_field($_POST['cod_postal']));
    }
}


// display the extra data in the order admin panel
function kia_display_order_data_in_admin($order)
{
    $domain = get_option('fan_options')['domain'];
    $order_data = $order->get_data();
    $data = array(
        array('Tip serviciu', 'Banca', 'IBAN', 'Nr. Plicuri', 'Nr. Colete', 'Greutate', 'Plata expeditie', 'Ramburs(bani)', 'Plata ramburs la', 'Valoare declarata', 'Persoana contact expeditor', 'Observatii', 'Continut', 'Nume destinatar', 'Persoana contact', 'Telefon', 'Fax', 'Email', 'Judet', 'Localitatea', 'Strada', 'Nr', 'Cod postal', 'Bloc', 'Scara', 'Etaj', 'Apartament', 'Inaltime pachet', 'Latime pachet', 'Lungime pachet', 'Restituire', 'Centru Cost', 'Optiuni', 'Packing', 'Date personale', 'Referinta', 'Strada-DropOff')
    );
    $diacritics_array = array('Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', /* Diacritice romanesti */ 'ă' => 'a', 'Ă' => 'A', 'â' => 'a', 'Â' => 'A', 'ș' => 's', 'ş' => 's', 'Ș' => 'S', 'Ş' => 'S', 'ț' => 't', 'ţ' => 't', 'Ț' => 'T', 'Ţ' => 'T');

    $order_id = $order_data['id'];
    $country = $order_data['billing']['country'];
    $state = $order_data['billing']['state'];
    $serviciu = 'Standard';
    $_banca = '';
    $_IBAN = '';
    $plicuri = '';
    $colete = '1';
    $greutate = '6';
    $plata_expeditie = 'expeditor';
    $ramburs = '';
    $plata_ramburs_la = '';
    $valoare_declarata = '';
    $persoana_contact_expeditor = '';
    $observatii = '';
    $continut = '';
    $nume_destinatar = $order_data['billing']['last_name'] . ' ' . $order_data['billing']['first_name'];
    $persoana_contact = $order_data['billing']['last_name'] . ' ' . $order_data['billing']['first_name'];
    $telefon = $order_data['billing']['phone'];
    $fax = '';
    $email = $order_data['billing']['email'];
    $judetDiacritice = WC()->countries->get_states($country)[strtr($state, $diacritics_array)];
    $judet = strtr($judetDiacritice, $diacritics_array);
    $localitate = strtr($order_data['billing']['city'], $diacritics_array);
    $strada = strtr($order_data['billing']['address_1'], $diacritics_array);
    $nr_strada = '';
    $cod_postal = get_post_custom_values('cod_postal', $order_id)[0] ?? '111111';
    $bloc = '';
    $scara = '';
    $etaj = '';
    $apartament = '';
    $inaltime_produs = '20';
    $latime_produs = '30';
    $lungime_produs = '30';
    $restituire = '';
    $centru_cost = '';
    $optiuni_speciale = '';
    $packing = '';
    $date_personale = '';
    $referinta = '';
    $strada_drop_off = '';

    if (!is_numeric($cod_postal) || strlen($cod_postal) > 6) {
        $cod_postal = '111111';
    }

    $csvRow = array($serviciu, $_banca, $_IBAN, $plicuri, $colete, $greutate, $plata_expeditie, $ramburs, $plata_ramburs_la, $valoare_declarata, $persoana_contact_expeditor, $observatii, $continut, $nume_destinatar, $persoana_contact, $telefon, $fax, $email, $judet, $localitate, $strada, $nr_strada, $cod_postal, $bloc, $scara, $etaj, $apartament, $inaltime_produs, $latime_produs, $lungime_produs, $restituire, $centru_cost, $optiuni_speciale, $packing, $date_personale, $referinta, $strada_drop_off);
    array_push($data, $csvRow);
    $filename = str_replace(' ', '', $order_data['billing']['last_name']) . '-' . $order_id . '.csv';


?>
<div id="custom-awb" class="order_data_column" style="width:100%">
    <h4><?php _e('Extra Details'); ?></h4>
    <?php
        echo '<button style="color:#4788BD;cursor:pointer;display:block" name="button1" class="csv-download">Genereaza CSV pentru AWB FAN Courier</button>';
        echo $filename;
        echo '<button style="color:#4788BD;display:block" name="button2" class="awb-post">Trimite AWB catre FAN Courier</button>';
        echo '<p style = "color:red">!!!! Nu apasati fara a genera CSV !!!!</p>'
        ?>
</div>

<script type="text/javascript">
(function($) {
    function saveCSV(array) {

        // (B) ARRAY TO CSV STRING
        var csv = "";
        array.forEach((row, i) => {
            row.forEach((col, j) => {
                if (i > 0 && j === 22) {
                    // COD POSTAL
                    csv += col + ",";
                } else {
                    csv += col + ",";
                }
            })
            csv += "\r\n";
        })

        // (C) CREATE BLOB OBJECT
        var myBlob = new Blob([csv], {
            type: "text/csv"
        });

        // (D) CREATE DOWNLOAD LINK
        var url = window.URL.createObjectURL(myBlob);
        var anchor = document.createElement("a");
        anchor.href = url;
        anchor.download = "<?php echo $filename ?>";

        // (E) "FORCE DOWNLOAD"
        // NOTE: MAY NOT ALWAYS WORK DUE TO BROWSER SECURITY
        // BETTER TO LET USERS CLICK ON THEIR OWN
        anchor.click();
        window.URL.revokeObjectURL(url);
        anchor.remove();
    }


    var jsArrayPost = '<?php echo json_encode($data); ?>';
    var jsArray = <?php echo json_encode($data); ?>;


    $('.csv-download').click(function(event) {
        event.preventDefault();
        saveCSV(jsArray);
        $.ajax({
            url: "<?php echo $domain ?>/wp-admin/admin-ajax.php",
            type: "POST",
            data: `action=post_file_on_server&filename=<?php echo $filename ?>&file=${jsArrayPost}`,
            success: function(msg) {
                console.log(msg)
            }
        });
    });

    $('.awb-post').click(function(event) {
        event.preventDefault();
        $.ajax({
            url: "<?php echo $domain ?>/wp-admin/admin-ajax.php",
            type: "POST",
            data: `action=post_fan&csv=<?php echo $domain ?>/wp-admin/csv_fan/<?php echo $filename ?>`,
            success: function(awb) {
                console.log(awb)
                var anchor = document.createElement("a");
                anchor.href = `https://www.selfawb.ro/fancourier/awb/view-awb?awb=${awb}`;
                anchor.target = "blank";
                anchor.innerHTML = 'Vezi AWB';
                document.querySelector('#custom-awb').insertAdjacentElement('beforeend',
                    anchor);
                // anchor.click();
                // anchor.remove();
            }
        });
        $('.awb-post').attr('disabled', "true");
    })

})(jQuery);
</script>

<?php }
add_action('woocommerce_admin_order_data_after_order_details', 'kia_display_order_data_in_admin');


// CUSTOM BULK ACTION GENERARE AWB
// Adding to admin order list bulk dropdown a custom action 'custom_GENERARE AWB'


add_filter('bulk_actions-edit-shop_order', 'generate_bulk_actions_edit_product', 20, 1);
function generate_bulk_actions_edit_product($actions)
{
    $actions['generate_awb_fan'] = __('Genereaza CSV pentru AWB Fan', 'woocommerce');
    return $actions;
}

// Make the action from selected orders
add_filter('handle_bulk_actions-edit-shop_order', 'generate_handle_bulk_action_edit_shop_order', 10, 3);
function generate_handle_bulk_action_edit_shop_order($redirect_to, $action, $post_ids)
{
    $domain = get_option('fan_options')['domain'];
    if ($action !== 'generate_awb_fan')
        return $redirect_to; // Exit

    $diacritics_array = array('Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', /* Diacritice romanesti */ 'ă' => 'a', 'Ă' => 'A', 'â' => 'a', 'Â' => 'A', 'ș' => 's', 'ş' => 's', 'Ș' => 'S', 'Ş' => 'S', 'ț' => 't', 'ţ' => 't', 'Ț' => 'T', 'Ţ' => 'T');


    $date = date('d-m-y-h:i:s');
    $folder = "csv_fan/" . 'Comenzi' . '-' . $date;
    mkdir($folder);
    $data = array(
        array('Tip serviciu', 'Banca', 'IBAN', 'Nr. Plicuri', 'Nr. Colete', 'Greutate', 'Plata expeditie', 'Ramburs(bani)', 'Plata ramburs la', 'Valoare declarata', 'Persoana contact expeditor', 'Observatii', 'Continut', 'Nume destinatar', 'Persoana contact', 'Telefon', 'Fax', 'Email', 'Judet', 'Localitatea', 'Strada', 'Nr', 'Cod postal', 'Bloc', 'Scara', 'Etaj', 'Apartament', 'Inaltime pachet', 'Latime pachet', 'Lungime pachet', 'Restituire', 'Centru Cost', 'Optiuni', 'Packing', 'Date personale', 'Referinta', 'Strada-DropOff')
    );

    foreach ($post_ids as $post_id) {
        $order = wc_get_order($post_id);
        $order_data = $order->get_data();

        $order_id = $order_data['id'];
        $country = $order_data['billing']['country'];
        $state = $order_data['billing']['state'];
        $serviciu = 'Standard';
        $_banca = '';
        $_IBAN = '';
        $plicuri = '';
        $colete = '1';
        $greutate = '6';
        $plata_expeditie = 'expeditor';
        $ramburs = '';
        $plata_ramburs_la = '';
        $valoare_declarata = '';
        $persoana_contact_expeditor = '';
        $observatii = '';
        $continut = '';
        $nume_destinatar = $order_data['billing']['last_name'] . ' ' . $order_data['billing']['first_name'];
        $persoana_contact = $order_data['billing']['last_name'] . ' ' .  $order_data['billing']['first_name'];
        $telefon = $order_data['billing']['phone'];
        $fax = '';
        $email = $order_data['billing']['email'];
        $judetDiacritice = WC()->countries->get_states($country)[strtr($state, $diacritics_array)];
        $judet = strtr($judetDiacritice, $diacritics_array);
        $localitate = strtr($order_data['billing']['city'], $diacritics_array);
        $strada = strtr($order_data['billing']['address_1'], $diacritics_array);
        $nr_strada = '';
        $cod_postal = get_post_custom_values('cod_postal', $order_id)[0] ?? '111111';
        $bloc = '';
        $scara = '';
        $etaj = '';
        $apartament = '';
        $inaltime_produs = '20';
        $latime_produs = '30';
        $lungime_produs = '30';
        $restituire = '';
        $centru_cost = '';
        $optiuni_speciale = '';
        $packing = '';
        $date_personale = '';
        $referinta = '';
        $strada_drop_off = '';

        if (!is_numeric($cod_postal) || strlen($cod_postal) > 6) {
            $cod_postal = '111111';
        }

        $csvRow = array($serviciu, $_banca, $_IBAN, $plicuri, $colete, $greutate, $plata_expeditie, $ramburs, $plata_ramburs_la, $valoare_declarata, $persoana_contact_expeditor, $observatii, $continut, $nume_destinatar, $persoana_contact, $telefon, $fax, $email, $judet, $localitate, $strada, $nr_strada, $cod_postal, $bloc, $scara, $etaj, $apartament, $inaltime_produs, $latime_produs, $lungime_produs, $restituire, $centru_cost, $optiuni_speciale, $packing, $date_personale, $referinta, $strada_drop_off);
        array_push($data, $csvRow);
    }
    $filename = 'Comenzi.csv';

    $f = fopen($folder . '/' . $filename, 'w');
    if ($f === false) {
        die('Error opening the file ' . $filename);
    }

    // write each row at a time to a file
    foreach ($data as $row) {
        fputcsv($f, $row);
    }
    // close the file
    fclose($f);
    //         return $redirect_to;

    return "$domain/wp-admin/$folder/$filename";
}

// The results notice from bulk action on orders
add_action('admin_notices', 'generate_bulk_action_admin_notice');

function generate_bulk_action_admin_notice()
{
    if (empty($_REQUEST['generate_awb_fan'])) return; // Exit

    $count = intval($_REQUEST['processed_count']);

    printf("<div id='message'>S-a generat awb pentru $count comenzi</div>");
}

// require 'post-fan.php';
// require 'post_file_on_server.php';
// require 'script_autogenerare_strada.php';