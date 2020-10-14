<?php

require_once 'class-woodiscount-database.php';
require_once 'class-common-discount.php';
require_once 'class-product-discount.php';
require_once 'class-paytake-discount.php';
require_once 'class-gift-discount.php';

class Woo_Discount_Implementor {

    

    private $discounts;
    private $modifier;

    public function __construct(){

        $db = new Woo_Discount_DB;

        $this->discounts = $db->load_discounts_from_db();

        $this->check_status();

        $this->implement_discounts();
        
    }

    public static function price_modifier($price, $modifier){

        $modifier_type = strpos($modifier, '%');

        if ($modifier_type){
            $new_price = $price - ($price / 100 * (int)$modifier);
        } else {
            $new_price = $price - $modifier;
        }

        return $new_price;
    }

    public static function clean_sale_prices(){

        $products = wc_get_products(array('limit' => -1));
        foreach ($products as $product){
            $product->set_sale_price(null);
            $product->save();
        }

    }

    public function check_status( ){

        $discounts = $this->discounts;

        $today = date('Y-m-d');

        for ($i = 0; $i <= count($discounts); $i++){

            if ($discounts[$i]['dateFrom'] && $discounts[$i]['dateTo']){

                if ($today < $discounts[$i]['dateFrom'] || $today > $discounts[$i]['dateTo']){

                    unset($discounts[$i]);
                }
            }
        }

        $this->discounts = $discounts;
    }



    public function implement_discounts(){

        $discounts = $this->discounts;

        foreach ($this->discounts as $discount){

            switch($discount['type']['id']){

                case 'common':
                    new Common_Discount($discount);
                    break;

                case 'products':
                    if (isset($discount['products'])){
                        $product_ids = $this->detect_products($discount['products']);
                        new Product_Discount($discount, $product_ids);
                    }
                    break;

                case 'payTake':
                    if (isset($discount['products'])){
                        $product_ids = $this->detect_products($discount['products']);
                        new Paytake_Discount($discount, $product_ids);
                    }
                    break;

                case 'gift':
                    if (isset($discount['products'])){
                        new Gift_Discount($discount);
                    }
                    break;
            }
        }
    }

    private function detect_products($items){

        $product_ids = array();

        foreach ($items as $item){

            switch ($item['itemType']){

                case 'product':
                    $product_id = $item['id'];
                    array_push($product_ids, $product_id);
                    break;
                
                case 'category':
                    $category = get_term( $item['id']);
                    $products = wc_get_products(array('category' => $category, 'limit' => -1));
                    foreach ($products as $product){
                        array_push($product_ids, $product->get_id());
                    }
                    break;
                
                case 'tag':
                    $tag = get_term( $item['id']);
                    $products = wc_get_products(array('tag' => $tag, 'limit' => -1));
                    foreach ($products as $product){
                        array_push($product_ids, $product->get_id());
                    }
                    break;
            }
        }

        return $product_ids;
    }

}

