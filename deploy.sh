#!/usr/bin/env bash
# Deploy to the OVH VPS (ssh alias `okto-vps`, see okto-hub/ACCESOS.md).
#
# Production data lives ONLY on the server and is never uploaded or replaced:
#   ~/sites/delphina/data/database.sqlite   (agenda, bookings, services, admin)
#   ~/sites/delphina/.env                   (secrets)
# Every deploy first backs up the database (aborts if that fails) and copies
# the backup to this machine (~/backups/delphina) as an off-server copy.
set -euo pipefail

cd "$(dirname "$0")"

LOCAL_BACKUPS="$HOME/backups/delphina"

echo "==> 1/5 Backing up the production database"
ssh okto-vps 'bash -s pre-deploy' < scripts/backup-db.sh

echo "==> 2/5 Copying the backup to this machine ($LOCAL_BACKUPS)"
mkdir -p "$LOCAL_BACKUPS"
LATEST="$(ssh okto-vps 'ls -t ~/backups/delphina/database-*.sqlite.gz | head -1')"
scp -q "okto-vps:$LATEST" "$LOCAL_BACKUPS/"

echo "==> 3/5 Building frontend"
npm run build

echo "==> 4/5 Packaging and uploading code"
ARCHIVE="$(mktemp -t delphina-XXXXXX).tgz"
tar -czf "$ARCHIVE" \
    --exclude=./.git --exclude=./node_modules --exclude=./vendor --exclude=./CV \
    --exclude=./.env --exclude='./.env.backup' --exclude='./.env.production' \
    --exclude=./data --exclude=./logs --exclude='./database/*.sqlite' \
    --exclude='./storage/logs/*.log' --exclude='./storage/framework/views/*.php' \
    --exclude='./storage/framework/sessions/*' --exclude=./resources/data \
    --exclude=./.claude --exclude=./public/hot .

# Safety net: never ship a database or secrets that could overwrite production.
if tar -tzf "$ARCHIVE" | grep -qE '(^|/)(data/|\.env$|[^/]*\.sqlite$)'; then
    echo "ABORT: the package contains a database, data/ or .env — nothing was deployed." >&2
    rm -f "$ARCHIVE"
    exit 1
fi

scp -q "$ARCHIVE" okto-vps:/tmp/delphina.tgz
rm -f "$ARCHIVE"

echo "==> 5/5 Rebuilding the container"
ssh okto-vps 'set -e
cd ~/sites/delphina
tar -xzf /tmp/delphina.tgz && rm /tmp/delphina.tgz
chmod +x scripts/backup-db.sh
docker compose up -d --build
sleep 5
curl -fsS -o /dev/null -w "health: %{http_code}\n" -H "Host: nailsbydelphina.okto.ie" http://127.0.0.1:8081/up'
