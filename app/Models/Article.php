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
                    'kicker' => 'ציות, הצהרה ומסגור שירות',
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
}
