FROM php:7.1-fpm-alpine

MAINTAINER Ghazaleh <franco.ghazaleh@gmail.com>

VOLUME /var/www/html
WORKDIR /var/www/html
COPY . /var/www/html

# Allow Composer to be run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Setup the Composer installer
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
  && php /tmp/composer-setup.php \
  && chmod a+x composer.phar \
  && mv composer.phar /usr/local/bin/composer

# Install composer dependencies
RUN echo pwd: `pwd` && echo ls: `ls`  # outputs:
                                      # pwd: /var/www/html
                                      # ls:
# RUN composer install --no-dev

