<?php
require_once( dirname( __FILE__ ) . '/../../../wp-load.php' );

global $wpdb;
$results = $wpdb->get_results("SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = 'product_ids' AND meta_value != '' LIMIT 5");
print_r($results);

$coupon = new WC_Coupon('CUPON_LANZAMIENTO');
echo "Coupon ID: " . $coupon->get_id() . "\n";
echo "Product IDs: "; print_r($coupon->get_product_ids());
