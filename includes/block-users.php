<?php

function wc_block_users_check_blocked()
{
    // Najpierw sprawdzamy, czy opcja blokowania użytkowników jest włączona.
    $is_blocking_enabled = get_option('wc_block_users_enabled', 'no'); // domyślnie 'no'

    // Jeśli blokowanie nie jest włączone, od razu kończymy funkcję.
    if ($is_blocking_enabled !== 'yes') {
        return;
    }

    global $wpdb;

    // Pobieranie danych klienta z formularza zamówienia
    $billing_email = isset($_POST['billing_email']) ? sanitize_email($_POST['billing_email']) : '';
    $billing_name = sanitize_text_field($_POST['billing_first_name'] ?? '') . ' ' . sanitize_text_field($_POST['billing_last_name'] ?? '');
    $billing_phone = isset($_POST['billing_phone']) ? wc_clean_phone_number(sanitize_text_field($_POST['billing_phone'])) : '';
    $billing_domain = substr(strrchr($billing_email, "@"), 1);
    $customer_ip = $_SERVER['REMOTE_ADDR'];

    $table_name = $wpdb->prefix . 'zablokowani_klienci';

    $values_to_check = array(
        'name' => $billing_name,
        'phone' => $billing_phone,
        'domain' => $billing_domain,
        'ip' => $customer_ip
    );

    $where_clauses = [];

    // Dodanie warunku dla emaili z użyciem LIKE
    if (!empty($billing_email)) {
        $where_clauses[] = $wpdb->prepare("(type = 'email' AND %s LIKE CONCAT('%', email, '%'))", $billing_email);
    }

    foreach ($values_to_check as $type => $value) {
        if (!empty($value)) {
            $where_clauses[] = $wpdb->prepare("(type = %s AND $type = %s)", $type, $value);
        }
    }

    if (count($where_clauses) == 0) {
        return; // Brak wartości do sprawdzenia
    }

    $where_clause = implode(' OR ', $where_clauses);
    $results = $wpdb->get_results("SELECT id FROM $table_name WHERE $where_clause");

    if ($results) {
        wc_add_notice('Niestety, w tej chwili nie możemy przetworzyć Twojego zamówienia. Spróbuj ponownie później.', 'error');
    }
}

add_action('woocommerce_checkout_process', 'wc_block_users_check_blocked');