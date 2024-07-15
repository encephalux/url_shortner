FROM ubuntu
ENV DEBIAN_FRONTEND noninteractive
SHELL ["/usr/bin/bash", "-c"]
RUN apt-get update && \
    apt-get install -y curl gpg software-properties-common && \
    add-apt-repository ppa:ondrej/php && apt-get update &&\
    apt-get install -y php php-mysql php-curl php-common mariadb-client mariadb-server
VOLUME ["/var/www/html/"]
EXPOSE 80