<?php
require_once( dirname( __FILE__ ) . '/../../../wp-load.php' );

$coupon = new WC_Coupon(1623); // CUPON_LANZAMIENTO
echo "get_code(): " . $coupon->get_code() . "\n";
echo "post_title: " . get_the_title(1623) . "\n";
