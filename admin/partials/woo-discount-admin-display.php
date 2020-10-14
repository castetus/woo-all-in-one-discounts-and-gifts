<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin/partials
 */
?>

<?php

add_action( 'current_screen', 'wpkama_widgets_screen' );

function wpkama_widgets_screen(){
    $screen = get_current_screen();
}

$common_path = site_url('/wp-content/plugins/woo-all-in-one-discounts-and-gifts/admin/');
// $common_path = 'https://barboss-etalon-spb.ru/wp-content/plugins/woo-all-in-one-discount-and-gifts/admin/';
$style_dir = dirname( __FILE__, 2 ) . '/css';
$script_dir = dirname( __FILE__, 2 ) . '/js';

$scripts = array_diff( scandir( $script_dir), array('..', '.'));
$styles = array_reverse(array_diff( scandir( $style_dir), array('..', '.')));

?>

<!DOCTYPE html><html lang=en>
<head>
<meta charset=utf-8><meta http-equiv=X-UA-Compatible content="IE=edge">
<meta name=viewport content="width=device-width,initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material Icons" rel=stylesheet type=text/css>
<link rel=stylesheet href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
<link rel=stylesheet href=https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css>
<!-- <link rel=stylesheet href=https://barboss-etalon-spb.ru/wp-content/plugins/woo-all-in-one-discounts-and-gifts/test.css> -->

<?php 
    foreach ($styles as $style){
        echo '<link rel=stylesheet href=' . $common_path . 'css/' . $style . '>';
    }
?>

</head>
<body><noscript><strong>We're sorry but woo-all-in-one-discounts-and-gifts doesn't work properly without JavaScript enabled. Please enable it to continue.</strong></noscript>
<div id=app></div>

<?php 
    foreach ($scripts as $script){
        echo '<script src=' . $common_path . 'js/' . $script . '></script>';
    }
?>
</body></html>
