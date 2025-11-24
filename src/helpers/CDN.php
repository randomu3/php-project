<?php

namespace AuraUI\Helpers;

/**
 *  C D N
 *
 * @package AuraUI\Helpers
 */
class CDN
{
    /**
     * CDN URL
     *
     * @var string
     */
    private static string $cdnUrl = '';

    /**
     * CDN enabled flag
     *
     * @var bool
     */
    private static bool $enabled = false;

    /**
     * Local URL
     *
     * @var string
     */
    private static string $localUrl = '';

    /**
     * Init
     *
     * @param mixed $cdnUrl Parameter
     * @param mixed $enabled Parameter
     *
     * @return void
     */
    public static function init(mixed $cdnUrl = null, mixed $enabled = null): void
    {
        self::$cdnUrl = $cdnUrl ?? getenv('CDN_URL') ?? '';
        self::$enabled = $enabled ?? (getenv('CDN_ENABLED') === 'true');
        self::$localUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }

    /**
     * Asset
     *
     * @param mixed $path Parameter
     *
     * @return string String value
     */
    public static function asset(mixed $path): string
    {
        // Убираем начальный слеш если есть
        $path = ltrim($path, '/');

        // Если CDN включен - используем CDN URL
        if (self::$enabled && !empty(self::$cdnUrl)) {
            return self::$cdnUrl . '/' . $path;
        }

        // Иначе локальный URL
        return '/' . $path;
    }

    /**
     * Image
     *
     * @param mixed $path Parameter
     *
     * @return string String value
     */
    public static function image(mixed $path): string
    {
        return self::asset($path);
    }

    /**
     * Css
     *
     * @param mixed $path Parameter
     *
     * @return string String value
     */
    public static function css(mixed $path): string
    {
        return self::asset($path);
    }

    /**
     * Js
     *
     * @param mixed $path Parameter
     *
     * @return string String value
     */
    public static function js(mixed $path): string
    {
        return self::asset($path);
    }

    /**
     * Is Enabled
     */
    public static function isEnabled()
    {
        return self::$enabled;
    }

    /**
     * Get Popular C D Ns
     *
     * @return array Data array
     */
    public static function getPopularCDNs(): array
    {
        return [
            'cloudflare' => [
                'name' => 'Cloudflare',
                'url' => 'https://www.cloudflare.com',
                'free' => true,
                'features' => ['Unlimited bandwidth', 'DDoS protection', 'SSL', 'Cache'],
                'setup' => 'Change nameservers to Cloudflare'
            ],
            'jsdelivr' => [
                'name' => 'jsDelivr',
                'url' => 'https://www.jsdelivr.com',
                'free' => true,
                'features' => ['NPM packages', 'GitHub repos', 'WordPress plugins'],
                'setup' => 'Use: https://cdn.jsdelivr.net/gh/user/repo@version/file'
            ],
            'unpkg' => [
                'name' => 'unpkg',
                'url' => 'https://unpkg.com',
                'free' => true,
                'features' => ['NPM packages', 'Fast', 'Simple'],
                'setup' => 'Use: https://unpkg.com/package@version/file'
            ],
            'bunny' => [
                'name' => 'BunnyCDN',
                'url' => 'https://bunny.net',
                'free' => false,
                'price' => '$1/TB',
                'features' => ['Fast', 'Cheap', 'Easy setup', 'Storage'],
                'setup' => 'Create pull zone, point to your origin'
            ]
        ];
    }

    /**
     * Preconnect Tags
     *
     * @return string String value
     */
    public static function preconnectTags(): string
    {
        if (!self::$enabled || empty(self::$cdnUrl)) {
            return '';
        }

        $domain = parse_url(self::$cdnUrl, PHP_URL_HOST);

        return sprintf(
            '<link rel="dns-prefetch" href="//%s">' . "\n" .
            '<link rel="preconnect" href="%s" crossorigin>',
            $domain,
            self::$cdnUrl
        );
    }

    /**
     * Sync
     *
     * @param mixed $directory Parameter
     * @param mixed $cdnConfig Parameter
     *
     * @return array Data array
     */
    public static function sync(mixed $directory, mixed $cdnConfig): array
    {
        // Это зависит от конкретного CDN провайдера
        // Пример для BunnyCDN или S3-compatible storage

        Logger::info('CDN sync started', ['directory' => $directory]);

        // Здесь будет логика синхронизации
        // Например, через FTP, S3 API, или rsync

        return [
            'success' => true,
            'files_synced' => 0,
            'message' => 'CDN sync not implemented yet'
        ];
    }
}

// Инициализируем при загрузке
CDN::init();
