# Réplica da produção: EL9 (AlmaLinux, equivalente ao CentOS Stream 9) + Apache + PHP (Remi)
# Versão do PHP via build-arg: docker compose build --build-arg PHP_VERSION=8.5
FROM almalinux:9
ARG PHP_VERSION=7.4

RUN dnf -y install epel-release \
 && dnf -y install https://rpms.remirepo.net/enterprise/remi-release-9.rpm \
 && dnf -y module reset php \
 && dnf -y module enable php:remi-${PHP_VERSION} \
 && dnf -y install httpd php php-fpm php-cli php-mysqlnd php-gd php-mbstring php-xml \
      php-zip php-intl php-soap php-opcache php-bcmath unzip \
 && if [ "${PHP_VERSION}" = "7.4" ]; then dnf -y install php-json; fi \
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
