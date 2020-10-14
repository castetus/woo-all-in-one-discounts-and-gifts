<?php

require_once 'class-woodiscount-implementor.php';

class Paytake_Discount{

    public $discount;
    public $product_ids;
    private $diff;

    public function __construct($discount, $product_ids){

        $this->discount = $discount;
        $this->product_ids = $product_ids;
        $this->diff = $this->discount['properties']['itemsTake'] - $this->discount['properties']['itemsPay'];

        add_action('woocommerce_before_single_product_summary', array($this, 'display_product_description'));
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'display_category_description'), 100);

        add_action( 'wp_head', array($this, 'implement_paytake'));

        add_filter('woocommerce_sale_flash', array($this, 'display_cat_description'));

    }

        public function implement_paytake(){

            if (!is_cart()){
                return;
            }

        $cart = WC()->cart;

        foreach ( $cart->cart_contents as $key => $value){
            if ( isset( $value["custom_price"] ) ){
                $cart->remove_cart_item($key);
            }
        }

        foreach ( $cart->get_cart() as $cart_item){

            if (in_array($cart_item['product_id'], $this->product_ids)){

                if ($cart_item['quantity'] >= $this->discount['properties']['itemsPay']){

                    if (!isset($cart_item['custom_price'])){
    
                        $quantity_multiplier = floor($cart_item['quantity'] / $this->discount['properties']['itemsPay']);
    
                        $items_to_add = $quantity_multiplier * $this->diff;
    
                        $cart->add_to_cart($cart_item['product_id'], $items_to_add, 0, array(), array('custom_price' => 0));

                    }
                }
            }
        }

        foreach ( $cart->cart_contents as $key => $value ) {
            if( isset( $value["custom_price"] ) ) {

                $value['data']->set_price($value["custom_price"]);
            }
        }  
        $cart->calculate_totals();
    }

    public function display_product_description(){

        global $product;

        if (in_array($product->get_id(), $this->product_ids))
        echo '<span class="product-discount-description" style="margin: 10px">' . $this->discount['properties']['productDescription'] . '</span>';
    }

    public function display_category_description(){
        global $product;
        if (is_product_category()){
            if (in_array($product->get_id(), $this->product_ids)){

                echo '<span class="paytake-category-description">' . $this->discount['properties']['catDescription'] . '</span>';
                // echo $this->discount['properties']['catDescription'];
            }
        }
    }
}