FROM php:8.4-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    libpq-dev

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Installer les extensions PHP (avec PostgreSQL)
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql gd

# Ignorer les avis de sécurité et installer
RUN composer config --global audit.block-insecure false \
    && composer install --no-dev --optimize-autoloader

# Installer et compiler Vite
RUN npm install && npm run build

# Créer les dossiers nécessaires
RUN mkdir -p /var/www/html/storage/framework/{cache,sessions,views} \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap

# ⬇️ MIGRATION (une seule fois) ⬇️
RUN php artisan migrate --force

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000