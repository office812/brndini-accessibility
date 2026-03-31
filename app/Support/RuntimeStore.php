<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

class RuntimeStore
{
    public static function all(string $scope): array
    {
        $path = static::pathForScope($scope);

        if (! File::exists($path)) {
            return [];
        }

        $decoded = json_decode((string) File::get($path), true);

        return is_array($decoded) ? $decoded : [];
    }

    public static function get(string $scope, string $key, mixed $default = null): mixed
    {
        $payload = static::all($scope);

        return $payload[$key] ?? $default;
    }

    public static function put(string $scope, string $key, mixed $value): void
    {
        $payload = static::all($scope);
        $payload[$key] = $value;

        static::write($scope, $payload);
    }

    public static function putMany(string $scope, array $values): void
    {
        $payload = array_merge(static::all($scope), $values);

        static::write($scope, $payload);
    }

    public static function forget(string $scope, ?string $key = null): void
    {
        $path = static::pathForScope($scope);

        if ($key === null) {
            if (File::exists($path)) {
                File::delete($path);
            }

            return;
        }

        $payload = static::all($scope);
        unset($payload[$key]);
        static::write($scope, $payload);
    }

    private static function write(string $scope, array $payload): void
    {
        $path = static::pathForScope($scope);
        File::ensureDirectoryExists(dirname($path));
        file_put_contents($path, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
    }

    private static function pathForScope(string $scope): string
    {
        $safeScope = preg_replace('/[^A-Za-z0-9._-]/', '-', $scope) ?: 'default';

        return storage_path('app/a11y-bridge-runtime/' . $safeScope . '.json');
    }
}
