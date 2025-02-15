FROM composer:latest AS composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install

FROM node:latest AS node

WORKDIR /var/www/html

COPY package.json package-lock.json ./
RUN npm install

FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

COPY . .

RUN a2enmod rewrite

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

COPY --from=composer /var/www/html/vendor /var/www/html/vendor

COPY --from=node /var/www/html/node_modules /var/www/html/node_modules

EXPOSE 80

CMD ["apache2-foreground"]
