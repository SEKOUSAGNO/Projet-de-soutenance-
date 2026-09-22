FROM php:8.2-apache

RUN printf '<Directory /var/www/html>\n AllowOverride All\n Require all granted\n</Directory>\n'
