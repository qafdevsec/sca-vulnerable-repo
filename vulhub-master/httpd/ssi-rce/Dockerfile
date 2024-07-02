FROM php:7.4.26-apache

LABEL maintainer="phithon <root@leavesongs.com>"

RUN set -ex \
    && a2enmod include cgid \
    && sed -i 's/Options -Indexes/Options -Indexes +Includes/' /etc/apache2/conf-enabled/docker-php.conf
 