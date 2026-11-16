#!/bin/bash
# Backup diario do banco de dados (mysqldump + gzip), com rotacao.
#
# Le as credenciais do .env do projeto (DB_HOST, DB_NAME, DB_USER, DB_PASS),
# sem colocar a senha na linha de comando (nao aparece no "ps").
#
# Uso:    backup_db.sh
# Cron:   30 2 * * * /home/pt/public_html/subdomain/spc/scripts/backup_db.sh
# Config (variaveis de ambiente, opcionais):
#   BACKUP_DIR   destino                       (padrao: /home/pt/backups/mysql)
#   KEEP_DAYS    dias de retencao              (padrao: 14)
#   ENV_FILE     caminho do .env               (padrao: <projeto>/.env)

set -o pipefail
umask 077

BACKUP_DIR="${BACKUP_DIR:-/home/pt/backups/mysql}"
KEEP_DAYS="${KEEP_DAYS:-14}"
ENV_FILE="${ENV_FILE:-$(cd "$(dirname "$0")/.." && pwd)/.env}"
LOG="$BACKUP_DIR/backup.log"

log() { echo "$(date '+%F %T') $*" >> "$LOG"; }

[ -r "$ENV_FILE" ] || { echo "ERRO: .env nao encontrado: $ENV_FILE" >&2; exit 1; }
mkdir -p "$BACKUP_DIR" || { echo "ERRO: nao foi possivel criar $BACKUP_DIR" >&2; exit 1; }

# Le uma chave do .env (sem "source", pois a senha pode ter caracteres especiais).
getenv() {
  local v
  v=$(grep -E "^[[:space:]]*$1[[:space:]]*=" "$ENV_FILE" | tail -n1 | cut -d= -f2-)
  v="${v%$'\r'}"
  v="${v#"${v%%[![:space:]]*}"}"; v="${v%"${v##*[![:space:]]}"}"
  case "$v" in \"*\") v="${v#\"}"; v="${v%\"}";; \'*\') v="${v#\'}"; v="${v%\'}";; esac
  printf '%s' "$v"
}

DB_HOST="$(getenv DB_HOST)"; DB_HOST="${DB_HOST:-localhost}"
DB_NAME="$(getenv DB_NAME)"
DB_USER="$(getenv DB_USER)"
DB_PASS="$(getenv DB_PASS)"
[ -n "$DB_NAME" ] && [ -n "$DB_USER" ] || { log "ERRO: DB_NAME/DB_USER ausentes no .env"; exit 1; }

# Evita duas execucoes ao mesmo tempo.
exec 9>"$BACKUP_DIR/.lock"
flock -n 9 || { log "AVISO: ja existe um backup em andamento"; exit 0; }

# Credenciais em arquivo temporario (modo 600), removido ao sair.
CNF="$(mktemp)"
trap 'rm -f "$CNF" "$TMP"' EXIT
{
  echo "[client]"
  echo "user=$DB_USER"
  echo "password=\"${DB_PASS//\\/\\\\}\""
  echo "host=$DB_HOST"
} > "$CNF"

STAMP="$(date +%F_%H%M)"
OUT="$BACKUP_DIR/${DB_NAME}_${STAMP}.sql.gz"
TMP="$OUT.partial"

log "INICIO backup de $DB_NAME"
if mysqldump --defaults-extra-file="$CNF" \
     --single-transaction --quick --routines --triggers --events \
     --default-character-set=utf8mb4 "$DB_NAME" 2>>"$LOG" | gzip -9 > "$TMP"; then
  # Validacao: gzip integro e dump terminou ("Dump completed").
  if gzip -t "$TMP" 2>>"$LOG" && zcat "$TMP" | tail -n 5 | grep -q "Dump completed"; then
    mv "$TMP" "$OUT"
    log "OK $OUT ($(du -h "$OUT" | cut -f1))"
  else
    log "ERRO: arquivo gerado esta incompleto, descartado"
    exit 1
  fi
else
  log "ERRO: mysqldump falhou"
  exit 1
fi

# Rotacao: apaga apenas backups deste banco com mais de KEEP_DAYS dias.
find "$BACKUP_DIR" -maxdepth 1 -type f -name "${DB_NAME}_*.sql.gz" -mtime +"$KEEP_DAYS" \
  -print -delete 2>/dev/null | while read -r f; do log "REMOVIDO (antigo) $f"; done

exit 0
