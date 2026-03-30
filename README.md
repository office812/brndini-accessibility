# A11Y Bridge

פלטפורמת Laravel ל־Cloudways עבור שירות נגישות מנוהל:

- לקוח פותח חשבון
- מגדיר widget מתוך dashboard
- מקבל `site key` וקוד הטמעה קבוע
- כל שינוי בדשבורד מתעדכן אוטומטית דרך `widget.js`

## מה יש כרגע

- הרשמה והתחברות עם session רגיל של Laravel
- דשבורד לניהול:
  - שם חברה
  - אימייל קשר
  - שם אתר
  - דומיין
  - קישור להצהרת נגישות
  - מיקום, צבע, גודל, שפה והעדפות widget
- endpoint ציבורי:
  - `/api/public/widget-config/{publicKey}`
- סקריפט הטמעה ציבורי:
  - `/widget.js`

## הטמעה באתר לקוח

```html
<script async src="https://YOUR-APP-DOMAIN/widget.js" data-a11y-bridge="YOUR_SITE_KEY"></script>
```

## פריסה ל-Cloudways

1. לחבר את ה־repo לאפליקציית ה־Laravel דרך `Deployment via Git`
2. להגדיר באפליקציה את קובץ `.env`
3. להריץ:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan optimize:clear
```

4. להגדיר `APP_URL` לדומיין של האפליקציה
5. לבדוק:

```bash
/api/health
/api/public/widget-config/{siteKey}
/widget.js
```

## מבנה חשוב

- [routes/web.php](/Users/mikid/projects/a11y-bridge/routes/web.php)
- [routes/api.php](/Users/mikid/projects/a11y-bridge/routes/api.php)
- [app/Http/Controllers/AuthController.php](/Users/mikid/projects/a11y-bridge/app/Http/Controllers/AuthController.php)
- [app/Http/Controllers/DashboardController.php](/Users/mikid/projects/a11y-bridge/app/Http/Controllers/DashboardController.php)
- [app/Http/Controllers/PublicWidgetController.php](/Users/mikid/projects/a11y-bridge/app/Http/Controllers/PublicWidgetController.php)
- [app/Models/Site.php](/Users/mikid/projects/a11y-bridge/app/Models/Site.php)
- [public/widget.js](/Users/mikid/projects/a11y-bridge/public/widget.js)
- [resources/views/dashboard.blade.php](/Users/mikid/projects/a11y-bridge/resources/views/dashboard.blade.php)

## הערה על הקוד הישן

ה־MVP הישן של `Node/Vite` יחד עם תוסף ה־WordPress נשמר תחת:

- [legacy-node-mvp](/Users/mikid/projects/a11y-bridge/legacy-node-mvp)

זה נשמר כדי שלא נאבד את העבודה הקודמת בזמן המעבר ל־Laravel.
