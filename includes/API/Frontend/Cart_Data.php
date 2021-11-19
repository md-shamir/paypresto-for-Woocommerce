<?php 
namespace PFW\RT\API\Frontend;
use WP_REST_Controller;

class Cart_Data extends WP_REST_Controller {

    protected $namespace;
    protected $rest_base;

    public function __construct() {
        $this->namespace = 'RT/v1';
        $this->rest_base = '/cart_data';
        add_action( 'rest_api_init', [$this, 'register_routes'] );
    }

    /**
     * Register routes
     */
    public function register_routes(){
        register_rest_route($this->namespace, $this->rest_base, [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [$this, 'get_cart_data'],
                'permission_callback' => [$this, 'get_route_access'],
            ]
        ]);
    }

    public function get_route_access( $request ){

        return true;

    }


    public function get_cart_data(){
        // global $woocommerce;
        // $items = $woocommerce->cart->get_cart();
        // $data = [];
        // foreach($items as $item => $values) {
        //     $_product =  wc_get_product( $values['data']->get_id() );
        //     //product image
        //     $getProductDetail = wc_get_product( $values['product_id'] );
        //     $image = $getProductDetail->get_image();
        //     $title = $_product->get_title();
        //     $quantity = $values['quantity'];
        //     $price = get_post_meta($values['product_id'] , '_price', true);
        //     $regular_price = get_post_meta($values['product_id'] , '_regular_price', true); 
            
        //     array_push($data, ['product_id' => $values['product_id'], 
        //                         'title' => $title,
        //                         'image' => $image,
        //                         'price' => $price,
        //                         'quantity' => $quantity,
        //                         'regular_price' => $regular_price
        //                     ]);

        // }
        return rest_ensure_response( json_encode(['Hello world']) );
    }

}