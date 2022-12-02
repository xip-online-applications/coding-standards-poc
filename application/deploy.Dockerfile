FROM xiponlineapplications/php-extra-prod:8.1 as prod

FROM php

COPY --from=prod /opt /opt

ARG COMPOSER_AUTH=""
RUN bash -c "COMPOSER_AUTH='$COMPOSER_AUTH' composer install --no-dev --optimize-autoloader --no-interaction --no-scripts"
RUN composer dump-env prod \
    && bin/console cache:warmup --env=prod
