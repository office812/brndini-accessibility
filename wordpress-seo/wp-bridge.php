<?php
/**
 * WordPress Bridge for Claude Code
 * Upload this file to your WordPress root directory (next to wp-config.php)
 * Access: https://brndini.co.il/wp-bridge.php?key=YOUR_KEY&action=ping
 */

$SECRET = 'brnd1n1-wp-2026';

if (($_GET['key'] ?? '') !== $SECRET) {
    http_response_code(403);
    die(json_encode(['error' => 'denied']));
}

header('Content-Type: application/json; charset=utf-8');

// Load WordPress
define('SHORTINIT', false);
require_once __DIR__ . '/wp-load.php';

$action = $_GET['action'] ?? 'ping';

// ─── PING ────────────────────────────────────────────────────────────────
if ($action === 'ping') {
    echo json_encode([
        'ok' => true,
        'time' => current_time('mysql'),
        'wp' => get_bloginfo('version'),
        'php' => phpversion(),
        'site' => get_site_url(),
    ]);

// ─── GET OPTION ──────────────────────────────────────────────────────────
} elseif ($action === 'get-option') {
    $name = sanitize_key($_GET['name'] ?? '');
    if (!$name) {
        echo json_encode(['error' => 'missing name']);
    } else {
        $value = get_option($name, '__NOT_FOUND__');
        echo json_encode(['name' => $name, 'value' => $value]);
    }

// ─── SET OPTION ──────────────────────────────────────────────────────────
} elseif ($action === 'set-option') {
    $name = sanitize_key($_GET['name'] ?? '');
    $value = $_GET['value'] ?? null;
    if (!$name) {
        echo json_encode(['error' => 'missing name']);
    } else {
        // If value looks like JSON, decode it
        if ($value && ($value[0] === '{' || $value[0] === '[')) {
            $decoded = json_decode($value, true);
            if ($decoded !== null) $value = $decoded;
        }
        $result = update_option($name, $value);
        echo json_encode(['ok' => $result, 'name' => $name]);
    }

// ─── UPDATE OPTION (JSON merge) ─────────────────────────────────────────
} elseif ($action === 'update-option') {
    $name = sanitize_key($_GET['name'] ?? '');
    $updates = json_decode($_GET['updates'] ?? '{}', true);
    if (!$name || !$updates) {
        echo json_encode(['error' => 'missing name or updates']);
    } else {
        $current = get_option($name, []);
        if (is_array($current)) {
            $merged = array_merge($current, $updates);
            $result = update_option($name, $merged);
            echo json_encode(['ok' => $result, 'name' => $name, 'keys_updated' => array_keys($updates)]);
        } else {
            echo json_encode(['error' => 'option is not an array', 'type' => gettype($current)]);
        }
    }

// ─── LIST OPTIONS (search) ──────────────────────────────────────────────
} elseif ($action === 'search-options') {
    global $wpdb;
    $like = sanitize_text_field($_GET['like'] ?? '');
    if (!$like) {
        echo json_encode(['error' => 'missing like parameter']);
    } else {
        $results = $wpdb->get_results(
            $wpdb->prepare("SELECT option_name, LENGTH(option_value) as size FROM {$wpdb->options} WHERE option_name LIKE %s LIMIT 50", '%' . $wpdb->esc_like($like) . '%'),
            ARRAY_A
        );
        echo json_encode(['results' => $results]);
    }

// ─── PLUGIN INSTALL (from URL) ──────────────────────────────────────────
} elseif ($action === 'plugin-install') {
    $url = esc_url_raw($_GET['url'] ?? '');
    if (!$url) {
        echo json_encode(['error' => 'missing url']);
    } else {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        $skin = new WP_Ajax_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader($skin);
        $result = $upgrader->install($url);
        echo json_encode([
            'ok' => $result === true,
            'result' => is_wp_error($result) ? $result->get_error_message() : $result,
            'messages' => $skin->get_upgrade_messages(),
        ]);
    }

// ─── PLUGIN ACTIVATE ────────────────────────────────────────────────────
} elseif ($action === 'plugin-activate') {
    $plugin = sanitize_text_field($_GET['plugin'] ?? '');
    if (!$plugin) {
        echo json_encode(['error' => 'missing plugin']);
    } else {
        $result = activate_plugin($plugin);
        echo json_encode([
            'ok' => !is_wp_error($result),
            'error' => is_wp_error($result) ? $result->get_error_message() : null,
        ]);
    }

// ─── PLUGIN DEACTIVATE ──────────────────────────────────────────────────
} elseif ($action === 'plugin-deactivate') {
    $plugin = sanitize_text_field($_GET['plugin'] ?? '');
    deactivate_plugins($plugin);
    echo json_encode(['ok' => true, 'plugin' => $plugin]);

// ─── PLUGIN LIST ────────────────────────────────────────────────────────
} elseif ($action === 'plugin-list') {
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $all = get_plugins();
    $active = get_option('active_plugins', []);
    $list = [];
    foreach ($all as $path => $info) {
        $list[] = [
            'path' => $path,
            'name' => $info['Name'],
            'active' => in_array($path, $active),
            'version' => $info['Version'],
        ];
    }
    echo json_encode($list);

// ─── CACHE PURGE ────────────────────────────────────────────────────────
} elseif ($action === 'cache-purge') {
    // FlyingPress
    if (function_exists('flying_press_purge_everything')) {
        flying_press_purge_everything();
        echo json_encode(['ok' => true, 'method' => 'flying_press']);
    } elseif (class_exists('FlyingPress\Purge')) {
        FlyingPress\Purge::purge_everything();
        echo json_encode(['ok' => true, 'method' => 'FlyingPress_class']);
    } else {
        // Try WP cache
        wp_cache_flush();
        echo json_encode(['ok' => true, 'method' => 'wp_cache_flush']);
    }

// ─── FILE READ ──────────────────────────────────────────────────────────
} elseif ($action === 'read') {
    $path = sanitize_text_field($_GET['path'] ?? '');
    $abs = realpath(ABSPATH . $path);
    if (!$abs || strpos($abs, ABSPATH) !== 0) {
        echo json_encode(['error' => 'invalid path']);
    } else {
        echo json_encode(['content' => base64_encode(file_get_contents($abs)), 'size' => filesize($abs)]);
    }

// ─── FILE WRITE ─────────────────────────────────────────────────────────
} elseif ($action === 'write') {
    $path = sanitize_text_field($_GET['path'] ?? '');
    $content = base64_decode($_GET['content'] ?? '');
    if (!$path) {
        echo json_encode(['error' => 'missing path']);
    } else {
        $abs = ABSPATH . $path;
        $dir = dirname($abs);
        if (!is_dir($dir)) wp_mkdir_p($dir);
        $bytes = file_put_contents($abs, $content);
        echo json_encode(['ok' => $bytes !== false, 'bytes' => $bytes]);
    }

// ─── FILE LIST ──────────────────────────────────────────────────────────
} elseif ($action === 'ls') {
    $path = sanitize_text_field($_GET['path'] ?? '');
    $abs = realpath(ABSPATH . $path);
    if (!$abs || strpos($abs, ABSPATH) !== 0 || !is_dir($abs)) {
        echo json_encode(['error' => 'not found']);
    } else {
        $items = [];
        foreach (array_diff(scandir($abs), ['.', '..']) as $f) {
            $fp = $abs . '/' . $f;
            $items[] = ['name' => $f, 'type' => is_dir($fp) ? 'dir' : 'file', 'size' => is_file($fp) ? filesize($fp) : 0];
        }
        echo json_encode($items);
    }

// ─── RUN WP-CLI COMMAND ─────────────────────────────────────────────────
} elseif ($action === 'wp-cli') {
    $cmd = sanitize_text_field($_GET['cmd'] ?? '');
    if (!$cmd) {
        echo json_encode(['error' => 'missing cmd']);
    } else {
        $output = [];
        $code = 0;
        exec('cd ' . ABSPATH . ' && wp ' . escapeshellcmd($cmd) . ' --allow-root 2>&1', $output, $code);
        echo json_encode(['output' => implode("\n", $output), 'code' => $code]);
    }

// ─── DB QUERY (read only) ───────────────────────────────────────────────
} elseif ($action === 'db-query') {
    global $wpdb;
    $sql = stripslashes($_GET['sql'] ?? '');
    if (!$sql || !preg_match('/^SELECT /i', $sql)) {
        echo json_encode(['error' => 'only SELECT queries allowed']);
    } else {
        $results = $wpdb->get_results($sql, ARRAY_A);
        echo json_encode(['results' => $results, 'rows' => count($results)]);
    }

// ─── UNKNOWN ─────────────────────────────────────────────────────────────
} else {
    echo json_encode(['error' => 'unknown action', 'available' => [
        'ping', 'get-option', 'set-option', 'update-option', 'search-options',
        'plugin-install', 'plugin-activate', 'plugin-deactivate', 'plugin-list',
        'cache-purge', 'read', 'write', 'ls', 'wp-cli', 'db-query'
    ]]);
}
