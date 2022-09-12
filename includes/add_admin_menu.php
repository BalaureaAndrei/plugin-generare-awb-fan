<?php

function fan_add_settings_page()
{
    add_options_page('Custom FAN AWB plugin page', 'Custom FAN AWB Menu', 'manage_options', 'fan-example-plugin', 'fan_render_plugin_settings_page');
}
add_action('admin_menu', 'fan_add_settings_page');

function fan_render_plugin_settings_page()
{
?>
<h2>FAN AWB Settings</h2>
<form action="options.php" method="post">
    <?php
        settings_fields('fan_options');
        do_settings_sections('fan_plugin'); ?>
    <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save'); ?>" />
</form>
<?php
}

function fan_register_settings()
{
    register_setting('fan_options', 'fan_options', 'fan_options_validate');
    add_settings_section('api_settings', 'API Settings', 'fan_plugin_section_text', 'fan_plugin');

    add_settings_field('fan_plugin_setting_domain', 'Domain', 'fan_plugin_setting_domain', 'fan_plugin', 'api_settings');
    add_settings_field('fan_plugin_setting_user', 'SelfAWB Username', 'fan_plugin_setting_user', 'fan_plugin', 'api_settings');
    add_settings_field('fan_plugin_setting_password', 'SelfAWB Password', 'fan_plugin_setting_password', 'fan_plugin', 'api_settings');
    add_settings_field('fan_plugin_setting_ID', 'SelfAWB Client ID', 'fan_plugin_setting_ID', 'fan_plugin', 'api_settings');
}
add_action('admin_init', 'fan_register_settings');

function fan_plugin_section_text()
{
    echo '<p>Here you can set all the options for using the API</p>';
}

function fan_plugin_setting_domain()
{
    $options = get_option('fan_options');
    echo "<input id='fan_plugin_setting_domain' name='fan_options[domain]' type='text' value='" . esc_attr($options['domain']) . "' />";
}

function fan_plugin_setting_user()
{
    $options = get_option('fan_options');
    echo "<input id='fan_plugin_setting_user' name='fan_options[user]' type='text' value='" . esc_attr($options['user']) . "' />";
}

function fan_plugin_setting_password()
{
    $options = get_option('fan_options');
    echo "<input id='fan_plugin_setting_password' name='fan_options[password]' type='text' value='" . esc_attr($options['password']) . "' />";
}

function fan_plugin_setting_ID()
{
    $options = get_option('fan_options');
    echo "<input id='fan_plugin_setting_ID' name='fan_options[ID]' type='text' value='" . esc_attr($options['ID']) . "' />";
}