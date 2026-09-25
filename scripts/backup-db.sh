#!/usr/bin/env bash
# Backup of the production SQLite database (runs ON the VPS).
#   ~/sites/delphina/scripts/backup-db.sh [label]
# Uses SQLite's online backup API (safe while the site is in use), checks the
# copy's integrity, gzips it into ~/backups/delphina and keeps 30 days.
# Called daily by cron and by deploy.sh before every deploy.
set -euo pipefail

DB="${DELPHINA_DB:-$HOME/sites/delphina/data/database.sqlite}"
DEST="${DELPHINA_BACKUP_DIR:-$HOME/backups/delphina}"
LABEL="${1:-daily}"
KEEP_DAYS=30

mkdir -p "$DEST"
STAMP="$(date +%Y%m%d-%H%M%S)"
OUT="$DEST/database-$STAMP-$LABEL.sqlite"

python3 - "$DB" "$OUT" <<'PY'
import sqlite3, sys
src, out = sys.argv[1], sys.argv[2]
source = sqlite3.connect(f"file:{src}?mode=ro", uri=True)
target = sqlite3.connect(out)
source.backup(target)
source.close()
check = target.execute("PRAGMA integrity_check").fetchone()[0]
counts = {t: target.execute(f"SELECT COUNT(*) FROM {t}").fetchone()[0]
          for t in ("available_dates", "appointments", "payments", "services", "users")}
target.close()
if check != "ok":
    sys.exit(f"integrity check failed: {check}")
print("backup ok:", ", ".join(f"{k}={v}" for k, v in counts.items()))
PY

gzip -9 "$OUT"
echo "saved $OUT.gz"

find "$DEST" -name 'database-*.sqlite.gz' -mtime +"$KEEP_DAYS" -delete
