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
        'entityID' => 'http://mfa-pw-manager.local:52022',
        'idp' => 'http://mfa-idp.local:52020',
        'discoURL' => null,
        'NameIDPolicy' => "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent",
    ],

    'mfa-idp-no-port' => [
        'saml:SP',
        'entityID' => 'http://mfapwmanager',
        'idp' => 'http://mfaidp',
        'discoURL' => null,
        'NameIDPolicy' => "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent",
    ],
];
