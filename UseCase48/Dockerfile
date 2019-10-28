FROM neighborhoods/php-fpm-phalcon:php7.3_phalcon3.4_newrelic
RUN apt-get update -y && apt-get install -y unzip procps

# Install the PHP pdo_pgsql extention
RUN docker-php-ext-install pdo_pgsql

## Install Redis
RUN pecl install redis-3.1.4 && \
    docker-php-ext-enable redis

## Install Opcache
RUN docker-php-ext-install opcache && \
    docker-php-ext-enable opcache

# Install composer and add its bin to the PATH.
RUN curl -s http://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

#RUN apt-get update -y && apt-get install -y unzip procps
ARG PROJECT_NAME=kojo_fitness

# COMPOSER_TOKEN can also be passed via the COMPOSER_GITHUB_TOKEN file
ARG COMPOSER_TOKEN=placeholder_token_you_must_replace_via_args_in_compose_file
ARG INSTALL_XDEBUG=false
ARG COMPOSER_INSTALL=true

ENV PROJECT_DIR=/var/www/html/${PROJECT_NAME}.neighborhoods.com
ENV IS_DOCKER=1

RUN usermod -u 1000 www-data
RUN mkdir -p $PROJECT_DIR
WORKDIR $PROJECT_DIR

COPY . $PROJECT_DIR

# Copy xdebug configration for remote debugging
COPY docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN bash docker/build.sh \
    --xdebug ${INSTALL_XDEBUG} \
    --composer-install ${COMPOSER_INSTALL}

ENTRYPOINT ["/var/www/html/kojo_fitness.neighborhoods.com/vendor/bin/kojo", "process:pool:server:start", "/var/www/html/kojo_fitness.neighborhoods.com/src/V1/Environment/"]
