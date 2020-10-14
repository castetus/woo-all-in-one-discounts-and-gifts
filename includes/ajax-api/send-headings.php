<?php

require_once dirname( __FILE__, 6 ) . '/wp-load.php';

require_once dirname( __FILE__, 3 ) . '/admin/partials/labels.php';

$data = file_get_contents('php://input');

if ($data){

    $headings = [];

    $headings['types'] = $discountTypes;
    $headings['labels'] = $labels;

    echo json_encode($headings);

}