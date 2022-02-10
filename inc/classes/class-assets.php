<?php 
namespace pizza\pool;

class assets {

    public function __construct()
    {
       add_action( 'wp_enqueue_scripts', [$this, 'scripts']); 
    }

   /**
    * load requre script only on checkout page to manage ajax load 
    */
   public function scripts() {
        wp_enqueue_script( 'pizza-pool-script', PIZZA_POOL_ASSETS_URL. 'assets/js/pizza-pool.js', ['jquery'], PIZZA_POOL_VERSION, 'true');
        wp_enqueue_style('pizza-pool-style', PIZZA_POOL_ASSETS_URL.'assets/css/pizza-pool.css', '', PIZZA_POOL_VERSION); 
       
    }
}