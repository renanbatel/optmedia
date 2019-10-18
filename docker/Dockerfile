FROM wordpress:5.0.0-php7.2-apache

# Update package repositories
RUN apt-get update

# Install required libraries for server
RUN apt-get install -y --no-install-recommends \
  libwebp-dev \
  libjpeg-dev \
  libmagickwand-dev \
  libpng-dev; \
  docker-php-ext-configure gd \
  --with-png-dir=/usr \
  --with-jpeg-dir=/usr \
  --with-webp-dir=/usr; \
  docker-php-ext-install gd

# Install server binaries required by plugin server providers
RUN apt-get install -y \
  jpegoptim \
  pngquant \
  optipng \
  ffmpeg \
  webp

# Install required packages for development
RUN apt-get install -y \
  git \
  subversion \
  mariadb-client \
  wget \
  unzip \
  less
RUN curl -s https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp; \
  chmod +x /usr/local/bin/wp; \
  curl -s https://getcomposer.org/download/1.9.0/composer.phar -o /usr/local/bin/composer; \
  chmod +x /usr/local/bin/composer; \
  curl -s https://phar.phpunit.de/phpunit-6.5.14.phar -o /usr/local/bin/phpunit; \
  chmod +x /usr/local/bin/phpunit; \
  pecl install xdebug; \
  echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini; \
  echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini; \
  echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/xdebug.ini; \
  echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini; \
  echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/xdebug.ini; \
  echo "xdebug.remote_connect_back=0" >> /usr/local/etc/php/conf.d/xdebug.ini; \
  echo "xdebug.idekey=docker" >> /usr/local/etc/php/conf.d/xdebug.ini

# Grant privileges to www-data on site's folder
RUN chown -R www-data:www-data /var/www/html; \
  chmod -R 774 /var/www/html

# Custom apache2 setup for plugin development
COPY bin/apache2-init.sh /usr/local/bin
RUN chmod +x /usr/local/bin/apache2-init.sh
ENTRYPOINT docker-entrypoint.sh apache2-init.sh
