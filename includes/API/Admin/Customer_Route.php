<?php 
// namespace ABC\RT\API\Admin;
// use WP_REST_Controller;

// class Customer_Route extends WP_REST_Controller {

//     protected $namespace;
//     protected $rest_base;

//     public function __construct() {
//         $this->namespace = 'abc/v1';
//         $this->rest_base = '/emailListing';
//         add_action( 'rest_api_init', [$this, 'register_routes'] );
//     }

//     /**
//      * Register routes
//      */
//     public function register_routes(){
//         register_rest_route($this->namespace, $this->rest_base, [
//             [
//                 'methods' => \WP_REST_Server::READABLE,
//                 'callback' => [$this, 'get_email_listing'],
//                 'permission_callback' => [$this, 'get_route_access'],
//             ]
//         ]);
//     }

//     public function get_route_access( $request ){

//         return true;

//     }


//     public function get_email_listing(){
//         global $wpdb;
//         $prefix = $wpdb->prefix;
//         $table = $prefix . "abc_product_data";
//         $results = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
//         return rest_ensure_response( json_encode($results) );
//     }

// }