#!/usr/bin/env bash
set -eux

# Installer PHP et extensions nécessaires
apt-get update && \
apt-get install -y php-cli php-mbstring php-xml php-bcmath php-curl unzip curl

# Lancer Laravel
php artisan serve --host 0.0.0.0 --port $PORT
