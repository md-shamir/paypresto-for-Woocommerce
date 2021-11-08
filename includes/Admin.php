<?php 
namespace ABC\RT;

use ABC\RT\API\Admin\Settings_Route;
use Automattic\WooCommerce\Admin\Features\Settings;

class Admin {
    public function __construct() {
        new Admin\Admin_Menu();
    }
}