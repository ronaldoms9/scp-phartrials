#!/bin/sh
mkdir -p /run/php-fpm
php-fpm -D
exec httpd -DFOREGROUND
