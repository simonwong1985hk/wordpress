#!/bin/bash

# variables
url=''
dbname=''
dbuser=''
dbpass=''

# create wp-config.php
wp config create --dbname=$dbname --dbuser=$dbuser --dbpass=$dbpass

# import database
wp db reset --yes && wp db import db.sql

# update url
wp search-replace $(wp option get siteurl) $url

# create .htaccess
cat << EOF > wp-cli.yml
apache_modules:
  - mod_rewrite
EOF
wp rewrite flush --hard