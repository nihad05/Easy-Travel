# Use official PHP image with necessary extensions
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory inside the container
WORKDIR /var/www

# Copy existing application code to the container
COPY . .

# Install PHP dependencies (you can comment this if you prefer to run it manually later)
RUN composer install

# Expose port 8000 (we will use artisan serve)
EXPOSE 8000

# Command to run when starting the container
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
