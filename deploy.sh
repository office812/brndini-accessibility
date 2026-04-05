#!/bin/bash
echo "[$(date)] CRON OK - user=$(whoami)" >> /home/535938.cloudwaysapps.com/axfpmrapnb/public_html/storage/logs/cron-test.log
TRIGGER="/home/535938.cloudwaysapps.com/axfpmrapnb/public_html/.deploy-trigger"
if [ -f "$TRIGGER" ]; then
  rm "$TRIGGER"
  echo "[$(date)] TRIGGER FOUND - running git..." >> /home/535938.cloudwaysapps.com/axfpmrapnb/public_html/storage/logs/cron-test.log
  cd /home/535938.cloudwaysapps.com/axfpmrapnb/public_html
  # Remove files that may have wrong ownership (written by PHP user) to allow git reset
  rm -f resources/views/errors/404.blade.php
  rm -f resources/views/errors/500.blade.php
  git fetch origin main >> /home/535938.cloudwaysapps.com/axfpmrapnb/public_html/storage/logs/cron-test.log 2>&1
  git reset --hard origin/main >> /home/535938.cloudwaysapps.com/axfpmrapnb/public_html/storage/logs/cron-test.log 2>&1
  php artisan migrate --force >> /home/535938.cloudwaysapps.com/axfpmrapnb/public_html/storage/logs/cron-test.log 2>&1
  php artisan optimize:clear >> /home/535938.cloudwaysapps.com/axfpmrapnb/public_html/storage/logs/cron-test.log 2>&1
  echo "[$(date)] DONE" >> /home/535938.cloudwaysapps.com/axfpmrapnb/public_html/storage/logs/cron-test.log
fi
