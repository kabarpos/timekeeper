#!/bin/bash

# =============================================================================
# TimeKeeper Production Deployment Script
# =============================================================================
# Usage: ./deploy.sh
# Deskripsi: Script untuk update production dengan satu perintah
# =============================================================================

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_DIR=$(pwd)
BACKUP_DIR="$PROJECT_DIR/backups"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_NAME="backup_$TIMESTAMP"

# Functions
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

# Create backup directory if not exists
create_backup_dir() {
    if [ ! -d "$BACKUP_DIR" ]; then
        mkdir -p "$BACKUP_DIR"
        log_info "Created backup directory: $BACKUP_DIR"
    fi
}

# Backup current state
backup_current_state() {
    log_info "Creating backup: $BACKUP_NAME"
    
    # Create backup directory for this deployment
    mkdir -p "$BACKUP_DIR/$BACKUP_NAME"
    
    # Backup important files and directories
    cp -r app "$BACKUP_DIR/$BACKUP_NAME/" 2>/dev/null || true
    cp -r config "$BACKUP_DIR/$BACKUP_NAME/" 2>/dev/null || true
    cp -r database "$BACKUP_DIR/$BACKUP_NAME/" 2>/dev/null || true
    cp -r resources "$BACKUP_DIR/$BACKUP_NAME/" 2>/dev/null || true
    cp -r routes "$BACKUP_DIR/$BACKUP_NAME/" 2>/dev/null || true
    cp composer.json "$BACKUP_DIR/$BACKUP_NAME/" 2>/dev/null || true
    cp composer.lock "$BACKUP_DIR/$BACKUP_NAME/" 2>/dev/null || true
    cp package.json "$BACKUP_DIR/$BACKUP_NAME/" 2>/dev/null || true
    cp package-lock.json "$BACKUP_DIR/$BACKUP_NAME/" 2>/dev/null || true
    cp .env "$BACKUP_DIR/$BACKUP_NAME/.env.backup" 2>/dev/null || true
    
    log_success "Backup created successfully: $BACKUP_NAME"
}

# Check if git repository
check_git_repo() {
    if [ ! -d ".git" ]; then
        log_error "This is not a git repository!"
        exit 1
    fi
}

# Check for uncommitted changes
check_uncommitted_changes() {
    if [ -n "$(git status --porcelain)" ]; then
        log_warning "You have uncommitted changes:"
        git status --short
        echo
        read -p "Do you want to continue? (y/N): " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            log_info "Deployment cancelled."
            exit 0
        fi
    fi
}

# Pull latest changes
pull_latest_changes() {
    log_info "Pulling latest changes from repository..."
    
    # Fetch latest changes
    git fetch origin
    
    # Check if there are new commits
    LOCAL=$(git rev-parse HEAD)
    REMOTE=$(git rev-parse origin/main 2>/dev/null || git rev-parse origin/master 2>/dev/null)
    
    if [ "$LOCAL" = "$REMOTE" ]; then
        log_info "Already up to date. No new changes to deploy."
        return 0
    fi
    
    # Show what will be updated
    log_info "New commits to be deployed:"
    git log --oneline $LOCAL..$REMOTE
    echo
    
    # Pull changes
    git pull origin main 2>/dev/null || git pull origin master 2>/dev/null
    log_success "Successfully pulled latest changes"
    
    return 1  # Indicate that there were updates
}

# Update Composer dependencies
update_composer() {
    log_info "Updating Composer dependencies..."
    
    if [ -f "composer.json" ]; then
        composer install --no-dev --optimize-autoloader --no-interaction
        log_success "Composer dependencies updated"
    else
        log_warning "composer.json not found, skipping Composer update"
    fi
}

# Update NPM dependencies and build assets
update_npm() {
    log_info "Updating NPM dependencies and building assets..."
    
    if [ -f "package.json" ]; then
        npm ci --production=false
        npm run build
        log_success "NPM dependencies updated and assets built"
    else
        log_warning "package.json not found, skipping NPM update"
    fi
}

# Run database migrations
run_migrations() {
    log_info "Running database migrations..."
    
    if [ -f "artisan" ]; then
        php artisan migrate --force
        log_success "Database migrations completed"
    else
        log_warning "artisan not found, skipping migrations"
    fi
}

# Clear and optimize caches
optimize_application() {
    log_info "Optimizing application..."
    
    if [ -f "artisan" ]; then
        # Clear caches
        php artisan cache:clear
        php artisan config:clear
        php artisan route:clear
        php artisan view:clear
        
        # Optimize for production
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
        
        log_success "Application optimized"
    else
        log_warning "artisan not found, skipping optimization"
    fi
}

# Set proper permissions
set_permissions() {
    log_info "Setting proper permissions..."
    
    # Set permissions for storage and bootstrap/cache
    if [ -d "storage" ]; then
        chmod -R 775 storage
        log_info "Storage permissions set"
    fi
    
    if [ -d "bootstrap/cache" ]; then
        chmod -R 775 bootstrap/cache
        log_info "Bootstrap cache permissions set"
    fi
    
    log_success "Permissions updated"
}

# Cleanup old backups (keep last 5)
cleanup_old_backups() {
    log_info "Cleaning up old backups..."
    
    if [ -d "$BACKUP_DIR" ]; then
        cd "$BACKUP_DIR"
        ls -t | tail -n +6 | xargs -r rm -rf
        cd "$PROJECT_DIR"
        log_success "Old backups cleaned up (kept last 5)"
    fi
}

# Rollback function
rollback() {
    log_error "Deployment failed! Starting rollback..."
    
    if [ -d "$BACKUP_DIR/$BACKUP_NAME" ]; then
        log_info "Restoring from backup: $BACKUP_NAME"
        
        # Restore files
        cp -r "$BACKUP_DIR/$BACKUP_NAME"/* . 2>/dev/null || true
        
        # Restore .env if exists
        if [ -f "$BACKUP_DIR/$BACKUP_NAME/.env.backup" ]; then
            cp "$BACKUP_DIR/$BACKUP_NAME/.env.backup" .env
        fi
        
        log_success "Rollback completed"
    else
        log_error "Backup not found! Manual intervention required."
    fi
}

# Main deployment function
main() {
    echo "=============================================="
    echo "  TimeKeeper Production Deployment Script"
    echo "=============================================="
    echo
    
    # Pre-deployment checks
    check_git_repo
    check_uncommitted_changes
    create_backup_dir
    
    # Create backup
    backup_current_state
    
    # Set trap for rollback on error
    trap rollback ERR
    
    # Pull latest changes
    if pull_latest_changes; then
        log_info "No updates available. Deployment completed."
        exit 0
    fi
    
    # Update dependencies and build
    update_composer
    update_npm
    
    # Database and optimization
    run_migrations
    optimize_application
    
    # Set permissions
    set_permissions
    
    # Cleanup
    cleanup_old_backups
    
    # Remove trap
    trap - ERR
    
    echo
    log_success "🎉 Deployment completed successfully!"
    log_info "Backup created: $BACKUP_NAME"
    echo "=============================================="
}

# Run main function
main "$@"