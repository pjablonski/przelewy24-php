# Przelewy24 PHP library

PHP wrapper for [www.przelewy24.pl](https://www.przelewy24.pl/) based on [mnastalski/przelewy24-php](https://github.com/mnastalski/przelewy24-php)  upgraded to P24 REST API and extended with new functionalities.

## Requirements

- PHP >=7.1.3

## Installation

```shell
composer require pjablonski/przelewy24-php
```

## Usage

### Creating an instance

```php
use Przelewy24\Przelewy24;

$przelewy24 = new Przelewy24([
    'merchantId' => '12345',
    'crc' => 'aef0...',
    'report' => 'report',
    'live' => false, // `true` for production/live mode
]);
```

### Creating a transaction

```php
$transaction = $przelewy24->transaction([
    'sessionId' => 'Unique identifier from merchant system',
    'urlReturn' => 'URL address to which customer will be redirected when transaction is complete',
    'urlStatus' => 'URL address to which transaction status will be send',
    'amount' => 'Transaction amount expressed in lowest currency unit, e.g. 1.23 PLN = 123',
    'description' => 'Transaction description',
    'email' => 'Customer e-mail',
    'currency' => 'Currency compatible with ISO, e.g. PLN',
    'language' => 'One of following language codes according to ISO 639-1: bg, cs, de, en, es, fr, hr, hu, it, nl, pl, pt, se, sk'
]);
```

Retrieve the transaction's token:

```php
$transaction->token();
```

Retrieve the redirect URL to the payment gateway:

```php
$transaction->redirectUrl();
```

Retrieve the payment methods:

```php
$transaction->paymentMethods($lang);
// $lang = one of these: pl , en (default pl)
```

### Listening for transaction status webhook

```php
$webhook = $przelewy24->handleWebhook();
```

### Verifying a transaction

```php
$przelewy24->verify([
    'sessionId' => 'Unique identifier from merchant system',
    'amount' => 'Transaction amount which format is presented as amount in lowest currency unit, e.g. 1.23 PLN = 123',
    'currency' => 'Currency compatible with ISO, e.g. PLN',
    'orderId' => 'Id of an order assigned by P24'
]);
```

### Error handling

Should Przelewy24's API return an erroneous response, an `ApiResponseException::class` (which extends `Przelewy24Exception::class`) will be thrown. You can therefore use a `try/catch` block to handle any errors:

```php
use Przelewy24\Exceptions\Przelewy24Exception;

try {
    $przelewy24->transaction([
        // ...
    ]);
} catch (Przelewy24Exception $e) {
    // Handle the error...
}
```
