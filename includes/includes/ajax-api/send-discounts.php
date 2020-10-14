<?php

require_once dirname( __FILE__, 2 ) . '/class-woodiscount-database.php';

$data = file_get_contents('php://input');

if ($data){

    $db = new Woo_Discount_DB;

    $test = $db->load_discounts_from_db();

    echo json_encode($test);

}