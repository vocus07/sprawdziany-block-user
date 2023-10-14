<?php

function add_settings_tab($settings_tabs)
{
    $settings_tabs['settings_tab_demo'] = __('Zablokowani', 'woocommerce');
    return $settings_tabs;
}

function settings_tab()
{
    woocommerce_admin_fields(wc_block_users_get_settings());
}

function update_settings()
{
    woocommerce_update_options(wc_block_users_get_settings());
}

function wc_block_users_get_settings()
{
    $settings = array(
        'section_end'     => array(
            'type' => 'sectionend',
            'id'   => 'wc_block_users_section_end',
        ),
        'section_title'   => array(
            'name' => __('Zablokowani Klienci', 'woocommerce-block-users'),
            'type' => 'title',
            'desc' => '',
            'id'   => 'wc_block_users_section_title',
        ),
        'enabled'         => array(
            'name' => __('Włącz blokowanie klientów', 'woocommerce-block-users'),
            'type' => 'checkbox',
            'desc' => __('Zaznacz, aby włączyć funkcję blokowania klientów.', 'woocommerce-block-users'),
            'id'   => 'wc_block_users_enabled',
        ),
        'blocked_emails'  => array(
            'name' => __('Adres E-mail', 'woocommerce-block-users'),
            'type' => 'textarea',
            'desc' => __('Możliwości blokowania: [nazwa@email.com] lub [nazwa]', 'woocommerce-block-users'),
            'id'   => 'wc_block_users_emails',
            'css'  => 'width:300px; height: 250px;',
        ),
        'blocked_names'   => array(
            'name' => __('Imię i Nazwisko', 'woocommerce-block-users'),
            'type' => 'textarea',
            'desc' => __('Możliwości blokowania: [Jan Kowalski]', 'woocommerce-block-users'),
            'id'   => 'wc_block_users_names',
            'css'  => 'width:300px; height: 250px;',
        ),
        'blocked_phones'  => array(
            'name' => __('Numer telefonu', 'woocommerce-block-users'),
            'type' => 'textarea',
            'desc' => __('Możliwości blokowania: [600-100-100] lub [600100100] lub [48 i +48]', 'woocommerce-block-users'),
            'id'   => 'wc_block_users_phones',
            'css'  => 'width:300px; height: 250px;',
        ),
        'blocked_domains' => array(
            'name' => __('Domena', 'woocommerce-block-users'),
            'type' => 'textarea',
            'desc' => __('Możliwości blokowania: [google.com]', 'woocommerce-block-users'),
            'id'   => 'wc_block_users_domains',
            'css'  => 'width:300px; height: 250px;',
        ),
        'blocked_ips'     => array(
            'name' => __('Adres IP', 'woocommerce-block-users'),
            'type' => 'textarea',
            'desc' => __('Możliwości blokowania: [111.222.333.444]', 'woocommerce-block-users'),
            'id'   => 'wc_block_users_ips',
            'css'  => 'width:300px; height: 250px;',
        ),
    );
    return $settings;
}

add_filter('woocommerce_settings_tabs_array', 'add_settings_tab', 50);
add_action('woocommerce_settings_tabs_settings_tab_demo', 'settings_tab');
add_action('woocommerce_update_options_settings_tab_demo', 'update_settings');