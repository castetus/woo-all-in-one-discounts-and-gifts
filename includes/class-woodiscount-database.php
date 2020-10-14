<?php

require_once dirname( __FILE__, 5 ) . '/wp-load.php';
require_once 'class-woodiscount-implementor.php';

class Woo_Discount_DB {

    public function __construct(){

        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table = $this->wpdb->prefix . 'wdag_discount';
        $this->meta_table = $this->wpdb->prefix . 'wdag_discount_meta';

    }

    public function check_discount($id){

        $check = $this->wpdb->get_results('SELECT * FROM '.$this->table.' WHERE discount_id = '.$id);
        return $check;

    }

    public function save_discount_to_db($data){

        global $wpdb;

        $discount_id = (string)$data->id;

        $check = $this->check_discount($discount_id);

        if ($check){

            $test = $this->remove_discount_from_db($discount_id);

            if ($data->type->id === 'products'){
                Woo_Discount_Implementor::clean_sale_prices();
            }

        } 
        
            $ins = $this->wpdb->insert(
                $this->table,
                array(
                    'discount_id' => $discount_id,
                    'discount_name' => $data->name,
                    'discount_type' => $data->type->id,
                    'date_from' => $data->dateFrom,
                    'date_to' => $data->dateTo,
                )    
            );

            $properties = $data->properties;

            foreach ($properties as $key => $value){

                if ($value != null){
                    $ins2 = $this->wpdb->insert(
                        $this->meta_table,
                        array(
                            'discount_id' => $discount_id,
                            'meta_key' => $key,
                            'meta_value' => $value
                        )
                    );
                }
            }

                    $products = $data->products;

                    foreach ($products as $product){

                            $ins3 = $this->wpdb->insert(
                                $this->meta_table,
                                array(
                                    'discount_id' => $discount_id,
                                    'meta_key' => $product->itemType,
                                    'meta_value' => $product->id
                                )
                            );
                    }

                return $data;
            }
       
        
    

    public function remove_discount_from_db ($id){

        $this->wpdb->delete(
            $this->table,
            array(
                'discount_id' => $id
            )
        );

        $this->wpdb->delete(
            $this->meta_table,
            ['discount_id' => $id]
        );

        return $id;

    }

    public function load_discounts_from_db(){

        $discounts = $this->wpdb->get_results("SELECT * from {$this->table}");

        if ($discounts){

            $items = [];

            foreach ($discounts as $discount){

                $item = array(

                    'id' => $discount->discount_id,
                    'name' => $discount->discount_name, 
                    'type' => [
                        'id' => $discount->discount_type, 
                    ],
                    'dateFrom' => $discount->date_from, 
                    'dateTo' => $discount->date_to, 

                );

                $item['products'] = [];

                $discount_id = $discount->discount_id;
                $metas = $this->wpdb->get_results('SELECT meta_key, meta_value from '.$this->meta_table.' WHERE discount_id = '.$discount_id);

                foreach ($metas as $meta){

                    $product_keys = ['product', 'category', 'tag'];

                    if (!in_array($meta->meta_key, $product_keys)){

                        $item['properties'][$meta->meta_key] = $meta->meta_value;

                    } else {

                        array_push($item['products'], array(
                            'itemType' => $meta->meta_key,
                            'id' => $meta->meta_value
                        ));

                    }

                }


                array_push($items, $item);

            }

        }

        return $items;

    }

}