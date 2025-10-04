# Étape 1 : PHP avec Composer
FROM php:8.2-fpm

# Installer extensions nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www

# Copier les fichiers
COPY . .

# Installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Donner les droits aux dossiers storage et bootstrap
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exposer le port
EXPOSE 8000

# Lancer Laravel avec artisan
CMD php artisan serve --host=0.0.0.0 --port=8000
