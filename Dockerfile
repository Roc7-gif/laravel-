# Étape 1 : PHP avec Composer
FROM php:8.2-fpm

# Installer extensions nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www

# Copier les fichiers
COPY . .

# Installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Copier le fichier .env si tu ne veux pas le gérer via Render
# COPY .env .env
# Sinon, configure les variables via l’onglet Environment de Render

# Générer la clé Laravel
RUN php artisan key:generate

# Donner les droits aux dossiers storage et bootstrap
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port attendu par Render
EXPOSE 10000  
# Render détecte automatiquement le PORT

# Lancer Laravel en utilisant le port dynamique fourni par Render
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
