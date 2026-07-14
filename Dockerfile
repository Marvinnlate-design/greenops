# --- Étape 1 : Build des assets Node/Vite ---
FROM node:20 AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# --- Étape 2 : Image PHP de production ---
FROM php:8.2-apache

# Installation des dépendances système et des extensions PHP requises par Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && a2enmod rewrite

# Installation de Composer
COPY --from:composer:latest /usr/bin/composer /usr/bin/composer

# Configuration du DocumentRoot d'Apache sur le dossier /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copie du code de l'application
WORKDIR /var/www/html
COPY . .

# Copie des assets compilés par Node à l'étape 1
COPY --from=node-builder /app/public/build ./public/build

# Installation des dépendances PHP de production
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Configuration des permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configuration du port de Render (Apache écoute par défaut sur le port 80, mais Render va injecter la variable PORT)
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf

EXPOSE 80

CMD ["apache2-foreground"]