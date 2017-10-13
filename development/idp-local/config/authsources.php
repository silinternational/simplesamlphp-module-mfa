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
            'mfaOptions' => '',
        ],
        'must_set_up_mfa:a' => [
            'employeeNumber' => ['22222'],
            'promptForMfa' => 'yes',
            'nagForMfa' => 'no',
            'mfaOptions' => '',
        ],
        'has_backupcode:a' => [
            'employeeNumber' => ['33333'],
            'promptForMfa' => 'yes',
            'nagForMfa' => 'no',
            'mfaOptions' => [
                [
                    'id' => '37',
                    'type' => 'backupcode',
                    'data' => '',
                ],
            ],
        ],
        'has_totp:a' => [
            'employeeNumber' => ['44444'],
            'promptForMfa' => 'yes',
            'nagForMfa' => 'no',
            'mfaOptions' => [
                [
                    'id' => '25',
                    'type' => 'totp',
                    'data' => '',
                ],
            ],
        ],
        'has_u2f:a' => [
            'employeeNumber' => ['55555'],
            'promptForMfa' => 'yes',
            'nagForMfa' => 'no',
            'mfaOptions' => [
                [
                    'id' => '96',
                    'type' => 'u2f',
                    'data' => '',
                ],
            ],
        ],
        'nag_for_mfa:a' => [
            'employeeNumber' => ['55555'],
            'promptForMfa' => 'no',
            'nagForMfa' => 'yes',
            'mfaOptions' => '',
        ],
    ],
];
