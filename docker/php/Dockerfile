FROM php:8.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev pkg-config \
    libjpeg-dev libfreetype6-dev libzip-dev default-mysql-client \
    nano procps htop cron supervisor

RUN pecl install pcov-1.0.12

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli bcmath mbstring zip exif pcntl \
    && docker-php-ext-enable opcache pcov
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./cronjob /etc/cron.d/cron
RUN chmod 0644 /etc/cron.d/cron && crontab /etc/cron.d/cron

COPY ./supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

ENTRYPOINT ["/var/www/docker/php/entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm"]
