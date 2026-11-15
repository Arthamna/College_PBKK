FROM php:8.4-apache

# php2shell (CVE-2026-109618/109619) is fixed in 8.4.27+; refuse to build anything older.
RUN apt-get update \
    && apt-get install -y --no-install-recommends libicu-dev libzip-dev libpng-dev libjpeg-dev libwebp-dev unzip git \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install intl pdo_mysql mysqli gd \
    && rm -rf /var/lib/apt/lists/* \
    && a2enmod rewrite headers

RUN php -r 'exit(version_compare(PHP_VERSION, "8.4.27", ">=") ? 0 : 1);' \
    || (echo "PHP < 8.4.27 is vulnerable to php2shell" && exit 1)

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-shopease.ini
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN printf 'ServerTokens Prod\nServerSignature Off\nServerName localhost\n' > /etc/apache2/conf-available/shopease.conf \
    && a2enconf shopease

WORKDIR /var/www/html

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
