@component('mail::message')
# ברוכים הבאים ל-A11Y Bridge, {{ $userName }}!

החשבון שלך נוצר בהצלחה. הווידג׳ט שלך מוכן להטמעה.

## הפרטים שלך

- **שם האתר:** {{ $siteName }}
- **Site Key:** `{{ $siteKey }}`

## שלב הטמעה

הוסף את הקוד הבא לפני תג `</body>` באתר שלך:

```html
<script async src="{{ config('app.url') }}/widget.js" data-a11y-bridge="{{ $siteKey }}"></script>
```

@component('mail::button', ['url' => $dashboardUrl])
כניסה לדashboard
@endcomponent

לאחר ההטמעה, הפלטפורמה תזהה את הווידג׳ט אוטומטית ותעדכן את מצב ההתקנה.

תודה שבחרת A11Y Bridge,
צוות Brndini
@endcomponent
