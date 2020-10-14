<?php

require_once dirname( __FILE__, 2 ) . '/class-woodiscount-database.php';

$data = file_get_contents('php://input');

if ($data){

    $discount = json_decode(stripslashes($data));

    $db = new Woo_Discount_DB;

    $test = $db->save_discount_to_db($discount->data);

    var_dump($discount->data);

}

