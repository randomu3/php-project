@echo off
echo ========================================
echo   AuraUI Code Formatter and Fixer
echo ========================================
echo.
echo [1/4] Fixing PHPDoc duplicates...
docker exec phpproject-web-1 php /var/www/tools/fix-phpdoc-duplicates.php
echo.
echo [2/4] Adding missing PHPDoc...
docker exec phpproject-web-1 php /var/www/tools/add-phpdoc-complete.php
echo.
echo [3/4] Formatting with PHP-CS-Fixer (PSR-12)...
docker exec phpproject-web-1 vendor/bin/php-cs-fixer fix /var/www/html --config=/var/www/html/.php-cs-fixer.php
echo.
echo [4/4] Copying files back to host...
docker cp phpproject-web-1:/var/www/html/controllers/. src/controllers/
docker cp phpproject-web-1:/var/www/html/helpers/. src/helpers/
docker cp phpproject-web-1:/var/www/html/core/. src/core/
echo.
echo ========================================
echo   All done! Your code is formatted.
echo ========================================
pause
