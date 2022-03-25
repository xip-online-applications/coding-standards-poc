FROM php:8.1-fpm

# Install additional tools
RUN rm /etc/apt/preferences.d/no-debian-php && \
    apt-get update && apt-get install -y \
    openssl g++ libzip-dev git zlib1g-dev libicu-dev libcurl3-dev zip unzip libpq-dev libonig-dev wget gpg \
    libxml2-dev librabbitmq-dev

# Install php plugins
RUN docker-php-ext-configure intl \
	&& docker-php-ext-install \
		mbstring pdo pdo_mysql zip opcache curl intl xml simplexml

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Install XDebug (easy debugging & code coverage)
RUN if [ ! "$LIVE" ]; then yes | pecl install xdebug && docker-php-ext-enable xdebug; fi

# Install AMQP
RUN docker-php-source extract \
    && git clone --branch master --depth 1 https://github.com/php-amqp/php-amqp.git /usr/src/php/ext/amqp \
    && cd /usr/src/php/ext/amqp && git submodule update --init \
    && docker-php-ext-install amqp

# Add config files
COPY php.ini /usr/local/etc/php/php.ini
COPY www.conf /usr/local/etc/php-fpm.d/www.conf

COPY xdebug.ini /tmp/php-dev-ext/docker-php-ext-xdebug.ini

RUN if [ ! "$LIVE" ]; then cp /tmp/php-dev-ext/* /usr/local/etc/php/conf.d/; fi

RUN wget -O phive.phar https://phar.io/releases/phive.phar
RUN wget -O phive.phar.asc https://phar.io/releases/phive.phar.asc
RUN gpg --keyserver hkps://keys.openpgp.org --recv-keys 0x9D8A98B29B2D5D79
RUN gpg --verify phive.phar.asc phive.phar
RUN chmod +x phive.phar
RUN mv phive.phar /usr/local/bin/phive

# Setup workdir and user
WORKDIR /var/www/application
RUN chown -R www-data:www-data /var/www

# Add access for default system user, so there's no issues with file permissions
ARG PUID
RUN if [ "$PUID" ]; then usermod -u $PUID www-data; fi
USER www-data