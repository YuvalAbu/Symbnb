FROM php:7.4.2-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/symbnb

RUN pecl install apcu && pecl install xdebug

RUN apt-get update && \
    apt-get install -y \
    libpq-dev \
    libpng-dev \
    curl \
    vim \
    nano \
    wget \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && docker-php-ext-install opcache \
    && docker-php-ext-install gd

RUN docker-php-ext-enable apcu && docker-php-ext-enable xdebug

# Enable PHP 7
RUN a2enmod rewrite
RUN a2enmod php7
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY $PWD/docker/php/app.ini /usr/local/etc/php/conf.d/app.ini
COPY $PWD/docker/php/apache.conf /etc/apache2/sites-available/apache.conf

RUN a2dissite 000-default default-ssl
RUN a2ensite apache.conf

# Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'c5b9b6d368201a9db6f74e2611495f369991b72d9c8cbd3ffbc63edff210eb73d46ffbfce88669ad33695ef77dc76976') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

COPY . ${APACHE_DOCUMENT_ROOT}

WORKDIR ${APACHE_DOCUMENT_ROOT}

RUN composer install