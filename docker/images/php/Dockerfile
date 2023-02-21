FROM php:8.2.0-apache-buster

ARG DEBIAN_FRONTEND=noninteractive
ARG user
ARG uid

# Update
RUN apt-get -y update --fix-missing && \
    apt-get upgrade -y && \
    apt-get --no-install-recommends install -y apt-utils apt-transport-https && \
    rm -rf /var/lib/apt/lists/*

# Install useful tools and install important libaries
RUN apt-get -y update && \
    apt-get -y --no-install-recommends install nano wget \
    dialog && \
    apt-get -y --no-install-recommends install default-mysql-client \
    zlib1g-dev \
    libzip-dev \
    libicu-dev && \
    apt-get -y --no-install-recommends install --fix-missing apt-utils \
    build-essential \
    git \
    gnupg2 \
    curl \
    iputils-ping \
    libonig-dev && \
    apt-get -y --no-install-recommends install --fix-missing libcurl4 \
    libcurl4-openssl-dev \
    zip \
    unzip \
    libxml2-dev \
    libssh2-1-dev \
    libssh2-1 \
    libxslt-dev \
    openssl && \
    rm -rf /var/lib/apt/lists/* && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install xdebug
RUN pecl install xdebug-3.2.0 && docker-php-ext-enable xdebug

# SSH2 extensions
RUN pecl install ssh2-1.3.1 && \
    docker-php-ext-enable ssh2

# SAMBA extension
RUN apt-get update && \
    apt install -y procps smbclient libsmbclient-dev && \
    pecl install smbclient && \
    pecl install xmlrpc-beta && \
    docker-php-ext-enable smbclient && \
    echo "extension=smbclient.so" > /usr/local/etc/php/conf.d/docker-php-ext-smbclient.ini

# Install other extensions
RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-install mysqli && \
    docker-php-ext-install curl && \
    docker-php-ext-install zip && \
    docker-php-ext-install -j$(nproc) intl && \
    docker-php-ext-install mbstring && \
    docker-php-ext-install gettext && \
    docker-php-ext-install calendar && \
    docker-php-ext-install exif && \
    docker-php-ext-install calendar && \
    docker-php-ext-install pcntl && \
    docker-php-ext-install intl && \
    docker-php-ext-install dom && \
    docker-php-ext-install xsl && \
    docker-php-ext-install simplexml && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install sockets

RUN docker-php-ext-enable simplexml
# Enable apache modules
RUN a2enmod rewrite headers

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install supervisor
RUN apt-get update && apt-get install -y \
    supervisor

# Clean up the image.
RUN apt-get clean && apt-get autoremove

# Set host user as working user
RUN useradd -G www-data,root -u $uid -d /home/$user $user
WORKDIR /var/www/html
USER $user

COPY /misc/php.ini /usr/local/etc/php/conf.d/php.ini

CMD ["/usr/bin/supervisord"]