#!/usr/bin/env sh

chown www-data:www-data -R /var/www/html/wp-content

# Install wordpress
su -s /bin/bash www-data -c "
    echo 'apache_modules:' >> wp-cli.yml
    echo '  - mod_rewrite' >> wp-cli.yml
    wp core install \
        --url='http://localhost:8000' \
        --title='OptMedia - Dev' \
        --admin_user=wordpress \
        --admin_password=wordpress \
        --admin_email=admin@optmedia.dev \
        --skip-email
    wp plugin activate optmedia
    wp rewrite structure '/%postname%/' --hard
    sed -i '11s/.*/# END WordPress\nphp_value upload_max_filesize 128M\n /' .htaccess
    sed -i '13s/.*/php_value post_max_size 128M\n /' .htaccess
    sed -i '14s/.*/php_value memory_limit 256M\n /' .htaccess
    sed -i '15s/.*/php_value max_execution_time 300\n /' .htaccess
    sed -i '16s/.*/php_value max_input_time 300\n/' .htaccess
    wp config set WP_DEBUG true --raw
    sed -i \"/^define('WP_DEBUG', true)/a @ini_set( 'error_log', '/var/www/html/wp-content/logs/debug.log' );\" wp-config.php 
    sed -i \"/^define('WP_DEBUG', true)/a @ini_set( 'log_errors', 1 );\" wp-config.php 
"

# Setup plugin unit test if test lib functions.php file doesn't exist
if [ ! -f /tmp/wordpress-tests-lib/includes/functions.php ]; then
    echo "> Setting up plugin unit test"
    echo "* Extra files will be removed after setup"

    su -s /bin/bash www-data -c "
        wp scaffold plugin-tests optmedia --force
        wp-content/plugins/optmedia/bin/install-wp-tests.sh $WORDPRESS_TEST_DATABASE root $MYSQL_ROOT_PASSWORD db 5.0.0
        mkdir -p /tmp/wordpress/wp-content/uploads
        rm wp-content/plugins/optmedia/phpunit.xml.dist
        rm wp-content/plugins/optmedia/.travis.yml
        rm wp-content/plugins/optmedia/.phpcs.xml.dist
        rm wp-content/plugins/optmedia/tests/test-sample.php
        rm -rf wp-content/plugins/optmedia/bin
    "

    echo "! Plugin unit test setup finished"
else
    echo "> Plugin unit test already setup"
    echo "* If you want to setup it again remove the wordpress container and recreate it"
fi

# Change some folders' permissions
chmod -R 777 /var/www/html/wp-content/plugins/optmedia
chmod -R 777 /var/www/html/wp-content/uploads
chmod -R 777 /var/www/html/wp-content/logs

apache2-foreground
