#!/bin/bash

# =============================================================================
# TimeKeeper Quick Update Script
# =============================================================================
# Usage: ./quick-update.sh
# Deskripsi: Script untuk update cepat tanpa backup lengkap (untuk perubahan kecil)
# =============================================================================

set -e

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m'

log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

echo "=========================================="
echo "  TimeKeeper Quick Update"
echo "=========================================="

# Check if git repo
if [ ! -d ".git" ]; then
    echo "Error: Not a git repository!"
    exit 1
fi

# Pull latest changes
log_info "Pulling latest changes..."
git pull

# Check if composer.lock changed
if git diff HEAD~1 --name-only | grep -q "composer.lock"; then
    log_info "Composer dependencies changed, updating..."
    composer install --no-dev --optimize-autoloader
fi

# Check if package-lock.json changed
if git diff HEAD~1 --name-only | grep -q "package-lock.json\|package.json"; then
    log_info "NPM dependencies changed, updating..."
    npm ci
    npm run build
fi

# Check if migrations added
if git diff HEAD~1 --name-only | grep -q "database/migrations"; then
    log_info "New migrations detected, running..."
    php artisan migrate --force
fi

# Always clear cache for safety
log_info "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optimize
log_info "Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

log_success "🚀 Quick update completed!"
echo "=========================================="