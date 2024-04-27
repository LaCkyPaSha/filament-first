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

# Use an official PHP image as base
FROM php:8.2

# Update package lists and install necessary dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory within the container
WORKDIR /app

# Copy the composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the application code
COPY . .

# Generate autoload files
RUN composer dump-autoload --optimize

# Your remaining Dockerfile commands
CMD php artisan serve --host=0.0.0.0 --port=8181
EXPOSE 8181


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
