<?php
require_once( dirname( __FILE__ ) . '/../../../wp-load.php' );

global $wpdb;
$post_id = 1613;
$coupon_ids = $wpdb->get_col( $wpdb->prepare(
    "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'product_ids' AND FIND_IN_SET(%d, meta_value)",
    $post_id
) );
print_r($coupon_ids);

foreach ($coupon_ids as $cid) {
    $c = new WC_Coupon($cid);
    echo $c->get_code() . " - " . wc_price($c->get_amount()) . "\n";
}

