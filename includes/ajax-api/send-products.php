<?php

require_once dirname( __FILE__, 6 ) . '/wp-load.php';

$data = [];

$products = wc_get_products( array('status' => 'publish', 'limit' => -1) );
foreach ($products as $product){

    $data_item = array(
        'type' => 'product',
        'id' => $product->get_id(),
        'name' => $product->name
    );

    array_push($data, $data_item);
}

$categories = get_terms(array(
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    'hierarchical' => false,
));

foreach ($categories as $category){

    $data_item = array(
        'type' => 'category',
        'id' => $category->term_id,
        'name' => $category->name
    );

    array_push($data, $data_item);
}

$tags = get_terms(array(
    'taxonomy' => 'product_tag',
    'hide_empty' => false,
));

foreach ($tags as $tag){

    $data_item = array(
        'type' => 'tag',
        'id' => $tag->term_id,
        'name' => $tag->name
    );

    array_push($data, $data_item);
}

$data = json_encode($data);

echo $data;

