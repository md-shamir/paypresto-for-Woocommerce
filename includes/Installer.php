<?php 
namespace ABC\RT;
/**
 * Run installer 
 */
class Installer {
    // public function run() {
    //     $this->add_version();
    //     $this->create_tables();
    // }

    // public function add_version(){
    //     $installed = get_option('abc_installed');
    //     if( ! $installed ) {
    //         update_option( 'abc_installed', time() );
    //     }
    //     update_option( 'abc_installed', RT_CART_VERSION );
    // }
    // /**
    //  * Create table
    //  *
    //  * @return void
    //  */
    // public function create_tables(){
    //     global $wpdb;

    //     $charset_collate = $wpdb->get_charset_collate();

    //     $schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}abc_product_data` (
    //       `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    //       `first_name` varchar(100) NOT NULL DEFAULT '',
    //       `last_name` varchar(100) NOT NULL DEFAULT '',
    //       `email` varchar(100) NOT NULL DEFAULT '',
    //       `order_details` longtext DEFAULT NULL,
    //       `email_sent` int(11) NOT NULL DEFAULT 0,
    //       `identifier` varchar(100) NOT NULL DEFAULT '',
    //       `last_email_sent` datetime NOT NULL,
    //       `order_placed` int(11) NOT NULL DEFAULT 0,
    //       `created_at` datetime NOT NULL,
    //       PRIMARY KEY (`id`)
    //     ) $charset_collate";

    //     if ( ! function_exists( 'dbDelta' ) ) {
    //         require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    //     }

    //     dbDelta( $schema );

    // }


}