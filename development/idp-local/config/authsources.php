<?php

$config = [
    'example-userpass' => [
        'exampleauth:UserPass',
        'no_mfa_needed:a' => [
            'eduPersonPrincipalName' => ['NO_MFA_NEEDED@mfaidp'],
            'eduPersonTargetID' => ['11111111-1111-1111-1111-111111111111'],
            'sn' => ['Needed'],
            'givenName' => ['No MFA'],
            'mail' => ['no_mfa_needed@example.com'],
            'employeeNumber' => ['11111'],
            'cn' => ['NO_MFA_NEEDED'],
            'mfa' => [
                'prompt' => 'no',
                'nag' => 'no',
                'options' => [],
            ],
        ],
        'must_set_up_mfa:a' => [
            'eduPersonPrincipalName' => ['MUST_SET_UP_MFA@mfaidp'],
            'eduPersonTargetID' => ['22222222-2222-2222-2222-222222222222'],
            'sn' => ['Set Up MFA'],
            'givenName' => ['Must'],
            'mail' => ['must_set_up_mfa@example.com'],
            'employeeNumber' => ['22222'],
            'cn' => ['MUST_SET_UP_MFA'],
            'mfa' => [
                'prompt' => 'yes',
                'nag' => 'no',
                'options' => [],
            ],
        ],
        'has_backupcode:a' => [
            'eduPersonPrincipalName' => ['HAS_BACKUPCODE@mfaidp'],
            'eduPersonTargetID' => ['33333333-3333-3333-3333-333333333333'],
            'sn' => ['Backupcode'],
            'givenName' => ['Has'],
            'mail' => ['has_backupcode@example.com'],
            'employeeNumber' => ['33333'],
            'cn' => ['HAS_BACKUPCODE'],
            'mfa' => [
                'prompt' => 'yes',
                'nag' => 'no',
                'options' => [
                    [
                        'id' => '7',
                        'type' => 'backupcode',
                        'data' => '',
                    ],
                ],
            ],
        ],
        'has_totp:a' => [
            'eduPersonPrincipalName' => ['HAS_TOTP@mfaidp'],
            'eduPersonTargetID' => ['44444444-4444-4444-4444-444444444444'],
            'sn' => ['TOTP'],
            'givenName' => ['Has'],
            'mail' => ['has_totp@example.com'],
            'employeeNumber' => ['44444'],
            'cn' => ['HAS_TOTP'],
            'mfa' => [
                'prompt' => 'yes',
                'nag' => 'no',
                'options' => [
                    [
                        'id' => '2',
                        'type' => 'totp',
                        'data' => '',
                    ],
                ],
            ],
        ],
        'has_u2f:a' => [
            'eduPersonPrincipalName' => ['HAS_U2F@mfaidp'],
            'eduPersonTargetID' => ['55555555-5555-5555-5555-555555555555'],
            'sn' => ['U2F'],
            'givenName' => ['Has'],
            'mail' => ['has_u2f@example.com'],
            'employeeNumber' => ['55555'],
            'cn' => ['HAS_U2F'],
            'mfa' => [
                'prompt' => 'yes',
                'nag' => 'no',
                'options' => [
                    [
                        'id' => '3',
                        'type' => 'u2f',
                        'data' => '',
                    ],
                ]
            ],
        ],
        'nag_for_mfa:a' => [
            'eduPersonPrincipalName' => ['NAG_FOR_MFA@mfaidp'],
            'eduPersonTargetID' => ['66666666-6666-6666-6666-666666666666'],
            'sn' => ['For MFA'],
            'givenName' => ['Nag'],
            'mail' => ['nag_for_mfa@example.com'],
            'employeeNumber' => ['666666'],
            'cn' => ['NAG_FOR_MFA'],
            'mfa' => [
                'prompt' => 'no',
                'nag' => 'yes',
                'options' => [],
            ],
        ],
        'has_all:a' => [
            'eduPersonPrincipalName' => ['has_all@mfaidp'],
            'eduPersonTargetID' => ['77777777-7777-7777-7777-777777777777'],
            'sn' => ['All'],
            'givenName' => ['Has'],
            'mail' => ['has_all@example.com'],
            'employeeNumber' => ['777777'],
            'cn' => ['HAS_ALL'],
            'mfa' => [
                'prompt' => 'yes',
                'nag' => 'no',
                'options' => [
                    [
                        'id' => '1',
                        'type' => 'backupcode',
                        'data' => '',
                    ],
                    [
                        'id' => '2',
                        'type' => 'totp',
                        'data' => '',
                    ],
                    [
                        'id' => '3',
                        'type' => 'u2f',
                        'data' => '',
                    ],
                ],
            ],
        ],
    ],
];
