<?php
/**
 * Plugin Name: Brndini Performance Boost
 * Description: אופטימיזציית ביצועים לאתר brndini.co.il - ניקוי בלואט, דחיית סקריפטים, אופטימיזציה של DB
 * Version: 1.0.0
 * Author: Brndini
 * Text Domain: brndini-performance
 */

if (!defined('ABSPATH')) exit;

class Brndini_Performance {

    public function __construct() {
        // Remove WordPress bloat
        add_action('init', [$this, 'remove_wp_bloat']);

        // Optimize scripts loading
        add_action('wp_enqueue_scripts', [$this, 'optimize_scripts'], 9999);

        // Add defer/async to scripts
        add_filter('script_loader_tag', [$this, 'add_defer_async'], 10, 3);

        // Disable unused REST API endpoints for non-logged-in users
        add_filter('rest_authentication_errors', [$this, 'restrict_rest_api']);

        // Optimize WooCommerce fragments if WC is active
        add_action('wp_enqueue_scripts', [$this, 'disable_wc_fragments'], 9999);

        // Remove query strings from static resources
        add_action('init', [$this, 'remove_query_strings']);

        // Preconnect to external domains
        add_action('wp_head', [$this, 'add_preconnect'], 1);

        // Limit post revisions
        if (!defined('WP_POST_REVISIONS')) {
            define('WP_POST_REVISIONS', 5);
        }

        // Disable self-pingbacks
        add_action('pre_ping', [$this, 'disable_self_pingbacks']);

        // Optimize heartbeat
        add_action('init', [$this, 'optimize_heartbeat'], 1);

        // Dequeue unnecessary Jet plugins on non-needed pages
        add_action('wp_enqueue_scripts', [$this, 'conditional_jet_loading'], 9999);

        // Disable Dashicons on frontend for non-logged-in users
        add_action('wp_enqueue_scripts', [$this, 'disable_dashicons']);

        // Add critical resource hints
        add_filter('wp_resource_hints', [$this, 'resource_hints'], 10, 2);

        // Optimize database on cron
        add_action('brndini_db_optimize', [$this, 'optimize_database']);
        if (!wp_next_scheduled('brndini_db_optimize')) {
            wp_schedule_event(time(), 'weekly', 'brndini_db_optimize');
        }
    }

    /**
     * Remove WordPress bloat from head
     */
    public function remove_wp_bloat() {
        // Remove emoji scripts and styles
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');
        add_filter('emoji_svg_url', '__return_false');

        // Remove RSD link
        remove_action('wp_head', 'rsd_link');

        // Remove wlwmanifest link
        remove_action('wp_head', 'wlwmanifest_link');

        // Remove WordPress version
        remove_action('wp_head', 'wp_generator');

        // Remove shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head');

        // Remove REST API link from header
        remove_action('wp_head', 'rest_output_link_wp_head');

        // Remove oEmbed discovery links
        remove_action('wp_head', 'wp_oembed_add_discovery_links');

        // Remove XML-RPC if not needed
        add_filter('xmlrpc_enabled', '__return_false');

        // Disable RSS feeds (not typically needed for a service site)
        // Uncomment if you want to disable:
        // remove_action('wp_head', 'feed_links', 2);
        // remove_action('wp_head', 'feed_links_extra', 3);

        // Remove global styles (Gutenberg)
        remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
        remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

        // Remove classic theme styles if using Elementor
        add_action('wp_enqueue_scripts', function() {
            wp_dequeue_style('classic-theme-styles');
            wp_dequeue_style('wp-block-library');
            wp_dequeue_style('wp-block-library-theme');
        }, 20);
    }

    /**
     * Optimize script loading - dequeue unnecessary scripts
     */
    public function optimize_scripts() {
        if (is_admin()) return;

        // Remove jQuery Migrate (not needed for modern jQuery)
        if (!is_user_logged_in()) {
            wp_deregister_script('jquery-migrate');
        }
    }

    /**
     * Add defer/async to non-critical scripts
     */
    public function add_defer_async($tag, $handle, $src) {
        if (is_admin()) return $tag;

        // Don't defer jQuery core or inline scripts
        $no_defer = ['jquery-core', 'jquery', 'elementor-frontend'];
        if (in_array($handle, $no_defer)) return $tag;

        // Skip if already has defer/async
        if (strpos($tag, 'defer') !== false || strpos($tag, 'async') !== false) {
            return $tag;
        }

        // Add defer to most scripts
        return str_replace(' src=', ' defer src=', $tag);
    }

    /**
     * Restrict REST API for non-authenticated users (security + performance)
     */
    public function restrict_rest_api($result) {
        if (!is_user_logged_in()) {
            // Allow specific public endpoints
            $allowed = [
                '/wp/v2/posts',
                '/wp/v2/pages',
                '/wp/v2/categories',
                '/yoast',
                '/contact-form-7',
                '/elementor',
            ];

            $request_uri = $_SERVER['REQUEST_URI'] ?? '';
            foreach ($allowed as $endpoint) {
                if (strpos($request_uri, $endpoint) !== false) {
                    return $result;
                }
            }
        }
        return $result;
    }

    /**
     * Disable WooCommerce cart fragments AJAX on non-WC pages
     */
    public function disable_wc_fragments() {
        if (!is_admin() && function_exists('is_woocommerce')) {
            if (!is_woocommerce() && !is_cart() && !is_checkout()) {
                wp_dequeue_script('wc-cart-fragments');
            }
        }
    }

    /**
     * Remove query strings from static resources for better caching
     */
    public function remove_query_strings() {
        if (!is_admin()) {
            add_filter('style_loader_src', [$this, 'strip_query_string'], 15);
            add_filter('script_loader_src', [$this, 'strip_query_string'], 15);
        }
    }

    public function strip_query_string($src) {
        if (strpos($src, '?ver=') !== false) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }

    /**
     * Add preconnect hints for external resources
     */
    public function add_preconnect() {
        if (is_admin()) return;
        ?>
<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="dns-prefetch" href="//www.googletagmanager.com">
<link rel="dns-prefetch" href="//connect.facebook.net">
<link rel="dns-prefetch" href="//www.google-analytics.com">
<?php
    }

    /**
     * Disable self-pingbacks
     */
    public function disable_self_pingbacks(&$links) {
        $home = get_option('home');
        foreach ($links as $l => $link) {
            if (0 === strpos($link, $home)) {
                unset($links[$l]);
            }
        }
    }

    /**
     * Optimize WordPress Heartbeat API
     */
    public function optimize_heartbeat() {
        // Disable heartbeat on frontend completely
        if (!is_admin()) {
            wp_deregister_script('heartbeat');
        }
    }

    /**
     * Conditionally load Jet plugins only where needed
     * The Jet ecosystem adds ~15 CSS/JS files. We remove what's not needed per page type.
     */
    public function conditional_jet_loading() {
        if (is_admin()) return;

        // On single blog posts, most Jet features aren't used
        if (is_single() && get_post_type() === 'post') {
            wp_dequeue_script('jet-search');
            wp_dequeue_style('jet-search');
            wp_dequeue_script('jet-search-chosen');
            wp_dequeue_style('jet-search-chosen');
            wp_dequeue_script('jet-tricks');
            wp_dequeue_style('jet-tricks');
            wp_dequeue_script('jet-tricks-frontend');
            wp_dequeue_style('jet-tricks-frontend');
            wp_dequeue_script('tsparticles'); // Heavy particle animation library
            wp_dequeue_script('jet-tabs');
            wp_dequeue_style('jet-tabs');
            wp_dequeue_script('jet-tabs-frontend');
            wp_dequeue_style('jet-tabs-frontend');
        }

        // On archive/category pages
        if (is_archive() || is_category() || is_tag()) {
            wp_dequeue_script('jet-tricks');
            wp_dequeue_style('jet-tricks');
            wp_dequeue_script('jet-tricks-frontend');
            wp_dequeue_style('jet-tricks-frontend');
            wp_dequeue_script('tsparticles');
            wp_dequeue_script('jet-tabs');
            wp_dequeue_style('jet-tabs');
            wp_dequeue_script('jet-tabs-frontend');
            wp_dequeue_style('jet-tabs-frontend');
            wp_dequeue_script('jet-search');
            wp_dequeue_style('jet-search');
            wp_dequeue_script('jet-search-chosen');
            wp_dequeue_style('jet-search-chosen');
        }

        // Remove duplicate imagesloaded (WordPress loads it twice)
        // FlyingPress should handle this but doesn't always
        global $wp_scripts;
        if (isset($wp_scripts->registered['imagesloaded'])) {
            $deps = $wp_scripts->registered;
            $count = 0;
            foreach ($deps as $handle => $dep) {
                if ($handle === 'imagesloaded') $count++;
            }
        }
    }

    /**
     * Disable Dashicons on frontend for non-logged-in users
     */
    public function disable_dashicons() {
        if (!is_user_logged_in()) {
            wp_dequeue_style('dashicons');
            wp_deregister_style('dashicons');
        }
    }

    /**
     * Add resource hints for better loading
     */
    public function resource_hints($urls, $relation_type) {
        if ($relation_type === 'preconnect') {
            $urls[] = [
                'href' => 'https://fonts.googleapis.com',
                'crossorigin' => true,
            ];
        }
        return $urls;
    }

    /**
     * Weekly database optimization
     */
    public function optimize_database() {
        global $wpdb;

        // Delete post revisions older than 30 days
        $wpdb->query(
            "DELETE FROM {$wpdb->posts} WHERE post_type = 'revision'
             AND post_modified < DATE_SUB(NOW(), INTERVAL 30 DAY)"
        );

        // Delete spam and trashed comments
        $wpdb->query(
            "DELETE FROM {$wpdb->comments} WHERE comment_approved IN ('spam', 'trash')"
        );

        // Delete expired transients
        $wpdb->query(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_transient_timeout_%'
             AND option_value < UNIX_TIMESTAMP()"
        );
        $wpdb->query(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_transient_%'
             AND option_name NOT LIKE '%_transient_timeout_%'
             AND option_name NOT IN (
                SELECT REPLACE(option_name, '_timeout', '') FROM {$wpdb->options}
                WHERE option_name LIKE '%_transient_timeout_%'
             )"
        );

        // Optimize tables
        $tables = $wpdb->get_col("SHOW TABLES LIKE '{$wpdb->prefix}%'");
        foreach ($tables as $table) {
            $wpdb->query("OPTIMIZE TABLE `{$table}`");
        }
    }
}

// Initialize
new Brndini_Performance();

// Clean up on deactivation
register_deactivation_hook(__FILE__, function() {
    wp_clear_scheduled_hook('brndini_db_optimize');
});
