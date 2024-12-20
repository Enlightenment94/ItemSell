<?php

add_theme_support( 'woocommerce' );

function my_theme_enqueue_styles() {
    wp_enqueue_style('my-theme-style', get_stylesheet_uri()); 
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

function my_theme_enqueue_scripts() {
    wp_enqueue_script('jquery');
    
    wp_enqueue_script('my-custom-script', 
        get_template_directory_uri() . '/assets/js/checkout.js', 
        array('jquery'), 
        '1.0.0', 
        true 
    );
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_scripts');

function load_custom_template() {
    $custom_pages = array(
        '/page-register'  => 'patterns/page-register.php',
        '/page-login'     => 'patterns/page-login.php',
        '/page-dashboard' => 'patterns/dashboard.php',
        '/page-checkout'  => 'patterns/page-checkout.php',
        '/page-verify'    => 'patterns/page-verify.php',
        '/page-forgot'    => 'patterns/page-forgot.php',
        '/page-reset'     => 'patterns/page-reset.php',
        '/page-download'  => 'patterns/page-download.php',
    );

    $current_url = $_SERVER['REQUEST_URI'];
    
    error_log("Current URL: " . $current_url);

    if ($current_url === '/') {
        require get_template_directory() . '/patterns/index.php';
        return; 
    }

    foreach ($custom_pages as $path => $template) {
        if (strpos($current_url, $path) !== false) {
            require get_template_directory() . '/' . $template;

            $css_file_name = str_replace('page-', '', basename($template, '.php'));
            wp_enqueue_style(
                $css_file_name . '-style', 
                get_template_directory_uri() . '/css/' . $css_file_name . '.css'
            );
        }
    }
}

add_action('template_redirect', 'load_custom_template');


function custom_register_user() {
    if (isset($_POST['register_payment_nonce']) && wp_verify_nonce($_POST['register_payment_nonce'], 'register_payment_form')) {
        
        $username = sanitize_text_field($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        
        if (empty($username) || empty($email) || empty($password)) {
            return; 
        }
        
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            echo '<p class="error">Error: ' . $user_id->get_error_message() . '</p>';
            return;
        }

        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);

        $product_id = 75;
        create_order_for_product($user_id, $product_id);
        
        wp_redirect(home_url('/page-checkout/'));
    }
}
add_action('template_redirect', 'custom_register_user');

function create_order_for_product($user_id, $product_id) {
    if (!class_exists('WooCommerce')) {
        return;
    }

    $order = wc_create_order();
    $order->add_product(wc_get_product($product_id), 1);
    
    $order->set_customer_id($user_id);
    $order->set_address(array(
        'first_name' => get_user_meta($user_id, 'first_name', true),
        'last_name'  => get_user_meta($user_id, 'last_name', true),
        'email'      => get_userdata($user_id)->user_email,
    ), 'billing');

    $order->calculate_totals();
    $order->update_status('pending', 'New user registration order for product ID ' . $product_id);
}




function process_payment_form() {
    if (isset($_POST['payment_form_nonce']) && wp_verify_nonce($_POST['payment_form_nonce'], 'process_payment_form')) {

        echo "I am here !!!";
        var_dump($_POST['payment_method_token']);
        $payment_method_token = isset($_POST['payment_method_token']) ? sanitize_text_field($_POST['payment_method_token']) : '';

        echo $payment_method_token;

        if ($payment_method_token) {
            echo "I am here !!!2";
            $order_id = 78; 
            $order = wc_get_order($order_id);

            if (!$order) {
                wc_add_notice('Nie znaleziono zamówienia', 'error');
                wp_redirect(get_permalink());
                exit;
            }

            $payment_gateway = WC()->payment_gateways->get_available_payment_gateways()['woocommerce_payments'];

            if ($payment_gateway) {
                $result = $payment_gateway->process_payment($order_id);

                if ($result['result'] === 'success') {
                    wp_redirect($order->get_checkout_order_received_url());
                    exit;
                } else {
                    wc_add_notice('Błąd płatności: ' . $result['message'], 'error');
                    wp_redirect(get_permalink());
                    exit;
                }
            } else {
                wc_add_notice('Nie znaleziono odpowiedniej bramki płatności', 'error');
                wp_redirect(get_permalink());
                exit;
            }
        } else {
            wc_add_notice('Brak tokena płatności', 'error');
            wp_redirect(get_permalink());
            exit;
        }
    }
}

add_action('template_redirect', 'process_payment_form');


add_filter( 'woocommerce_checkout_fields', 'simplify_checkout_fields' );

function simplify_checkout_fields( $fields ) {
    unset( $fields['billing']['billing_company'] );
    unset( $fields['billing']['billing_address_1'] );
    unset( $fields['billing']['billing_address_2'] );
    unset( $fields['billing']['billing_city'] );
    unset( $fields['billing']['billing_postcode'] );
    unset( $fields['billing']['billing_country'] );
    unset( $fields['billing']['billing_state'] );
    unset( $fields['billing']['billing_email'] );
    unset( $fields['billing']['billing_phone'] );
    unset( $fields['order']['order_comments'] );

    $fields['billing']['billing_first_name']['label'] = 'Imię';
    $fields['billing']['billing_last_name']['label'] = 'Nazwisko';

    return $fields;
}
?>
