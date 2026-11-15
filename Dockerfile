# Réplica da produção: EL8 (AlmaLinux, sucessor do CentOS) + Apache + PHP 7.4 (Remi)
FROM almalinux:8

RUN dnf -y install epel-release \
 && dnf -y install https://rpms.remirepo.net/enterprise/remi-release-8.rpm \
 && dnf -y module reset php \
 && dnf -y module enable php:remi-7.4 \
 && dnf -y install httpd php php-fpm php-cli php-mysqlnd php-gd php-mbstring php-xml \
      php-zip php-json php-intl php-soap php-opcache php-bcmath unzip \
 && dnf clean all

COPY docker/php-overrides.ini /etc/php.d/99-overrides.ini
COPY docker/php-fpm-pool.conf /etc/php-fpm.d/www.conf
COPY docker/start.sh /start.sh
COPY docker/vhost.conf /etc/httpd/conf.d/zz-app.conf

# Mesmo UID do host (macOS = 501) evita problemas de permissão no bind mount
ARG APP_UID=501
RUN chmod +x /start.sh && mkdir -p /run/php-fpm \
 && usermod -u ${APP_UID} apache && groupmod -g 20 apache 2>/dev/null || true

WORKDIR /var/www/html
EXPOSE 80
CMD ["/start.sh"]
