#!/usr/bin/env bash

set -e
set -x

composer install --no-interaction --no-scripts --no-progress

# Give composer time to install any new dependencies of this project
whenavail mfaidp 80 200 echo mfaidp ready

# Run the feature tests
./vendor/bin/behat --config=features/behat.yml
