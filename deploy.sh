#!/usr/bin/env bash
# Deploy to the OVH VPS (ssh alias `okto-vps`, see okto-hub/ACCESOS.md).
# Builds the frontend, uploads the code (never .env, data or personal files)
# and rebuilds the container. The production .env and SQLite database live
# only on the server in ~/sites/delphina/{.env,data/}.
set -euo pipefail

cd "$(dirname "$0")"

npm run build

ARCHIVE="$(mktemp -t delphina-XXXXXX).tgz"
tar -czf "$ARCHIVE" \
    --exclude=./.git --exclude=./node_modules --exclude=./vendor --exclude=./CV \
    --exclude=./.env --exclude='./.env.backup' --exclude='./.env.production' \
    --exclude=./data --exclude=./logs --exclude=./database/database.sqlite \
    --exclude='./storage/logs/*.log' --exclude='./storage/framework/views/*.php' \
    --exclude='./storage/framework/sessions/*' --exclude=./resources/data \
    --exclude=./.claude --exclude=./public/hot .

scp -q "$ARCHIVE" okto-vps:/tmp/delphina.tgz
rm -f "$ARCHIVE"

ssh okto-vps 'set -e
cd ~/sites/delphina
tar -xzf /tmp/delphina.tgz && rm /tmp/delphina.tgz
docker compose up -d --build
sleep 5
curl -fsS -o /dev/null -w "health: %{http_code}\n" -H "Host: nailsbydelphina.okto.ie" http://127.0.0.1:8081/up'
