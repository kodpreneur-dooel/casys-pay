# Casys Pay (Laravel Package)

This package provides a reusable Casys VPOS payment integration for Laravel.

## Installation (local path for development)

1. Add a path repository in your app's `composer.json`:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "packages/casys-pay"
    }
  ]
}
```

2. Require the package:

```bash
composer require codepreneur/casys-pay
```

3. Publish config (optional):

```bash
php artisan vendor:publish --tag=casys-config
```

## Configuration

Set environment values:

```
CASYS_MERCHANT_ID=
CASYS_MERCHANT_NAME=
CASYS_CURRENCY=MKD
CASYS_PASSWORD=
CASYS_PAYMENT_URL=https://vpos.cpay.com.mk/mk-MK
CASYS_SUCCESS_URL=/casys/success
CASYS_FAIL_URL=/casys/fail
```

## Usage

Build a payload in your application:

```php
use Codepreneur\CasysPay\Contracts\CasysClientInterface;

$payload = app(CasysClientInterface::class)->buildPayload([
    'amount' => 1200,
    'details1' => (string) $order->id,
    'details2' => (string) now()->timestamp,
    'customer' => [
        'first_name' => $order->customer_first_name,
        'last_name' => $order->customer_last_name,
        'email' => $order->customer_email,
        'phone' => $order->customer_phone,
    ],
]);
```

Render a simple form:

```blade
@include('casys::casys.form', [
    'paymentUrl' => config('casys.payment_url'),
    'payload' => $payload->toArray(),
])
```

## Handling callbacks

Listen for events:

```php
use Codepreneur\CasysPay\Events\CasysPaymentSucceeded;
use Codepreneur\CasysPay\Events\CasysPaymentFailed;

Event::listen(CasysPaymentSucceeded::class, function ($event) {
    // update order/membership/etc.
});

Event::listen(CasysPaymentFailed::class, function ($event) {
    // mark order failed
});
```
