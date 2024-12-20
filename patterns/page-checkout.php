<?php

if ( !is_user_logged_in() ) {
    wp_redirect( home_url( '/page-login/' ));    
    exit;
}

?>

<?php
function check_cart_and_order_status() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();

        if (WC()->cart->is_empty()) {
            $orders = wc_get_orders(array(
                'customer_id' => $user_id,
                'status'      => array('completed', 'processing')
            ));

            if (empty($orders)) {
                $product_id = 75; 
                $quantity = 1;
                WC()->cart->add_to_cart($product_id, $quantity);                
            } else {
                echo "<center>You have complete orders!</center>";
                wp_safe_redirect(site_url("/page-dashboard/"));
                exit;
            }
        }

        $orders_wait_for_pay = wc_get_orders(array(
            'customer_id' => $user_id,
            'status'      => array('on-hold')
        ));

        if (!empty($orders_wait_for_pay)) {
            echo "<div class='alert alert-warning'>Czekamy aż opłacisz kurs manualnie.</div>";

            foreach ($orders_wait_for_pay as $order) {
                $order_id = $order->get_id();
                $order_status = wc_get_order_status_name($order->get_status());

                echo "<div class='pending-order'>";
                echo "Order ID: #{$order_id} - Status: {$order_status}";
                echo " | <a href='" . esc_url(add_query_arg(array(
                    'delete_order_id' => $order_id,
                    'redirect' => $_SERVER['REQUEST_URI']
                ))) . "' onclick='return confirm(\"Are you sure you want to delete this order?\");'>Usuń zamówienie</a>";
                echo "</div>";
            }

            if (isset($_GET['delete_order_id'])) {
                $delete_order_id = intval($_GET['delete_order_id']);
                $order_to_delete = wc_get_order($delete_order_id);

                if ($order_to_delete && $order_to_delete->get_customer_id() === $user_id) {
                    $order_to_delete->delete(true); 
                    wp_safe_redirect(remove_query_arg(array('delete_order_id', 'redirect')));
                    exit;
                }
            }

            return 1;
        } else {
            $orders = wc_get_orders(array(
                'customer_id' => $user_id,
            ));

            if(!empty($orders)){
                echo "<center>You have complete orders!</center>";
                wp_safe_redirect(site_url("/page-dashboard/"));
                exit;
            }
        }
    }

    return 0;
}

$flag_manual_pay = check_cart_and_order_status();
?>

<div id="main-content">
    <?php

    if ( ! class_exists( 'WooCommerce' ) ) {
        echo 'WooCommerce nie jest zainstalowane.';
        return;
    }else{
    }

    if($flag_manual_pay != 1){
        wp_enqueue_script( 'wc-checkout' );

        if ( class_exists( 'WooCommerce' ) ) {
            //woocommerce_checkout();
            //do_action( 'woocommerce_checkout_order_review' );
        } else {
            echo 'WooCommerce nie jest aktywne.';
        }

        define( 'WOOCOMMERCE_CHECKOUT', true );

        if ( class_exists( 'WooCommerce' ) && ! WC()->session->has_session() ) {
            WC()->session->set_customer_session_cookie( true );
        }

        if ( class_exists( 'WooCommerce' ) ) {
            $checkout = WC()->checkout();
            wc_get_template( 'checkout/form-checkout.php', array( 'checkout' => $checkout ) );
        } else {
            echo 'WooCommerce nie jest aktywne.';
        }
    }

    ?>

    <div class="margin">
        <img src="<?php echo get_template_directory_uri() . "/logo.webp"; ?>" alt="Logo" style="display: block; margin: 0 auto; width: 150px;" />
    </div>

    <p>Po otrzymaniu płatności otrzymasz nielimitowany dostęp do praktycznej wiedzy wordpress wizard, wprzypadku bramek odrazu przy płaceniu, przy manualnym po zatwierdzeniu przelewu. Jeśli opłacono kurs po zalogowaniu powinno nastąpić przekierowanie do ekranu z kursem.</p>

</div>

