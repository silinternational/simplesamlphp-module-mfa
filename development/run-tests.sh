#!/usr/bin/env bash

runny ./setup-logentries.sh

runny composer install --no-interaction --no-scripts

sleep 10

# Run the feature tests
./vendor/bin/behat --config=features/behat.yml
