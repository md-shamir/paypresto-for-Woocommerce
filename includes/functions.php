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
            $this->icon = $this->get_option('logo') ? $this->get_option('logo') : '';
            $this->has_fields = true;
            $this->method_title = __('Paypresto', 'rt-paypresto');
            $this->method_description = __('Pay with Paypresto', 'rt-paypresto');
            $this->supports = array(
                'products'
            );
    
            $this->init_form_fields();
            $this->init_settings();
            $this->title = $this->get_option('title');
            $this->method_description = $this->get_option('description') ? $this->get_option('description') : '';
            $this->enabled = $this->get_option('enabled');
            $this->paypresto_api =  $this->get_option('paypresto_api_key') ? $this->get_option( 'paypresto_api_key' ) : '';
            $this->coin_ranking_api = $this->get_option( 'test_publishable_key' ) ? $this->get_option('publishable_key') : '';
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            // We need custom JavaScript to obtain a token
            add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
            add_action( 'woocommerce_api_paypresto', array( $this, 'webhook' ) );
        }
        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => 'Enable/Disable',
                    'label' => 'Enable Paypresto',
                    'type' => 'checkbox',
                    'description' => __('Enable Paypresto payment gateway for your store', 'rt-paypresto'),
                    'default' => 'no'
                ),
                'title' => array(
                    'title' => 'Memo',
                    'type' => 'text',
                    'description' => 'Customer sets memo information in orders here',
                    'default' => __('Credit Card', 'rt-paypresto'),
                    'desc_tip' => true
                ),
                'description' => array(
                    'title' => 'Description',
                    'type' => 'textarea',
                    'description' => __('Please enter description for your payment gateway', 'rt-paypresto'),
                    'default' => __('Pay with Payprest payment method', 'rt-payprest'),                
                ),
                // 'testmode' => array(
                //     'title' => __('Test mode', 'rt-paypresto'),
                //     'label' => __('Enable test mode', 'rt-paypresto'),
                //     'type' => 'checkbox',
                //     'description' => __('Enable test mode to test your payment method'),
                //     'default' => 'yes',
                //     'desc_tip' => true
                // ),
                'paypresto_api_key' => array(
                    'title'       => __('Private Key', 'rt-paypresto'),
                    'type'        => 'password'
                ),
                'coin_ranking_api_key' => array(
                    'title'       => __('Coin Ranking API key', 'rt-paypresto'),
                    'type'        => 'password',
                ),
                'logo' => array(
                    'title' => 'Logo',
                    'type' => 'text',
                    'description' => __('Please enter payment gateway logo url', 'rt-paypresto'),
                ),
                // 'publishable_key' => array(
                //     'title'       => __('Live Publishable Key', 'rt-paypresto'),
                //     'type'        => 'text'
                // ),
                // 'private_key' => array(
                //     'title'       => __('Live Private Key', 'rt-paypresto'),
                //     'type'        => 'password'
                // )
            );
        }
    
        public function payment_fields(){
            // ok, let's display some description before the payment form
            // if ( $this->method_description ) {
            //     // you can instructions for test mode, I mean test card numbers etc.
            //     // if ( $this->testmode ) {
            //     //     $this->description .= ' TEST MODE ENABLED. In test mode, you can use the card numbers listed in <a href="#">documentation</a>.';
            //     //     $this->description  = trim( $this->description );
            //     // }
            //     // display the description with <p> tags etc.
            //     echo wpautop( wp_kses_post( $this->description ) );
            // }
            do_action( 'woocommerce_credit_card_form_start', $this->id );
            if( $this->method_description ) {
                echo wpautop( wp_kses_post( $this->method_description ) );
            }
            $modal = '';
            $modal .= '<div class="modal micromodal-slide" id="rt-paypresto" aria-hidden="true">';
                $modal .= '<div class="modal__overlay" tabindex="-1" data-micromodal-close>';
                    $modal .= '<div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">';
                        $modal .= '<div id="paypresto_widget"></div>';
                    $modal .= '</div>';
                $modal .= '</div>';
            $modal .= '</div>';
            echo $modal;
            do_action( 'woocommerce_credit_card_form_end', $this->id );
            
 
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

            if( is_checkout() ) {
                wp_enqueue_script( 'bsv', 'https://unpkg.com/bsv', array(), null, false );
                wp_enqueue_script( 'txforge', 'https://unpkg.com/txforge', array(), null, false );
                wp_enqueue_script( 'paypresto', 'https://unpkg.com/paypresto.js', array(), null, false );
                wp_enqueue_script( 'rt-axios' );
                wp_enqueue_script( 'rt-modal' );
                wp_enqueue_script( 'rt-script' );
                wp_enqueue_style( 'rt-frontend-style' );


                wp_localize_script( 'rt-script', 'RT_FRONTEND', [
                    'adminURL' => admin_url( '/' ),
                    'ajaxURL' => admin_url( 'admin-ajax.php' ),
                    'apiURL'  => site_url() . '/wp-json',
                    'error'   => __( 'Something went wrong', 'wedevs-academy' ),
                    'security' => wp_create_nonce('rt-checkout-action'),
                    'payprestoApiKey' => $this->paypresto_api,
                    'coinrankingApiKey' => $this->get_option('coin_ranking_api_key'),
                    'title' => $this->title,
                    'description' => $this->method_description
                ] );
            }

        }
    
        public function validate_fields(){
            return true;
        }
    
        public function process_payment( $order_id ){
    
        }
    
        public function webhook() {
    
        }
    
        
    }
    
}

// add_action('woocommerce_after_checkout_form', function(){
//     require_once RT_PAYPRESTO_PATH . "/includes/Frontend/views/micromodal.php";
// });


// add_action('wp_head', function(){
//     echo '<div id="rt-paypresto"></div>';
// });

// add_action('wp_footer', function(){
//     WC()->cart->add_to_cart(1201);
// });
