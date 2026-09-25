# Production image for the OVH VPS (served behind the Cloudflare Tunnel).
# Frontend assets are compiled locally (`npm run build`) and shipped in public/build.
FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip \
    && docker-php-ext-install opcache \
    && a2enmod rewrite headers expires deflate \
    && rm -rf /var/lib/apt/lists/*

# Serve Laravel's public/ folder and honour its .htaccess.
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && printf '<Directory ${APACHE_DOCUMENT_ROOT}>\n    AllowOverride All\n    Require all granted\n</Directory>\nServerName localhost\nServerTokens Prod\nServerSignature Off\n' > /etc/apache2/conf-enabled/laravel.conf \
    && printf 'expose_php=Off\nshort_open_tag=Off\nmemory_limit=256M\nopcache.enable=1\nopcache.validate_timestamps=0\n' > /usr/local/etc/php/conf.d/production.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --no-progress

COPY . .
RUN composer dump-autoload --optimize --no-dev \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/delphina-entrypoint
RUN chmod +x /usr/local/bin/delphina-entrypoint

ENTRYPOINT ["delphina-entrypoint"]
CMD ["apache2-foreground"]
