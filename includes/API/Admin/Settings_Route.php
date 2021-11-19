<?php 
namespace ABC\RT\API\Admin;
use WP_REST_Controller;

class Settings_Route extends WP_REST_Controller {

//     protected $namespace;
//     protected $rest_base;

//     public function __construct() {
//         $this->namespace = 'abc/v1';
//         $this->rest_base = '/products';
//         add_action( 'rest_api_init', [$this, 'register_routes'] );
//     }

//     /**
//      * Register routes
//      */
//     public function register_routes(){
//         register_rest_route($this->namespace, $this->rest_base, [
//             [
//                 'methods' => \WP_REST_Server::READABLE,
//                 'callback' => [$this, 'get_items'],
//                 'permission_callback' => [$this, 'get_route_access'],
//             ],
//             [
//                 'methods' => \WP_REST_Server::CREATABLE,
//                 'callback' => [$this, 'create_items'],
//                 'permission_callback' => [$this, 'get_route_access'],
//             ]
//         ]);
//     }

//     public function get_route_access( $request ){

//         return true;

//     }


//     /**
//      * Response api request
//      *
//      * @param [type] $request
//      * @return json
//      */
//     public function get_items( $request ){
//         $response = get_option('abc_products');

//         return rest_ensure_response( $response );
//     }

//     public function create_items( $request ){

//         $coupon = isset($request['coupon']) ? sanitize_text_field($request['coupon']) : '';
//         $products = isset($request['products']) ? sanitize_text_field($request['products']) : '';
//         // save optin data 
//         delete_option('abc_products');
//         update_option( 'abc_products', array(
//             'coupon' => $coupon,
//             'products' => $products,
//         ));

//         $data = get_option('abc_products');
//         return rest_ensure_response( array(
//             'success' => true,
//             'message' => 'New record created successfully',
//             'data' => $data,
//         ) );
//     }


}