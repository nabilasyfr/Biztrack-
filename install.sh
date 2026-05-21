#!/bin/bash
# ============================================================
# BizTrack UMKM — Installer Script
# ============================================================
# Jalankan: bash install.sh
# ============================================================

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
BOLD='\033[1m'
NC='\033[0m'

echo ""
echo -e "${BLUE}${BOLD}=====================================${NC}"
echo -e "${BLUE}${BOLD}   🏪 BizTrack UMKM - Installer     ${NC}"
echo -e "${BLUE}${BOLD}=====================================${NC}"
echo ""

# Check PHP
if ! command -v php &> /dev/null; then
    echo -e "${RED}❌ PHP tidak ditemukan. Install PHP >= 8.2 terlebih dahulu.${NC}"
    exit 1
fi
PHP_VER=$(php -r "echo PHP_VERSION;")
echo -e "${GREEN}✅ PHP: $PHP_VER${NC}"

# Check Composer
if ! command -v composer &> /dev/null; then
    echo -e "${RED}❌ Composer tidak ditemukan. Install Composer terlebih dahulu.${NC}"
    exit 1
fi
echo -e "${GREEN}✅ Composer ditemukan${NC}"

# Install dependencies
echo ""
echo -e "${YELLOW}📦 Menginstall dependencies...${NC}"
composer install --no-interaction --prefer-dist --optimize-autoloader

# Setup .env
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚙️  Membuat file .env...${NC}"
    cp .env.example .env
fi

# Generate key
echo -e "${YELLOW}🔑 Generate application key...${NC}"
php artisan key:generate --force

# Ask DB config
echo ""
echo -e "${BOLD}Database Configuration:${NC}"
read -p "DB Host [127.0.0.1]: " DB_HOST
DB_HOST=${DB_HOST:-127.0.0.1}
read -p "DB Port [3306]: " DB_PORT
DB_PORT=${DB_PORT:-3306}
read -p "DB Name [biztrack_umkm]: " DB_NAME
DB_NAME=${DB_NAME:-biztrack_umkm}
read -p "DB Username [root]: " DB_USER
DB_USER=${DB_USER:-root}
read -s -p "DB Password: " DB_PASS
echo ""

# Update .env
sed -i "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env
sed -i "s/DB_PORT=.*/DB_PORT=$DB_PORT/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASS/" .env

# Set permissions
echo -e "${YELLOW}🔒 Mengatur permissions...${NC}"
chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache 2>/dev/null || true

# Run migrations
echo -e "${YELLOW}🗄️  Menjalankan migrasi & seeder...${NC}"
php artisan migrate --seed --force

echo ""
echo -e "${GREEN}${BOLD}=====================================${NC}"
echo -e "${GREEN}${BOLD}   ✅ Instalasi selesai!             ${NC}"
echo -e "${GREEN}${BOLD}=====================================${NC}"
echo ""
echo -e "Jalankan server:"
echo -e "${BLUE}  php artisan serve${NC}"
echo ""
echo -e "Buka browser: ${BLUE}http://localhost:8000${NC}"
echo ""
echo -e "${BOLD}Demo Login:${NC}"
echo -e "  Owner:  owner@biztrack.com / password"
echo -e "  Kasir:  cashier@biztrack.com / password"
echo ""
