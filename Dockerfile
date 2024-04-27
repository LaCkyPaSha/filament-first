#FROM php:8.2
#
#ENV COMPOSER_ALLOW_SUPERUSER 1
#
#RUN apt-get update -y && apt-get install -y openssl zip unzip git libonig-dev zlib1g-dev
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#RUN docker-php-ext-install pdo mbstring
#WORKDIR /app
#COPY . /app
#COPY start.sh /app
#RUN composer install --ignore-platform-req=ext-intl --ignore-platform-req=ext-zip
#
##CMD ["/app/start.sh"]
#
## Make start.sh executable
#RUN chmod +x /app/start.sh
#
## Set the entrypoint to start.sh
#ENTRYPOINT ["/app/start.sh"]
#//////////////////////////////////////////////
#CMD php artisan serve --host=0.0.0.0 --port=8181
#EXPOSE 8181

#FROM php:8.2
#RUN apt-get update && apt-get install -y libicu-dev zlib1g-dev
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#RUN docker-php-ext-install intl zip
#WORKDIR /app
#COPY . /app
#RUN composer install
#
#CMD php artisan serve --host=0.0.0.0 --port=8181
#EXPOSE 8181
#/////////////////////////////////////////////////////////

#FROM php:8.2
#
## Set environment variable to allow Composer to run as superuser
#ENV COMPOSER_ALLOW_SUPERUSER 1
#
## Install required packages
#RUN apt-get update -y && apt-get install -y openssl zip unzip git libonig-dev zlib1g-dev
#
## Install Composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#
## Install PHP extensions
#RUN docker-php-ext-install pdo mbstring
#
## Set working directory
#WORKDIR /app
#
## Copy application files
#COPY . /app
#
## Install dependencies using Composer
#RUN composer install --ignore-platform-reqs
#
## Copy start.sh script and make it executable
#COPY start.sh /app/start.sh
#RUN chmod +x /app/start.sh
#
#CMD ["/app/start.sh"]
#//////////////////////////////////////////////////////////
# Set the entrypoint to start.sh
#ENTRYPOINT ["/app/start.sh"]

#CMD php artisan serve --host=0.0.0.0 --port=8181
#
## Expose port 8000
#EXPOSE 8181
#///////////////////////////////////////////////////////////

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
