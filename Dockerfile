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
#///////////////////////////////////////////////
#FROM php:8.2
#RUN apt-get update && apt-get install -y libicu-dev zlib1g-dev
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
##RUN docker-php-ext-install intl zip
#WORKDIR /app
#COPY . /app
#RUN composer install
#
#CMD php artisan serve --host=0.0.0.0 --port=8181
#EXPOSE 8181
#/////////////////////////////////////////////////////////
# Stage 1: Composer dependencies installation
#FROM php:8.2 AS composer
#
#RUN apt-get update && apt-get install -y \
#    libicu-dev \
#    zlib1g-dev \
#    && rm -rf /var/lib/apt/lists/*
#
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#
#WORKDIR /app
#
## Copy only the composer files
#COPY composer.json composer.lock ./
#
## Install Composer dependencies. If it fails, output a message but do not fail the build.
#RUN composer install || echo "Composer install failed. Continuing without dependencies."

# Stage 2: Application runtime
#FROM php:8.2
#
#RUN apt-get update && apt-get install -y \
#    libicu-dev \
#    zlib1g-dev \
#    && rm -rf /var/lib/apt/lists/*
#
##//////////////////
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
##//////////////////
#
#WORKDIR /app
#
##//////////////////////////////
#
## Copy only the composer files
#COPY composer.json composer.lock ./
#
## Install Composer dependencies. If it fails, output a message but do not fail the build.
#RUN composer install || echo "Composer install failed. Continuing without dependencies."
##////////////////////////////////////
#
### Copy the application code from the composer stage
##COPY --from=composer /app /app
#
##/////////////////////////////////////
#
## Copy the rest of the application code
#COPY . .
#
## Your remaining Dockerfile commands
#CMD php artisan serve --host=0.0.0.0 --port=8181
#EXPOSE 8181
#///////////////////////////////

## Use an offici# Use an official PHP image as base
#FROM php:8.2
#
## Update package lists and install necessary dependencies
#RUN apt-get update && apt-get install -y \
#   libicu-dev \
#   zlib1g-dev \
#   && rm -rf /var/lib/apt/lists/*
#
## Install Composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#
## Set working directory within the container
#WORKDIR /app
#
## Copy the composer files and install dependencies
#COPY composer.json composer.lock ./
#RUN php -d memory_limit=-1 /usr/local/bin/composer install --no-scripts --no-autoloader
#
## Clear Composer cache
#RUN php -d memory_limit=-1 /usr/local/bin/composer clear-cache
#
## Copy the rest of the application code
#COPY . .
#
## Generate autoload files
#RUN php -d memory_limit=-1 /usr/local/bin/composer dump-autoload --optimize
#
## Your remaining Dockerfile commands
#CMD php artisan serve --host=0.0.0.0 --port=8181
#EXPOSE 8181


#/////////////////////////////////////////////////////////

#FROM php:8.2
#
#ENV DOCKER_BUILDKIT=1
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
#//////////////////////////////////////////////////////////

#FROM php:8.2
#RUN apt-get update -y && apt-get install -y openssl zip unzip git
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#RUN docker-php-ext-install pdo mbstring
#WORKDIR /app
#COPY . /app
#RUN composer install
#
#CMD php artisan serve --host=0.0.0.0 --port=8181
#EXPOSE 8181

#//////////////////////////////////////////

# Use an official PHP image as base
#FROM php:8.2-fpm
#ARG user
#ARG uid
#
## Install dependencies
#RUN apt update && apt install -y \
#    git \
#    curl \
#    libpng-dev \
#    libonig-dev \
#    libxml2-dev \
#    && apt clean && rm -rf /var/lib/apt/lists/*
#
## Install PHP extensions
#RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
#
## Create Composer configuration directory and set permissions
#RUN mkdir -p /home/$user/.composer && \
#    chown -R $user:$user /home/$user
#
## Set working directory
#WORKDIR /var/www
#
## Clear Composer cache
#RUN #php -d memory_limit=-1 composer clear-cache
#
## Switch to non-root user
#USER $user
#
#COPY start.sh /app/start.sh
#RUN chmod +x /app/start.sh
#ENTRYPOINT ["/app/start.sh"]
#/////////////////////////////////////////

#FROM alpine:latest
## Install SQLite
#RUN apk --no-cache add sqlite
## Create a directory to store the database
#WORKDIR /db
## Copy your SQLite database file into the container
#COPY initial-db.sqlite /db/
## Expose the port if needed
## EXPOSE 1433
## Command to run when the container starts
#CMD ["sqlite3", "/data/initial-db.sqlite"]

#///////////////////////////////////////////////////

FROM php:8.2-fpm
#WORKDIR /var/www
#
#COPY start.sh /html/start.sh

WORKDIR /var/www/html

RUN apt-get update -y && apt-get install -y \
    libicu-dev \
    unzip zip \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    sqlite3 \
    libsqlite3-dev \
    libzip-dev

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install gettext intl pdo_sqlite gd zip

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /app
COPY . /app
RUN composer install

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY start.sh /app/start.sh
RUN chmod +x /app/start.sh
#RUN sed -i 's/\r$//' /app/start.sh
#ENTRYPOINT ["/app/start.sh"]
CMD ["/app/start.sh"]

#CMD php artisan serve --host=0.0.0.0 --port=9000
#
#EXPOSE 9000
