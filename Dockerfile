FROM php:8.2-fpm
ARG user
ARG uid
RUN apt update && apt install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev
RUN apt clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user
WORKDIR /var/www
USER $user

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
