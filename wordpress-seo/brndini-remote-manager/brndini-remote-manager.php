<?php
/**
 * Plugin Name: Brndini Remote Manager
 * Description: REST API endpoints for remote site management by Claude Code
 * Version: 1.0.0
 * Author: Brndini
 */

if (!defined('ABSPATH')) exit;

add_action('rest_api_init', function () {
    $namespace = 'brndini/v1';

    // GET/SET any WordPress option
    register_rest_route($namespace, '/option', [
        [
            'methods' => 'GET',
            'callback' => 'brndini_get_option',
            'permission_callback' => 'brndini_check_admin',
            'args' => ['name' => ['required' => true]],
        ],
        [
            'methods' => 'POST',
            'callback' => 'brndini_set_option',
            'permission_callback' => 'brndini_check_admin',
        ],
    ]);

    // Delete option
    register_rest_route($namespace, '/option/delete', [
        'methods' => 'POST',
        'callback' => 'brndini_delete_option',
        'permission_callback' => 'brndini_check_admin',
    ]);

    // Search options
    register_rest_route($namespace, '/options/search', [
        'methods' => 'GET',
        'callback' => 'brndini_search_options',
        'permission_callback' => 'brndini_check_admin',
        'args' => ['like' => ['required' => true]],
    ]);

    // Plugin management
    register_rest_route($namespace, '/plugin/install', [
        'methods' => 'POST',
        'callback' => 'brndini_install_plugin',
        'permission_callback' => 'brndini_check_admin',
    ]);

    register_rest_route($namespace, '/plugin/activate', [
        'methods' => 'POST',
        'callback' => 'brndini_activate_plugin',
        'permission_callback' => 'brndini_check_admin',
    ]);

    register_rest_route($namespace, '/plugin/deactivate', [
        'methods' => 'POST',
        'callback' => 'brndini_deactivate_plugin',
        'permission_callback' => 'brndini_check_admin',
    ]);

    // Cache purge
    register_rest_route($namespace, '/cache/purge', [
        'methods' => 'POST',
        'callback' => 'brndini_purge_cache',
        'permission_callback' => 'brndini_check_admin',
    ]);

    // File operations
    register_rest_route($namespace, '/file/read', [
        'methods' => 'GET',
        'callback' => 'brndini_read_file',
        'permission_callback' => 'brndini_check_admin',
        'args' => ['path' => ['required' => true]],
    ]);

    register_rest_route($namespace, '/file/write', [
        'methods' => 'POST',
        'callback' => 'brndini_write_file',
        'permission_callback' => 'brndini_check_admin',
    ]);

    register_rest_route($namespace, '/file/list', [
        'methods' => 'GET',
        'callback' => 'brndini_list_files',
        'permission_callback' => 'brndini_check_admin',
        'args' => ['path' => ['required' => true]],
    ]);

    // WP eval (run PHP code)
    register_rest_route($namespace, '/eval', [
        'methods' => 'POST',
        'callback' => 'brndini_wp_eval',
        'permission_callback' => 'brndini_check_admin',
    ]);

    // DB query (SELECT only)
    register_rest_route($namespace, '/db/query', [
        'methods' => 'POST',
        'callback' => 'brndini_db_query',
        'permission_callback' => 'brndini_check_admin',
    ]);

    // Ping
    register_rest_route($namespace, '/ping', [
        'methods' => 'GET',
        'callback' => function () {
            return new WP_REST_Response([
                'ok' => true,
                'wp' => get_bloginfo('version'),
                'php' => phpversion(),
                'time' => current_time('mysql'),
                'site' => get_site_url(),
            ]);
        },
        'permission_callback' => 'brndini_check_admin',
    ]);
});

function brndini_check_admin() {
    return current_user_can('manage_options');
}

// ─── OPTIONS ────────────────────────────────────────────────────────────────

function brndini_get_option(WP_REST_Request $req) {
    global $wpdb;
    $name = $req->get_param('name');
    // Direct DB query to preserve case sensitivity
    $row = $wpdb->get_row($wpdb->prepare(
        "SELECT option_value FROM {$wpdb->options} WHERE option_name = %s", $name
    ));
    if (!$row) {
        return new WP_REST_Response(['error' => 'not found'], 404);
    }
    $value = maybe_unserialize($row->option_value);
    return new WP_REST_Response(['name' => $name, 'value' => $value]);
}

function brndini_set_option(WP_REST_Request $req) {
    global $wpdb;
    $body = $req->get_json_params();
    $name = $body['name'] ?? '';
    $value = $body['value'] ?? null;
    if (!$name) {
        return new WP_REST_Response(['error' => 'missing name'], 400);
    }
    // Direct DB query to preserve case sensitivity
    $serialized = maybe_serialize($value);
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name = %s", $name
    ));
    if ($exists) {
        $result = $wpdb->update($wpdb->options,
            ['option_value' => $serialized],
            ['option_name' => $name]
        );
    } else {
        $result = $wpdb->insert($wpdb->options, [
            'option_name' => $name,
            'option_value' => $serialized,
            'autoload' => 'yes',
        ]);
    }
    // Clear cache
    wp_cache_delete($name, 'options');
    return new WP_REST_Response(['ok' => $result !== false, 'name' => $name]);
}

function brndini_delete_option(WP_REST_Request $req) {
    global $wpdb;
    $body = $req->get_json_params();
    $name = $body['name'] ?? '';
    $wpdb->delete($wpdb->options, ['option_name' => $name]);
    wp_cache_delete($name, 'options');
    return new WP_REST_Response(['ok' => true, 'deleted' => $wpdb->rows_affected]);
}

function brndini_search_options(WP_REST_Request $req) {
    global $wpdb;
    $like = $req->get_param('like');
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT option_name, LENGTH(option_value) as size FROM {$wpdb->options}
         WHERE option_name LIKE %s LIMIT 50",
        '%' . $wpdb->esc_like($like) . '%'
    ), ARRAY_A);
    return new WP_REST_Response(['results' => $results]);
}

// ─── PLUGINS ────────────────────────────────────────────────────────────────

function brndini_install_plugin(WP_REST_Request $req) {
    $body = $req->get_json_params();
    $url = $body['url'] ?? '';
    if (!$url) {
        return new WP_REST_Response(['error' => 'missing url'], 400);
    }
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    require_once ABSPATH . 'wp-admin/includes/plugin.php';

    $skin = new WP_Ajax_Upgrader_Skin();
    $upgrader = new Plugin_Upgrader($skin);
    $result = $upgrader->install($url);
    return new WP_REST_Response([
        'ok' => $result === true,
        'messages' => $skin->get_upgrade_messages(),
    ]);
}

function brndini_activate_plugin(WP_REST_Request $req) {
    $body = $req->get_json_params();
    $plugin = $body['plugin'] ?? '';
    $result = activate_plugin($plugin);
    return new WP_REST_Response([
        'ok' => !is_wp_error($result),
        'error' => is_wp_error($result) ? $result->get_error_message() : null,
    ]);
}

function brndini_deactivate_plugin(WP_REST_Request $req) {
    $body = $req->get_json_params();
    $plugin = $body['plugin'] ?? '';
    deactivate_plugins($plugin);
    return new WP_REST_Response(['ok' => true]);
}

// ─── CACHE ──────────────────────────────────────────────────────────────────

function brndini_purge_cache() {
    $method = 'wp_cache_flush';
    if (class_exists('FlyingPress\Purge')) {
        \FlyingPress\Purge::purge_everything();
        $method = 'FlyingPress';
    } elseif (function_exists('flying_press_purge_everything')) {
        flying_press_purge_everything();
        $method = 'flying_press_function';
    }
    wp_cache_flush();
    return new WP_REST_Response(['ok' => true, 'method' => $method]);
}

// ─── FILES ──────────────────────────────────────────────────────────────────

function brndini_read_file(WP_REST_Request $req) {
    $path = $req->get_param('path');
    $abs = realpath(ABSPATH . $path);
    if (!$abs || strpos($abs, realpath(ABSPATH)) !== 0 || !is_file($abs)) {
        return new WP_REST_Response(['error' => 'not found'], 404);
    }
    return new WP_REST_Response([
        'content' => base64_encode(file_get_contents($abs)),
        'size' => filesize($abs),
    ]);
}

function brndini_write_file(WP_REST_Request $req) {
    $body = $req->get_json_params();
    $path = $body['path'] ?? '';
    $content = $body['content'] ?? '';
    if (!$path) {
        return new WP_REST_Response(['error' => 'missing path'], 400);
    }
    $abs = ABSPATH . $path;
    wp_mkdir_p(dirname($abs));
    $bytes = file_put_contents($abs, $content);
    return new WP_REST_Response(['ok' => $bytes !== false, 'bytes' => $bytes]);
}

function brndini_list_files(WP_REST_Request $req) {
    $path = $req->get_param('path');
    $abs = realpath(ABSPATH . $path);
    if (!$abs || strpos($abs, realpath(ABSPATH)) !== 0 || !is_dir($abs)) {
        return new WP_REST_Response(['error' => 'not found'], 404);
    }
    $items = [];
    foreach (array_diff(scandir($abs), ['.', '..']) as $f) {
        $fp = $abs . '/' . $f;
        $items[] = [
            'name' => $f,
            'type' => is_dir($fp) ? 'dir' : 'file',
            'size' => is_file($fp) ? filesize($fp) : 0,
        ];
    }
    return new WP_REST_Response($items);
}

// ─── EVAL ───────────────────────────────────────────────────────────────────

function brndini_wp_eval(WP_REST_Request $req) {
    $body = $req->get_json_params();
    $code = $body['code'] ?? '';
    if (!$code) {
        return new WP_REST_Response(['error' => 'missing code'], 400);
    }
    ob_start();
    try {
        eval($code);
        $output = ob_get_clean();
        return new WP_REST_Response(['ok' => true, 'output' => $output]);
    } catch (\Throwable $e) {
        ob_end_clean();
        return new WP_REST_Response(['error' => $e->getMessage()], 500);
    }
}

// ─── DB QUERY ───────────────────────────────────────────────────────────────

function brndini_db_query(WP_REST_Request $req) {
    global $wpdb;
    $body = $req->get_json_params();
    $sql = $body['sql'] ?? '';
    if (!$sql || !preg_match('/^\s*SELECT\s/i', $sql)) {
        return new WP_REST_Response(['error' => 'only SELECT queries allowed'], 400);
    }
    $results = $wpdb->get_results($sql, ARRAY_A);
    return new WP_REST_Response(['results' => $results, 'rows' => count($results)]);
}
