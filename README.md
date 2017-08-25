# adyen-paypal-express-checkout
Adyen PayPal Express Checkout Proof of Concept


# Building Docker
```bash
docker build -t adyen-paypal-express-checkout .
docker run -d -p 127.0.0.1:4747:80 --name adyen-paypal-express-checkout adyen-paypal-express-checkout
open http://127.0.0.1:4747
```
