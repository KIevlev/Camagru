FROM php:7.2.2-fpm

RUN apt-get update && \
	apt-get install -y mysql-server && \
	docker-php-ext-install mysqli pdo pdo_mysql && \
    docker-php-ext-enable mysqli


WORKDIR "/var/www"
