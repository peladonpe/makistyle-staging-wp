<?php
require_once( dirname( __FILE__ ) . '/../../../wp-load.php' );

$coupon = new WC_Coupon(1623); // CUPON_LANZAMIENTO
$expiry_date = $coupon->get_date_expires();

if ($expiry_date) {
    echo "Expires: " . $expiry_date->getTimestamp() . "\n";
    echo "Current time: " . time() . "\n";
    if ($expiry_date->getTimestamp() < time()) {
        echo "EXPIRED\n";
    } else {
        echo "VALID\n";
    }
} else {
    echo "NO EXPIRY DATE\n";
}
