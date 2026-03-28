# Casys Pay

Reusable Casys VPOS payment integration for Laravel applications and packages.

## Installation

Install the package via Composer:

```bash
composer require codepreneur/casys-pay
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag="casys-config"
```

Optionally publish the views:

```bash
php artisan vendor:publish --tag="casys-views"
```

For package development, this repository now ships with an Orchestra Testbench workbench and Laravel Boost:

```bash
composer install
php artisan boost:install
```

If Codex MCP support is not registered automatically, add it from the package root:

```bash
codex mcp add laravel-boost -- php artisan boost:mcp
```

## Configuration

Set these environment variables in the consuming app or workbench:

```env
CASYS_MERCHANT_ID=
CASYS_MERCHANT_NAME=
CASYS_CURRENCY=MKD
CASYS_PASSWORD=
CASYS_PAYMENT_URL=https://vpos.cpay.com.mk/mk-MK
CASYS_SUCCESS_URL=/casys/success
CASYS_FAIL_URL=/casys/fail
CASYS_SUCCESS_VIEW=
CASYS_FAIL_VIEW=
```

## Usage

Build a payment payload:

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

Render the hosted payment form:

```blade
@include('casys::casys.form', [
    'paymentUrl' => config('casys.payment_url'),
    'payload' => $payload->toArray(),
])
```

## Handling callbacks

Listen for the success and failure events dispatched by the package:

```php
use Codepreneur\CasysPay\Events\CasysPaymentFailed;
use Codepreneur\CasysPay\Events\CasysPaymentSucceeded;
use Illuminate\Support\Facades\Event;

Event::listen(CasysPaymentSucceeded::class, function ($event) {
    // update order or membership state
});

Event::listen(CasysPaymentFailed::class, function ($event) {
    // mark the payment attempt as failed
});
```

## Testing

Run the test suite with:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
