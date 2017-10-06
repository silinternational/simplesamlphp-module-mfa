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
            'promptForMfa' => ['no'],
            'mfaOptionsJson' => [],
        ],
        'must_set_up_mfa:a' => [
            'employeeNumber' => ['22222'],
            'promptForMfa' => ['yes'],
            'mfaOptionsJson' => [],
        ],
        'has_backupcode:a' => [
            'employeeNumber' => ['33333'],
            'promptForMfa' => ['yes'],
            'mfaOptionsJson' => ['{"id":37, "type":"backupcode", "data":""}'],
        ],
        'has_totp:a' => [
            'employeeNumber' => ['44444'],
            'promptForMfa' => ['yes'],
            'mfaOptionsJson' => ['{"id":25, "type":"totp", "data":""}'],
        ],
        'has_u2f:a' => [
            'employeeNumber' => ['55555'],
            'promptForMfa' => ['yes'],
            'mfaOptionsJson' => ['{"id":96, "type":"u2f", "data":""}'],
        ],
    ],
];
