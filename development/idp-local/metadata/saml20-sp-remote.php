<?php
/**
 * SAML 2.0 remote SP metadata for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote
 */

// With port number (for testing from browser on host O/S):
$metadata['http://mfa-sp.local:8081'] = [
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
	'AssertionConsumerService' => 'http://mfa-sp.local:8081/module.php/saml/sp/saml2-acs.php/mfa-idp',
	'SingleLogoutService' => 'http://mfa-sp.local:8081/module.php/saml/sp/saml2-logout.php/mfa-idp',
    'certData' => 'MIIDXTCCAkWgAwIBAgIJAM9xjgDMzRW7MA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNVBAYTAlVTMRMwEQYDVQQIDApTb21lLVN0YXRlMSEwHwYDVQQKDBhJbnRlcm5ldCBXaWRnaXRzIFB0eSBMdGQwHhcNMTcwNjA2MTQzNTQzWhcNMjcwNjA2MTQzNTQzWjBFMQswCQYDVQQGEwJVUzETMBEGA1UECAwKU29tZS1TdGF0ZTEhMB8GA1UECgwYSW50ZXJuZXQgV2lkZ2l0cyBQdHkgTHRkMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoGg03EBTojHpWhUJgHcYqzeVLgAT/Xm2ksaHHf6SMhuZoSMf7auB/C09UP/ml2M/Kv0aVAAbYPmaOmuKhidO5RIKpSw+wltLoP5Ib/x5FWHpd8qqGPvafH2C6ofJkOt1aGjQ3rH0yRDp9l8sTjBfgFQdzRD2pfbM5Ux5o5eSp4XBM8VeeMJRiSb9Yw4cRPTiS5PSeixtSa3ppix7LjF43M6yKKkpodCO26fEX2rppAt9qTs6OjaysZTgVVNq3k89QWz/WPNTSJ+J2HZH9DwWjeEkiKvPE75SsdGWvL7s0dSbrOWJ0pcg+NH6B4PwhyEwvtCkcxr5yBwFYIzXjKP0twIDAQABo1AwTjAdBgNVHQ4EFgQUDgbZG2ts36J3TgRIlnrMS1Pn3j0wHwYDVR0jBBgwFoAUDgbZG2ts36J3TgRIlnrMS1Pn3j0wDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAZIeI5Qe5Np6RFQQm2qMa8XwVMpitbVYCIux6STAw1s5wvSudmuJwpJBDCt0f/fb/xoUChQdmlYZ8rGVxmmTQTl2RW6W++8jaUfiUqf0ACLug0gOV5nLj+MPV7Qj3xzVTJRHR+xHtKgMX/ArCasAv+nCBRXVXjQmZw6Ub9k5aHHURDjLYq+KtO1DuC6HN08dkQhfeb3IJCCKNTu/i0ZBVvEEifJYKwq+5tLrMhVKQYoAr1JN6DCGJ2qAuyxNX9Ab7ngWUjtXG/Wxe75dA804S42hR1GPl2Qshhy5ZnjfswwDPWnCyJg3QsxGKa24wTQi+KOt83ev+Zrur9umBdaJLqA==',
];

// Without port number (for testing from another docker container):
$metadata['http://mfasp'] = [
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
	'AssertionConsumerService' => 'http://mfasp/module.php/saml/sp/saml2-acs.php/mfa-idp-no-port',
	'SingleLogoutService' => 'http://mfasp/module.php/saml/sp/saml2-logout.php/mfa-idp-no-port',
    'certData' => 'MIIDXTCCAkWgAwIBAgIJAM9xjgDMzRW7MA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNVBAYTAlVTMRMwEQYDVQQIDApTb21lLVN0YXRlMSEwHwYDVQQKDBhJbnRlcm5ldCBXaWRnaXRzIFB0eSBMdGQwHhcNMTcwNjA2MTQzNTQzWhcNMjcwNjA2MTQzNTQzWjBFMQswCQYDVQQGEwJVUzETMBEGA1UECAwKU29tZS1TdGF0ZTEhMB8GA1UECgwYSW50ZXJuZXQgV2lkZ2l0cyBQdHkgTHRkMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoGg03EBTojHpWhUJgHcYqzeVLgAT/Xm2ksaHHf6SMhuZoSMf7auB/C09UP/ml2M/Kv0aVAAbYPmaOmuKhidO5RIKpSw+wltLoP5Ib/x5FWHpd8qqGPvafH2C6ofJkOt1aGjQ3rH0yRDp9l8sTjBfgFQdzRD2pfbM5Ux5o5eSp4XBM8VeeMJRiSb9Yw4cRPTiS5PSeixtSa3ppix7LjF43M6yKKkpodCO26fEX2rppAt9qTs6OjaysZTgVVNq3k89QWz/WPNTSJ+J2HZH9DwWjeEkiKvPE75SsdGWvL7s0dSbrOWJ0pcg+NH6B4PwhyEwvtCkcxr5yBwFYIzXjKP0twIDAQABo1AwTjAdBgNVHQ4EFgQUDgbZG2ts36J3TgRIlnrMS1Pn3j0wHwYDVR0jBBgwFoAUDgbZG2ts36J3TgRIlnrMS1Pn3j0wDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEAZIeI5Qe5Np6RFQQm2qMa8XwVMpitbVYCIux6STAw1s5wvSudmuJwpJBDCt0f/fb/xoUChQdmlYZ8rGVxmmTQTl2RW6W++8jaUfiUqf0ACLug0gOV5nLj+MPV7Qj3xzVTJRHR+xHtKgMX/ArCasAv+nCBRXVXjQmZw6Ub9k5aHHURDjLYq+KtO1DuC6HN08dkQhfeb3IJCCKNTu/i0ZBVvEEifJYKwq+5tLrMhVKQYoAr1JN6DCGJ2qAuyxNX9Ab7ngWUjtXG/Wxe75dA804S42hR1GPl2Qshhy5ZnjfswwDPWnCyJg3QsxGKa24wTQi+KOt83ev+Zrur9umBdaJLqA==',
];
