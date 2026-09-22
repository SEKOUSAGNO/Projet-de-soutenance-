FROM php:8.2-apache

# Activer mod_rewrite
RUN a2enmod rewrite

# Autoriser .htaccess et définir inscription.php comme page d'accueil
RUN printf '%s\n' \
'<Directory /var/www/html>' \
'    AllowOverride All' \
'    Require all granted' \
'</Directory>' \
'DirectoryIndex inscription.php index.php index.html' \
> /etc/apache2/conf-available/siman-job.conf

# Activer la configuration Apache
RUN a2enconf siman-job

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier le projet dans Apache
COPY . /var/www/html

# Donner les bonnes permissions
RUN chown -R www-data:www-data /var/www/html

# Port Apache
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]
