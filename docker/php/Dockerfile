FROM php:8.2-apache

# Habilita o mod_rewrite do Apache (necessário para URLs amigáveis)
RUN a2enmod rewrite

# Instala extensões PHP comuns para ecommerce
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Define a pasta pública como raiz do Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
