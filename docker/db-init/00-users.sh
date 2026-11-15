#!/bin/bash
# Roda antes do dump (ordem alfabética). O dump tem views com DEFINER `pt`@`localhost`,
# mas o usuário criado pela imagem é `pt`@`%`; sem isso as views falham ao serem consultadas.
mariadb -uroot -p"${MARIADB_ROOT_PASSWORD}" <<SQL
CREATE USER IF NOT EXISTS 'pt'@'localhost' IDENTIFIED BY '${MARIADB_PASSWORD}';
GRANT ALL PRIVILEGES ON \`${MARIADB_DATABASE}\`.* TO 'pt'@'localhost';
SQL
