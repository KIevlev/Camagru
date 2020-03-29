FROM php:7.4-fpm-alpine

ENV TIMEZONE Europe/Moscow

RUN apt-get update && \
    apt-get install -y msmtp && \ 
	apt-get install -y mysql-server && \
	docker-php-ext-install mysqli pdo pdo_mysql && \
    docker-php-ext-enable mysqli  \
#    rm -rf /var/lib/apt/lists/* && \
#    update-alternatives --install /usr/sbin/sendmail sendmail /usr/bin/msmtp 10

COPY php.ini /usr/local/etc/php/

#ADD msmtprc /etc/msmtprc
#ADD .msmtprc ~/.msmtprc
#RUN chmod 600 /etc/msmtprc
#RUN chmod 600 ~/.msmtprc



WORKDIR "/var/www"
