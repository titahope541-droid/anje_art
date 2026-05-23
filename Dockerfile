# Use the official PHP image with Apache
FROM php:8.2-apache

# Install required PHP extensions (add more as needed)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache mod_rewrite (optional, for pretty URLs)
RUN a2enmod rewrite

# Copy website files to Apache document root
COPY . /var/www/html/

# Set permissions (optional, adjust as needed)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
