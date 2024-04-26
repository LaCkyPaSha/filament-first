FROM php:8.2
RUN apt-get update -y && apt-get install -y openssl zip unzip git libonig-dev zlib1g-dev
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring
WORKDIR /app
COPY . /app
RUN composer install --ignore-platform-req=ext-intl --ignore-platform-req=ext-zip

RUN ls

CMD ["filament-first/start.sh"]

#CMD php artisan serve --host=localhost --port=8000
#EXPOSE 8000

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
