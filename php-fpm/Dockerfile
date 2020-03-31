FROM php:7.2.2-fpm

ENV TIMEZONE Europe/Moscow

RUN apt-get update && \
    apt-get install -y msmtp && \ 
	apt-get install -y mysql-server && \
    apt-get install -y vsftpd && \
	docker-php-ext-install mysqli pdo pdo_mysql && \
    docker-php-ext-enable mysqli &&\
    rm -rf /var/lib/apt/lists/* && \
    update-alternatives --install /usr/sbin/sendmail sendmail /usr/bin/msmtp 10

COPY php.ini /usr/local/etc/php/

ADD msmtprc /etc/msmtprc
#ADD .msmtprc ~/.msmtprc
RUN chmod 0644 /etc/msmtprc
#RUN chmod 600 ~/.msmtprc

EXPOSE 20-21 30000-31000

#RUN openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -keyout /etc/ssl/private/vsftpd.pem -out /etc/ssl/private/vsftpd.pem
#RUN ufw allow 20:21/tcp
#RUN ufw allow 30000:31000/tcp
#RUN ufw allow OpenSSH
#RUN ufw disable
#RUN ufw enable

COPY vsftpd.conf /etc/vsftpd.conf

RUN useradd -d /home/admin -m -s/bin/bash -p $(openssl passwd -1 admin) admin
RUN echo "admin" | tee -a /etc/vsftpd.user_list

RUN mkdir -p /home/admin/ftp/icons
RUN mkdir -p /home/admin/ftp/photos
RUN chmod 550 /home/admin/ftp
RUN chmod 750 /home/admin/ftp/icons
RUN chmod 750 /home/admin/ftp/photos
RUN chown -R admin: /home/admin/ftp
    
RUN echo -e '#!/bin/sh\necho "This account is limited to FTP access only."' | tee -a  /bin/ftponly &&\
chmod a+x /bin/ftponly   
RUN usermod admin -s /bin/ftponly

WORKDIR "/var/www"
