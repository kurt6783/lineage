FROM php:7.4.9-fpm

ENV PHP_FPM_LOCATION="/usr/local/etc"
ENV PHP_INI_LOCATION="/usr/local/etc/php"
#ENV TIMEZONE="Asia/Taipei"
ENV PHP_MEMORY_LIMIT="128M"
ENV MAX_UPLOAD="50M"
ENV PHP_MAX_FILE_UPLOAD="50"
ENV PHP_MAX_POST="100M"
ENV COMPOSER_ALLOW_SUPERUSER="1"
ENV MAX_INPUT_TIME="180"
ENV MAX_EXECUTION_TIME="180"
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"
ENV PHP_OPCACHE_MAX_ACCELERATED_FILES="10000"
ENV PHP_OPCACHE_MEMORY_CONSUMPTION="192"
ENV PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

RUN apt-get update -y && apt-get upgrade -y
RUN apt-get install -y --no-install-recommends libc-client-dev libkrb5-dev libcurl4-openssl-dev pkg-config  libfreetype6-dev libjpeg62-turbo-dev libpng-dev libmagickwand-dev libmcrypt-dev libmemcached-dev zlib1g-dev libenchant-dev libtidy-dev libxslt1-dev libzip-dev libsnmp-dev libperl-dev libssl-dev libpng-dev libjpeg-dev libpq-dev

#WKHTML
RUN apt-get install -y --no-install-recommends wkhtmltopdf

RUN rm -r /var/lib/apt/lists/*

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && \
    docker-php-ext-configure opcache --enable-opcache

RUN docker-php-ext-install -j$(nproc) \
    curl ftp exif json session \
    gettext bcmath calendar exif sockets \
    bz2 enchant soap xmlrpc tidy xsl \
    zip snmp pgsql pdo_pgsql intl\
    tokenizer fileinfo iconv phar posix imap opcache

RUN pecl install mcrypt-1.0.3 && docker-php-ext-enable mcrypt
RUN pecl install redis-4.0.1 && docker-php-ext-enable redis
RUN export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" && \
    pecl install imagick-3.4.4 && docker-php-ext-enable imagick
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer
RUN pecl install xdebug && docker-php-ext-enable xdebug
# COPY xdebug.ini ${PHP_INI_LOCATION}/php/conf.d/docker-php-ext-xdebug.ini

#TIMEZONE
#RUN echo "${TIMEZONE}" > /etc/timezone

#PHP INI SETTING
RUN cp -Rp ${PHP_INI_LOCATION}/php.ini-production ${PHP_INI_LOCATION}/php.ini && \
    sed -i "s|;*date.timezone =.*|date.timezone = ${TIMEZONE}|i" ${PHP_INI_LOCATION}/php.ini && \
    sed -i "s|;*memory_limit =.*|memory_limit = ${PHP_MEMORY_LIMIT}|i" ${PHP_INI_LOCATION}/php.ini && \
    sed -i "s|;*upload_max_filesize =.*|upload_max_filesize = ${MAX_UPLOAD}|i" ${PHP_INI_LOCATION}/php.ini && \
    sed -i "s|;*max_file_uploads =.*|max_file_uploads = ${PHP_MAX_FILE_UPLOAD}|i" ${PHP_INI_LOCATION}/php.ini && \
    sed -i "s|;*post_max_size =.*|post_max_size = ${PHP_MAX_POST}|i" ${PHP_INI_LOCATION}/php.ini && \
    sed -i "s|;*cgi.fix_pathinfo=.*|cgi.fix_pathinfo= 0|i" ${PHP_INI_LOCATION}/php.ini && \
    sed -i "s|;*max_input_time =.*|max_input_time = ${MAX_INPUT_TIME}|i" ${PHP_INI_LOCATION}/php.ini && \
    sed -i "s|;*max_execution_time =.*|max_execution_time = ${MAX_EXECUTION_TIME}|i" ${PHP_INI_LOCATION}/php.ini

#ENABLE OPCAHCE
# RUN echo 'opcache.enable=1 \n\
RUN echo 'opcache.enable=0 \n\
opcache.revalidate_freq=0 \n\
opcache.validate_timestamps=${PHP_OPCACHE_VALIDATE_TIMESTAMPS} \n\
opcache.max_accelerated_files=${PHP_OPCACHE_MAX_ACCELERATED_FILES} \n\
opcache.memory_consumption=${PHP_OPCACHE_MEMORY_CONSUMPTION} \n\
opcache.max_wasted_percentage=${PHP_OPCACHE_MAX_WASTED_PERCENTAGE} \n\
opcache.interned_strings_buffer=16 \n\
opcache.fast_shutdown=1 \n\
opcache.load_comments=Off \n\
opcache.save_comments=Off' > ${PHP_INI_LOCATION}/conf.d/docker-php-ext-opcache-recommend.ini

#SETUP PHP FPM WWW CONF
RUN mv ${PHP_FPM_LOCATION}/php-fpm.d/www.conf ${PHP_FPM_LOCATION}/php-fpm.d/www.conf.bak
RUN touch ${PHP_FPM_LOCATION}/php-fpm.d/www.conf
RUN echo '[www] \n\
user = www-data \n\
group = www-data \n\
listen = 127.0.0.1:9000 \n\
listen.owner = www-data \n\
listen.group = www-data \n\
listen.mode = 0660 \n\
pm = static \n\
pm.max_children = 30 \n\
pm.max_requests = 100 \n\
request_terminate_timeout = 0 \n\
listen.backlog = 1024' > ${PHP_FPM_LOCATION}/php-fpm.d/www.conf

EXPOSE 9000

LABEL Author="KK HSIAO"
LABEL Version="1.0.0-PHP-FPM-7.4"
LABEL Description="PHP FPM 7.4. All extensions."