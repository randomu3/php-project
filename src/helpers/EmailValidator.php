<?php

namespace AuraUI\Helpers {

    /**
     * Email Validator
     *
     * Validates email addresses and checks for disposable/spam domains
     *
     * @package AuraUI\Helpers
     */
    class EmailValidator
    {
        /**
         * List of known disposable email domains
         *
         * @var array
         */
        private static array $disposableDomains = [
            'tempmail.com',
            'guerrillamail.com',
            '10minutemail.com',
            'mailinator.com',
            'throwaway.email',
            'temp-mail.org',
            'fakeinbox.com',
            'trashmail.com',
            'yopmail.com',
            'maildrop.cc',
            'getnada.com',
            'temp-mail.io',
            'mohmal.com',
            'sharklasers.com',
        ];

        /**
         * Validate email address
         *
         * @param string $email Email address to validate
         *
         * @return array Result with 'valid' boolean and 'error' message
         */
        public static function validate(string $email): array
        {
            // Basic format validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['valid' => false, 'error' => 'Некорректный формат email'];
            }

            // Extract domain
            $domain = strtolower(substr(strrchr($email, "@"), 1));

            // Check if disposable domain
            if (in_array($domain, self::$disposableDomains)) {
                return [
                    'valid' => false,
                    'error' => 'Временные email адреса не разрешены'
                ];
            }

            // Check if domain has MX records (real email server)
            if (!self::hasMXRecord($domain)) {
                return [
                    'valid' => false,
                    'error' => 'Email домен не существует или не принимает письма'
                ];
            }

            return ['valid' => true, 'error' => ''];
        }

        /**
         * Check if domain has MX records
         *
         * @param string $domain Domain to check
         *
         * @return bool True if domain has MX records
         */
        private static function hasMXRecord(string $domain): bool
        {
            // Skip MX check for localhost/development
            if (in_array($domain, ['localhost', 'example.com', 'test.com'])) {
                return true;
            }

            return checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A');
        }

        /**
         * Add disposable domain to blacklist
         *
         * @param string $domain Domain to add
         *
         * @return void
         */
        public static function addDisposableDomain(string $domain): void
        {
            $domain = strtolower($domain);
            if (!in_array($domain, self::$disposableDomains)) {
                self::$disposableDomains[] = $domain;
            }
        }
    }
}
