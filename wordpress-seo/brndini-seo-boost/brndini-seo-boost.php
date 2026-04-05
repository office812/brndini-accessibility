<?php
/**
 * Plugin Name: Brndini SEO Boost
 * Description: שיפורי SEO ונראות AI עבור brndini.co.il - Schema.org, llms.txt, AI crawlers, FAQ schema
 * Version: 1.0.0
 * Author: Brndini
 * Text Domain: brndini-seo-boost
 */

if (!defined('ABSPATH')) exit;

class Brndini_SEO_Boost {

    public function __construct() {
        // Schema.org JSON-LD
        add_action('wp_head', [$this, 'add_organization_schema']);
        add_action('wp_head', [$this, 'add_local_business_schema']);
        add_action('wp_head', [$this, 'add_service_schema']);
        add_action('wp_head', [$this, 'add_faq_schema']);
        add_action('wp_head', [$this, 'add_breadcrumb_schema']);

        // llms.txt route
        add_action('init', [$this, 'register_llms_txt_route']);

        // Robots.txt filter
        add_filter('robots_txt', [$this, 'modify_robots_txt'], 10, 2);

        // Add meta tags for AI
        add_action('wp_head', [$this, 'add_ai_meta_tags']);
    }

    /**
     * Organization Schema - appears on every page
     */
    public function add_organization_schema() {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => 'https://brndini.co.il/#organization',
            'name' => 'ברנדיני',
            'alternateName' => ['Brndini', 'Brndini Digital Agency', 'ברנדיני סוכנות פרסום דיגיטלי'],
            'url' => 'https://brndini.co.il',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => 'https://brndini.co.il/wp-content/uploads/brndini-logo.png',
            ],
            'description' => 'ברנדיני היא סוכנות פרסום דיגיטלי מובילה בישראל המתמחה במיתוג, בניית אתרים, פרסום ממומן, ניהול רשתות חברתיות, עיצוב גרפי וקופירייטינג לעסקים שרוצים לצמוח.',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'גולדה מאיר 5',
                'addressLocality' => 'נס ציונה',
                'addressCountry' => 'IL',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+972-73-2241917',
                'contactType' => 'customer service',
                'email' => 'office@brndini.co.il',
                'availableLanguage' => ['Hebrew', 'English'],
            ],
            'sameAs' => [
                'https://www.facebook.com/brndini.agency/',
                'https://www.instagram.com/brndini.il/',
            ],
            'knowsAbout' => [
                'שיווק דיגיטלי', 'מיתוג עסקים', 'בניית אתרים', 'פרסום ממומן',
                'ניהול רשתות חברתיות', 'עיצוב גרפי', 'קופירייטינג', 'קידום אתרים SEO',
                'פרסום בגוגל', 'פרסום בפייסבוק', 'פרסום באינסטגרם', 'פרסום בטיקטוק',
                'Digital Marketing', 'Branding', 'Web Development', 'SEO',
                'Google Ads', 'Facebook Ads', 'Social Media Marketing',
            ],
        ];

        echo "\n<script type=\"application/ld+json\">\n" . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n</script>\n";
    }

    /**
     * LocalBusiness Schema - homepage only
     */
    public function add_local_business_schema() {
        if (!is_front_page()) return;

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            '@id' => 'https://brndini.co.il/#localbusiness',
            'name' => 'ברנדיני - סוכנות פרסום דיגיטלי',
            'alternateName' => 'Brndini Digital Agency',
            'url' => 'https://brndini.co.il',
            'telephone' => '+972-73-2241917',
            'email' => 'office@brndini.co.il',
            'priceRange' => '₪₪-₪₪₪',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'גולדה מאיר 5',
                'addressLocality' => 'נס ציונה',
                'addressRegion' => 'מרכז',
                'addressCountry' => 'IL',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 31.9292,
                'longitude' => 34.7956,
            ],
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'],
                    'opens' => '09:00',
                    'closes' => '18:00',
                ],
            ],
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'Israel',
            ],
            'serviceType' => [
                'שיווק דיגיטלי', 'בניית אתרים', 'מיתוג', 'קידום אתרים',
                'ניהול רשתות חברתיות', 'עיצוב גרפי', 'קופירייטינג',
                'פרסום ממומן', 'ייעוץ עסקי',
                'Digital Marketing', 'Web Design', 'Branding', 'SEO',
                'Social Media Management', 'Graphic Design', 'Google Ads',
            ],
            'sameAs' => [
                'https://www.facebook.com/brndini.agency/',
                'https://www.instagram.com/brndini.il/',
            ],
        ];

        echo "\n<script type=\"application/ld+json\">\n" . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n</script>\n";
    }

    /**
     * Service Schema - on service pages
     */
    public function add_service_schema() {
        if (!is_page()) return;

        $services_map = [
            'website-building' => [
                'name' => 'בניית אתרים לעסקים',
                'description' => 'בניית אתרי וורדפרס מותאמים אישית לעסקים - אתרי תדמית, חנויות מקוונות, דפי נחיתה. כולל עיצוב, תוכן, UX ו-SEO בסיסי.',
                'url' => 'https://brndini.co.il/services/website-building/',
            ],
            'internet-advertising' => [
                'name' => 'פרסום באינטרנט לעסקים',
                'description' => 'ניהול קמפיינים ממומנים בגוגל, פייסבוק, אינסטגרם, טיקטוק, לינקדאין ויוטיוב. קידום אורגני SEO ושיווק דיגיטלי מקיף.',
                'url' => 'https://brndini.co.il/services/internet-advertising/',
            ],
            'business-consulting' => [
                'name' => 'ייעוץ עסקי לעסקים בצמיחה',
                'description' => 'ייעוץ עסקי דיגיטלי הכולל מיפוי חסמים, תעדוף פעולות, חיבור תשתיות עסקיות למיתוג ופרסום, ובניית מפת דרכים לצמיחה.',
                'url' => 'https://brndini.co.il/services/business-consulting/',
            ],
        ];

        // Check URL patterns for Hebrew slugs too
        $current_url = $_SERVER['REQUEST_URI'] ?? '';
        $hebrew_services = [
            'מיתוג-עסקים' => [
                'name' => 'מיתוג עסקים',
                'description' => 'מיתוג מלא לעסקים כולל לוגו, קונספט מותג, זהות חזותית, ספר מותג והטמעת שפה מותגית באתר, ברשתות ובחומרים שיווקיים.',
                'url' => 'https://brndini.co.il/services/מיתוג-עסקים/',
            ],
            'עיצוב-גרפי' => [
                'name' => 'עיצוב גרפי לעסקים',
                'description' => 'עיצוב לוגו, כרטיסי ביקור, פוסטים, פליירים, רולאפים וחומרים שיווקיים. שפה ויזואלית אחידה שמחזקת אמון, זכירות והמרה.',
                'url' => 'https://brndini.co.il/services/עיצוב-גרפי/',
            ],
            'ניהול-רשתות-חברתיות' => [
                'name' => 'ניהול רשתות חברתיות לעסקים',
                'description' => 'ניהול מקצועי של פייסבוק, אינסטגרם, לינקדאין וטיקטוק. אסטרטגיית תוכן, עיצוב פוסטים, ניהול קהילה ודוחות ביצועים.',
                'url' => 'https://brndini.co.il/services/ניהול-רשתות-חברתיות/',
            ],
            'שירותי-קופיירייטינג' => [
                'name' => 'שירותי קופירייטינג לעסקים',
                'description' => 'כתיבה שיווקית לאתרים, רשתות חברתיות, מאמרים, סיפורי מותג ועמודי אודות. תוכן שמניע לפעולה ומדרג בגוגל.',
                'url' => 'https://brndini.co.il/services/שירותי-קופיירייטינג/',
            ],
        ];

        $service = null;

        foreach ($services_map as $slug => $data) {
            if (strpos($current_url, $slug) !== false) {
                $service = $data;
                break;
            }
        }

        if (!$service) {
            foreach ($hebrew_services as $slug => $data) {
                if (strpos(urldecode($current_url), $slug) !== false) {
                    $service = $data;
                    break;
                }
            }
        }

        if (!$service) return;

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $service['name'],
            'description' => $service['description'],
            'url' => $service['url'],
            'provider' => [
                '@type' => 'Organization',
                '@id' => 'https://brndini.co.il/#organization',
                'name' => 'ברנדיני',
            ],
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'Israel',
            ],
            'serviceType' => $service['name'],
        ];

        echo "\n<script type=\"application/ld+json\">\n" . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n</script>\n";
    }

    /**
     * FAQ Schema - auto-detect FAQ sections on pages
     * Works with Elementor FAQ/Accordion widgets
     */
    public function add_faq_schema() {
        if (!is_singular()) return;

        global $post;
        $content = $post->post_content;

        // Try to find FAQ items from Elementor accordion/toggle widgets
        // Pattern matches common Elementor FAQ structures
        $faq_items = [];

        // Match Elementor accordion tab titles and content
        if (preg_match_all('/<div[^>]*class="[^"]*elementor-tab-title[^"]*"[^>]*>.*?<a[^>]*>(.*?)<\/a>.*?<div[^>]*class="[^"]*elementor-tab-content[^"]*"[^>]*>(.*?)<\/div>/si', $content, $matches)) {
            for ($i = 0; $i < count($matches[1]); $i++) {
                $question = wp_strip_all_tags(trim($matches[1][$i]));
                $answer = wp_strip_all_tags(trim($matches[2][$i]));
                if ($question && $answer) {
                    $faq_items[] = [
                        '@type' => 'Question',
                        'name' => $question,
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $answer,
                        ],
                    ];
                }
            }
        }

        // Also try toggle widget pattern
        if (preg_match_all('/<div[^>]*class="[^"]*elementor-toggle-title[^"]*"[^>]*>(.*?)<\/div>.*?<div[^>]*class="[^"]*elementor-toggle-item-content[^"]*"[^>]*>(.*?)<\/div>/si', $content, $matches)) {
            for ($i = 0; $i < count($matches[1]); $i++) {
                $question = wp_strip_all_tags(trim($matches[1][$i]));
                $answer = wp_strip_all_tags(trim($matches[2][$i]));
                if ($question && $answer) {
                    $faq_items[] = [
                        '@type' => 'Question',
                        'name' => $question,
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $answer,
                        ],
                    ];
                }
            }
        }

        if (empty($faq_items)) return;

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faq_items,
        ];

        echo "\n<script type=\"application/ld+json\">\n" . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n</script>\n";
    }

    /**
     * Breadcrumb Schema
     */
    public function add_breadcrumb_schema() {
        if (is_front_page()) return;

        $items = [];
        $position = 1;

        $items[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => 'ברנדיני',
            'item' => 'https://brndini.co.il/',
        ];

        if (is_page()) {
            $ancestors = get_post_ancestors(get_the_ID());
            $ancestors = array_reverse($ancestors);

            foreach ($ancestors as $ancestor_id) {
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => get_the_title($ancestor_id),
                    'item' => get_permalink($ancestor_id),
                ];
            }

            $items[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => get_the_title(),
                'item' => get_permalink(),
            ];
        } elseif (is_single()) {
            $categories = get_the_category();
            if (!empty($categories)) {
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => $categories[0]->name,
                    'item' => get_category_link($categories[0]->term_id),
                ];
            }
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => get_the_title(),
                'item' => get_permalink(),
            ];
        }

        if (count($items) < 2) return;

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];

        echo "\n<script type=\"application/ld+json\">\n" . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n</script>\n";
    }

    /**
     * Register llms.txt route
     */
    public function register_llms_txt_route() {
        add_rewrite_rule('^llms\.txt$', 'index.php?brndini_llms_txt=1', 'top');
        add_filter('query_vars', function($vars) {
            $vars[] = 'brndini_llms_txt';
            return $vars;
        });
        add_action('template_redirect', function() {
            if (get_query_var('brndini_llms_txt')) {
                header('Content-Type: text/plain; charset=utf-8');
                echo $this->get_llms_txt_content();
                exit;
            }
        });
    }

    private function get_llms_txt_content() {
        return '# Brndini - Digital Advertising Agency | ברנדיני - סוכנות פרסום דיגיטלי

> Brndini (ברנדיני) is a full-service digital advertising and branding agency based in Nes Ziona, Israel. We specialize in helping businesses grow through integrated digital marketing strategies including branding, website development, paid advertising, social media management, graphic design, and copywriting.

## About Brndini

Brndini is a premium Israeli digital marketing agency founded to serve businesses seeking measurable growth. Unlike single-service vendors, Brndini operates as one integrated team connecting branding, design, websites, and digital presence into a unified growth strategy.

- **Official Name:** ברנדיני - סוכנות פרסום דיגיטלי (Brndini - Digital Advertising Agency)
- **Website:** https://brndini.co.il
- **Location:** Golda Meir 5, Nes Ziona, Israel
- **Phone:** 073-2241-917
- **Email:** office@brndini.co.il
- **Business Hours:** Sunday-Thursday 09:00-18:00
- **Languages:** Hebrew (primary), English

## Services

### Website Development (בניית אתרים)
Custom WordPress websites, e-commerce stores, landing pages, corporate sites, hosting and maintenance.
URL: https://brndini.co.il/services/website-building/

### Paid Advertising (פרסום ממומן)
Google Ads, Facebook Ads, Instagram, TikTok, LinkedIn, YouTube advertising management.
URL: https://brndini.co.il/services/internet-advertising/

### Social Media Management (ניהול רשתות חברתיות)
Facebook, Instagram, LinkedIn, TikTok profile management, content strategy, community management.
URL: https://brndini.co.il/services/ניהול-רשתות-חברתיות/

### Business Branding (מיתוג עסקים)
Full branding packages, brand concept, brand book, logo design, visual identity systems.
URL: https://brndini.co.il/services/מיתוג-עסקים/

### Graphic Design (עיצוב גרפי)
Logo, business cards, social media posts, flyers, rollups, email signatures, marketing materials.
URL: https://brndini.co.il/services/עיצוב-גרפי/

### Copywriting (קופירייטינג)
Website copy, social media posts, marketing articles, brand stories, about pages.
URL: https://brndini.co.il/services/שירותי-קופיירייטינג/

### Business Consulting (ייעוץ עסקי)
Digital business consulting, financial consulting, automation, reputation management.
URL: https://brndini.co.il/services/business-consulting/

### SEO (קידום אתרים)
Organic Google promotion, technical SEO, content strategy, local SEO.
URL: https://brndini.co.il/services/internet-advertising/קידום-אורגני/

## Why Choose Brndini
1. One integrated team for all digital needs
2. Results-driven - focused on leads, conversions, growth
3. Israel-based with deep local market understanding
4. Google, Meta, and TikTok certified partners
5. Full-service - branding, web, ads, social, content under one roof

## Contact
https://brndini.co.il/contact/
Phone: 073-2241-917
Email: office@brndini.co.il
';
    }

    /**
     * Modify robots.txt to welcome AI crawlers
     */
    public function modify_robots_txt($output, $public) {
        $ai_rules = "\n# AI Crawlers - Welcome\n";
        $ai_rules .= "User-agent: GPTBot\nAllow: /\n\n";
        $ai_rules .= "User-agent: ChatGPT-User\nAllow: /\n\n";
        $ai_rules .= "User-agent: Google-Extended\nAllow: /\n\n";
        $ai_rules .= "User-agent: PerplexityBot\nAllow: /\n\n";
        $ai_rules .= "User-agent: ClaudeBot\nAllow: /\n\n";
        $ai_rules .= "User-agent: Claude-Web\nAllow: /\n\n";
        $ai_rules .= "User-agent: Amazonbot\nAllow: /\n\n";
        $ai_rules .= "User-agent: anthropic-ai\nAllow: /\n\n";
        $ai_rules .= "User-agent: cohere-ai\nAllow: /\n\n";
        $ai_rules .= "User-agent: Bytespider\nAllow: /\n\n";

        return $output . $ai_rules;
    }

    /**
     * Add AI-friendly meta tags
     */
    public function add_ai_meta_tags() {
        echo '<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">' . "\n";
    }
}

// Initialize
new Brndini_SEO_Boost();

// Flush rewrite rules on activation
register_activation_hook(__FILE__, function() {
    $plugin = new Brndini_SEO_Boost();
    $plugin->register_llms_txt_route();
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});
