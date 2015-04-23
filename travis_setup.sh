#!/bin/bash

curl https://wordpress.org/latest.tar.gz | tar xz
ln -s $(pwd) wordpress/wp-content/themes/
cp wordpress/wp-config-sample.php wordpress/wp-config.php

mysql -e 'create user ivanhoe;' -u root
mysql -e 'create database ivanhoe;' -u root
mysql -e 'create database test_ivanhoe;' -u root
mysql -e 'grant all privileges on ivanhoe.* to "ivanhoe"@"localhost" identified by "ivanhoe";' -u root
mysql -e 'grant all privileges on test_ivanhoe.* to "ivanhoe"@"localhost" identified by "ivanhoe";' -u root

