@php
    $futureProducts = [
        [
            'title' => 'מרכז SEO ותוכן',
            'description' => 'מערכת שתאגד מחקר מילות מפתח, מבנה עמודים, רעיונות לתוכן ומעקב התקדמות במקום אחד.',
            'highlights' => ['מילות מפתח', 'תוכן', 'מבנה עמודים', 'מעקב'],
        ],
        [
            'title' => 'מרכז לידים וצמיחה',
            'description' => 'שכבת ניהול ללידים, טפסים, מקורות תנועה ופעולות follow-up לעסקים שרוצים יותר שליטה.',
            'highlights' => ['לידים', 'טפסים', 'המרות', 'חזרה מהירה'],
        ],
        [
            'title' => 'Hub לאוטומציות עסקיות',
            'description' => 'כלים לחיבור בין CRM, מיילים, טפסים ותהליכי מכירה שחוסכים זמן ומסדרים את העבודה.',
            'highlights' => ['CRM', 'אינטגרציות', 'מיילים', 'תהליכים'],
        ],
        [
            'title' => 'בקרת אתר וביצועים',
            'description' => 'שכבת חיווי על מהירות, יציבות, דפי מפתח וצווארי בקבוק שאפשר לזהות בזמן אמת.',
            'highlights' => ['ביצועים', 'בריאות אתר', 'מהירות', 'חיווי'],
        ],
    ];
@endphp

<div class="brndini-future-grid">
    @foreach ($futureProducts as $futureProduct)
        <article class="future-product-card">
            <span class="future-product-icon" aria-hidden="true">{{ str($futureProduct['title'])->substr(0, 1) }}</span>
            <h3>{{ $futureProduct['title'] }}</h3>
            <p>{{ $futureProduct['description'] }}</p>
            <div class="future-product-highlights">
                @foreach ($futureProduct['highlights'] as $highlight)
                    <span class="preview-pill">{{ $highlight }}</span>
                @endforeach
            </div>
        </article>
    @endforeach
</div>
