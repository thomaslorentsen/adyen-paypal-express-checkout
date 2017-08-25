<?php
/*
 This PHP code provides a payment form for the Adyen Hosted Payment Pages
 */

$skinCode        = $_ENV['ADYEN_SKINCODE'];
$merchantAccount = $_ENV['ADYEN_MERCHANT'];
$hmacKey         = $_ENV['ADYEN_HMAC'];

/*
 payment-specific details
 */

$params = array(
    "merchantReference" => "SKINTEST-1435226439255",
    "merchantAccount"   =>  $merchantAccount,
    "currencyCode"      => "EUR",
    "paymentAmount"     => "199",
    "sessionValidity"   => "2020-12-25T10:31:06Z",
    "shipBeforeDate"    => "2017-07-01",
    "shopperLocale"     => "en_GB",
    "skinCode"          => $skinCode,
    "brandCode"         => "",
    "shopperEmail"      => "test@adyen.com",
    "shopperReference"  => "123",

    // Shopper information
    "shopper.firstName"=> "Testperson-nl",
    "shopper.lastName"=> "Approved",
    "shopper.dateOfBirthDayOfMonth"=> "10",
    "shopper.dateOfBirthMonth"=> "07",
    "shopper.dateOfBirthYear"=> "1970",
    "shopper.gender"=> "MALE",
    "shopper.telephoneNumber"=> "0104691602",
    "shopperIP"=> "62.128.7.69",

    // Billing Address fields (used for AVS checks)
    "billingAddress.street" =>"Neherkade",
    "billingAddress.houseNumberOrName" => "1",
    "billingAddress.city" => "Gravenhage",
    "billingAddress.postalCode" => "2521VA",
    "billingAddress.stateOrProvince" => "NH",
    "billingAddress.country" => "NL",
    "billingAddressType" => "",

    // Delivery/Shipping Address fields
    "deliveryAddress.street" => "Neherkade",
    "deliveryAddress.houseNumberOrName" => "1",
    "deliveryAddress.city" => "Gravenhage",
    "deliveryAddress.postalCode" => "2521VA",
    "deliveryAddress.stateOrProvince" => "NH",
    "deliveryAddress.country" => "NL",
    "deliveryAddressType" => "",

);

/*
 process fields
 */

// The character escape function
$escapeval = function($val) {
    return str_replace(':','\\:',str_replace('\\','\\\\',$val));
};

// Sort the array by key using SORT_STRING order
ksort($params, SORT_STRING);

// Generate the signing data string
$signData = implode(":",array_map($escapeval,array_merge(array_keys($params), array_values($params))));

// base64-encode the binary result of the HMAC computation
$merchantSig = base64_encode(hash_hmac('sha256',$signData,pack("H*" , $hmacKey),true));
$params["merchantSig"] = $merchantSig;

?>


<!-- Complete submission form -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Adyen Payment</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
</head>
<body>
<form name="adyenForm" action="https://test.adyen.com/hpp/select.shtml" method="post">

    <?php
    foreach ($params as $key => $value){
        echo '        <input type="hidden" name="' .htmlspecialchars($key,   ENT_COMPAT | ENT_HTML401 ,'UTF-8').
            '" value="' .htmlspecialchars($value, ENT_COMPAT | ENT_HTML401 ,'UTF-8') . '" />' ."\n" ;
    }
    ?>
    <input type="submit" value="Submit" />
</form>
</body>
</html>
<!-- Adyen PayPal Express Checkout is running -->