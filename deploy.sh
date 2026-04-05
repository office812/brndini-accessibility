#!/bin/bash
LOG="/tmp/a11y-deploy.log"
echo "[$(date)] CRON OK - user=$(whoami)" >> "$LOG"
TRIGGER="/home/535938.cloudwaysapps.com/axfpmrapnb/public_html/.deploy-trigger"
if [ -f "$TRIGGER" ]; then
  rm "$TRIGGER"
  echo "[$(date)] TRIGGER FOUND" >> "$LOG"
  cd /home/535938.cloudwaysapps.com/axfpmrapnb/public_html
  # Remove ALL PHP-user-owned blade files that may block git reset
  find resources/views/errors/ -name "*.blade.php" -delete 2>/dev/null || true
  find storage/framework/views/ -name "*.php" -delete 2>/dev/null || true
  git fetch origin main >> "$LOG" 2>&1
  git reset --hard origin/main >> "$LOG" 2>&1
  chmod 755 deploy.sh
  php artisan migrate --force >> "$LOG" 2>&1
  php artisan optimize:clear >> "$LOG" 2>&1
  echo "[$(date)] DONE" >> "$LOG"
fi
