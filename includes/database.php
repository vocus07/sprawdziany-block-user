<?php
global $wpdb;

// Tworzenie tabeli podczas aktywacji pluginu
function wc_block_users_activate()
{
    global $wpdb;

    $table_name      = $wpdb->prefix . 'zablokowani_klienci';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT AUTO_INCREMENT,
        email VARCHAR(255),
        name VARCHAR(255),
        phone VARCHAR(50),
        ip VARCHAR(50),
        domain VARCHAR(255),
        type ENUM('email', 'name', 'phone', 'ip', 'domain'),
        PRIMARY KEY (id),
        INDEX email_index (email),
        INDEX name_index (name),
        INDEX phone_index (phone),
        INDEX ip_index (ip),
        INDEX domain_index (domain),
        INDEX type_index (type)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

function wc_block_users_save_to_database()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'zablokowani_klienci';

    // Sprawdzanie, czy funkcja została wywołana poprzez aktualizację opcji WooCommerce
    if (isset($_POST['save'])) {

        // Czyszczenie tabeli - zakładamy, że każda aktualizacja będzie zastępować wszystkie dane w tabeli
        $wpdb->query("TRUNCATE TABLE $table_name");

        $fields = array(
            'wc_block_users_emails'  => 'email',
            'wc_block_users_names'   => 'name',
            'wc_block_users_phones'  => 'phone',
            'wc_block_users_domains' => 'domain',
            'wc_block_users_ips'     => 'ip',
        );

        foreach ($fields as $field_id => $type) {
            if (isset($_POST[$field_id])) {
                $items = explode("\n", sanitize_textarea_field($_POST[$field_id]));
                foreach ($items as $item) {
                    // Jeżeli typ to 'phone', wyczyść numer przed dodaniem do bazy danych
                    if ($type === 'phone') {
                        $item = wc_clean_phone_number($item);
                    }
                    if (!empty(trim($item))) {
                        $data   = array('type' => $type, $type => trim($item));
                        $format = array('%s', '%s');
                        $wpdb->insert($table_name, $data, $format);
                    }
                }
            }
        }
    }
}

add_action('woocommerce_update_options_settings_tab_demo', 'wc_block_users_save_to_database');
