# Multi-Factor Authentication (MFA) simpleSAMLphp Module #
A simpleSAMLphp module for prompting the user for MFA credentials (such as a
TOTP code, etc.).

This mfa module is implemented as an Authentication Processing Filter, 
or AuthProc. That means it can be configured in the global config.php file or 
the SP remote or IdP hosted metadata.

It is recommended to run the mfa module at the IdP, and configure the
filter to run before all the other filters you may have enabled.

## How to use the module ##
Simply include `simplesamlphp/composer-module-installer` and this module as 
required in your `composer.json` file. The `composer-module-installer` package 
will discover this module and copy it into the `modules` folder within 
`simplesamlphp`.

You will then need to set filter parameters in your config. We recommend adding 
them to the `'authproc'` array in your `metadata/saml20-idp-hosted.php` file.

Example (for `metadata/saml20-idp-hosted.php`):

    use Sil\PhpEnv\Env;
    
    // ...
    
    'authproc' => [
        10 => [
            // Required:
            'class' => 'mfa:Mfa',
            'employeeIdAttr' => 'employeeNumber',
            'idBrokerAccessToken' => Env::get('ID_BROKER_ACCESS_TOKEN'),
            'idBrokerAssertValidIp' => Env::get('ID_BROKER_ASSERT_VALID_IP'),
            'idBrokerBaseUri' => Env::get('ID_BROKER_BASE_URI'),
            'idBrokerTrustedIpRanges' => Env::get('ID_BROKER_TRUSTED_IP_RANGES'),
            'mfaSetupUrl' => 'https://idm.example.com/mfa/',

            // Optional:
            'loggerClass' => '\\Sil\\Psr3Adapters\\Psr3SamlLogger',
        ],
        
        // ...
    ],

The `employeeIdAttr` parameter represents the SAML attribute name which has 
the user's Employee ID stored in it. In certain situations, this may be 
displayed to the user, as well as being used in log messages.

The `loggerClass` parameter specifies the name of a PSR-3 compatible class that 
can be autoloaded, to use as the logger within ExpiryDate.

The `mfaSetupUrl` parameter is for the URL of where to send the user if they
want/need to set up MFA.

## Testing Locally ##

### Setup ###
Add entries to your hosts file to associate `mfasp` and `mfaidp`
with the IP address of your docker containers (which is the IP address from
the Vagrantfile if you are running docker within the Vagrant VM).

### Automated Testing ###
Run `make test`.

### Manual Testing ###
Go to <http://mfasp:8081/module.php/core/authenticate.php?as=mfa-idp> in
your browser and sign in with one of the users defined in
`development/idp-local/config/authsources.php`.

## Contributing ##
To contribute, please submit issues or pull requests at 
https://github.com/silinternational/simplesamlphp-module-mfa

## Acknowledgements ##
This is adapted from the `silinternational/simplesamlphp-module-expirychecker`
module, which itself is adapted from other modules. Thanks to all those who
contributed to that work.
