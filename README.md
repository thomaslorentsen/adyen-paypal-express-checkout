[![Build Status](https://travis-ci.org/thomaslorentsen/adyen-paypal-express-checkout.svg?branch=master)](https://travis-ci.org/thomaslorentsen/adyen-paypal-express-checkout)
[![Docker Build](https://img.shields.io/docker/automated/imacatlol/adyen-paypal-express-checkout.svg)](https://hub.docker.com/r/imacatlol/adyen-paypal-express-checkout/)
[![Docker pulls](https://img.shields.io/docker/pulls/imacatlol/adyen-paypal-express-checkout.svg)](https://hub.docker.com/r/imacatlol/adyen-paypal-express-checkout/)

# adyen-paypal-express-checkout
Adyen PayPal Express Checkout Proof of Concept


# Running Container
```bash
docker pull imacatlol/adyen-paypal-express-checkout
```

```ADYEN_SKINCODE```, ```ADYEN_MERCHANT``` and ```ADYEN_HMAC``` are mandatory environment values.

```bash
docker run \
  -d -p 127.0.0.1:4747:80 \
  --name adyen-paypal-express-checkout \
  -e ADYEN_SKINCODE="SkinCode" \
  -e ADYEN_MERCHANT="Merchant-Account" \
  -e ADYEN_HMAC="YOUR_HMAC_CODE" \
  imacatlol/adyen-paypal-express-checkout
open http://127.0.0.1:4747
```

# Optional Environment Vars
Additional vars can be passed with ```-e``` to override the defaults:

| param | var | default |
| --- | --- | --- |
| ```currencyCode``` | ```ADYEN_CURRENCY_CODE``` | ```GBP``` |
| ```paymentAmount``` | ```ADYEN_AMOUNT``` | ```2000``` |
| ```shopperEmail``` | ```ADYEN_SHOPPER_EMAIL``` | ```test@adyen.com``` |

# Usage
## Modifying The Payload
Update the values in the text area and then submit ```update payload``` to set the values and generate a new HMAC.
