@php
    $serviceCards = [
        [
            'title' => 'אחסון וניהול שרת',
            'description' => 'אחסון יציב, גיבויים, תחזוקה שוטפת ושקט תפעולי לאתרים שצריכים בסיס חזק.',
            'highlights' => ['אחסון מהיר', 'גיבויים', 'ניהול שרת', 'זמינות גבוהה'],
        ],
        [
            'title' => 'SEO וקידום אורגני',
            'description' => 'שיפור מהירות, תוכן, היררכיית עמודים ומבנה אתר כדי לייצר יותר תנועה לאורך זמן.',
            'highlights' => ['מחקר מילות מפתח', 'תוכן', 'אופטימיזציה', 'דוחות'],
        ],
        [
            'title' => 'קמפיינים ופרסום',
            'description' => 'ניהול קמפיינים, דפי נחיתה, מדידה ושיפור המרות סביב יעדי הצמיחה של העסק.',
            'highlights' => ['Google Ads', 'Meta', 'מדידה', 'המרות'],
        ],
        [
            'title' => 'תחזוקת אתר',
            'description' => 'עדכונים, שיפורים, תיקונים וזמינות שוטפת כדי שהאתר יישאר חד, נקי ומהיר.',
            'highlights' => ['עדכונים', 'תיקונים', 'שיפורים', 'זמינות'],
        ],
        [
            'title' => 'שדרוג אתר קיים',
            'description' => 'ריענון עיצוב, שיפור חוויית משתמש והמרה, בלי לפרק הכול ולהתחיל מאפס.',
            'highlights' => ['עיצוב', 'UX', 'ביצועים', 'המרה'],
        ],
        [
            'title' => 'דפי נחיתה ואוטומציות',
            'description' => 'עמודים ממירים, טפסים, CRM ותהליכים שמחברים בין תנועה, לידים וצוות המכירות.',
            'highlights' => ['דפי נחיתה', 'טפסים', 'CRM', 'אוטומציות'],
        ],
    ];

    $brndiniServiceCtaHref = auth()->check() ? route('dashboard.services') : route('brndini.services') . '#public-service-form';
    $brndiniServiceCtaLabel = auth()->check() ? 'לשירותי Brndini' : 'להשארת פנייה עסקית';
@endphp

<div class="brndini-service-grid">
    @foreach ($serviceCards as $serviceCard)
        <article class="brndini-service-card">
            <span class="brndini-service-icon" aria-hidden="true">{{ str($serviceCard['title'])->substr(0, 1) }}</span>
            <h3>{{ $serviceCard['title'] }}</h3>
            <p>{{ $serviceCard['description'] }}</p>
            <div class="brndini-service-highlights">
                @foreach ($serviceCard['highlights'] as $highlight)
                    <span class="preview-pill">{{ $highlight }}</span>
                @endforeach
            </div>
        </article>
    @endforeach
</div>

<div class="brndini-service-actions">
    <a class="primary-button button-link" href="{{ $brndiniServiceCtaHref }}">{{ $brndiniServiceCtaLabel }}</a>
    <a class="ghost-button button-link" href="{{ route('brndini.services') }}">לכל שירותי Brndini</a>
</div>
