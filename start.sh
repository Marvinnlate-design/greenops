#!/bin/bash

echo "=== Vérification des dossiers de cache ==="

# Créer tous les dossiers nécessaires
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Forcer les permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Afficher l'arborescence pour debug
ls -la /var/www/html/storage/framework/
ls -la /var/www/html/bootstrap/

echo "=== Nettoyage du cache Laravel ==="
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "=== Recréation du cache de vues ==="
php artisan view:cache || echo "View cache generation failed, but continuing..."

echo "=== Démarrage du serveur ==="
php artisan serve --host=0.0.0.0 --port=8000