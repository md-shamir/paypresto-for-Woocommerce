<?php 
namespace PFW\RT\Frontend;
class Ajax {
    public function __construct() {
        add_action( 'wp_ajax_rt_checkout_process', [ $this, 'rt_checkout_process'] );
        add_action( 'wp_ajax_nopriv_rt_checkout_process', [ $this, 'rt_checkout_process'] );
        //checkout proccess
        add_action( 'wp_ajax_rt_payment_process', [ $this, 'rt_payment_process'] );
        add_action( 'wp_ajax_nopriv_rt_payment_process', [ $this, 'rt_payment_process'] );
    }

    public function rt_checkout_process(){
        global $woocommerce;
        if( !wp_verify_nonce( $_REQUEST['security'], 'rt-checkout-action' ) ){
            wp_send_json_error( [
                'message' => __('There is something wrong!', 'rt-paypresto'),
            ] );
        }
        $items = $woocommerce->cart->get_cart();
        $data = [];
        foreach($items as $item => $values) {
            $_product =  wc_get_product( $values['data']->get_id() );
            //product image
            $getProductDetail = wc_get_product( $values['product_id'] );
            $image = $getProductDetail->get_image();
            $title = $_product->get_title();
            $quantity = $values['quantity'];
            $price = get_post_meta($values['product_id'] , '_price', true);
            $regular_price = get_post_meta($values['product_id'] , '_regular_price', true); 
            
            array_push($data, ['product_id' => $values['product_id'], 
                                'title' => $title,
                                'image' => $image,
                                'price' => $price,
                                'quantity' => $quantity,
                                'regular_price' => $regular_price
                            ]);

            }
            // payment gateway info 
            $wc_gateways      = new \WC_Payment_Gateways();
            $payment_gateways = $wc_gateways->get_available_payment_gateways();
        
            // Loop through Woocommerce available payment gateways
            $gateway_info = [];

            foreach( $payment_gateways as $gateway_id => $gateway ){
                if( $gateway_id == 'paypresto' ) {
                    $title = $gateway->get_title();
                    $description = $gateway->get_description();
                    $paypresto_api = $gateway->settings['paypresto_api_key'];
                    $coin_ranking = $gateway->settings['coin_ranking_api_key'];
                    array_push($gateway_info, ['title' => $title, 'description' => $description, 'paypresto_api' => $paypresto_api, 'coin_ranking' => $coin_ranking ]);
                }
                
            }
            wp_send_json_success( [
                'cart_items' => json_encode($data),
                'gateway_info' => json_encode($gateway_info),
            ] );
    }


    public function rt_payment_process(){
        global $woocommerce;
        $email = isset($_POST['customer_email']) ? $_POST['customer_email'] : '';
        $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $items = $woocommerce->cart->get_cart();
        $address = array(
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'company'    => '',
            'email'      => $email,
            'phone'      => '',
            'address_1'  => '',
            'address_2'  => '',
            'city'       => '',
            'state'      => '',
            'postcode'   => '',
            'country'    => ''
        );
        $order = wc_create_order();
        foreach($items as $item => $values) { 
            $product_id =  $values['data']->get_id();
            //product image
            $product = wc_get_product( $product_id );
            $produuct_image = $product->get_image(); // accepts 2 arguments ( size, attr )

            $product_image = $product->get_title();
            $quantity = $values['quantity'];
            $price = get_post_meta($product_id, '_price', true);
            $regular_price = get_post_meta($product_id, '_regular_price', true);
            $sale_price = get_post_meta($product_id, '_sale_price', true);
            $order->add_product( $product,  $quantity);
        }
        $order->set_address($address, 'billing');
        $order->update_status('completed', true );
        WC()->cart->empty_cart();
        // $new_order->set_address($address, 'shipping');
        $order_received_url = wc_get_endpoint_url( 'order-received', $order->get_id(), wc_get_checkout_url() );
        // $order_received_url = add_query_arg( 'key', $order->get_order_key(), $order_received_url );

        wp_send_json_success( [
            'message' => 'success',
            'redirect' => $order_received_url
        ] );
    }

}