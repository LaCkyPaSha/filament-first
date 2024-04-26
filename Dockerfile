FROM php:8.2-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libsqlite3-dev \
    libonig-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Manually install pdo_sqlite extension
RUN docker-php-source extract \
    && cd /usr/src/php/ext/pdo_sqlite \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && docker-php-ext-enable pdo_sqlite \
    && docker-php-source delete

# Install other PHP extensions
#RUN docker-php-ext-install mbstring zip exif pcntl

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www \
    && useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 8000 and start php-fpm server
EXPOSE 8000
CMD ["php-fpm"]

#FROM php:8.2-fpm
#ARG user
#ARG uid
#RUN apt update && apt install -y \
#    git \
#    curl \
#    libpng-dev \
#    libonig-dev \
#    libxml2-dev
#RUN apt clean && rm -rf /var/lib/apt/lists/*
#RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#RUN useradd -G www-data,root -u $uid -d /home/$user $user
#RUN mkdir -p /home/$user/.composer && \
#    chown -R $user:$user /home/$user
#WORKDIR /var/www
#USER $user

#FROM richarvey/nginx-php-fpm:1.7.2
#
#COPY . .
#
## Image config
#ENV SKIP_COMPOSER 1
#ENV WEBROOT /var/www/html/public
#ENV PHP_ERRORS_STDERR 1
#ENV RUN_SCRIPTS 1
#ENV REAL_IP_HEADER 1
#
## Laravel config
#ENV APP_ENV production
#ENV APP_DEBUG false
#ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]
