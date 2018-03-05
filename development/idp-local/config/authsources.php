<?php

use Sil\SspMfa\Behat\fakes\FakeIdBrokerClient;

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
                        'data' => [
                            'count' => 10,
                        ],
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
                        'data' => [
                            'count' => 8,
                        ],
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
        'has_rate_limited_mfa:a' => [
            'eduPersonPrincipalName' => ['HAS_RATE_LIMITED_MFA@mfaidp'],
            'eduPersonTargetID' => ['88888888-8888-8888-8888-888888888888'],
            'sn' => ['Rate-Limited MFA'],
            'givenName' => ['Has'],
            'mail' => ['has_rate_limited_mfa@example.com'],
            'employeeNumber' => ['88888'],
            'cn' => ['HAS_RATE_LIMITED_MFA'],
            'mfa' => [
                'prompt' => 'yes',
                'nag' => 'no',
                'options' => [
                    [
                        'id' => FakeIdBrokerClient::RATE_LIMITED_MFA_ID,
                        'type' => 'backupcode',
                        'data' => [
                            'count' => 5,
                        ],
                    ],
                ],
            ],
        ],
        'has_4_backupcodes:a' => [
            'eduPersonPrincipalName' => ['HAS_4_BACKUPCODES@mfaidp'],
            'eduPersonTargetID' => ['99999999-9999-9999-9999-999999999999'],
            'sn' => ['Backupcodes'],
            'givenName' => ['Has 4'],
            'mail' => ['has_4_backupcodes@example.com'],
            'employeeNumber' => ['99999'],
            'cn' => ['HAS_4_BACKUPCODES'],
            'mfa' => [
                'prompt' => 'yes',
                'nag' => 'no',
                'options' => [
                    [
                        'id' => '90',
                        'type' => 'backupcode',
                        'data' => [
                            'count' => 4,
                        ],
                    ],
                ],
            ],
        ],
        'has_1_backupcode_only:a' => [
            'eduPersonPrincipalName' => ['HAS_1_BACKUPCODE_ONLY@mfaidp'],
            'eduPersonTargetID' => ['00000010-0010-0010-0010-000000000010'],
            'sn' => ['Only, And No Other MFA'],
            'givenName' => ['Has 1 Backupcode'],
            'mail' => ['has_1_backupcode_only@example.com'],
            'employeeNumber' => ['00010'],
            'cn' => ['HAS_1_BACKUPCODE_ONLY'],
            'mfa' => [
                'prompt' => 'yes',
                'nag' => 'no',
                'options' => [
                    [
                        'id' => '100',
                        'type' => 'backupcode',
                        'data' => [
                            'count' => 1,
                        ],
                    ],
                ],
            ],
        ],
        'has_1_backupcode_plus:a' => [
            'eduPersonPrincipalName' => ['HAS_1_BACKUPCODE_PLUS@mfaidp'],
            'eduPersonTargetID' => ['00000011-0011-0011-0011-000000000011'],
            'sn' => ['Plus Other MFA'],
            'givenName' => ['Has 1 Backupcode'],
            'mail' => ['has_1_backupcode_plus@example.com'],
            'employeeNumber' => ['00011'],
            'cn' => ['HAS_1_BACKUPCODE_PLUS'],
            'mfa' => [
                'prompt' => 'yes',
                'nag' => 'no',
                'options' => [
                    [
                        'id' => '110',
                        'type' => 'backupcode',
                        'data' => [
                            'count' => 1,
                        ],
                    ],
                    [
                        'id' => '112',
                        'type' => 'totp',
                        'data' => '',
                    ],
                ],
            ],
        ],
        'has_u2f_totp:a' => [
            'eduPersonPrincipalName' => ['has_u2f_totp@mfaidp'],
            'eduPersonTargetID' => ['00000012-0012-0012-0012-000000000012'],
            'sn' => ['U2F And TOTP'],
            'givenName' => ['Has'],
            'mail' => ['has_u2f_totp@example.com'],
            'employeeNumber' => ['00012'],
            'cn' => ['HAS_U2F_TOTP'],
            'mfa' => [
                'prompt' => 'yes',
                'nag' => 'no',
                'options' => [
                    [
                        'id' => '120',
                        'type' => 'totp',
                        'data' => '',
                    ],
                    [
                        'id' => '121',
                        'type' => 'u2f',
                        'data' => '',
                    ],
                ],
            ],
        ],
        'has_u2f_backupcodes:a' => [
            'eduPersonPrincipalName' => ['has_u2f_backupcodes@mfaidp'],
            'eduPersonTargetID' => ['00000013-0013-0013-0013-000000000013'],
            'sn' => ['U2F And Backup Codes'],
            'givenName' => ['Has'],
            'mail' => ['has_u2f_backupcodes@example.com'],
            'employeeNumber' => ['00013'],
            'cn' => ['HAS_U2F_BACKUPCODES'],
            'mfa' => [
                'prompt' => 'yes',
                'nag' => 'no',
                'options' => [
                    [
                        'id' => '130',
                        'type' => 'backupcode',
                        'data' => [
                            'count' => 10,
                        ],
                    ],
                    [
                        'id' => '131',
                        'type' => 'u2f',
                        'data' => '',
                    ],
                ],
            ],
        ],
        'has_u2f_totp_backupcodes:a' => [
            'eduPersonPrincipalName' => ['has_u2f_totp_backupcodes@mfaidp'],
            'eduPersonTargetID' => ['00000014-0014-0014-0014-000000000014'],
            'sn' => ['U2F, TOTP, And Backup Codes'],
            'givenName' => ['Has'],
            'mail' => ['has_u2f_totp_backupcodes@example.com'],
            'employeeNumber' => ['00014'],
            'cn' => ['HAS_U2F_TOTP_BACKUPCODES'],
            'mfa' => [
                'prompt' => 'yes',
                'nag' => 'no',
                'options' => [
                    [
                        'id' => '140',
                        'type' => 'totp',
                        'data' => '',
                    ],
                    [
                        'id' => '141',
                        'type' => 'backupcode',
                        'data' => [
                            'count' => 10,
                        ],
                    ],
                    [
                        'id' => '142',
                        'type' => 'u2f',
                        'data' => '',
                    ],
                ],
            ],
        ],
        'has_totp_backupcodes:a' => [
            'eduPersonPrincipalName' => ['has_totp_backupcodes@mfaidp'],
            'eduPersonTargetID' => ['00000015-0015-0015-0015-000000000015'],
            'sn' => ['TOTP And Backup Codes'],
            'givenName' => ['Has'],
            'mail' => ['has_totp_backupcodes@example.com'],
            'employeeNumber' => ['00015'],
            'cn' => ['HAS_TOTP_BACKUPCODES'],
            'mfa' => [
                'prompt' => 'yes',
                'nag' => 'no',
                'options' => [
                    [
                        'id' => '150',
                        'type' => 'totp',
                        'data' => '',
                    ],
                    [
                        'id' => '151',
                        'type' => 'backupcode',
                        'data' => [
                            'count' => 10,
                        ],
                    ],
                ],
            ],
        ],
    ],
];
