<?php

function check_for_plugin_update($checked_data)
{
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    $plugin_data     = get_plugin_data(plugin_dir_path(__FILE__) . 'sprawdziany-block-users.php');
    $current_version = $plugin_data['Version'];

    $api_response = wp_remote_get('https://raw.githubusercontent.com/vocus07/sprawdziany-block-user/main/update.json');

    if (!is_wp_error($api_response) && ($api_response['response']['code'] == 200)) {
        $response = json_decode($api_response['body']);

        // Debugowanie:
        if (is_object($response)) {
            error_log('Odpowiedź jest prawidłowym obiektem.');
            error_log('Nowa wersja: ' . $response->new_version);
        } else {
            error_log('Odpowiedź nie jest prawidłowym obiektem. Oto odpowiedź:');
            error_log(print_r($api_response['body'], true));
        }

        // Zmieniono klucz 'version' na 'new_version'
        if (isset($response->new_version) && version_compare($response->new_version, $current_version, '>')) {
            $plugin_slug = 'sprawdziany-block-users/sprawdziany-block-users.php';

            $update_obj              = new stdClass();
            $update_obj->id          = $plugin_slug;
            $update_obj->slug        = $response->slug;
            $update_obj->new_version = $response->new_version;
            $update_obj->url         = $response->homepage;
            $update_obj->package     = $response->download_url;
            $update_obj->tested      = $response->tested;
            // Możesz dodać więcej pól jeśli są potrzebne

            $checked_data->response[$plugin_slug] = $update_obj;
        }
    }

    return $checked_data;
}
add_filter('pre_set_site_transient_update_plugins', 'check_for_plugin_update');

function force_check_plugin_updates()
{
    $plugin_updates = get_site_transient('update_plugins');
    if ($plugin_updates) {
        $plugin_updates = check_for_plugin_update($plugin_updates);
        set_site_transient('update_plugins', $plugin_updates);
    }
}
add_action('init', 'force_check_plugin_updates');
