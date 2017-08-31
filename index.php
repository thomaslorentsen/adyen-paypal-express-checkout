<?php
/*
 This PHP code provides a payment form for the Adyen Hosted Payment Pages
 */

require_once 'vendor/autoload.php';

// Mandatory Values
$skinCode        = $_ENV['ADYEN_SKINCODE'];
$merchantAccount = $_ENV['ADYEN_MERCHANT'];
$hmacKey         = $_ENV['ADYEN_HMAC'];

// Optional Values
$currencyCode = isset($_ENV['ADYEN_CURRENCY_CODE']) ? $_ENV['ADYEN_CURRENCY_CODE'] : 'GBP';
$paymentAmount = isset($_ENV['ADYEN_AMOUNT']) ? $_ENV['ADYEN_AMOUNT'] : '2000';
$shopperEmail = isset($_ENV['ADYEN_SHOPPER_EMAIL']) ? $_ENV['ADYEN_SHOPPER_EMAIL'] : 'test@adyen.com';

/*
 payment-specific details
 */

$params = array(
    "merchantReference" => uniqid('SKINTEST-'),
    "merchantAccount"   => $merchantAccount,
    "currencyCode"      => $currencyCode,
    "paymentAmount"     => $paymentAmount,
    "sessionValidity"   => "2020-12-25T10:31:06Z",
    "shipBeforeDate"    => "2017-08-25",
    "shopperLocale"     => "en_GB",
    "skinCode"          => $skinCode,
    "brandCode"         => "paypal_ecs",
    "shopperEmail"      => $shopperEmail,
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

    // Redirect url
    //"resultURL" => 'http://127.0.0.1:4747/callback.php'
    "resURL" => 'http://127.0.0.1:4747/callback.php'

);

if ($_POST) {
    $rawData = trim($_POST['data']);
    $data = explode(PHP_EOL, $rawData);
    foreach ($data as $line) {
        $line = trim($line);
        list($key, $value) = explode(":", $line, 2);
        $params[$key] = $value;
    }
}

$signature = new \RoundPartner\Adyen\Signature($hmacKey);
$params["merchantSig"] = $signature->generate($params);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Adyen Payment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1>PayPal Express Checkout</h1>
    <div>
        <form name="adyenForm" action="https://test.adyen.com/hpp/skipDetails.shtml" method="post">
            <?php
            foreach ($params as $key => $value){
                echo '<input type="hidden" name="' .htmlspecialchars($key,   ENT_COMPAT | ENT_HTML401 ,'UTF-8').
                    '" value="' .htmlspecialchars($value, ENT_COMPAT | ENT_HTML401 ,'UTF-8') . '" />' ."\n" ;
            }
            ?>
            <input type="submit" value="Checkout" />
        </form>
    </div>
    <h2>Payload:</h2>
    <div>
        <form method="POST" action="/">
            <textarea name="data" style="height:300px;width:500px">
<?php
foreach ($params as $key => $value){
    echo '' .htmlspecialchars($key,   ENT_COMPAT | ENT_HTML401 ,'UTF-8') .
        ':' .htmlspecialchars($value, ENT_COMPAT | ENT_HTML401 ,'UTF-8') . PHP_EOL ;
}
?>
            </textarea>
            <br />
            <br />

            <input type="submit" value="Update Payload" />
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

</body>
</html>
<!-- Adyen PayPal Express Checkout is running -->