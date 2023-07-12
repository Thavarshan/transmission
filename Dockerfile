FROM php:8.0-apache
WORKDIR /var/www/html
COPY . .
RUN ./vendor/bin/sail up -d
