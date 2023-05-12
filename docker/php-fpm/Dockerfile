FROM php:8.1-fpm-alpine

ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions gd xdebug pdo_mysql mysqli mongodb dba

# Setting permission for SF cache issue
RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories
RUN apk --no-cache add shadow && usermod -u 1000 www-data
RUN usermod -u 1000 www-data

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
    
# RUN docker-php-ext-install pdo_mysql
# RUN docker-php-ext-install mysqli

COPY ./config/php.ini /usr/local/etc/php/

CMD ["php-fpm"]

EXPOSE 9000