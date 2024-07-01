FROM php:8.2-apache

# dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip exif

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

# permissions
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY apache.conf /etc/apache2/sites-available/000-default.conf

# composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

EXPOSE 80
