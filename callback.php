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
    <a class="btn" href="/">Go Back</a>

<?php if (array_key_exists('payment_token', $_POST)) { ?>
<h1>Token</h1>
<textarea onfocus="this.select();" onmouseup="return false;" style="height:300px;width:500px"><?php echo $_POST['payment_token']; ?></textarea>
<?php } ?>

<h1>Response:</h1>
GET:<pre>
<?php
echo json_encode($_GET, JSON_PRETTY_PRINT);
?>
</pre>

POST:<pre>
<?php
$post = $_POST;
if (array_key_exists('payment_token', $post)) {
    $post['payment_token'] = str_replace(["\n","\r"], ["",""], $post['payment_token']);
}
echo json_encode($post, JSON_PRETTY_PRINT);
?>
</pre>

<?php

require_once 'vendor/autoload.php';

$hmacKey         = $_ENV['ADYEN_HMAC'];

$params = $_GET;
$signatureValidator = adyen_hmac($hmacKey, $params);
$signatureValidator = new \RoundPartner\Adyen\Signature($hmacKey);
$signature = $signatureValidator->generate($params);

?>
<h1><?php
if ($signature == $_GET["merchantSig"]) { ?>
    This is a valid response
<? } else { ?>
    This is an invalid response
<? } ?></h1>
<pre><?php echo $signature ?></pre>
</div>
</body>
</html>