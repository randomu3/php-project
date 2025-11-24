<?php

namespace AuraUI\Helpers;

/**
 *  Cache Helper
 *
 * @package AuraUI\Helpers
 */
class CacheHelper
{
    /**
     * Generate E Tag
     *
     * @param  $content Parameter
     *
     * @return string String value
     */
    public static function generateETag($content): string
    {
        return md5($content);
    }

    /**
     * Check E Tag
     *
     * @param string $etag Parameter
     *
     * @return void
     */
    public static function checkETag(string $etag): void
    {
        $clientETag = $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';

        if ($clientETag === $etag) {
            header('HTTP/1.1 304 Not Modified');
            header('ETag: ' . $etag);
            exit;
        }

        header('ETag: ' . $etag);
    }

    /**
     * Set Cache Headers
     *
     * @param  $maxAge Parameter
     *
     * @return void
     */
    public static function setCacheHeaders($maxAge = 3600): void
    {
        header('Cache-Control: public, max-age=' . $maxAge);
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
    }

    /**
     * Set Dynamic Headers
     *
     * @param  $maxAge Parameter
     *
     * @return void
     */
    public static function setDynamicHeaders($maxAge = 600): void
    {
        header(sprintf('Cache-Control: public, max-age=%s, must-revalidate', $maxAge));
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
    }

    /**
     * No Cache
     *
     * @return void
     */
    public static function noCache(): void
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");
    }

    /**
     * Clear O Pcache
     */
    public static function clearOPcache()
    {
        if (function_exists('opcache_reset')) {
            return opcache_reset();
        }

        return false;
    }

    /**
     * Get O Pcache Stats
     *
     * @return array|false|null
     */
    public static function getOPcacheStats(): array|false|null
    {
        if (function_exists('opcache_get_status')) {
            return opcache_get_status(false);
        }

        return null;
    }

    /**
     * Asset
     *
     * @param string $path Parameter
     * @param  $version Parameter
     *
     * @return string String value
     */
    public static function asset(string $path, $version = null): string
    {
        $version ??= ASSET_VERSION;
        return $path . '?v=' . $version;
    }
}
