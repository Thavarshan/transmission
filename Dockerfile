FROM php:8.0-apache
WORKDIR /var/www/html
COPY . .
RUN chown -R www-data:www-data /var/www/html/storage
RUN a2enmod rewrite
RUN service apache2 restart
RUN ./vendor/bin/sail up -d
