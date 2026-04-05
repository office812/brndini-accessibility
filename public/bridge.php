<?php
/**
 * A11Y Bridge - Server Bridge
 * Direct file read/write access for Claude Code
 */

$SECRET = 'brnd1n1-br1dg3-2026';

if (($_GET['key'] ?? '') !== $SECRET) {
    http_response_code(403);
    die('denied');
}

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$APP  = '/home/535938.cloudwaysapps.com/axfpmrapnb/public_html';
$act  = $_GET['action'] ?? 'ping';

// ─── PING ────────────────────────────────────────────────────────────────────
if ($act === 'ping') {
    echo json_encode([
        'ok'   => true,
        'time' => date('Y-m-d H:i:s'),
        'php'  => phpversion(),
        'user' => get_current_user(),
        'app'  => $APP,
    ]);

// ─── READ FILE ───────────────────────────────────────────────────────────────
} elseif ($act === 'read') {
    $rel = ltrim($_GET['path'] ?? '', '/');
    $abs = realpath($APP . '/' . $rel);
    if (!$abs || strpos($abs, $APP) !== 0 || !is_file($abs)) {
        echo json_encode(['error' => 'not found: ' . $rel]);
    } else {
        echo json_encode(['content' => base64_encode(file_get_contents($abs))]);
    }

// ─── WRITE FILE ──────────────────────────────────────────────────────────────
} elseif ($act === 'write') {
    $rel     = ltrim($_GET['path'] ?? '', '/');
    $content = base64_decode($_GET['content'] ?? '');
    if (!$rel || $content === false) {
        echo json_encode(['error' => 'missing path or content']);
    } else {
        $abs = $APP . '/' . $rel;
        $dir = dirname($abs);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $r = file_put_contents($abs, $content);
        echo json_encode(['ok' => $r !== false, 'bytes' => $r, 'path' => $abs]);
    }

// ─── LIST DIRECTORY ──────────────────────────────────────────────────────────
} elseif ($act === 'list') {
    $rel = ltrim($_GET['path'] ?? '', '/');
    $abs = realpath($APP . '/' . $rel);
    if (!$abs || strpos($abs, $APP) !== 0 || !is_dir($abs)) {
        echo json_encode(['error' => 'not found']);
    } else {
        $items = [];
        foreach (array_diff(scandir($abs), ['.', '..']) as $f) {
            $fp      = $abs . '/' . $f;
            $items[] = [
                'name' => $f,
                'type' => is_dir($fp) ? 'dir' : 'file',
                'size' => is_file($fp) ? filesize($fp) : 0,
            ];
        }
        echo json_encode(['path' => $rel, 'items' => $items]);
    }

// ─── DEPLOY LOG ──────────────────────────────────────────────────────────────
// ─── WRITE TO /tmp/ ──────────────────────────────────────────────────────────
} elseif ($act === 'tmp-write') {
    $filename = basename($_GET['filename'] ?? '');
    $content  = base64_decode($_GET['content'] ?? '');
    if (!$filename || $content === false || $content === '') {
        echo json_encode(['error' => 'missing filename or content']);
    } else {
        $abs = '/tmp/' . $filename;
        $r   = file_put_contents($abs, $content);
        if ($r !== false) chmod($abs, 0755);
        echo json_encode(['ok' => $r !== false, 'bytes' => $r, 'path' => $abs]);
    }

// ─── DEPLOY LOG ──────────────────────────────────────────────────────────────
} elseif ($act === 'log') {
    $logFile = '/tmp/deploy.log';
    $content = file_exists($logFile)
        ? implode('', array_slice(file($logFile), -100))
        : 'No deploy log yet.';
    echo json_encode(['log' => $content]);

// ─── TRIGGER DEPLOY ──────────────────────────────────────────────────────────
} elseif ($act === 'deploy') {
    // Write trigger inside APP dir - accessible by both PHP-FPM and cron
    file_put_contents($APP . '/.deploy-trigger', '1');
    echo json_encode(['queued' => true, 'message' => 'Deploy will run within 60 seconds']);

// ─── APP ENV ─────────────────────────────────────────────────────────────────
} elseif ($act === 'env') {
    $envFile = $APP . '/.env';
    $content = file_exists($envFile) ? file_get_contents($envFile) : 'not found';
    // Strip sensitive keys before returning
    $lines = array_filter(explode("\n", $content), function($line) {
        return !preg_match('/^(DB_PASSWORD|APP_KEY|MAIL_PASSWORD|REDIS_PASSWORD)=/i', $line);
    });
    echo json_encode(['env' => implode("\n", $lines)]);

// ─── LARAVEL LOG ─────────────────────────────────────────────────────────────
} elseif ($act === 'laravel-log') {
    $logFile = $APP . '/storage/logs/laravel.log';
    if (!file_exists($logFile)) {
        echo json_encode(['log' => 'No Laravel log found']);
    } else {
        $lines = array_slice(file($logFile), -80);
        echo json_encode(['log' => implode('', $lines)]);
    }

} else {
    echo json_encode(['error' => 'unknown action: ' . $act]);
}
