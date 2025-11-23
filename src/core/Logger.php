<?php
/**
 * Logger - система логирования
 */
class Logger {
    private static $logDir = __DIR__ . '/../../logs';
    
    const ERROR = 'ERROR';
    const WARNING = 'WARNING';
    const INFO = 'INFO';
    const DEBUG = 'DEBUG';
    const AUTH = 'AUTH';
    const ADMIN = 'ADMIN';
    const EMAIL = 'EMAIL';
    
    /**
     * Логировать сообщение
     */
    public static function log($level, $message, $context = []) {
        self::ensureLogDir();
        
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userId = $_SESSION['user_id'] ?? 'guest';
        
        // Форматируем контекст
        $contextStr = !empty($context) ? ' | ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        
        // Формируем строку лога
        $logLine = sprintf(
            "[%s] [%s] [IP: %s] [User: %s] %s%s\n",
            $timestamp,
            $level,
            $ip,
            $userId,
            $message,
            $contextStr
        );
        
        // Определяем файл лога
        $filename = self::getLogFilename($level);
        
        // Записываем в файл
        file_put_contents($filename, $logLine, FILE_APPEND | LOCK_EX);
        
        // Также записываем критичные ошибки в общий лог
        if ($level === self::ERROR) {
            error_log($message);
        }
    }
    
    /**
     * Логировать ошибку
     */
    public static function error($message, $context = []) {
        self::log(self::ERROR, $message, $context);
    }
    
    /**
     * Логировать предупреждение
     */
    public static function warning($message, $context = []) {
        self::log(self::WARNING, $message, $context);
    }
    
    /**
     * Логировать информацию
     */
    public static function info($message, $context = []) {
        self::log(self::INFO, $message, $context);
    }
    
    /**
     * Логировать отладку
     */
    public static function debug($message, $context = []) {
        self::log(self::DEBUG, $message, $context);
    }
    
    /**
     * Логировать аутентификацию
     */
    public static function auth($message, $context = []) {
        self::log(self::AUTH, $message, $context);
    }
    
    /**
     * Логировать действия админа
     */
    public static function admin($message, $context = []) {
        self::log(self::ADMIN, $message, $context);
    }
    
    /**
     * Логировать отправку email
     */
    public static function email($message, $context = []) {
        self::log(self::EMAIL, $message, $context);
    }
    
    /**
     * Получить имя файла лога
     */
    private static function getLogFilename($level) {
        $date = date('Y-m-d');
        
        switch ($level) {
            case self::AUTH:
                return self::$logDir . "/auth-{$date}.log";
            case self::ADMIN:
                return self::$logDir . "/admin-{$date}.log";
            case self::EMAIL:
                return self::$logDir . "/email-{$date}.log";
            case self::ERROR:
                return self::$logDir . "/error-{$date}.log";
            default:
                return self::$logDir . "/app-{$date}.log";
        }
    }
    
    /**
     * Создать директорию для логов если не существует
     */
    private static function ensureLogDir() {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }
        
        // Создаем .gitignore чтобы не коммитить логи
        $gitignore = self::$logDir . '/.gitignore';
        if (!file_exists($gitignore)) {
            file_put_contents($gitignore, "*\n!.gitignore\n");
        }
    }
    
    /**
     * Очистить старые логи (старше N дней)
     */
    public static function cleanup($days = 30) {
        self::ensureLogDir();
        
        $files = glob(self::$logDir . '/*.log');
        $cutoff = time() - ($days * 24 * 60 * 60);
        
        foreach ($files as $file) {
            if (filemtime($file) < $cutoff) {
                unlink($file);
            }
        }
    }
    
    /**
     * Получить последние N строк из лога
     */
    public static function tail($type = 'app', $lines = 100) {
        $date = date('Y-m-d');
        $filename = self::$logDir . "/{$type}-{$date}.log";
        
        if (!file_exists($filename)) {
            return [];
        }
        
        $file = new SplFileObject($filename);
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key();
        
        $startLine = max(0, $totalLines - $lines);
        $result = [];
        
        $file->seek($startLine);
        while (!$file->eof()) {
            $line = $file->current();
            if (!empty(trim($line))) {
                $result[] = $line;
            }
            $file->next();
        }
        
        return $result;
    }
}
