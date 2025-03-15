FROM php:8.2-apache

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les extensions PHP nécessaires pour Symfony
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev unzip libicu-dev libonig-dev git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql intl mbstring

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Activation de mod_rewrite pour Symfony
RUN a2enmod rewrite

# Modifier le DocumentRoot d'Apache pour pointer vers le dossier public
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Copier les fichiers de l'application dans le conteneur
COPY . /var/www/html

# Attribuer les permissions à www-data
RUN chown -R www-data:www-data /var/www/html

# Exposer le port 80
EXPOSE 80

CMD ["apache2-foreground"]
