# Use a imagem oficial do PHP
FROM php:8.2-fpm

# Instale dependências do sistema
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    unixodbc-dev \
    gcc \
    g++ \
    make \
    curl \
    gnupg \
    && apt-get clean

# Adicione o repositório do Microsoft ODBC Driver
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql17

# Instale extensões PHP
RUN docker-php-ext-install zip pdo pdo_mysql

# Instale a extensão pdo_sqlsrv via PECL
RUN pecl install pdo_sqlsrv \
    && docker-php-ext-enable pdo_sqlsrv

# Instale o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Defina o diretório de trabalho
WORKDIR /var/www

# Copie o código do projeto
COPY . .

# Instale as dependências do Laravel
RUN composer install

# Expõe a porta padrão para o PHP-FPM
EXPOSE 9000

# Comando para iniciar o PHP-FPM
CMD ["php-fpm"]