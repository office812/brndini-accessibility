= A11Y Bridge =
Contributors: brndini
Requires at least: 6.0
Tested up to: 6.5
Stable tag: 0.2.0
License: Proprietary

תוסף WordPress שמחבר אתר ל-A11Y Bridge עם audit ראשוני, site key, embed אוטומטי של widget ושיפורי frontend בטוחים.

== Features ==

* onboarding בסיסי עם פרטי חברה, API key ו-endpoint חיצוני
* הזנת `widget.js` ו-`site key` מה-platform dashboard
* הטמעה אוטומטית של ה-widget באתר דרך התוסף
* local audit של עמוד הבית + ספריית המדיה
* REST routes מאובטחים עם API key לקבלת סטטוס והרצת audit
* skip link בטוח לניווט מקלדת
* focus ring ברור יותר לרכיבים אינטראקטיביים
* חיזוק lang attribute כאשר צריך

== Installation ==

1. העלה את התיקייה `a11y-bridge` אל `wp-content/plugins/`.
2. הפעל את התוסף.
3. היכנס אל `A11Y Bridge` בלוח הבקרה.
4. שמור את פרטי החיבור, הדבק את `Widget script URL` ואת `Site key`.
5. סמן `Auto-embed hosted widget` אם אתה רוצה שהתוסף יטמיע את הקוד אוטומטית.
6. הרץ audit ראשון.

== Notes ==

התוסף לא מבטיח “נגישות מלאה”. הוא פותח workflow מסודר של audit, triage, safe fixes, והטמעה נשלטת של widget מהפלטפורמה.
