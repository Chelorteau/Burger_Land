# Utilise une image officielle de PHP avec Apache
FROM php:8.1-apache

# Installe les extensions nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Active le mod_rewrite pour Apache
RUN a2enmod rewrite

# Copie tous les fichiers du projet dans le conteneur
COPY . /var/www/html

# Définit le répertoire de travail
WORKDIR /var/www/html

# Donne les permissions nécessaires aux fichiers pour Apache
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expose le port 80
EXPOSE 8000

# Commande pour lancer Apache
CMD ["apache2-foreground"]
