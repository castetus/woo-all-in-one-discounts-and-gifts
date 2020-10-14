<?php

require_once 'class-woodiscount-implementor.php';

class Product_Discount{

    public $discount;
    public $modifier;
    public $product_ids;

    public function __construct($discount, $product_ids){

        $this->discount = $discount;
        $this->product_ids = $product_ids;

        add_action( 'wp_head', array($this, 'implement_product'));

    }

    public function implement_product(){

        

        foreach ($this->product_ids as $product_id){

            $product = new WC_Product($product_id);
            $regular_price = $product->get_regular_price();

            $sale_price = Woo_Discount_Implementor::price_modifier($regular_price, $this->discount['properties']['discount']);

            $product->set_sale_price($sale_price);
            if (isset($this->discount['dateFrom'])){
                $product->set_date_on_sale_from($this->discount['dateFrom']);
            }
            if (isset($this->discount['dateTo'])){
                $product->set_date_on_sale_to($this->discount['dateTo']);
            }
            $product->save();

            

        }
        add_filter('woocommerce_sale_flash', array($this, 'display_cat_description'));
        add_action('woocommerce_before_single_product_summary', array($this, 'display_product_description'));

    }

    public function display_cat_description($html){
        global $product;
        if (is_product_category()){
            if (in_array($product->get_id(), $this->product_ids)){

                $html = '<span class="onsale">' . $this->discount['properties']['catDescription'] . '</span>';
                return $html;
            }
        }
    }

    public function display_product_description(){
        global $product;

        if (in_array($product->get_id(), $this->product_ids))
        echo '<span class="product-discount-description" style="margin: 10px">' . $this->discount['properties']['productDescription'] . '</span>';
    }

}