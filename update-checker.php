<?php

function check_for_plugin_update($checked_data)
{
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    $plugin_data     = get_plugin_data(plugin_dir_path(__DIR__) . 'sprawdziany-block-users.php');
    $current_version = $plugin_data['Version'];

    $api_response = wp_remote_get('https://raw.githubusercontent.com/vocus07/sprawdziany-block-user/main/update.json');

    if (!is_wp_error($api_response) && ($api_response['response']['code'] == 200)) {
        $response = json_decode($api_response['body']);
        if (version_compare($response->version, $current_version, '>')) {
            $checked_data->response['sprawdziany-block-users/sprawdziany-block-users.php'] = (array)$response;
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
