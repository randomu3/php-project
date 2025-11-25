<?php

namespace AuraUI\Helpers;

/**
 * Session Tracker
 * 
 * Tracks user sessions with device info and geolocation
 * 
 * @package AuraUI\Helpers
 */
class SessionTracker
{
    private \PDO $db;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = getDB();
    }

    /**
     * Track user session
     *
     * @param int $userId User ID
     * @param string $sessionId PHP session ID
     *
     * @return bool Success status
     */
    public function track(int $userId, string $sessionId): bool
    {
        $ip = $this->getClientIP();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $deviceInfo = $this->parseUserAgent($userAgent);
        $geoInfo = $this->getGeoInfo($ip);

        try {
            // Check if session exists
            $stmt = $this->db->prepare("SELECT id FROM user_sessions_extended WHERE session_id = ?");
            $stmt->execute([$sessionId]);
            
            if ($stmt->fetch()) {
                // Update existing session
                $stmt = $this->db->prepare("
                    UPDATE user_sessions_extended 
                    SET last_activity = NOW(), is_active = 1
                    WHERE session_id = ?
                ");
                $stmt->execute([$sessionId]);
            } else {
                // Create new session
                $stmt = $this->db->prepare("
                    INSERT INTO user_sessions_extended 
                    (user_id, session_id, ip_address, user_agent, country, city, device_type, browser)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $userId,
                    $sessionId,
                    $ip,
                    $userAgent,
                    $geoInfo['country'] ?? null,
                    $geoInfo['city'] ?? null,
                    $deviceInfo['device'],
                    $deviceInfo['browser']
                ]);
            }
            
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * End user session
     *
     * @param string $sessionId PHP session ID
     *
     * @return bool Success status
     */
    public function endSession(string $sessionId): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE user_sessions_extended SET is_active = 0 WHERE session_id = ?");
            $stmt->execute([$sessionId]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Get client IP address
     *
     * @return string IP address
     */
    private function getClientIP(): string
    {
        $headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return '0.0.0.0';
    }

    /**
     * Parse user agent string
     *
     * @param string $userAgent User agent string
     *
     * @return array Device and browser info
     */
    private function parseUserAgent(string $userAgent): array
    {
        $device = 'desktop';
        $browser = 'Unknown';

        // Detect device type
        if (preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $userAgent)) {
            $device = preg_match('/iPad|Tablet/i', $userAgent) ? 'tablet' : 'mobile';
        }

        // Detect browser
        if (preg_match('/Firefox\/[\d.]+/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Edg\/[\d.]+/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/Chrome\/[\d.]+/i', $userAgent) && !preg_match('/Edg/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari\/[\d.]+/i', $userAgent) && !preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Opera|OPR/i', $userAgent)) {
            $browser = 'Opera';
        }

        return ['device' => $device, 'browser' => $browser];
    }

    /**
     * Get geo info from IP (basic implementation)
     *
     * @param string $ip IP address
     *
     * @return array Country and city
     */
    private function getGeoInfo(string $ip): array
    {
        // Skip for local IPs
        if (in_array($ip, ['127.0.0.1', '0.0.0.0', '::1']) || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
            return ['country' => 'Local', 'city' => null];
        }

        // You can integrate with ip-api.com or similar service
        // For now, return empty
        return ['country' => null, 'city' => null];
    }

    /**
     * Cleanup old inactive sessions
     *
     * @param int $days Days to keep
     *
     * @return int Number of deleted sessions
     */
    public function cleanup(int $days = 30): int
    {
        $stmt = $this->db->prepare("
            DELETE FROM user_sessions_extended 
            WHERE is_active = 0 AND last_activity < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        $stmt->execute([$days]);
        return $stmt->rowCount();
    }
}
