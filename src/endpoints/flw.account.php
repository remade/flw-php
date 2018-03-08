<?php

return [
    'resolve' => [
        'method' => 'POST',
        'params' => [
            'bank_code' => 'destbankcode',
            'bank_account_number' => 'recipientaccount',
        ],
        'url' => 'pay/resolveaccount',
        'validate' => [
            'bank_code' => ['required'],
            'bank_account_number' => ['required'],
        ],
        'prepare' => [
            'bank_code' => ['3des'],
            'bank_account_number' => ['3des'],
        ]
    ]
];