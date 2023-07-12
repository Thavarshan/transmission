FROM php:8.0-apache
WORKDIR /var/www/html
COPY . .
COPY --from=vendor /app/vendor/ vendor/
RUN chown -R www-data:www-data /var/www/html/storage
RUN a2enmod rewrite
RUN service apache2 restart
RUN ./vendor/bin/sail up -d
