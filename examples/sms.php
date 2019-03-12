<?php

use KielD01\SmscUA\Sender;
use KielD01\SmscUA\Types\Sms;

require '../vendor/autoload.php';

Sender::setCredentials('__LOGIN__', '__PWD__');
Sender::setLocale('ua');

$sms = new Sms();

$response = $sms
    ->setMessage('Тестове повідомлення ' . time())
    ->setPhones(['380636638372'])
    ->send();

dd($response);
