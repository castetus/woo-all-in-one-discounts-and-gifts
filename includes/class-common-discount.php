<?php

require_once 'class-woodiscount-implementor.php';

class Common_Discount{

    // public $modifier;
    public $discount;
    public $discount_size;

    public function __construct ($discount){

        $this->discount = $discount;

        // $this->discount_size = 125;

        // add_action( 'woocommerce_after_calculate_totals', array($this, 'implement_common'));

        // WC()->cart->calculate_fees();

        add_action('woocommerce_cart_calculate_fees', array($this, 'calculate_common_fee'));
        add_action('wp_head', array($this, 'calculate_discount'));
        add_action('woocommerce_after_calculate_totals', array($this, 'calculate_discount'));

    }

    public function calculate_discount(){

        $cart = WC()->cart;
        $subtotal = $cart->subtotal;

        if ($subtotal >= $this->discount['properties']['subtotalFrom'] && $subtotal <= $this->discount['properties']['subtotalTo']){

            $new_subtotal = Woo_Discount_Implementor::price_modifier($subtotal, $this->discount['properties']['discount']);

            $this->discount_size = $subtotal - $new_subtotal;

            $cart->discount_size = $this->discount_size;

            $cart->set_subtotal($new_subtotal);

        }

        if (isset($cart->discount_size) && $cart->discount_size != '0'){

            if (isset($this->discount['properties']['cartString'])){

                add_action('woocommerce_cart_collaterals', array($this, 'display_cart_string'), 5);
            }
        }
    }

    public function show_cart(){
        $this->discount_size = 125;
    }

    public function display_cart_string(){

        if (!is_cart()){
            return;
        }

        echo '<h3>' . $this->discount['properties']['cartString'] . ': ' . $this->discount_size . get_woocommerce_currency_symbol(get_woocommerce_currency()) . '</h3>';

    }   

    public function calculate_common_fee(WC_Cart $cart){
        
        	$discount = $this->discount_size;
	
	        $cart->add_fee($this->discount['properties']['cartString'], -$discount);
    }

}