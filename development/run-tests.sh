#!/usr/bin/env bash

./setup-logentries.sh

# Try to install composer dev dependencies
cd /data/vendor/simplesamlphp/simplesamlphp/modules/mfa
composer install --no-interaction --no-scripts

# If that failed, exit.
rc=$?; if [[ $rc != 0 ]]; then exit $rc; fi

# Run the feature tests
./vendor/bin/behat --config=features/behat.yml

# If they failed, exit.
rc=$?; if [[ $rc != 0 ]]; then exit $rc; fi

# Switch back to the folder we were in.
cd -
