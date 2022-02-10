<?php 
namespace pizza\pool;

class delivery {

    public function __construct()
    {

        add_filter('woocommerce_order_button_html', [$this, 'should_display_place_order_button'] );
        add_action( 'woocommerce_after_checkout_billing_form', [$this, 'add_dilavery_option_fields'] );
        add_action( 'woocommerce_cart_calculate_fees', [$this, 'woo_add_cart_fee'] );
        add_action( 'woocommerce_cart_calculate_fees', [$this, 'prefix_add_discount_line'] );

    }

    public function should_display_place_order_button($button) {
        $is_open =  new \pizza\pool\schedules;
        if($is_open->is_pizza_pool_open() == 'open'){
            return $button;
        }else{
            echo $is_open->is_pizza_pool_open();
        }
      
    }
    public function add_dilavery_option_fields($checkout) {
        echo '<div id="message_fields">';
        woocommerce_form_field( 'airport_pickup', array(
            'type'          => 'select',
            'class'         => array('airport_pickup form-row-wide'),
            'label'         => __('Would you like us to arrange transportation from the airport to your starting hotel?'),
            'required'    => true,
            'options'     => array(
                            '10_dine-in'=>'Dine-in',
                            '0_takeaway' => __('Takeaway'),
                            '0_Delivery' => __('Delivery')
            ),
            'default' => 'N'), 
            $checkout->get_value( 'airport_pickup' ));
	echo '</div>';
    }
    function woo_add_cart_fee( $cart ){
        if ( ! $_POST || ( is_admin() && ! is_ajax() ) ) {
            return;
        }
    
        if ( isset( $_POST['post_data'] ) ) {
            parse_str( $_POST['post_data'], $post_data );
        } else {
            $post_data = $_POST;
        }
    
        if (isset($post_data['airport_pickup'])) {
            
            $get_discount_val = explode('_', $post_data['airport_pickup']);
            $duduct_numb = $get_discount_val[0];
            $value_text = ucfirst(end($get_discount_val));
          
            if($get_discount_val[0] > 0 ){
                $extracost = ($cart->subtotal * $get_discount_val[0])/100;
                WC()->cart->add_fee( esc_html__("$duduct_numb % Charge for $value_text"), $extracost );
            }else{
                WC()->cart->add_fee( esc_html__("$duduct_numb % Charge for $value_text"), '' );
            }
            
        }
    
    }

    // get first order 

    public function is_user_first_order () {
        // Get all customer orders
    $customer_orders = get_posts( array(
        'numberposts' => 1, // one order is enough
        'meta_key'    => '_customer_user',
        'meta_value'  => get_current_user_id(),
        'post_type'   => 'shop_order', // WC orders post type
        'post_status' => 'wc-completed', // Only orders with "completed" status
        'fields'      => 'ids', // Return Ids "completed"
    ) );

    // return "true" when customer has already at least one order (false if not)
     return count($customer_orders) > 0 ? true : false; 
    }

    function prefix_add_discount_line( $cart ) {

        if($this->is_user_first_order() || !is_user_logged_in()){
            $discount = $cart->subtotal * 0.4;      
            $cart->add_fee( __( '40% Discount for first order', 'yourtext-domain' ) , -$discount );
        }      
      }
    
} 