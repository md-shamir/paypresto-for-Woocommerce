<?php 
namespace PFW\RT;
class Assets {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [$this, 'register_assets'] );
        add_action( 'admin_enqueue_scripts', [$this, 'register_assets'] );
    }

    public function get_scripts() {
        return [
            'rt-admin-script' => [
                'src' => RT_PAYPRESTO_ASSETS . '/js/admin.js',
                'version' => filemtime( RT_PAYPRESTO_PATH . '/assets/js/admin.js' ),
                'deps' => [ 'jquery' ]
            ],
            'rt-frontend-script' => [
                'src' => RT_PAYPRESTO_ASSETS . '/js/frontend.js',
                'version' => filemtime( RT_PAYPRESTO_PATH . '/assets/js/frontend.js' ),
                'deps' => [ 'jquery' ]
            ],
            'rt-paypresto-script' => [
                'src' => RT_PAYPRESTO_ASSETS . '/js/paypresto.js',
                'version' => filemtime( RT_PAYPRESTO_PATH . '/assets/js/paypresto.js' ),
                'deps' => [ 'jquery' ]
            ],
            'rt-manifest-script' => [
                'src' => RT_PAYPRESTO_ASSETS . '/js/manifest.js',
                'version' => filemtime( RT_PAYPRESTO_PATH . '/assets/js/manifest.js' ),
                'deps' => [ 'jquery' ]
            ],
            'rt-vendor-script' => [
                'src' => RT_PAYPRESTO_ASSETS . '/js/vendor.js',
                'version' => filemtime( RT_PAYPRESTO_PATH . '/assets/js/vendor.js' ),
                'deps' => [ 'jquery' ]
            ],
            
        ];
    }
    public function get_styles(){
        return [
            'rt-admin-style' => [
                'src'     => RT_PAYPRESTO_ASSETS . '/css/admin.css',
                'version' => filemtime( RT_PAYPRESTO_PATH . '/assets/css/admin.css' )
            ],
            'rt-frontend-style' => [
                'src'     => RT_PAYPRESTO_ASSETS . '/css/frontend.css',
                'version' => filemtime( RT_PAYPRESTO_PATH . '/assets/css/frontend.css' )
            ],
            // 'academy-enquiry-style' => [
            //     'src'     => RT_CART_ASSETS . '/css/enquiry.css',
            //     'version' => filemtime( RT_CART_PATH . '/assets/css/enquiry.css' )
            // ],
        ];
    }

    
    public function register_assets(){
        $scripts = $this->get_scripts();
        $styles = $this->get_styles();
        $site_url = site_url() . '/index.php';

        foreach( $scripts as $handle => $script ){
            $deps = isset( $scripts['deps'] ) ? $script['deps'] : false;
            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        foreach( $styles as $handle => $style ){
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;
            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }
        wp_localize_script( 'rt-admin-script', 'RT_ADMIN', [
            'adminURL' => admin_url( '/' ),
            'ajaxURL' => admin_url( 'admin-ajax.php' ),
            'apiURL'  => $site_url . '/wp-json',
            'error'   => __( 'Something went wrong', 'wedevs-academy' ),
        ] );

        wp_localize_script( 'rt-paypresto-script', 'RT_FRONTEND', [
            'adminURL' => admin_url( '/' ),
            'ajaxURL' => admin_url( 'admin-ajax.php' ),
            'apiURL'  => $site_url . '/wp-json',
            'error'   => __( 'Something went wrong', 'wedevs-academy' ),
            'security' => wp_create_nonce('rt-checkout-action'),
        ] );
    }
} 