#!/usr/bin/env bash

# Make sure any new composer dependencies of this project are present.
composer require "silinternational/simplesamlphp-module-mfa dev-develop@dev" --update-no-dev --update-with-dependencies

/data/symlink.sh

touch /data/vendor/simplesamlphp/simplesamlphp/modules/exampleauth/enable

/data/run.sh
