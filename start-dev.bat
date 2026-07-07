@echo off
setlocal EnableExtensions

echo ==================================================
echo  AI Laravel Starter - DEV
echo ==================================================
echo.

if not exist ".env" copy ".env.example" ".env" >nul
if not exist "database\database.sqlite" type nul > "database\database.sqlite"

php artisan config:clear
php artisan migrate --force
php artisan form-bridge:generate

npx concurrently --kill-others --names "APP,QUEUE,VITE" --prefix-colors "green,cyan,magenta" "php artisan serve --host=127.0.0.1 --port=8000" "php artisan queue:work --tries=1 --timeout=300" "npm run dev"

endlocal
