FROM php:8.2-apache

# Habilita o mod_rewrite do Apache (necessário para URLs amigáveis)
RUN a2enmod rewrite

# Instala dependências do sistema necessárias para compilar a extensão MongoDB
RUN apt-get update && apt-get install -y \
    libssl-dev \
    libsasl2-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Instala a extensão MongoDB via PECL
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Instala o Composer (gerenciador de dependências PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define a pasta pública como raiz do Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf