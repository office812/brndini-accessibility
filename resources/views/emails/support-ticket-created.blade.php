@component('mail::message')
# פנייה חדשה נפתחה

פנייה חדשה מחכה לטיפול במרכז התמיכה.

## פרטי הפנייה

| שדה | ערך |
|-----|-----|
| **מספר פנייה** | {{ $ticket->reference_code }} |
| **נושא** | {{ $ticket->subject }} |
| **קטגוריה** | {{ $ticket->category }} |
| **עדיפות** | {{ $ticket->priority }} |

## פרטי הפונה

- **שם:** {{ $userName }}
- **אימייל:** {{ $userEmail }}
- **אתר:** {{ $siteName }} ({{ $siteDomain }})

## תוכן הפנייה

{{ $ticket->message }}

@component('mail::button', ['url' => $adminUrl])
טיפול בפנייה
@endcomponent

צוות Brndini
@endcomponent
