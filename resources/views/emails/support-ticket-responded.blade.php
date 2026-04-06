@component('mail::message')
# קיבלת תגובה לפנייה שלך

צוות התמיכה של A11Y Bridge הגיב לפנייה **{{ $ticket->reference_code }}**.

## הפנייה המקורית

**{{ $ticket->subject }}**

{{ $ticket->message }}

---

## תגובת הצוות

{{ $adminResponse }}

@component('mail::button', ['url' => $supportUrl])
לצפייה בכל הפניות
@endcomponent

אם יש לך שאלות נוספות, אפשר לפתוח פנייה חדשה מהדashboard.

צוות Brndini
@endcomponent
