#! /bin/bash

# Includes your config file
source config.sh

if [ $# -ne 1 ]; then
    echo $0: usage: Destination Name
    exit 1
fi

DEST=$1


# Download WP Core.
wp core download --locale=fr_FR

composer install

# Generate the wp-config.php file
wp core config --dbhost=localhost --dbname=$DEST --dbuser=$DB_USER --dbpass=$DB_PASS --dbprefix=wps_ --locale=fr_FR --extra-php <<PHP
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);
PHP

#install db
wp db create --dbuser=$DB_USER --dbpass=$DB_PASS

# Install the WordPress database.
wp core install --url=$BASE_URL/$DEST --title=$DEST --admin_user=admin --admin_password=admin --admin_email=admin@admin.fr

# set debug to true
wp config set WP_DEBUG true --raw

# DELETE README.HTML
echo Deleting readme.html
rm -f readme.html

# DELETE LICENCE.TXT
echo Deleting licence.txt
rm -f license.txt

echo Deleting junk plugins...
rm -r wp-content/plugins/hello.php
rm -r wp-content/plugins/akismet
echo



open $BASE_URL/$DEST