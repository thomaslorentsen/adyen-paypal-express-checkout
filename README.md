[![Build Status](https://travis-ci.org/thomaslorentsen/adyen-paypal-express-checkout.svg?branch=master)](https://travis-ci.org/thomaslorentsen/adyen-paypal-express-checkout)
[![Docker Build](https://img.shields.io/docker/automated/imacatlol/adyen-paypal-express-checkout.svg)](https://hub.docker.com/r/imacatlol/adyen-paypal-express-checkout/)
[![Docker pulls](https://img.shields.io/docker/pulls/imacatlol/adyen-paypal-express-checkout.svg)](https://hub.docker.com/r/imacatlol/adyen-paypal-express-checkout/)

# adyen-paypal-express-checkout
Adyen PayPal Express Checkout Proof of Concept


# Building Docker
```bash
docker build -t adyen-paypal-express-checkout .
```
```bash
docker run \
  -d -p 127.0.0.1:4747:80 \
  --name adyen-paypal-express-checkout \
  -e ADYEN_SKINCODE="SkinCode" \
  -e ADYEN_MERCHANT="Merchant-Account" \
  -e ADYEN_HMAC="YOUR_HMAC_CODE" \
  adyen-paypal-express-checkout
open http://127.0.0.1:4747
```
