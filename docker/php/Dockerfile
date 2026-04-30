FROM php:8.1-fpm

COPY php.ini /usr/local/etc/php/

RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip curl gnupg \
  && docker-php-ext-install pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

RUN curl -sS https://packages.stripe.dev/api/security/keypair/stripe-cli-gpg/public \
  | gpg --dearmor \
  | tee /usr/share/keyrings/stripe.gpg > /dev/null \
  && echo "deb [signed-by=/usr/share/keyrings/stripe.gpg] https://packages.stripe.dev/stripe-cli-debian-local stable main" \
  > /etc/apt/sources.list.d/stripe.list \
  && apt update \
  && apt install -y stripe

WORKDIR /var/www
