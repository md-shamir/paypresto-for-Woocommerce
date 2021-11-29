<?php
/**
 * Plugin Name: Paypresto for Woocommerce
 * Plugin URI: https://reliabletechies.com/products/plugins
 * Description: Payment gateway for woocommerce
 * Author: Reliable Techies
 * Version: 1.0.0
 * Requires at least: 5.2,
 * Requires PHP: 7.3
 * Author URI: https://reliabletechies.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pfw-rt
 */

 // Exit if direct access

 defined('ABSPATH') || exit;

 require_once __DIR__ . '/vendor/autoload.php';

 final class RT_Paypresto {

    const version = "1.0.0";

    /**
     * Class construction 
     */
    private function __construct(){
        $this->define_constants();
        register_activation_hook( __FILE__, [ $this, 'activate'] );
        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );

    }
    /**
     * Initialize a singleton instance 
     * @return \RT_Cart
     */
    public static function init(){
        static $instance = false;
        if( ! $instance ) {
            $instance = new self();
        }
        return $instance;
    }
    /**
     * Assigned plugin constances
     *
     * @return void
     */
    public function define_constants(){
        define( 'RT_PAYPRESTO_VERSION', self::version );
        define( 'RT_PAYPRESTO_FILE', __FILE__ );
        define( 'RT_PAYPRESTO_PATH', __DIR__ );
        define( 'RT_PAYPRESTO_URL', plugins_url( '', RT_PAYPRESTO_FILE ) );
        define( 'RT_PAYPRESTO_ASSETS', RT_PAYPRESTO_URL . '/assets' );
    }
    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin(){
        new \PFW\RT\Assets();
        new \PFW\RT\Frontend\Ajax();
        //new \PFW\RT\Api();

        if( is_admin() ) {
            new \PFW\RT\Admin();
        } else {
            new \PFW\RT\Frontend();
            if( defined('DOING_AJAX') && DOING_AJAX ) {
                new \PFW\RT\Frontend\Ajax();
            }
        }
    }

    public function activate(){
        // $installer = new \PFW\RT\Installer();
        // $installer->run();
    }
    

 }

 /**
  * Initialize the main plugin 
  * @return \RT_Paypresto
  */

  function pfw_cart(){
      return RT_Paypresto::init();
  }

  pfw_cart();