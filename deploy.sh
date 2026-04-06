#!/bin/bash
LOG="/tmp/a11y-deploy.log"
echo "[$(date)] CRON OK - user=$(whoami)" >> "$LOG"
TRIGGER="/home/535938.cloudwaysapps.com/axfpmrapnb/public_html/.deploy-trigger"
if [ -f "$TRIGGER" ]; then
  rm "$TRIGGER"
  echo "[$(date)] TRIGGER FOUND" >> "$LOG"
  cd /home/535938.cloudwaysapps.com/axfpmrapnb/public_html
  # Ensure git can write to directories that PHP may have taken ownership of
  chmod 777 resources/views/errors/ 2>/dev/null || true
  chmod 777 storage/framework/views/ 2>/dev/null || true
  # Remove PHP-user-owned files that block git reset
  find resources/views/errors/ -name "*.blade.php" -delete 2>/dev/null || true
  find storage/framework/views/ -name "*.php" -delete 2>/dev/null || true
  # Remove untracked files that would conflict with git reset (e.g. bridge.php placed manually)
  git clean -f public/bridge.php 2>/dev/null || true
  git fetch origin main >> "$LOG" 2>&1
  git reset --hard origin/main >> "$LOG" 2>&1
  chmod 755 deploy.sh
  php artisan migrate --force >> "$LOG" 2>&1
  php artisan optimize:clear >> "$LOG" 2>&1
  echo "[$(date)] DONE" >> "$LOG"
fi
