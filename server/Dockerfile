FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions (for MySQL, etc.)
RUN docker-php-ext-install pdo pdo_mysql

# Copy backend source code
COPY ./ /var/www/html/

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
