<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Remade\Flutterwave\Flutterwave;
use Remade\Flutterwave\Encryption;

$flw = new Flutterwave([
    'environment' => 'staging',
    'version' => 'v1',
    'merchant_key' => 'tk_5CoHkpgc90',
    'api_key' => 'tk_TPHz2rgxKp7qNijRCSyn',
]);

print_r($flw->account->resolve([
    'bank_code' => '044',
    'bank_account_number' => '044',
    'bank_number' => '044',
]));