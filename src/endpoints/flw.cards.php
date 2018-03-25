<?php

return [

    'resolve' => [
        'method' => 'POST',
        'params' => [
            'validate_option' => 'validateoption', //(SMS | VOICE)
            'authorization_model' => 'authmodel', //(BVN | NOAUTH)
            'card.number' => 'cardno',
            'card.cvv' => 'cvv',
            'card.issuing_country' => 'country',
            'card.expiry_month' => 'expirymonth',
            'card.expiry_year' => 'expiryyear',
            'bvn' => 'bvn',
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