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
echo '# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress' > .htaccess