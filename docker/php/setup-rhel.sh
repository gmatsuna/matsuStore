#!/bin/bash
# Script de setup do ambiente PHP nativo para VM Red Hat / RHEL 9
# Não usa Docker - instala tudo diretamente no sistema operacional

set -e

echo "Instalando extensão MongoDB para PHP..."
sudo dnf install -y --enablerepo=TJSP_EPEL_EPEL_-_x86_64 php-pecl-mongodb

echo "Verificando extensão..."
php -m | grep -i mongo

echo "Instalando Composer (se ainda não existir)..."
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
fi

composer --version

echo "Setup concluído!"
