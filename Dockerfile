FROM composer AS vendor
COPY composer.json /app
COPY composer.lock /app
RUN composer install

FROM php:8-alpine as php-deps
RUN apk --update add --no-cache ${PHPIZE_DEPS}  \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

FROM php:8-alpine as php-cli
ENV XDEBUG_MODE=coverage
WORKDIR /app
COPY . /app
COPY --from=vendor /app/vendor /app/vendor
COPY --from=php-deps $PHP_INI_DIR/conf.d $PHP_INI_DIR/conf.d
COPY --from=php-deps /usr/local/lib/php/extensions /usr/local/lib/php/extensions
ENTRYPOINT ["php", "./bin/recipe-stats-calculator.php", "recipe-calculator"]
