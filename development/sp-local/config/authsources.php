<?php

$config = [

    // This is a authentication source which handles admin authentication.
    'admin' => [
        // The default is to use core:AdminPassword, but it can be replaced with
        // any authentication source.

        'core:AdminPassword',
    ],

    'mfa-idp' => [
        'saml:SP',
        'entityID' => 'http://mfasp:8081',
        'idp' => 'http://mfaidp:8085',
        'discoURL' => null,
        'NameIDPolicy' => "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent",
    ],

    'mfa-idp-no-port' => [
        'saml:SP',
        'entityID' => 'http://mfasp',
        'idp' => 'http://mfaidp',
        'discoURL' => null,
        'NameIDPolicy' => "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent",
    ],
];
