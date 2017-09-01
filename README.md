[![Build Status](https://travis-ci.org/thomaslorentsen/adyen-paypal-express-checkout.svg?branch=master)](https://travis-ci.org/thomaslorentsen/adyen-paypal-express-checkout)
[![Docker Build](https://img.shields.io/docker/automated/imacatlol/adyen-paypal-express-checkout.svg)](https://hub.docker.com/r/imacatlol/adyen-paypal-express-checkout/)
[![Docker pulls](https://img.shields.io/docker/pulls/imacatlol/adyen-paypal-express-checkout.svg)](https://hub.docker.com/r/imacatlol/adyen-paypal-express-checkout/)

# Adyen PayPal Express Checkout POC
This is a proof of concept container for testing Adyen PayPal Express Checkout integration.

With this container you can request a payment to be taken from a users PayPal account using Adyens HHP api.
Once the payment has been taken the callback will display the returned result and validate the response.

## Integration Notes

- For this container to work you need to have PayPal Express Checkout enabled and have [Hosted Payment Pages](https://docs.adyen.com/developers/payment-methods/paypal) enabled to take PayPal payments.
- This composer uses the [Adyen HMAC Validator](https://github.com/thomaslorentsen/adyen-hpp-hmac) library to calculate the signature

# Running Container
Pull the container from Docker Hub
```bash
docker pull imacatlol/adyen-paypal-express-checkout
```
Set the environment variables and start the container
```bash
docker run \
  -d -p 127.0.0.1:4747:80 \
  --name adyen-paypal-express-checkout \
  -e ADYEN_SKINCODE="SkinCode" \
  -e ADYEN_MERCHANT="Merchant-Account" \
  -e ADYEN_HMAC="YOUR_HMAC_CODE" \
  imacatlol/adyen-paypal-express-checkout
```
You can then open the web app from your browser
```bash
open http://0.0.0.0:4747
```
# Mandatory Environment Vars
The following environment variables are mandatory:
```ADYEN_SKINCODE```, ```ADYEN_MERCHANT```, ```ADYEN_HMAC```

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
