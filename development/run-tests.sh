#!/usr/bin/env bash

# Exit this script if a step fails
set -e

composer install --no-interaction --no-scripts --no-progress

# Give composer time to install any new dependencies of this project
sleep 200

# Run the feature tests
./vendor/bin/behat --config=features/behat.yml
