# Laravel Gkash

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laraditz/gkash.svg?style=flat-square)](https://packagist.org/packages/laraditz/gkash)
[![Total Downloads](https://img.shields.io/packagist/dt/laraditz/gkash.svg?style=flat-square)](https://packagist.org/packages/laraditz/gkash)
![GitHub Actions](https://github.com/laraditz/gkash/actions/workflows/main.yml/badge.svg)

Simple laravel package for Gkash Payment Gateway.

## Installation

You can install the package via composer:

```bash
composer require laraditz/gkash
```

## Before Start

Configure your variables in your `.env` (recommended) or you can publish the config file and change it there.
```
GKASH_MERCHANT_ID=<your_merchant_id_here>
GKASH_SIGNATURE_KEY=<your_signature_key_here>
GKASH_SANDBOX_MODE=true # true or false for sandbox mode
```

(Optional) You can publish the config file via this command:
```bash
php artisan vendor:publish --provider="Laraditz\Gkash\GkashServiceProvider" --tag="config"
```

Run the migration command to create the necessary database table.
```bash
php artisan migrate
```

## Usage

### Create Payment
To create payment and get the payment URL to be redirected to. You can use service container or facade.
```php
// Create a payment

// Using service container
$payment = app('gkash')->refNo('ABC1234')->amount(100)->returnUrl('https://returnurl.com')->createPayment();

// Using facade
$payment =  \Gkash::refNo('ABC1234')->amount(100)->returnUrl('https://returnurl.com')->createPayment();

```

Return example:
```php
[
    "code" => "5Xpj9IPN",
    "currency_code" => "MYR",
    "amount" => 100,
    "payment_url" => "http://myapp.com/gkash/pay/5Xpj9IPN"
]
```

Redirect to the `payment_url` to proceed to Gkash payment page. Once done, you will be redirected to the returnUrl. Below is the sample response returned.
```php
[
  "status" => "66 - Failed",
  "description" => "Terminal ID/TradingName not configured.",
  "CID" => "M161-U-40592",
  "POID" => "M161-PO-149461",
  "cartid" => "5Xpj9IPN",
  "amount" => "1.00",
  "currency" => "MYR",
  "PaymentType" => "TnG ECOMM",
  "signature" => "0bfe2724c9c29dcd5c086a1f45f28ce0b702dd86dddef8eb40d46001ce76dff76a8f18b9f993f6cbb104206041866f239c4239878f62c043b4252a0c00a3a374"
]
```

## Event

This package also provide some events to allow your application to listen to it. You can create your listener and register it under event below.

| Event                                     |  Description  
|-------------------------------------------|-----------------------|
| Laraditz\Gkash\Events\BackendReceived    | Received backend response from Gkash for a payment. Can use to update your payment status and other details


### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email raditzfarhan@gmail.com instead of using the issue tracker.

## Credits

-   [Raditz Farhan](https://github.com/laraditz)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

