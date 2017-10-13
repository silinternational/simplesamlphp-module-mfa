#!/usr/bin/env bash

/data/symlink.sh

touch /data/vendor/simplesamlphp/simplesamlphp/modules/exampleauth/enable

/data/run.sh
