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
            'nagForMfa' => 'yes',
            'mfaOptions' => '',
        ],
        'must_set_up_mfa:a' => [
            'employeeNumber' => ['22222'],
            'promptForMfa' => 'yes',
            'nagForMfa' => 'no',
            'mfaOptions' => '',
        ],
        'has_backupcode:a' => [
            'eduPersonPrincipalName' => ['has_backupcode@mfa-idp.local'],
            'eduPersonTargetID' => ['11111111-1111-1111-1111-111111111111'],
            'sn' => ['Has'],
            'givenName' => ['Backupcode'],
            'mail' => ['hasBackupcode@example.com'],
            'employeeNumber' => ['33333'],
            'promptForMfa' => 'yes',
            'nagForMfa' => 'no',
            'mfaOptions' => [
                [
                    'id' => '1',
                    'type' => 'backupcode',
                    'data' => '',
                ],
            ],
        ],
        'has_totp:a' => [
            'employeeNumber' => ['33333'],
            'promptForMfa' => 'yes',
            'nagForMfa' => 'no',
            'mfaOptions' => [
                [
                    'id' => '2',
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
        'has_all:a' => [
            'eduPersonPrincipalName' => ['has_all@mfa-idp.local'],
            'eduPersonTargetID' => ['11111111-1111-1111-1111-111111111111'],
            'sn' => ['Has'],
            'givenName' => ['All'],
            'mail' => ['has-all@example.com'],
            'employeeNumber' => ['66666'],
            'promptForMfa' => 'yes',
            'nagForMfa' => 'no',
            'mfaOptions' => [
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
];
