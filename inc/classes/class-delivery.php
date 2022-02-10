<?php 
namespace pizza\pool;

class delivery {

    public function __construct()
    {

        add_filter('woocommerce_order_button_html', [$this, 'should_display_place_order_button'] );
        add_action( 'woocommerce_after_checkout_billing_form', [$this, 'add_dilavery_option_fields'] );
        add_action( 'woocommerce_cart_calculate_fees', [$this, 'add_fee_for_deliver_optons'] );
        add_action( 'woocommerce_cart_calculate_fees', [$this, 'user_first_order_discount'] );

    }

    /**
     * should_display_place_order_button
     *
     * @param [type] $button
     * @return default woocommerce place order button or open and close time for pizza pool 
     */

    public function should_display_place_order_button( $button ) {
        $is_open =  new \pizza\pool\schedules;
        if($is_open->is_pizza_pool_open() == 'open'){
            return $button;
        }else{
            echo $is_open->is_pizza_pool_open();
        }
      
    }
    /**
     * add_dilavery_option_fields
     *
     * @param [string] $checkout
     * Add new fields on woocommerce checkout page to change delivery options
     */

    public function add_dilavery_option_fields( $checkout ) {
        echo '<div id="pizza-pool-delivery-options-filds">';
        woocommerce_form_field( 'pool-delivery-option', [
            'type'          => 'select',
            'class'         => ['pool-delivery-option form-row-wide'],
            'label'         => esc_html__('Please select your delivery Type'),
            'required'    => true,
            'options'     => [
                            '10_dine-in'=>'Dine-in',
                            '0_takeaway' => __('Takeaway'),
                            '0_Delivery' => __('Delivery')
                            ],
            'default' => '0_Delivery'], 
            $checkout->get_value( 'pool-delivery-option' ));
	    echo '</div>';
    }

    /**
     * add_fee_for_deliver_optons
     * if should add extra change for deliver then add it 
     */

    function add_fee_for_deliver_optons( $cart ){
      
        if ( ! $_POST || ( is_admin() && ! is_ajax() ) ) {
            return;
        }
    
        if ( isset( $_POST['post_data'] ) ) {
            parse_str( $_POST['post_data'], $post_data );
        } else {
            $post_data = $_POST;
        }
    
        if (isset($post_data['pool-delivery-option'])) {
            
            $get_discount_val = explode('_', $post_data['pool-delivery-option']);
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

    /**
     * is_user_first_order
     * Check user ordering first time 
     * @return bool
     */

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
        return empty($customer_orders) ? 1 : 0; 
    }

    /**
     * user_first_order_discount
     * if user first order make 40% discount on checkout page 
     */

    function user_first_order_discount( $cart ) {
         
        if($this->is_user_first_order() || !is_user_logged_in()){
            $discount = $cart->subtotal * 0.4;      
            $cart->add_fee( __( '40% Discount for first order', 'pizza-pool' ) , -$discount );
        } 

      }
    
} 