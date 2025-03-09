# Utiliser l'image PHP officielle avec Apache
FROM php:8.2-apache

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libicu-dev zip unzip git curl libonig-dev \
    && docker-php-ext-install pdo pdo_mysql intl

# Installer Composer depuis l'image officielle Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail dans le conteneur
WORKDIR /var/www/html

# Copier les fichiers du projet local vers le conteneur
COPY . /var/www/html

# Donner les bons droits aux fichiers dans le conteneur
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/var

# Exposer le port 81 pour Apache
EXPOSE 81

# Commande par défaut pour démarrer Apache
CMD ["apache2-foreground"]
