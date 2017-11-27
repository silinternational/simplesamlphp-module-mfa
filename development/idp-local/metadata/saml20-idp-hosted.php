<?php

use Sil\PhpEnv\Env;
use Sil\Psr3Adapters\Psr3SamlLogger;
use Sil\SspMfa\Behat\fakes\FakeIdBrokerClient;

/**
 * SAML 2.0 IdP configuration for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-hosted
 */
$metadata['http://mfa-idp.local:8085'] = [
	/*
	 * The hostname of the server (VHOST) that will use this SAML entity.
	 *
	 * Can be '__DEFAULT__', to use this entry by default.
	 */
	'host' => 'mfa-idp.local',

	// X.509 key and certificate. Relative to the cert directory.
	'privatekey' => 'dummy.pem',
	'certificate' => 'dummy.crt',

	/*
	 * Authentication source to use. Must be one that is configured in
	 * 'config/authsources.php'.
	 */
	'auth' => 'example-userpass',
    
    'authproc' => [
        10 => [
            'class' => 'mfa:Mfa',
            'employeeIdAttr' => 'employeeNumber',
            'idBrokerAccessToken' => Env::get('ID_BROKER_ACCESS_TOKEN'),
            'idBrokerAssertValidIp' => Env::get('ID_BROKER_ASSERT_VALID_IP'),
            'idBrokerBaseUri' => Env::get('ID_BROKER_BASE_URI'),
            'idBrokerClientClass' => FakeIdBrokerClient::class,
            'idBrokerTrustedIpRanges' => Env::get('ID_BROKER_TRUSTED_IP_RANGES'),
            'mfaLearnMoreUrl' => Env::get('MFA_LEARN_MORE_URL'),
            'mfaSetupUrl' => Env::get('MFA_SETUP_URL'),
            'loggerClass' => Psr3SamlLogger::class,
        ],
    ],
];

// Copy the metadata to also work from another docker container.
$metadata['http://mfaidp'] = [
	/*
	 * The hostname of the server (VHOST) that will use this SAML entity.
	 *
	 * Can be '__DEFAULT__', to use this entry by default.
	 */
	'host' => 'mfaidp', // *** DIFFERENT! ***

	// X.509 key and certificate. Relative to the cert directory.
	'privatekey' => 'dummy.pem',
	'certificate' => 'dummy.crt',

	/*
	 * Authentication source to use. Must be one that is configured in
	 * 'config/authsources.php'.
	 */
	'auth' => 'example-userpass',
    
    'authproc' => [
        10 => [
            'class' => 'mfa:Mfa',
            'employeeIdAttr' => 'employeeNumber',
            'idBrokerAccessToken' => Env::get('ID_BROKER_ACCESS_TOKEN'),
            'idBrokerAssertValidIp' => Env::get('ID_BROKER_ASSERT_VALID_IP'),
            'idBrokerBaseUri' => Env::get('ID_BROKER_BASE_URI'),
            'idBrokerClientClass' => FakeIdBrokerClient::class,
            'idBrokerTrustedIpRanges' => Env::get('ID_BROKER_TRUSTED_IP_RANGES'),
            'mfaLearnMoreUrl' => Env::get('MFA_LEARN_MORE_URL'),
            'mfaSetupUrl' => Env::get('MFA_SETUP_URL_FOR_TESTS'), // *** DIFFERENT! ***
            'loggerClass' => Psr3SamlLogger::class,
        ],
    ],
];
