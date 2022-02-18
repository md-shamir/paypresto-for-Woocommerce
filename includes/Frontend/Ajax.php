<?php 
namespace PFW\RT\Frontend;
class Ajax {
    public function __construct() {
        add_action( 'wp_ajax_rt_checkout_process', [ $this, 'rt_checkout_process'] );
        add_action( 'wp_ajax_nopriv_rt_checkout_process', [ $this, 'rt_checkout_process'] );
        //checkout proccess
        add_action( 'wp_ajax_rt_payment_process', [ $this, 'rt_payment_process'] );
        add_action( 'wp_ajax_nopriv_rt_payment_process', [ $this, 'rt_payment_process'] );

        add_action( 'wp_ajax_rt_validate_user_email', [ $this, 'rt_validate_user_email'] );
        add_action( 'wp_ajax_nopriv_rt_validate_user_email', [ $this, 'rt_validate_user_email'] );
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
        // $email = isset($_POST['customer_email']) ? $_POST['customer_email'] : '';
        // $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        // $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $form_data = isset($_POST['form_data']) ? $_POST['form_data'] : '';
        // echo json_encode($form_data['billing_first_name']);
        // exit;
        $address = array(
            'first_name' => $form_data['billing_first_name'],
            'last_name'  => $form_data['billing_last_name'],
            'company'    => $form_data['billing_company'],
            'email'      => $form_data['billing_email'],
            'phone'      => $form_data['billing_phone'],
            'address_1'  => $form_data['billing_address_1'],
            'address_2'  => $form_data['billing_address_2'],
            'city'       => $form_data['billing_city'],
            'state'      => $form_data['billing_state'],
            'postcode'   => $form_data['billing_postcode'],
            'country'    => $form_data['billing_country'],
        );

        $order = wc_create_order();

        $items = $woocommerce->cart->get_cart();
        foreach($items as $item => $values) { 
            $product_id =  $values['data']->get_id();
            $product = wc_get_product( $product_id );
            $quantity = $values['quantity'];
            $order->add_product( wc_get_product($product_id),  $quantity);
        }
        $order->set_address($address, 'billing');
        update_post_meta( $order->id, '_payment_method', 'Paypresto' );
        update_post_meta( $order->id, '_payment_method_title', 'Paypresto' );
        $order->calculate_totals();
        $order->update_status('completed', 'paypresto', true );
        WC()->cart->empty_cart();
        $order_received_url = wc_get_endpoint_url( 'order-received', $order->get_id(), wc_get_checkout_url() );
        wp_send_json_success( [
            'message' => 'success',
            'redirect' => $order_received_url
        ] );
    }

    public function rt_validate_user_email(){
        if( is_user_logged_in() ) {
            return;
        }
        $email = isset($_GET['email']) ? $_GET['email'] : '';
        $exist = email_exists( $email );
        if( ! empty($exist) ) {
            wp_send_json_success([
                'success' => true,
                'login' => get_permalink( wc_get_page_id( 'myaccount' ) )
            ]);
        }
    }

}
