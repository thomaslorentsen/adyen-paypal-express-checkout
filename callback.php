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


<h1>Response:</h1>
GET:<pre>
<?php
echo json_encode($_GET, JSON_PRETTY_PRINT);
?>
</pre>

POST:<pre>
<?php
echo json_encode($_POST, JSON_PRETTY_PRINT);
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
<h1><pre><?php echo $signature ?></pre></h1>
<?php
if ($signature == $_GET["merchantSig"]) { ?>
    This is a valid response
<? } else { ?>
    This is an invalid response
<? } ?>
</div>
</body>
</html>