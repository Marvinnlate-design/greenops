#!/usr/bin/env bash

echo "Running Composer install..."
composer install --no-dev --optimize-autoloader

echo "Running npm install..."
npm install

echo "Running npm build..."
npm run build

echo "Build completed successfully!"