<?php

$config = [
    'example-userpass' => [
        'exampleauth:UserPass',
        'no_mfa_needed:a' => [
            'eduPersonPrincipalName' => ['NO_MFA_NEEDED@mfa-idp.local'],
            'eduPersonTargetID' => ['11111111-1111-1111-1111-111111111111'],
            'sn' => ['Needed'],
            'givenName' => ['No MFA'],
            'mail' => ['no_mfa_needed@example.com'],
            'employeeNumber' => ['11111'],
            'cn' => ['NO_MFA_NEEDED'],
            'promptForMfa' => 'no',
            'mfaOptions' => [],
        ],
        'must_set_up_mfa:a' => [
            'eduPersonPrincipalName' => ['MUST_SET_UP_MFA@mfa-idp.local'],
            'eduPersonTargetID' => ['22222222-2222-2222-2222-222222222222'],
            'sn' => ['Set Up MFA'],
            'givenName' => ['Must'],
            'mail' => ['must_set_up_mfa@example.com'],
            'employeeNumber' => ['22222'],
            'cn' => ['MUST_SET_UP_MFA'],
            'promptForMfa' => 'yes',
            'mfaOptions' => [],
        ],
        'has_backupcode:a' => [
            'eduPersonPrincipalName' => ['HAS_BACKUPCODE@mfa-idp.local'],
            'eduPersonTargetID' => ['33333333-3333-3333-3333-333333333333'],
            'sn' => ['Backupcode'],
            'givenName' => ['Has'],
            'mail' => ['has_backupcode@example.com'],
            'employeeNumber' => ['33333'],
            'cn' => ['HAS_BACKUPCODE'],
            'promptForMfa' => 'yes',
            'mfaOptions' => [
                [
                    'id' => 37,
                    'type' => 'backupcode',
                    'data' => '',
                ],
            ],
        ],
        'has_totp:a' => [
            'eduPersonPrincipalName' => ['HAS_TOTP@mfa-idp.local'],
            'eduPersonTargetID' => ['33333333-3333-3333-3333-333333333333'],
            'sn' => ['TOTP'],
            'givenName' => ['Has'],
            'mail' => ['has_totp@example.com'],
            'employeeNumber' => ['33333'],
            'cn' => ['HAS_TOTP'],
            'promptForMfa' => 'yes',
            'mfaOptions' => [
                [
                    'id' => 25,
                    'type' => 'totp',
                    'data' => '',
                ],
            ],
        ],
    ],
];
