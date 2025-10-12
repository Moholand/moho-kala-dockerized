# Use the official PHP image with the required extensions
FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory permissions
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy the PHP configuration file
# COPY ./php.ini /usr/local/etc/php/conf.d/

# Set the correct permissions for Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# RUN chown -R www-data:www-data /var/www/storage
# RUN chmod -R 775 /var/www/storage

# Expose the port the app runs on
EXPOSE 9000

# Start the PHP FastCGI Process Manager
CMD ["php-fpm"]
