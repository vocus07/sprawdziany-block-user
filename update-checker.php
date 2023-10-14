<?php

function check_for_plugin_update($checked_data)
{
    $api_response = wp_remote_get('https://github.com/vocus07/sprawdziany-block-user/blob/main/update.json');
    if (!is_wp_error($api_response) && ($api_response['response']['code'] == 200)) {
        $response = json_decode($api_response['body']);
        if (version_compare($response->new_version, $current_version, '>')) {
            $checked_data->response['sprawdziany-block-users/sprawdziany-block-users.php'] = $response;
        }
    }
    return $checked_data;
}
add_filter('pre_set_site_transient_update_plugins', 'check_for_plugin_update');