<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    public const TOPIC_DEFINITIONS = [
        'widget' => [
            'label' => 'וידג׳ט נגישות',
            'pillar' => 'וידג׳ט והטמעה',
            'intent' => 'commercial',
            'cta' => 'signup',
            'eyebrow' => 'Widget knowledge',
            'summary' => 'מה הכלי נותן בפועל, איך מטמיעים אותו, ואיך מנהלים אותו בלי להפוך אותו לשירות.',
            'keywords' => ['widget', 'ווידג׳ט', 'תוסף', 'accessibility widget', 'script', 'snippet', 'הטמעה', 'install', 'site key'],
        ],
        'statement' => [
            'label' => 'הצהרת נגישות',
            'pillar' => 'הצהרה וציות',
            'intent' => 'informational',
            'cta' => 'signup',
            'eyebrow' => 'Statement knowledge',
            'summary' => 'איך כותבים, מנהלים ומציגים הצהרת נגישות בסיסית כחלק ממוצר מסודר.',
            'keywords' => ['הצהרה', 'statement', 'הצהרת נגישות', 'עמוד הצהרה'],
        ],
        'compliance' => [
            'label' => 'WCAG, ADA וציות',
            'pillar' => 'ציות והבנה רגולטורית',
            'intent' => 'informational',
            'cta' => 'free-tool',
            'eyebrow' => 'Compliance knowledge',
            'summary' => 'מה צריך להבין סביב WCAG, ADA, בדיקות, גבולות של widget, ומה כן ולא מבטיחים.',
            'keywords' => ['wcag', 'ada', 'ציות', 'compliance', 'audit', 'בדיקה', 'score', 'התראות', 'חוק'],
        ],
        'platforms' => [
            'label' => 'נגישות לפי פלטפורמה',
            'pillar' => 'הטמעה לפי CMS ופלטפורמה',
            'intent' => 'commercial',
            'cta' => 'signup',
            'eyebrow' => 'Platform guides',
            'summary' => 'איך מטמיעים ומנהלים את הכלי ב־WordPress, Shopify, Webflow, Wix ואתרים מותאמים.',
            'keywords' => ['wordpress', 'shopify', 'webflow', 'wix', 'cms', 'custom'],
        ],
        'seo' => [
            'label' => 'נגישות, SEO ו־AI SEO',
            'pillar' => 'נגישות וצמיחה אורגנית',
            'intent' => 'hybrid',
            'cta' => 'services',
            'eyebrow' => 'Growth knowledge',
            'summary' => 'איך נגישות מתחברת ל־SEO, חוויית משתמש, trust, תוכן, ו־AI overviews.',
            'keywords' => ['seo', 'content', 'organic', 'ai seo', 'ai', 'חיפוש', 'תוכן', 'צמיחה'],
        ],
        'agencies' => [
            'label' => 'סוכנויות וצוותים',
            'pillar' => 'ניהול כמה אתרים ולקוחות',
            'intent' => 'commercial',
            'cta' => 'services',
            'eyebrow' => 'Agency knowledge',
            'summary' => 'איך סוכנויות, צוותי שיווק וצוותים פנימיים יכולים לעבוד עם שכבת הנגישות בצורה מסודרת.',
            'keywords' => ['agency', 'partner', 'client', 'לקוחות', 'סוכנות', 'צוות'],
        ],
    ];

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'meta_description',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function readingTimeMinutes(): int
    {
        $text = trim(strip_tags($this->body ?? ''));
        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);

        return max(3, (int) ceil(count($words) / 180));
    }

    public function coverTheme(): array
    {
        $haystack = Str::lower(trim(implode(' ', [
            $this->title,
            $this->excerpt,
            Str::limit(strip_tags($this->body ?? ''), 240),
        ])));

        $themes = [
            [
                'match' => ['wcag', 'compliance', 'ציות', 'הצהרת', 'statement', 'חוק', 'ada'],
                'theme' => [
                    'slug' => 'compliance',
                    'eyebrow' => 'Compliance article',
                    'kicker' => 'ציות, הצהרה ושקיפות',
                    'chips' => ['WCAG', 'הצהרה', 'ציות'],
                ],
            ],
            [
                'match' => ['audit', 'בדיקה', 'scan', 'score', 'ניטור', 'התראות'],
                'theme' => [
                    'slug' => 'audit',
                    'eyebrow' => 'Audit article',
                    'kicker' => 'בדיקות, ציונים והתראות',
                    'chips' => ['בדיקה', 'ניטור', 'ציון'],
                ],
            ],
            [
                'match' => ['wordpress', 'shopify', 'webflow', 'install', 'הטמעה', 'snippet', 'script'],
                'theme' => [
                    'slug' => 'integration',
                    'eyebrow' => 'Integration guide',
                    'kicker' => 'הטמעה, חיבור ואתר חי',
                    'chips' => ['הטמעה', 'WordPress', 'Snippet'],
                ],
            ],
            [
                'match' => ['seo', 'content', 'מאמר', 'בלוג', 'organic', 'חיפוש'],
                'theme' => [
                    'slug' => 'content',
                    'eyebrow' => 'Growth article',
                    'kicker' => 'תוכן, צמיחה ואמון שוק',
                    'chips' => ['SEO', 'תוכן', 'צמיחה'],
                ],
            ],
            [
                'match' => ['agency', 'client', 'לקוחות', 'סוכנות', 'reseller', 'partner'],
                'theme' => [
                    'slug' => 'agency',
                    'eyebrow' => 'Agency article',
                    'kicker' => 'לקוחות, מכירה ושירות',
                    'chips' => ['Agency', 'לקוחות', 'שירות'],
                ],
            ],
        ];

        foreach ($themes as $entry) {
            foreach ($entry['match'] as $needle) {
                if ($needle !== '' && Str::contains($haystack, Str::lower($needle))) {
                    return $entry['theme'];
                }
            }
        }

        return [
            'slug' => 'platform',
            'eyebrow' => 'Platform article',
            'kicker' => 'ניהול נגישות בפלטפורמה אחת',
            'chips' => ['Platform', 'Widget', 'Dashboard'],
        ];
    }

    public static function knowledgeTopics(): array
    {
        return self::TOPIC_DEFINITIONS;
    }

    public function knowledgeTopicKey(): string
    {
        $haystack = Str::lower(trim(implode(' ', [
            $this->title,
            $this->excerpt,
            strip_tags($this->body ?? ''),
            $this->meta_description,
        ])));

        foreach (self::TOPIC_DEFINITIONS as $key => $topic) {
            foreach ($topic['keywords'] as $keyword) {
                if ($keyword !== '' && Str::contains($haystack, Str::lower($keyword))) {
                    return $key;
                }
            }
        }

        return 'widget';
    }

    public function knowledgeTopic(): array
    {
        return self::TOPIC_DEFINITIONS[$this->knowledgeTopicKey()] ?? self::TOPIC_DEFINITIONS['widget'];
    }

    public function topicLabel(): string
    {
        return $this->knowledgeTopic()['label'];
    }

    public function pillarLabel(): string
    {
        return $this->knowledgeTopic()['pillar'];
    }

    public function contentIntentLabel(): string
    {
        return match ($this->knowledgeTopic()['intent']) {
            'commercial' => 'כוונה מסחרית',
            'hybrid' => 'מידע עם כוונת צמיחה',
            default => 'כוונה אינפורמטיבית',
        };
    }

    public function targetCtaKey(): string
    {
        return $this->knowledgeTopic()['cta'];
    }

    public function targetCta(): array
    {
        return match ($this->targetCtaKey()) {
            'services' => [
                'label' => 'שירותי Brndini',
                'route' => route('brndini.services'),
                'copy' => 'אם הנושא הזה כבר נוגע לאתר, לשיווק או לשדרוג רחב יותר, אפשר לעבור מכאן ישירות לשירותים העסקיים.',
            ],
            'free-tool' => [
                'label' => 'מה כלול בחינם',
                'route' => route('free-tool'),
                'copy' => 'אם אתה רוצה להבין את גבולות השכבה החינמית לפני פתיחת חשבון, זה המקום להמשיך אליו.',
            ],
            default => [
                'label' => 'פתיחת חשבון חינמי',
                'route' => route('register.show'),
                'copy' => 'אם הנושא הזה רלוונטי לאתר שלך, אפשר לפתוח חשבון חינמי ולהתחיל לעבוד מתוך הדשבורד.',
            ],
        };
    }

    public function articleSummary(): string
    {
        return $this->knowledgeTopic()['summary'];
    }

    public function introAnswer(): string
    {
        $paragraphs = preg_split('/\n{2,}/u', trim((string) $this->body), -1, PREG_SPLIT_NO_EMPTY);

        if (! empty($paragraphs)) {
            return trim($paragraphs[0]);
        }

        return $this->excerpt;
    }
}
