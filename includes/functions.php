<?php 
add_filter('woocommerce_payment_gateways', 'paypresto_payment');
function paypresto_payment( $gateways ){
        $gateways[] = 'Paypresto_Payment';
        return $gateways;
    }
add_action('plugins_loaded', 'init_paypresto_gateway');
function init_paypresto_gateway(){
    class Paypresto_Payment extends WC_Payment_Gateway {
    
        public function __construct() {
            $this->id = "paypresto";
            $this->icon = RT_PAYPRESTO_URL . '/images/paypresto.png';
            $this->has_fields = false;
            $this->method_title = __('Paypresto', 'rt-paypresto');
            $this->description = __('Paypresto payment gateway for Woocommerce');
            $this->supports = array(
                'products'
            );
    
            $this->init_form_fields();
            $this->init_settings();
            $this->title = $this->get_option('title');
            $this->description = $this->get_option( 'description' );
            $this->enabled = $this->get_option('enabled');
            $this->testmode = $this->get_option('testmode');
            $this->private_key = $this->testmode ? $this->get_option('test_private_key') : $this->get_option( 'private_key' );
            $this->publishable_key = $this->testmode ? $this->get_option( 'test_publishable_key' ) : $this->get_option('publishable_key');
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            // We need custom JavaScript to obtain a token
            add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
            add_action( 'woocommerce_api_paypresto', array( $this, 'webhook' ) );
        }
        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => 'Enable/Disable',
                    'label' => 'Enable Payprest',
                    'type' => 'checkbox',
                    'description' => __('Please enable Payprest payment gateway for your store payment gateway'),
                    'default' => 'no'
                ),
                'title' => array(
                    'title' => 'Title',
                    'type' => 'text',
                    'description' => 'Please enter name of your payment gateway',
                    'default' => __('Credit Card', 'rt-paypresto'),
                    'desc_tip' => true
                ),
                'description' => array(
                    'title' => 'Description',
                    'type' => 'textarea',
                    'description' => __('Please enter description for your payment gateway', 'rt-paypresto'),
                    'default' => __('Pay with Payprest payment method', 'rt-payprest'),                
                ),
                'testmode' => array(
                    'title' => __('Test mode', 'rt-paypresto'),
                    'label' => __('Enable test mode', 'rt-paypresto'),
                    'type' => 'checkbox',
                    'description' => __('Enable test mode to test your payment method'),
                    'default' => 'yes',
                    'desc_tip' => true
                ),
                'test_publishable_key' => array(
                    'title'       => __('Test Publishable Key', 'rt-paypresto'),
                    'type'        => 'text'
                ),
                'test_private_key' => array(
                    'title'       => __('Test Private Key', 'rt-paypresto'),
                    'type'        => 'password',
                ),
                'publishable_key' => array(
                    'title'       => __('Live Publishable Key', 'rt-paypresto'),
                    'type'        => 'text'
                ),
                'private_key' => array(
                    'title'       => __('Live Private Key', 'rt-paypresto'),
                    'type'        => 'password'
                )
            );
        }
    
        public function payment_fields(){
            // ok, let's display some description before the payment form
            // if ( $this->description ) {
            //     // you can instructions for test mode, I mean test card numbers etc.
            //     if ( $this->testmode ) {
            //         $this->description .= ' TEST MODE ENABLED. In test mode, you can use the card numbers listed in <a href="#">documentation</a>.';
            //         $this->description  = trim( $this->description );
            //     }
            //     // display the description with <p> tags etc.
            //     echo wpautop( wp_kses_post( $this->description ) );
            // }
            // do_action( 'woocommerce_credit_card_form_start', $this->id );
            
            // echo '<div id="rt-frontend-app"></div>';
        
            // do_action( 'woocommerce_credit_card_form_end', $this->id );
 
        }
    
        public function payment_scripts(){
            // if( ! is_cart() || ! is_checkout() || isset($_GET['pay_for_order'])) {
            //     return;
            // }
            // if( $this->enabled === 'no' ) {
            //     return;
            // }
            // if ( empty( $this->private_key ) || empty( $this->publishable_key ) ) {
            //     return;
            // }
            // // if ( ! $this->testmode && ! is_ssl() ) {
            // //     return;
            // // }
            wp_enqueue_script('rt-frontend-script');
            wp_enqueue_script('rt-manifest-script');
            wp_enqueue_script('rt-vendor-script');
            wp_enqueue_script('rt-paypresto-script');
        }
    
        public function validate_fields(){
    
        }
    
        public function process_payment( $order_id ){
    
        }
    
        public function webhook() {
    
        }
    
        
    }
    
}

add_filter( 'woocommerce_cart_needs_payment', '__return_false' );
add_action('woocommerce_checkout_before_order_review', function(){
    echo '<div id="rt-frontend-app"></div>';
});

// add_action('wp_footer', function(){
//     WC()->cart->add_to_cart(1201);
// });
// add_action( 'wp_ajax_rt_checkout_process', 'rt_checkout_process' );
// add_action( 'wp_ajax_nopriv_rt_checkout_process', 'rt_checkout_process' );

// function rt_checkout_process(){
//     echo json_encode(["Hello world"]);
//     exit;
    
// }