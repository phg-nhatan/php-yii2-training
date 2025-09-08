FROM php:7.4-cli

# Các thư viện & extension cho Yii2 trên PHP 7.4
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql intl mbstring zip gd \
    && rm -rf /var/lib/apt/lists/*

# Composer 2 (yêu cầu PHP >= 7.2.5 — ok với 7.4)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
