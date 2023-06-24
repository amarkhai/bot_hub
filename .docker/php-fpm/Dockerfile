FROM php:8.2-fpm as php-fpm-dev
WORKDIR /var/www/app
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    vim
# Clear cache to reduce the size of the image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN pecl install -o -f xdebug \
    && docker-php-ext-configure zip \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo_pgsql mbstring zip \
    && rm -rf /tmp/pear
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

FROM php:8.2-fpm as php-fpm-prod
WORKDIR /var/www/app
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    libpq-dev \
    unzip \
    git \
    vim
# Clear cache to reduce the size of the image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-configure zip \
    && docker-php-ext-install pdo_pgsql mbstring zip \
    && rm -rf /tmp/pear
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
