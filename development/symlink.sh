#!/usr/bin/env bash

rm -rf /data/vendor/simplesamlphp/simplesamlphp/modules/mfa
ln -s /mfa /data/vendor/simplesamlphp/simplesamlphp/modules/

#ln -s /mfa/development/ssp/authsources.php /data/vendor/simplesamlphp/simplesamlphp/config/authsources.php
#ln -s /mfa/development/ssp/saml20-idp-hosted.php /data/vendor/simplesamlphp/simplesamlphp/metadata/saml20-idp-hosted.php
#mkdir -p /data/vendor/simplesamlphp/simplesamlphp/cert/
#ln -s /mfa/development/ssp/saml.crt /data/vendor/simplesamlphp/simplesamlphp/cert/saml.crt
#ln -s /mfa/development/ssp/saml.pem /data/vendor/simplesamlphp/simplesamlphp/cert/saml.pem
