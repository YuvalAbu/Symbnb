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
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

# Install node
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.2/install.sh | bash
RUN bash -i -c 'nvm install 12.16.1'

COPY . ${APACHE_DOCUMENT_ROOT}

WORKDIR ${APACHE_DOCUMENT_ROOT}