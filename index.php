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
$resURL = isset($_ENV['ADYEN_RES_URL']) ? $_ENV['ADYEN_RES_URL'] : 'http://127.0.0.1:4747/callback.php';

/*
 payment-specific details
 */

$params = array(
    "merchantReference" => uniqid('SKINTEST-'),
    "merchantAccount" => $merchantAccount,
    "currencyCode" => $currencyCode,
    "paymentAmount"     => $paymentAmount,
    "sessionValidity" => date("Y-m-d\TH:i:s\Z", strtotime('+21 days')),
    //"shipBeforeDate" => date("Y-m-d", strtotime('+7 days')),
    "shopperLocale" => "en_GB",
    "skinCode" => $skinCode,
    "brandCode" => "paypal_ecs",
    "shopperEmail" => $shopperEmail,
    //"shopperReference" => "123",

    // Shopper information
    //"shopperIP" => "62.128.7.69",

    // Redirect url
    "resURL" => 'http://127.0.0.1:4747/callback.php'

);

if ($_POST) {
    $params = [];
    $rawData = trim($_POST['data']);
    $data = explode(PHP_EOL, $rawData);
    foreach ($data as $line) {
        $line = trim($line);
        list($key, $value) = explode(":", $line, 2);
        if ('merchantSig' === $key) {
            continue;
        }
        $params[$key] = $value;
    }
}

if (!$params['paymentAmount'] && !isset($params["recurringContract"])) {
    $params["recurringContract"] = 'RECURRING';
}

$signature = new \RoundPartner\Adyen\Signature($hmacKey);
$params["merchantSig"] = $signature->generate($params);


use GuzzleHttp\Client;
if (isset($_GET['get']) && $_GET['get'] === 'redirect') {
    $client = new Client(['base_uri' => 'https://test.adyen.com']);
    $response = $client->post('/hpp/skipDetails.shtml', ['form_params' => $params, 'allow_redirects' => false]);
    $responseHeaders = $response->getHeaders();
    $responseBody = $response->getBody()->getContents();
    if (isset($responseHeaders['Set-Cookie'])) {
        foreach ($responseHeaders['Set-Cookie'] as $header) {
            header('Set-Cookie: ' . $header);
        }
    }
}

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
    <p>Posts a form to adyen and automatically redirects to PayPal</p>
    <div>
        <form name="adyenForm" action="https://test.adyen.com/hpp/skipDetails.shtml" method="post">
            <?php
            foreach ($params as $key => $value){
                echo '<input type="hidden" name="' . htmlspecialchars($key,   ENT_COMPAT | ENT_HTML401 ,'UTF-8') .
                    '" value="' . htmlspecialchars($value, ENT_COMPAT | ENT_HTML401 ,'UTF-8') . '" />' . PHP_EOL ;
            }
            ?>
            <input type="submit" value="Checkout" />
        </form>
    </div>
    <br />
    <h2>Redirect</h2>
    <p>Posts a request to Adyen and gets the PayPal redirect url</p>
    <div>
        <form name="adyenForm" action="/?get=redirect" method="post">
                        <textarea name="data" style="height:300px;width:500px;display:none;">
<?php
foreach ($params as $key => $value){
    echo '' .htmlspecialchars($key,   ENT_COMPAT | ENT_HTML401 ,'UTF-8') .
        ':' .htmlspecialchars($value, ENT_COMPAT | ENT_HTML401 ,'UTF-8') . PHP_EOL ;
}
?>
            </textarea>
            <input type="submit" value="Get Redirect Url" />
        </form>
        <?php
        if (isset($responseHeaders)) {
            //echo '<pre>'.json_encode($responseHeaders, JSON_PRETTY_PRINT).'</pre>';
        }
        if (isset($responseHeaders['Set-Cookie'])) {
            echo '<h3>Cookies</h3>';
            foreach($responseHeaders['Set-Cookie'] as $header) {
                echo '<input value="' . htmlentities($header) . '" style="width: 500px" /><br />';
            }
        }
        if (isset($responseHeaders['Location'][0])) {
            echo '<h3>Redirect Url</h3>';
            echo '<input value="' . $responseHeaders['Location'][0] . '" onfocus="this.select();" onmouseup="return false;" style="width: 500px" /><br />';
        }
        if (isset($responseBody) && $responseBody) {
            echo '<h3>Body</h3>';
            echo '<p>'.htmlentities($responseBody).'</p>';
        }
        ?>
    </div>
    <br />
    <br />
    <h2>Payload:</h2>
    <p>Update the payload by changing the values below</p>
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
            <!--<select name="recurringContract">
                <option value="">NONE</option>
                <option value="RECURRING">RECURRING</option>
            </select><br />-->
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