#!/bin/bash

# Exécuter les migrations si le flag n'existe pas
if [ ! -f /var/www/html/storage/migrated.lock ]; then
    echo "Exécution des migrations..."
    php artisan migrate --force
    php artisan db:seed --class=SensorActuatorSeeder --force
    php artisan sensors:simulate --count=30
    touch /var/www/html/storage/migrated.lock
    echo "Migrations terminées."
else
    echo "Migrations déjà exécutées."
fi

# Lancer le serveur
php artisan serve --host=0.0.0.0 --port=8000