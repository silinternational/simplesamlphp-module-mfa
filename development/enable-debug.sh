#!/usr/bin/env bash

echo "Installing php-xdebug"
apt-get update -y
apt-get install -y php-xdebug
phpenmod xdebug

INI_FILE="/etc/php/7.0/apache2/php.ini"
echo "Configuring debugger in $INI_FILE"
echo "xdebug.remote_enable=1" >> $INI_FILE
echo "xdebug.remote_host=$XDEBUG_REMOTE_HOST" >> $INI_FILE
echo "xdebug.remote_autostart=1" >> $INI_FILE
echo "xdebug.remote_connect_back=1" >> $INI_FILE
echo "xdebug.remote_port=9000" >> $INI_FILE
echo "xdebug.idekey=netbeans-xdebug" >> $INI_FILE

mkdir -p /data/vendor/simplesamlphp/simplesamlphp/modules/sildisco
touch /data/vendor/simplesamlphp/simplesamlphp/modules/sildisco/default-enable

apachectl -k graceful

sed -i "s/^<?php$/<?php defined('YII_DEBUG') || define('YII_DEBUG', true);/" /data/vendor/simplesamlphp/simplesamlphp/www/_include.php
