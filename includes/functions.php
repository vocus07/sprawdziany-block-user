<?php
function wc_get_real_ip_address()
{
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip); // usuń białe znaki
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
    return null;  // Jeśli nie możemy znaleźć adresu IP, zwracamy null
}


function wc_clean_phone_number($phone)
{
    // Usuń spacje, myślniki, nawiasy, znaki plus i inne znaki specjalne
    $cleaned = preg_replace('/[^0-9]/', '', $phone);

    // Jeśli numer zaczyna się od "48", usuń te cyfry
    if (substr($cleaned, 0, 2) === '48') {
        $cleaned = substr($cleaned, 2);
    }

    return $cleaned;
}
