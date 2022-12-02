FROM xiponlineapplications/php-extra-composer:8.1 as composer
FROM xiponlineapplications/php-extra-opcache:8.1 as opcache
FROM xiponlineapplications/php-extra-mysql:8.1 as mysql
FROM xiponlineapplications/php-extra-redis:8.1 as redis
FROM xiponlineapplications/php-extra-amqp:8.1 as amqp
FROM xiponlineapplications/php-extra-dev:8.1 as dev

FROM xiponlineapplications/php:8.1.4-fpm AS base

COPY --from=composer /opt /opt
COPY --from=opcache /opt /opt
COPY --from=mysql /opt /opt
COPY --from=redis /opt /opt
COPY --from=amqp /opt /opt
COPY --from=dev /opt /opt

FROM base AS build

COPY --chown=www-data:www-data composer.json composer.lock symfony.lock .env ./

ARG COMPOSER_AUTH=""
RUN bash -c "COMPOSER_AUTH='$COMPOSER_AUTH' composer install --optimize-autoloader --no-interaction --no-scripts"

COPY --chown=www-data:www-data ./ ./
