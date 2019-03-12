# PHP SMSC UA

## What's that?
This is the package, to send regular SMS to any available recipient

## Prerequisites    
- Register account via [SMSC.UA](https://smsc.ua)
- Fill up balance with a little amount

## Installation

`composer require kield01/smsc-ua`

## Usage

```php

use KielD01\SmscUA\Sender;
use KielD01\SmscUA\Types\Sms;

/** Available Methods List */

/** Set Credential to access the API */
Sender::setCredentials(['__LOGIN__', '__PWD__']);
Sender::setCredentials('__LOGIN__', '__PWD__');

/** Set error messages locale */
Sender::setLocale('ua');

$sms = new Sms();

$response = $sms
    ->setPhones(
    ['380666666666'] || // As an array of numbers
    '380666666666,380666666667;380666666668' || // As the string, delimited by symbols like '`,`, `;`, and/or `|`'
    '380666666666'
    )
    ->setMessage('Test Message') // Sets string as message
    ->send(); // Deliver messages via API

```
