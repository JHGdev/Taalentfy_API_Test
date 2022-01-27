ARG php_version

FROM php:${php_version}

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list 
    
RUN apt-get -y update
#RUN apt-get update && apt-get install -y --fix-missing \
#    mariadb-client \

RUN curl -L -C - --progress-bar -o /usr/local/bin/composer https://getcomposer.org/composer.phar
RUN chmod 755 /usr/local/bin/composer
RUN apt-get install -y git zip unzip
RUN apt-get install -y symfony-cli
RUN docker-php-ext-install mysqli \
    pdo_mysql
RUN a2enmod rewrite
RUN echo "date.timezone=UTC" >> /usr/local/etc/php/conf.d/timezone.ini
# Clean repository
RUN apt-get clean && rm -rf /var/lib/apt/lists/*