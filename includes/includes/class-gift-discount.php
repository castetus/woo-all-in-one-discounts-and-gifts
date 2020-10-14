<?php

class Gift_Discount{

    public $discount;

    public function __construct($discount){

        $this->discount = $discount;

        add_action( 'woocommerce_after_calculate_totals', array($this, 'implement_gift'), 200);

    }

    public function implement_gift(WC_Cart $cart){

        $condition = false;
        $gift = false;

        $subtotal = $cart->subtotal;
        $gift_product_id = $this->discount['products'][0]['id'];
        $gift_product = new WC_Product($gift_product_id);

        if ($gift_product->get_stock_quantity() < 1){
            return;
        }

        if ($subtotal >= $this->discount['properties']['subtotalFrom'] && $subtotal <= $this->discount['properties']['subtotalTo']){
            $condition = true;
        }

        foreach ( $cart->cart_contents as $key => $value ) {
            if (isset( $value["gift"])){
                $gift = true;
                $gift_key = $key;

            }

        }

        if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
            return;

        if ($condition && !$gift){

            $gift_key = $cart->add_to_cart($gift_product_id, 1, 0, array(), array('gift' => 'gift'));

            $cart->calculate_totals();

        } else if (!$condition && $gift){

            $cart->remove_cart_item($gift_key);

            $cart->calculate_totals();

        } else {

            return;
        }

    }

}