FROM php:8.0-apache

# Habilita o mod_rewrite do Apache (necessário para URLs amigáveis)
RUN a2enmod rewrite

# Instala dependências do sistema necessárias para compilar a extensão MongoDB
# e para o Composer conseguir extrair pacotes (unzip)
RUN apt-get update && apt-get install -y \
    libssl-dev \
    libsasl2-dev \
    pkg-config \
    libzip-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Compila e instala a extensão MongoDB diretamente do código-fonte (contorna
# instabilidades do servidor PECL)
RUN git clone -b 1.19.3 https://github.com/mongodb/mongo-php-driver.git /tmp/mongo-php-driver \
    && cd /tmp/mongo-php-driver \
    && git submodule update --init \
    && phpize \
    && ./configure \
    && make -j$(nproc) \
    && make install \
    && docker-php-ext-enable mongodb \
    && rm -rf /tmp/mongo-php-driver

# Instala a extensão zip do PHP (necessária para o Composer)
RUN docker-php-ext-install zip

# Instala o Composer (gerenciador de dependências PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define a pasta pública como raiz do Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
