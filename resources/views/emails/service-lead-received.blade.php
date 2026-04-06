@component('mail::message')
# ליד חדש התקבל

@if($source === 'public')
פנייה חדשה הגיעה מטופס הצור קשר הציבורי.
@else
פנייה שירות חדשה הגיעה מלקוח קיים.
@endif

## פרטי הפנייה

@if(!empty($lead['name']))
- **שם:** {{ $lead['name'] }}
@elseif(!empty($lead['user_name']))
- **שם:** {{ $lead['user_name'] }}
@endif

@if(!empty($lead['email']))
- **אימייל:** {{ $lead['email'] }}
@elseif(!empty($lead['user_email']))
- **אימייל:** {{ $lead['user_email'] }}
@endif

@if(!empty($lead['contact_phone']))
- **טלפון:** {{ $lead['contact_phone'] }}
@endif

- **שירות:** {{ $lead['service_type'] ?? '—' }}
- **מטרה:** {{ $lead['goal'] ?? '—' }}

@if(!empty($lead['preferred_contact']))
- **אמצעי קשר מועדף:** {{ $lead['preferred_contact'] }}
@endif

@if(!empty($lead['urgency_level']))
- **דחיפות:** {{ $lead['urgency_level'] }}
@endif

@if(!empty($lead['budget_range']))
- **תקציב:** {{ $lead['budget_range'] }}
@endif

## הודעה

{{ $lead['message'] ?? '—' }}

@component('mail::button', ['url' => $adminUrl])
לניהול הליד
@endcomponent

צוות Brndini
@endcomponent
