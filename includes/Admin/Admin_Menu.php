<?php 
namespace PFW\RT\Admin;
class Admin_Menu {
    

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'settings_page' ] );
    }

    public function settings_page(){
        global $submenu;

        $capability = "manage_options";
        $slug = "abc-settings";

        add_menu_page( __('Paypresto', 'rt-pfw'), __('Paypresto', 'paypresto'), $capability, $slug, [$this, 'paypresto_page_template'] );

        if( current_user_can( $capability )) {
            $submenu[$slug][] = [__('Paypresto settings', 'rt-pfw'), $capability, 'admin.php?page='.$slug . '#/'];
            // $submenu[$slug][] = [__('AB email listing', 'ab-cart'), $capability, 'admin.php?page='.$slug . '#/email-listing'];
        }
    }

    public function paypresto_page_template(){
        wp_enqueue_script( 'abc-admin-script' );
        wp_enqueue_script( 'abc-manifest-script' );
        wp_enqueue_script( 'abc-vendor-script' );
        
        wp_enqueue_style( 'abc-admin-style' );
        
        echo '<div class="wrap"><div id="paypresto-admin-app"></div></div>';
    }


}