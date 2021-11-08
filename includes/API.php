<?php 
namespace ABC\RT;

class API {
    public function __construct() {
        new API\Admin\Settings_Route();
        new API\Admin\Customer_Route();
    }
}