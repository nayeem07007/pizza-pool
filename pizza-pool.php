<?php 
/*
* Plugin Name: Pizza pool
* Description: Pizza Pool is a WooCommerce extention to make aditional delivery option for the resturent  
* Author: Md Nayeem Farid
* Author URI: https://mdnayeemfarid.com
* Plugin URI: https://mdnayeemfarid.com/pizza-pool
* Version: 1.0
* Text Domain: pizza-pool
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'PIZZA_POOL_VERSION', '1.0' );
define( 'PIZZA_POOL_ASSETS_URL', plugin_dir_url( __FILE__ ) );
define('PIZZA_POOL_DIR', __DIR__);

add_action( 'plugins_loaded', 'pizza_pool_language' );

function pizza_pool_language() {
  load_plugin_textdomain( 'pizza-pool' );
}
// autoload class 
require_once PIZZA_POOL_DIR.'/inc/init.php';

// load required css and js 
  new pizza\pool\assets();

//  load food delivery options
new \pizza\pool\delivery;




