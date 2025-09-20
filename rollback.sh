#!/bin/bash

# =============================================================================
# TimeKeeper Rollback Script
# =============================================================================
# Usage: ./rollback.sh [backup_name]
# Deskripsi: Script untuk rollback ke backup sebelumnya
# =============================================================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
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

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

PROJECT_DIR=$(pwd)
BACKUP_DIR="$PROJECT_DIR/backups"

echo "=========================================="
echo "  TimeKeeper Rollback Script"
echo "=========================================="

# Check if backup directory exists
if [ ! -d "$BACKUP_DIR" ]; then
    log_error "Backup directory not found: $BACKUP_DIR"
    exit 1
fi

# If backup name provided, use it
if [ -n "$1" ]; then
    BACKUP_NAME="$1"
    BACKUP_PATH="$BACKUP_DIR/$BACKUP_NAME"
    
    if [ ! -d "$BACKUP_PATH" ]; then
        log_error "Backup not found: $BACKUP_NAME"
        exit 1
    fi
else
    # List available backups
    log_info "Available backups:"
    ls -la "$BACKUP_DIR" | grep "^d" | grep "backup_" | awk '{print $9}' | sort -r
    echo
    
    # Get latest backup
    BACKUP_NAME=$(ls -t "$BACKUP_DIR" | grep "backup_" | head -n 1)
    BACKUP_PATH="$BACKUP_DIR/$BACKUP_NAME"
    
    if [ -z "$BACKUP_NAME" ]; then
        log_error "No backups found!"
        exit 1
    fi
    
    log_warning "Will rollback to latest backup: $BACKUP_NAME"
    read -p "Continue? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        log_info "Rollback cancelled."
        exit 0
    fi
fi

log_info "Starting rollback to: $BACKUP_NAME"

# Create current state backup before rollback
CURRENT_BACKUP="rollback_backup_$(date +"%Y%m%d_%H%M%S")"
log_info "Creating backup of current state: $CURRENT_BACKUP"
mkdir -p "$BACKUP_DIR/$CURRENT_BACKUP"

# Backup current important files
cp -r app "$BACKUP_DIR/$CURRENT_BACKUP/" 2>/dev/null || true
cp -r config "$BACKUP_DIR/$CURRENT_BACKUP/" 2>/dev/null || true
cp -r resources "$BACKUP_DIR/$CURRENT_BACKUP/" 2>/dev/null || true
cp -r routes "$BACKUP_DIR/$CURRENT_BACKUP/" 2>/dev/null || true
cp .env "$BACKUP_DIR/$CURRENT_BACKUP/.env.backup" 2>/dev/null || true

# Restore from backup
log_info "Restoring files from backup..."

# Restore directories
if [ -d "$BACKUP_PATH/app" ]; then
    rm -rf app
    cp -r "$BACKUP_PATH/app" .
    log_info "Restored app directory"
fi

if [ -d "$BACKUP_PATH/config" ]; then
    rm -rf config
    cp -r "$BACKUP_PATH/config" .
    log_info "Restored config directory"
fi

if [ -d "$BACKUP_PATH/resources" ]; then
    rm -rf resources
    cp -r "$BACKUP_PATH/resources" .
    log_info "Restored resources directory"
fi

if [ -d "$BACKUP_PATH/routes" ]; then
    rm -rf routes
    cp -r "$BACKUP_PATH/routes" .
    log_info "Restored routes directory"
fi

if [ -d "$BACKUP_PATH/database" ]; then
    rm -rf database
    cp -r "$BACKUP_PATH/database" .
    log_info "Restored database directory"
fi

# Restore files
if [ -f "$BACKUP_PATH/composer.json" ]; then
    cp "$BACKUP_PATH/composer.json" .
    log_info "Restored composer.json"
fi

if [ -f "$BACKUP_PATH/composer.lock" ]; then
    cp "$BACKUP_PATH/composer.lock" .
    log_info "Restored composer.lock"
fi

if [ -f "$BACKUP_PATH/package.json" ]; then
    cp "$BACKUP_PATH/package.json" .
    log_info "Restored package.json"
fi

if [ -f "$BACKUP_PATH/package-lock.json" ]; then
    cp "$BACKUP_PATH/package-lock.json" .
    log_info "Restored package-lock.json"
fi

# Restore .env if exists (but keep current one as backup)
if [ -f "$BACKUP_PATH/.env.backup" ]; then
    log_warning "Found .env in backup. Current .env will be kept as .env.current"
    cp .env .env.current 2>/dev/null || true
    cp "$BACKUP_PATH/.env.backup" .env
    log_info "Restored .env (current saved as .env.current)"
fi

# Reinstall dependencies
log_info "Reinstalling dependencies..."
if [ -f "composer.json" ]; then
    composer install --no-dev --optimize-autoloader
fi

if [ -f "package.json" ]; then
    npm ci
    npm run build
fi

# Clear caches
log_info "Clearing caches..."
php artisan cache:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Optimize
log_info "Optimizing application..."
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# Set permissions
if [ -d "storage" ]; then
    chmod -R 775 storage
fi

if [ -d "bootstrap/cache" ]; then
    chmod -R 775 bootstrap/cache
fi

echo
log_success "🔄 Rollback completed successfully!"
log_info "Rolled back to: $BACKUP_NAME"
log_info "Current state backed up as: $CURRENT_BACKUP"
echo "=========================================="