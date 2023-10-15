<?php
/*
Plugin Name: Sprawdziany Block Users
Description: Prosty plugin do blokowania użytkowników w WooCommerce.
Version: 1.0.0
Author: vocus
 */

// Włączanie plików
require_once plugin_dir_path(__FILE__) . 'update-checker.php';

require_once plugin_dir_path(__FILE__) . 'includes/functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/database.php';
require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/block-users.php';

register_activation_hook(__FILE__, 'wc_block_users_activate');
