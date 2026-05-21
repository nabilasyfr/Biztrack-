@echo off
cls
echo =====================================
echo    BizTrack UMKM - Windows Installer
echo =====================================
echo.

echo [1/5] Memeriksa PHP...
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP tidak ditemukan! Install PHP 8.2+ terlebih dahulu.
    pause
    exit /b 1
)
echo [OK] PHP ditemukan

echo [2/5] Install Composer dependencies...
composer install --no-interaction --prefer-dist --optimize-autoloader
if %errorlevel% neq 0 (
    echo [ERROR] Composer install gagal!
    pause
    exit /b 1
)

echo [3/5] Setup file .env...
if not exist .env (
    copy .env.example .env
)

echo [4/5] Generate application key...
php artisan key:generate --force

echo.
echo [PENTING] Edit file .env dan sesuaikan konfigurasi database:
echo   DB_DATABASE=biztrack_umkm
echo   DB_USERNAME=root
echo   DB_PASSWORD=your_password
echo.
echo Buat database MySQL dengan nama: biztrack_umkm
echo Kemudian tekan ENTER untuk lanjut...
pause

echo [5/5] Menjalankan migrasi dan seeder...
php artisan migrate --seed --force

echo.
echo =====================================
echo    INSTALASI SELESAI!
echo =====================================
echo.
echo Jalankan server dengan:
echo   php artisan serve
echo.
echo Buka browser: http://localhost:8000
echo.
echo Demo Login:
echo   Owner:  owner@biztrack.com / password
echo   Kasir:  cashier@biztrack.com / password
echo.
pause
