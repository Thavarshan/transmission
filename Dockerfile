FROM php:8.0-apache
WORKDIR /var/www/html
COPY . .
RUN ./sail up -d
