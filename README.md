# A11Y Bridge

MVP לפלטפורמת נגישות שמציגה מודל מוצר בריא:

- סריקת URL ראשונית
- דירוג ממצאים לפי חומרה
- auto-fix opportunities זהירים
- backlog להמשך תיקון אנושי

## Run locally

```bash
npm install
npm run dev
```

האפליקציה תיפתח ב-`http://localhost:5173` וה-API ירוץ על `http://localhost:8787`.

## Build

```bash
npm run build
npm run start
```

## Important note

ה-MVP הזה בכוונה לא מבטיח “נגישות מלאה בלחיצת כפתור”. הוא מציג מוצר שממפה סיכונים ויוצר workflow אמיתי לתיקון.

## WordPress plugin

תוסף ה-WordPress נמצא תחת `wordpress-plugin/a11y-bridge`.

הוא כולל:

- מסך חיבור ו-onboarding
- חיבור ל-`widget.js` ול-`site key` מה-platform dashboard
- הטמעה אוטומטית של ה-widget באתר דרך WordPress
- audit ראשוני מתוך WordPress
- REST endpoints עם API key
- skip link ושיפורי focus בטוחים

## Hosted platform flow

האפליקציה הראשית עכשיו תומכת גם ב-flow של SaaS hosted:

- משתמש נרשם ומקבל site key ציבורי
- הלקוח מגדיר widget מתוך dashboard
- הפלטפורמה מייצרת embed snippet קבוע
- `widget.js` מושך config עדכני לפי site key
- שינוי בהגדרות מתעדכן אוטומטית בכל אתר מוטמע

הנתונים נשמרים כרגע בקובץ מקומי:

`server/data/store.json`
