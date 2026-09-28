FROM php:8.2-apache

# Installer les dépendances PostgreSQL
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Configuration Apache
RUN printf '<Directory /var/www/html>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/siman-job.conf

RUN a2enconf siman-job

# Répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet
COPY . /var/www/html

# Donner les droits à Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]

# Port Apache
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]
