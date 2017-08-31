<h1>Response:</h1>
GET:<pre>
<?php
var_dump($_GET);
?>
</pre>

POST:<pre>
<?php
var_dump($_POST);
?>
</pre>

<?php

require_once 'vendor/autoload.php';

$hmacKey         = $_ENV['ADYEN_HMAC'];

$params = array_merge($_GET);
$params["merchantSig"] = null;
$params = array_filter($params);

$signature = adyen_hmac($hmacKey, $params);

?>
<h1><pre><?php echo $signature ?></pre></h1>
<?php
if ($signature == $_GET["merchantSig"]) { ?>
    This is a valid response
<? } else { ?>
    This is an invalid response
<? } ?>
