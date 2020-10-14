<?php 

require_once dirname( __FILE__, 2 ) . '/class-woodiscount-database.php';

$data = json_decode(file_get_contents('php://input'));

if ($data){

    $db = new Woo_Discount_DB;

    $db->remove_discount_from_db($data->data);

    var_dump($data);
}