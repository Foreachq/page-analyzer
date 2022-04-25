FROM php

ARG NODE_VERSION=16

RUN apt-get update && apt-get install -y \
    git \
    wget \
    libpq-dev \
    libzip-dev
RUN docker-php-ext-install pdo pdo_pgsql zip

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN curl -sL https://deb.nodesource.com/setup_$NODE_VERSION.x | bash -
RUN apt-get install -y nodejs

ENV CC_TEST_REPORTER_URL https://codeclimate.com/downloads/test-reporter/test-reporter-latest-linux-amd64
ENV CC_TEST_REPORTER_NAME cc-reporter
RUN wget -O ${CC_TEST_REPORTER_NAME} ${CC_TEST_REPORTER_URL} && chmod +x ${CC_TEST_REPORTER_NAME}

WORKDIR /app

COPY . /app
RUN composer install
RUN npm ci

EXPOSE 8000

CMD ["bash", "-c", "php artisan serve --host=0.0.0.0 --port=$PORT"]
