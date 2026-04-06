<?php
/**
 * Plugin Name: Brndini SEO Boost
 * Description: SEO overhaul + AI SEO for brndini.co.il - noindex, schemas, archive titles, utility templates, structured data
 * Version: 2.0.0
 * Author: Brndini
 * Text Domain: brndini-seo-boost
 */

if (!defined('ABSPATH')) exit;

class Brndini_SEO_Boost {

    /**
     * Utility page slugs that should be noindexed and stripped down
     */
    private $utility_slugs = ['ty', 'privacy-center-il'];

    /**
     * Service page URL patterns
     */
    private $service_slugs = [
        'website-building',
        'internet-advertising',
        'business-consulting',
    ];

    private $service_slugs_hebrew = [
        'מיתוג-עסקים',
        'עיצוב-גרפי',
        'ניהול-רשתות-חברתיות',
        'שירותי-קופירייטינג',
    ];

    /**
     * Minimum posts for a tag to be indexed
     */
    private $min_tag_posts = 3;

    public function __construct() {
        // === EXISTING FEATURES ===
        add_action('wp_head', [$this, 'add_service_schema']);
        add_action('init', [$this, 'register_llms_txt_route']);
        add_filter('robots_txt', [$this, 'modify_robots_txt'], 10, 2);
        add_filter('wp_get_attachment_image_attributes', [$this, 'fix_empty_alt_text'], 10, 3);

        // === SEO OVERHAUL - NEW FEATURES ===

        // 1. noindex utility pages (Yoast filters)
        add_filter('wpseo_robots', [$this, 'noindex_utility_pages']);
        add_filter('wpseo_sitemap_entry', [$this, 'exclude_utility_from_sitemap'], 10, 3);
        add_action('wpseo_xmlsitemap_index', [$this, 'filter_sitemap_index']);

        // 2. Utility page body classes + CSS cleanup
        add_filter('body_class', [$this, 'add_page_type_classes']);
        add_action('wp_head', [$this, 'utility_and_service_page_css'], 99);

        // 3. Fix archive H1 titles
        add_filter('get_the_archive_title', [$this, 'fix_archive_titles']);

        // 4. noindex weak tag archives
        add_filter('wpseo_robots', [$this, 'noindex_weak_tags']);

        // 6. Person/ProfilePage schema on author pages
        add_action('wp_head', [$this, 'add_author_schema']);

        // 7. Article/BlogPosting schema on blog posts
        add_action('wp_head', [$this, 'add_article_schema']);

        // 8. Case study URL redirects
        add_action('template_redirect', [$this, 'handle_case_study_redirects']);

        // 14. Fix Yoast titles for archives/services
        add_filter('wpseo_title', [$this, 'fix_yoast_titles']);
        add_filter('wpseo_opengraph_title', [$this, 'fix_yoast_titles']);

        // 15. AI meta tags - improved (skip on utility pages)
        add_action('wp_head', [$this, 'add_ai_meta_tags']);
    }

    // =========================================================================
    // 1. NOINDEX UTILITY PAGES
    // =========================================================================

    /**
     * Set noindex,follow for utility pages via Yoast
     */
    public function noindex_utility_pages($robots) {
        if (!is_page()) return $robots;

        $current_slug = get_post_field('post_name', get_queried_object_id());
        if (in_array($current_slug, $this->utility_slugs, true)) {
            return 'noindex, follow';
        }

        return $robots;
    }

    /**
     * Exclude utility pages from Yoast XML sitemap
     */
    public function exclude_utility_from_sitemap($url, $type, $post) {
        if ($type === 'post' && is_object($post) && isset($post->post_name)) {
            if (in_array($post->post_name, $this->utility_slugs, true)) {
                return false;
            }
        }
        return $url;
    }

    /**
     * Filter sitemap index to exclude utility pages
     */
    public function filter_sitemap_index($index) {
        return $index;
    }

    // =========================================================================
    // 2. UTILITY PAGE TEMPLATE - CSS CLEANUP (Elementor compatible)
    // =========================================================================

    /**
     * Add body classes for page type identification
     */
    public function add_page_type_classes($classes) {
        if (is_page()) {
            $slug = get_post_field('post_name', get_queried_object_id());

            // Utility pages
            if (in_array($slug, $this->utility_slugs, true)) {
                $classes[] = 'brndini-utility-page';
            }

            // Service pages
            $current_url = urldecode($_SERVER['REQUEST_URI'] ?? '');
            $all_services = array_merge($this->service_slugs, $this->service_slugs_hebrew);
            foreach ($all_services as $service_slug) {
                if (strpos($current_url, '/services/') !== false && strpos($current_url, $service_slug) !== false) {
                    $classes[] = 'brndini-service-page';
                    break;
                }
            }

            // Contact page
            if ($slug === 'contact' || strpos($current_url, '/contact') !== false) {
                $classes[] = 'brndini-contact-page';
            }
        }

        // Author archive
        if (is_author()) {
            $classes[] = 'brndini-author-archive';
        }

        // Tag archive
        if (is_tag()) {
            $classes[] = 'brndini-tag-archive';
        }

        return $classes;
    }

    /**
     * CSS rules to clean up utility, service, and contact pages
     * Works with Elementor - only hides/adjusts existing widgets via CSS
     */
    public function utility_and_service_page_css() {
        ?>
<style id="brndini-page-cleanup">
/* Utility pages: hide author box, newsletter, sitemap, login CTA */
.brndini-utility-page .elementor-widget-author-box,
.brndini-utility-page .author-box,
.brndini-utility-page .brndini-author-box,
.brndini-utility-page [data-widget_type="author-box.default"],
.brndini-utility-page .elementor-widget-wp-widget-newsletter,
.brndini-utility-page .newsletter-widget,
.brndini-utility-page .elementor-widget-sitemap,
.brndini-utility-page .wp-sitemap-widget,
.brndini-utility-page .client-login-cta,
.brndini-utility-page .elementor-widget-login,
.brndini-utility-page [data-widget_type*="login"] {
    display: none !important;
}

/* Service pages: hide duplicate author box (keep only the first one) */
.brndini-service-page .elementor-widget-author-box ~ .elementor-widget-author-box,
.brndini-service-page .author-box ~ .author-box,
.brndini-service-page [data-widget_type="author-box.default"] ~ [data-widget_type="author-box.default"] {
    display: none !important;
}

/* Contact page: hide sitemap block and unnecessary marketing blocks */
.brndini-contact-page .elementor-widget-sitemap,
.brndini-contact-page .wp-sitemap-widget,
.brndini-contact-page .sitemap-section {
    display: none !important;
}
</style>
        <?php
    }

    // =========================================================================
    // 3. FIX ARCHIVE H1 TITLES
    // =========================================================================

    /**
     * Replace generic blog H1 with proper dynamic titles for archives
     */
    public function fix_archive_titles($title) {
        if (is_author()) {
            $author = get_queried_object();
            if ($author && isset($author->display_name)) {
                return 'מאמרים מאת ' . $author->display_name;
            }
        }

        if (is_tag()) {
            $tag = single_tag_title('', false);
            if ($tag) {
                return $tag . ' - מאמרים ומדריכים';
            }
        }

        if (is_post_type_archive('brd_author') || is_post_type_archive('authors') || (is_page() && get_post_field('post_name', get_queried_object_id()) === 'authors')) {
            return 'כותבי ברנדיני';
        }

        return $title;
    }

    // =========================================================================
    // 4. NOINDEX WEAK TAG ARCHIVES
    // =========================================================================

    /**
     * Set noindex for tags with fewer than minimum posts
     */
    public function noindex_weak_tags($robots) {
        if (!is_tag()) return $robots;

        $tag = get_queried_object();
        if ($tag && isset($tag->count) && $tag->count < $this->min_tag_posts) {
            return 'noindex, follow';
        }

        return $robots;
    }

    // =========================================================================
    // 6. PERSON / PROFILEPAGE SCHEMA
    // =========================================================================

    /**
     * Add ProfilePage + Person schema on author archive pages
     */
    public function add_author_schema() {
        if (!is_author()) return;

        $author = get_queried_object();
        if (!$author) return;

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfilePage',
            'mainEntity' => [
                '@type' => 'Person',
                '@id' => get_author_posts_url($author->ID) . '#person',
                'name' => $author->display_name,
                'url' => get_author_posts_url($author->ID),
                'description' => $author->description ?: '',
                'worksFor' => [
                    '@type' => 'Organization',
                    '@id' => 'https://brndini.co.il/#organization',
                    'name' => 'ברנדיני',
                ],
            ],
        ];

        // Add job title if available
        $job_title = get_the_author_meta('job_title', $author->ID);
        if ($job_title) {
            $schema['mainEntity']['jobTitle'] = $job_title;
        }

        // Add profile image
        $avatar_url = get_avatar_url($author->ID, ['size' => 256]);
        if ($avatar_url) {
            $schema['mainEntity']['image'] = $avatar_url;
        }

        // Add social links if available
        $social_links = [];
        $linkedin = get_the_author_meta('linkedin', $author->ID);
        if ($linkedin) $social_links[] = $linkedin;
        $facebook = get_the_author_meta('facebook', $author->ID);
        if ($facebook) $social_links[] = $facebook;
        if (!empty($social_links)) {
            $schema['mainEntity']['sameAs'] = $social_links;
        }

        echo "\n<script type=\"application/ld+json\">\n" . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n</script>\n";
    }

    // =========================================================================
    // 7. ARTICLE / BLOGPOSTING SCHEMA
    // =========================================================================

    /**
     * Add Article schema on single blog posts
     * Skips if Yoast already handles it (checks for existing schema)
     */
    public function add_article_schema() {
        if (!is_single() || get_post_type() !== 'post') return;

        // Check if Yoast already outputs Article schema
        if (class_exists('WPSEO_Schema_Article')) return;

        $post = get_post();
        if (!$post) return;

        $author = get_userdata($post->post_author);
        $thumb_url = get_the_post_thumbnail_url($post->ID, 'large');

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            '@id' => get_permalink($post->ID) . '#article',
            'headline' => get_the_title($post->ID),
            'url' => get_permalink($post->ID),
            'datePublished' => get_the_date('c', $post->ID),
            'dateModified' => get_the_modified_date('c', $post->ID),
            'author' => [
                '@type' => 'Person',
                'name' => $author ? $author->display_name : '',
                'url' => $author ? get_author_posts_url($author->ID) : '',
            ],
            'publisher' => [
                '@type' => 'Organization',
                '@id' => 'https://brndini.co.il/#organization',
                'name' => 'ברנדיני',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => 'https://brndini.co.il/wp-content/uploads/brndini-logo.png',
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => get_permalink($post->ID),
            ],
        ];

        if ($thumb_url) {
            $schema['image'] = $thumb_url;
        }

        $excerpt = get_the_excerpt($post->ID);
        if ($excerpt) {
            $schema['description'] = wp_strip_all_tags($excerpt);
        }

        $categories = get_the_category($post->ID);
        if (!empty($categories)) {
            $schema['articleSection'] = $categories[0]->name;
        }

        $tags = get_the_tags($post->ID);
        if (!empty($tags)) {
            $schema['keywords'] = implode(', ', wp_list_pluck($tags, 'name'));
        }

        echo "\n<script type=\"application/ld+json\">\n" . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n</script>\n";
    }

    // =========================================================================
    // 8. CASE STUDY URL REDIRECTS
    // =========================================================================

    /**
     * Redirect inconsistent case study URLs to canonical path
     * Consolidates /case-study/ to Hebrew /סיפורי-הצלחה/
     */
    public function handle_case_study_redirects() {
        $request_uri = urldecode($_SERVER['REQUEST_URI'] ?? '');

        // Redirect /case-study/X/ to /סיפורי-הצלחה/X/ (301 permanent)
        if (preg_match('#^/case-study/(.+)$#u', $request_uri, $matches)) {
            $slug = $matches[1];
            // Always use https to avoid mixed-content and http→https redirect chains
            $target_url = 'https://brndini.co.il/' . rawurlencode('סיפורי-הצלחה') . '/' . $slug;
            wp_redirect($target_url, 301);
            exit;
        }

        // Also handle /case-study/ archive → /סיפורי-הצלחה/
        if (rtrim($request_uri, '/') === '/case-study') {
            wp_redirect('https://brndini.co.il/' . rawurlencode('סיפורי-הצלחה') . '/', 301);
            exit;
        }
    }

    // =========================================================================
    // 14. FIX YOAST TITLES FOR ARCHIVES/SERVICES
    // =========================================================================

    /**
     * Ensure Yoast titles match H1 for key pages
     */
    public function fix_yoast_titles($title) {
        // Author archive
        if (is_author()) {
            $author = get_queried_object();
            if ($author && isset($author->display_name)) {
                return 'מאמרים מאת ' . $author->display_name . ' | ברנדיני';
            }
        }

        // Tag archive
        if (is_tag()) {
            $tag = single_tag_title('', false);
            if ($tag) {
                return $tag . ' - מאמרים ומדריכים | ברנדיני';
            }
        }

        // Authors listing page (custom post type archive or regular page)
        if (is_post_type_archive('brd_author') || is_post_type_archive('authors') || (is_page() && get_post_field('post_name', get_queried_object_id()) === 'authors')) {
            return 'כותבי ברנדיני | ברנדיני';
        }

        return $title;
    }

    // =========================================================================
    // 15. AI META TAGS (improved - skip utility pages)
    // =========================================================================

    /**
     * Add AI-friendly meta tags - skip utility pages
     */
    public function add_ai_meta_tags() {
        // Don't add indexing meta tags on utility pages
        if (is_page()) {
            $slug = get_post_field('post_name', get_queried_object_id());
            if (in_array($slug, $this->utility_slugs, true)) {
                return;
            }
        }

        echo '<meta name="robots" content="max-image-preview:large, max-snippet:-1, max-video-preview:-1">' . "\n";
    }

    // =========================================================================
    // EXISTING FEATURES (preserved from v1.0)
    // =========================================================================

    /**
     * Fix empty alt text on images
     */
    public function fix_empty_alt_text($attr, $attachment, $size) {
        if (empty($attr['alt'])) {
            $caption = get_the_excerpt($attachment->ID);
            $title = get_the_title($attachment->ID);
            $attr['alt'] = $caption ?: $title ?: 'ברנדיני - סוכנות פרסום דיגיטלי';
        }
        return $attr;
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

        $current_url = $_SERVER['REQUEST_URI'] ?? '';
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

> Brndini is a full-service digital advertising and branding agency based in Nes Ziona, Israel. We specialize in helping businesses grow through integrated digital marketing strategies including branding, website development, paid advertising, social media management, graphic design, and copywriting.

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

### Website Development
Custom WordPress websites, e-commerce stores, landing pages, corporate sites, hosting and maintenance.
URL: https://brndini.co.il/services/website-building/

### Paid Advertising
Google Ads, Facebook Ads, Instagram, TikTok, LinkedIn, YouTube advertising management.
URL: https://brndini.co.il/services/internet-advertising/

### Social Media Management
Facebook, Instagram, LinkedIn, TikTok profile management, content strategy, community management.
URL: https://brndini.co.il/services/ניהול-רשתות-חברתיות/

### Business Branding
Full branding packages, brand concept, brand book, logo design, visual identity systems.
URL: https://brndini.co.il/services/מיתוג-עסקים/

### Graphic Design
Logo, business cards, social media posts, flyers, rollups, email signatures, marketing materials.
URL: https://brndini.co.il/services/עיצוב-גרפי/

### Copywriting
Website copy, social media posts, marketing articles, brand stories, about pages.
URL: https://brndini.co.il/services/שירותי-קופיירייטינג/

### Business Consulting
Digital business consulting, financial consulting, automation, reputation management.
URL: https://brndini.co.il/services/business-consulting/

### SEO
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
     * Modify robots.txt to welcome AI crawlers + block utility pages
     */
    public function modify_robots_txt($output, $public) {
        // Block utility pages from crawl (noindex handles search results)
        $utility_rules = "\n# Utility / System Pages - noindex handled via meta\n";
        $utility_rules .= "Disallow: /ty/\n";
        $utility_rules .= "Disallow: /privacy-center-il/\n\n";

        // AI crawlers welcome
        $ai_rules = "# AI Crawlers - Welcome\n";
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

        return $output . $utility_rules . $ai_rules;
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
