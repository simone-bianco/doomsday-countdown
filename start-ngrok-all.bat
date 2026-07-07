@echo off
setlocal EnableExtensions

echo ==================================================
echo  AI Laravel Starter - NGROK ALL-IN-ONE
echo ==================================================
echo.

echo [1/4] Safe startup checks...
echo This script does not stop existing ngrok, php, or artisan processes.
echo If ports 8000 or ngrok are already in use, close only the starter-owned process and rerun.
echo OK
echo.

echo [2/4] Prepare local starter files...
if not exist ".env" copy ".env.example" ".env" >nul
if not exist "database\database.sqlite" type nul > "database\database.sqlite"
php artisan config:clear
php artisan migrate --force
php artisan form-bridge:generate
echo OK
echo.

echo [3/4] Build frontend assets...
call npm run build
if %ERRORLEVEL% neq 0 exit /b 1
echo OK
echo.

echo [4/4] Start app, queue, and ngrok for this starter window...
npx concurrently --kill-others --names "APP,QUEUE,NGROK" --prefix-colors "green,cyan,magenta" "php artisan serve --host=127.0.0.1 --port=8000" "php artisan queue:work --tries=1 --timeout=300" "ngrok http 127.0.0.1:8000"

endlocal
