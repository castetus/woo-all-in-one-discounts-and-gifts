<?php

add_action('wp_ajax_send_headings', 'send_headings');
function send_headings(){

    echo 'headings';
    wp_die();
}

add_action('wp_ajax_send_discounts', 'send_discounts');
function send_discounts(){

    global $wpdb;

    echo 'discounts';
    wp_die();
}

add_action('wp_ajax_save_discount', 'save_discount');
function save_discount(){

    // $data = json_encode($_POST);
    
    // $file = plugin_dir_path(__FILE__) . 'test.json';
    // file_put_contents($file, $data);

    echo 'test';

    wp_die();
}

add_action('wp_ajax_remove_discount', 'remove_discount');
function remove_discount(){

    echo 'removed';
    wp_die();
}