<?php

return [
    /**
     * Resolve an Account Number
     */
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
    ],

    /**
     * Link an Access Bank Account
     */
    'link' => [
        'method' => 'POST',
        'params' => [
            'bank_account_number' => 'accountnumber',
        ],
        'url' => 'pay/linkaccount',
        'validate' => [
            'bank_account_number' => ['required'],
        ],
        'prepare' => [
            'bank_account_number' => ['3des'],
        ]
    ],

    /**
     * Unlink an access bank account
     */
    'unlink' => [
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
    ],

    /**
     * Get all linked access bank accounts
     */
    'getLinkedAccounts' => [
        'method' => 'POST',
        'params' => [
        ],
        'url' => 'pay/getlinkedaccounts',
        'validate' => [
        ],
        'prepare' => [
        ]
    ],

    /**
     * Validate Account Linking
     */
    'validateLinking' => [
        'method' => 'POST',
        'params' => [
            'otp' => 'otp',
            'related_reference' => 'relatedreference',
            'otp_type' => 'otptype',
        ],
        'url' => 'pay/linkaccount/validate',
        'validate' => [
            'otp' => ['required'],
            'related_reference' => ['required'],
            'otp_type' => ['required', 'in:ACCOUNT_DEBIT,PHONE_OTP'],
        ],
        'prepare' => [
            'otp' => ['3des'],
            'related_reference' => ['3des'],
            'otp_type' => ['3des'],
        ]
    ],

    /**
     * Send Money to an account from linked account
     */
    'send' => [
        'method' => 'POST',
        'params' => [
            'account_token' => 'accounttoken',
            'bank_code' => 'destbankcode',
            'bank_account_number' => 'recipientaccount',
            'reference' => 'uniquereference',
            'country' => 'country',
            'currency' => 'currency',
            'amount' => 'transferamount',
            'description' => 'narration',
            'recipient_name' => 'recipientname',
            'sender_name' => 'sendername',
        ],
        'url' => 'pay/send',
        'validate' => [
            'account_token' => ['required'],
            'bank_code' => ['required'],
            'bank_account_number' => ['required'],
            'reference' => ['required'],
            'country' => ['required'],
            'currency' => ['required'],
            'amount' => ['required'],
            'description' => ['required'],
            'recipient_name' => ['required'],
            'sender_name' => ['required'],
        ],
        'prepare' => [
            'account_token' => ['3des'],
            'bank_code' => ['3des'],
            'bank_account_number' => ['3des'],
            'reference' => ['3des'],
            'country' => ['3des'],
            'currency' => ['3des'],
            'amount' => ['3des'],
            'description' => ['3des'],
            'recipient_name' => ['3des'],
            'sender_name' => ['3des'],
        ]
    ],

    /**
     * Check Transaction reference
     */
    'transactionStatus' => [
        'method' => 'POST',
        'params' => [
            'reference' => 'uniquereference',
        ],
        'url' => 'pay/resolveaccount',
        'validate' => [
            'reference' => ['required'],
        ],
        'prepare' => [
            'reference' => ['3des'],
        ]
    ]
];