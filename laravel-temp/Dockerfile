FROM php:8.4-fpm

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libzip-dev \
    zip \
    jpegoptim optipng pngquant gifsicle \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j$(nproc) gd zip pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy composer files
COPY composer.json composer.lock ./

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy package.json
COPY package.json ./

# Install npm dependencies
RUN npm install

# Copy app files
COPY . .

# Build frontend assets
RUN npm run build

# Create storage and cache directories
RUN mkdir -p storage/logs storage/framework/cache storage/framework/views \
    && chmod -R 775 storage bootstrap/cache

# Set proper permissions
RUN chown -R www-data:www-data /app

# Generate APP_KEY if not exists
RUN php artisan key:generate --force || true

# Expose port
EXPOSE 8000

# Start Laravel development server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
