FROM php:7.3-apache

#Actualizar
RUN apt-get update
#Instalar git y unzip
RUN apt-get install -y git unzip

#Instalar módulos de pdo, pdo_mysql y mysqli
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite

#Instalar composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

VOLUME /var/www/html
EXPOSE 80
