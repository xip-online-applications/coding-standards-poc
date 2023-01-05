FROM ghcr.io/xip-online-applications/php-docker-containers/php:8.1-fpm AS base

COPY --from=ghcr.io/xip-online-applications/php-docker-containers/php-extra-composer:8.1 /opt /opt
COPY --from=ghcr.io/xip-online-applications/php-docker-containers/php-extra-opcache:8.1 /opt /opt
COPY --from=ghcr.io/xip-online-applications/php-docker-containers/php-extra-mysql:8.1 /opt /opt
COPY --from=ghcr.io/xip-online-applications/php-docker-containers/php-extra-redis:8.1 /opt /opt
COPY --from=ghcr.io/xip-online-applications/php-docker-containers/php-extra-amqp:8.1 /opt /opt
COPY --from=ghcr.io/xip-online-applications/php-docker-containers/php-extra-dev:8.1 /opt /opt

FROM base AS build

COPY --chown=www-data:www-data composer.json composer.lock symfony.lock .env ./

ARG COMPOSER_AUTH=""
RUN bash -c "COMPOSER_AUTH='$COMPOSER_AUTH' composer install --optimize-autoloader --no-interaction --no-scripts"

COPY --chown=www-data:www-data ./ ./
