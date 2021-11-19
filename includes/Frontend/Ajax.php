<?php 
namespace PFW\RT\Frontend;
class Ajax {
    public function __construct() {
        add_action( 'wp_ajax_rt_checkout_process', [ $this, 'rt_checkout_process'] );
        add_action( 'wp_ajax_nopriv_rt_checkout_process', [ $this, 'rt_checkout_process'] );
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
            wp_send_json_success( [
                'cart_items' => json_encode($data),
            ] );
    }


}