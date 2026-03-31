# Cloudways Auto Deploy

כדי שהפרויקט יתעדכן אוטומטית אחרי כל `push` ל־`main`, הוספנו workflow של GitHub Actions:

- קובץ: `.github/workflows/deploy-cloudways.yml`
- טריגר: כל push ל־`main`
- פעולות על השרת:
  - `git pull origin main`
  - `composer install --no-dev --optimize-autoloader --no-interaction`
  - `php artisan migrate --force`
  - `php artisan optimize:clear`

## מה צריך להגדיר פעם אחת ב-GitHub

ב־GitHub repo:

`Settings` -> `Secrets and variables` -> `Actions`

צור 3 secrets:

1. `CW_HOST`
   - כתובת השרת או ה־IP של Cloudways
   - לדוגמה: `199.247.20.181`

2. `CW_USER`
   - משתמש SSH של Cloudways
   - עדיף משתמש עם גישה ל־app path של הפרויקט

3. `CW_APP_PATH`
   - הנתיב המלא של האפליקציה בשרת
   - לדוגמה:
   - `/home/535938.cloudwaysapps.com/axfpmrapnb/public_html`

4. `CW_SSH_PRIVATE_KEY`
   - המפתח הפרטי של SSH שיש לו גישה לשרת Cloudways

## איך מגדירים SSH key לשרת

1. צור key חדש על המחשב:
   - `ssh-keygen -t ed25519 -C "github-actions-cloudways"`

2. קח את ה־public key מתוך הקובץ `.pub`

3. ב־Cloudways:
   - `Servers`
   - `Master Credentials`
   - `SSH Public Keys`
   - הוסף את ה־public key

4. את ה־private key שמור בתוך secret בשם `CW_SSH_PRIVATE_KEY`

## מה קורה אחרי זה

מרגע שה־secrets מוכנים:

- כל push חדש ל־`main` יריץ deploy אוטומטי
- לא צריך יותר להקליד ידנית בטרמינל:
  - `git pull`
  - `php artisan migrate --force`
  - `php artisan optimize:clear`

## הערה חשובה

מכיוון שה־workflow מריץ migrations אוטומטית, צריך להמשיך לכתוב migrations בטוחות קדימה בלבד.
