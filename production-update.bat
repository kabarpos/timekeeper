@echo off
setlocal enabledelayedexpansion

REM ==========================================
REM TimeKeeper Production Update Script (Windows)
REM ==========================================
REM Script untuk update otomatis production dengan analisis perubahan file

set "PROJECT_DIR=%~dp0"
set "BACKUP_DIR=%PROJECT_DIR%backup\updates"
set "LOG_FILE=%PROJECT_DIR%storage\logs\production-update.log"
set "COMMIT_FILE=%PROJECT_DIR%storage\last_deploy_commit.txt"

REM Create necessary directories
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"
if not exist "%PROJECT_DIR%storage\logs" mkdir "%PROJECT_DIR%storage\logs"

echo [%date% %time%] ========================================== >> "%LOG_FILE%"
echo [%date% %time%] TimeKeeper Production Update Started >> "%LOG_FILE%"
echo [%date% %time%] ========================================== >> "%LOG_FILE%"

echo.
echo ==========================================
echo TimeKeeper Production Update
echo ==========================================
echo.

REM Check if git is available
git --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Git is not installed or not in PATH
    echo [%date% %time%] [ERROR] Git is not installed or not in PATH >> "%LOG_FILE%"
    pause
    exit /b 1
)

REM Check if PHP is available
php --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] PHP is not installed or not in PATH
    echo [%date% %time%] [ERROR] PHP is not installed or not in PATH >> "%LOG_FILE%"
    pause
    exit /b 1
)

REM Check if Composer is available
composer --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Composer is not installed or not in PATH
    echo [%date% %time%] [ERROR] Composer is not installed or not in PATH >> "%LOG_FILE%"
    pause
    exit /b 1
)

REM Check if NPM is available
npm --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] NPM is not installed or not in PATH
    echo [%date% %time%] [ERROR] NPM is not installed or not in PATH >> "%LOG_FILE%"
    pause
    exit /b 1
)

echo [STEP] Checking for repository changes...
echo [%date% %time%] [STEP] Checking for repository changes... >> "%LOG_FILE%"

REM Get current commit hash
for /f "tokens=*" %%i in ('git rev-parse HEAD') do set "CURRENT_COMMIT=%%i"

REM Initialize update flags
set "NEEDS_COMPOSER=false"
set "NEEDS_NPM=false"
set "NEEDS_MIGRATION=false"
set "NEEDS_CACHE_CLEAR=false"
set "NEEDS_QUEUE_RESTART=false"

REM Check if previous commit file exists
if exist "%COMMIT_FILE%" (
    set /p PREVIOUS_COMMIT=<"%COMMIT_FILE%"
    echo [ANALYSIS] Previous commit: !PREVIOUS_COMMIT!
    echo [ANALYSIS] Current commit: %CURRENT_COMMIT%
    echo [%date% %time%] [ANALYSIS] Previous commit: !PREVIOUS_COMMIT! >> "%LOG_FILE%"
    echo [%date% %time%] [ANALYSIS] Current commit: %CURRENT_COMMIT% >> "%LOG_FILE%"
    
    REM Get changed files
    git diff --name-only !PREVIOUS_COMMIT! %CURRENT_COMMIT% > temp_changes.txt 2>nul
    
    REM Check if there are changes
    for /f %%i in ("temp_changes.txt") do set size=%%~zi
    if !size! equ 0 (
        echo [INFO] No changes detected since last deployment
        echo [%date% %time%] [INFO] No changes detected since last deployment >> "%LOG_FILE%"
        del temp_changes.txt
        goto :end
    )
    
    echo [ANALYSIS] Changed files detected:
    echo [%date% %time%] [ANALYSIS] Changed files detected: >> "%LOG_FILE%"
    
    REM Analyze changed files
    for /f "tokens=*" %%f in (temp_changes.txt) do (
        echo   - %%f
        echo [%date% %time%] [ANALYSIS]   - %%f >> "%LOG_FILE%"
        
        REM Check file patterns to determine what needs updating
        echo %%f | findstr /i "composer.json composer.lock" >nul && (
            set "NEEDS_COMPOSER=true"
            set "NEEDS_CACHE_CLEAR=true"
            echo [ANALYSIS] → Composer dependencies changed
            echo [%date% %time%] [ANALYSIS] → Composer dependencies changed >> "%LOG_FILE%"
        )
        
        echo %%f | findstr /i "package.json package-lock.json" >nul && (
            set "NEEDS_NPM=true"
            echo [ANALYSIS] → NPM dependencies changed
            echo [%date% %time%] [ANALYSIS] → NPM dependencies changed >> "%LOG_FILE%"
        )
        
        echo %%f | findstr /i "database\\migrations\\" >nul && (
            set "NEEDS_MIGRATION=true"
            echo [ANALYSIS] → Database migrations changed
            echo [%date% %time%] [ANALYSIS] → Database migrations changed >> "%LOG_FILE%"
        )
        
        echo %%f | findstr /i "config\\ routes\\ app\\" >nul && (
            set "NEEDS_CACHE_CLEAR=true"
            set "NEEDS_QUEUE_RESTART=true"
            echo [ANALYSIS] → Application code changed
            echo [%date% %time%] [ANALYSIS] → Application code changed >> "%LOG_FILE%"
        )
        
        echo %%f | findstr /i "resources\\css\\ resources\\js\\ vite.config.js tailwind.config.js" >nul && (
            set "NEEDS_NPM=true"
            echo [ANALYSIS] → Frontend assets changed
            echo [%date% %time%] [ANALYSIS] → Frontend assets changed >> "%LOG_FILE%"
        )
    )
    
    del temp_changes.txt
) else (
    echo [WARNING] No previous deployment record found. Performing full update.
    echo [%date% %time%] [WARNING] No previous deployment record found. Performing full update. >> "%LOG_FILE%"
    set "NEEDS_COMPOSER=true"
    set "NEEDS_NPM=true"
    set "NEEDS_MIGRATION=true"
    set "NEEDS_CACHE_CLEAR=true"
    set "NEEDS_QUEUE_RESTART=true"
)

REM Create backup
echo.
echo [STEP] Creating backup...
echo [%date% %time%] [STEP] Creating backup... >> "%LOG_FILE%"

set "BACKUP_NAME=pre-update-%date:~10,4%%date:~4,2%%date:~7,2%_%time:~0,2%%time:~3,2%%time:~6,2%"
set "BACKUP_NAME=%BACKUP_NAME: =0%"
set "BACKUP_PATH=%BACKUP_DIR%\%BACKUP_NAME%"

mkdir "%BACKUP_PATH%" 2>nul

REM Backup critical directories and files
if exist "app" xcopy "app" "%BACKUP_PATH%\app\" /E /I /Q >nul 2>&1
if exist "config" xcopy "config" "%BACKUP_PATH%\config\" /E /I /Q >nul 2>&1
if exist "database" xcopy "database" "%BACKUP_PATH%\database\" /E /I /Q >nul 2>&1
if exist "resources" xcopy "resources" "%BACKUP_PATH%\resources\" /E /I /Q >nul 2>&1
if exist "routes" xcopy "routes" "%BACKUP_PATH%\routes\" /E /I /Q >nul 2>&1
if exist ".env" copy ".env" "%BACKUP_PATH%\" >nul 2>&1
if exist "composer.json" copy "composer.json" "%BACKUP_PATH%\" >nul 2>&1
if exist "package.json" copy "package.json" "%BACKUP_PATH%\" >nul 2>&1

echo [INFO] Backup created at: %BACKUP_PATH%
echo [%date% %time%] [INFO] Backup created at: %BACKUP_PATH% >> "%LOG_FILE%"

REM Pull latest changes
echo.
echo [STEP] Pulling latest changes from repository...
echo [%date% %time%] [STEP] Pulling latest changes from repository... >> "%LOG_FILE%"

git fetch origin
if errorlevel 1 (
    echo [ERROR] Failed to fetch from origin
    echo [%date% %time%] [ERROR] Failed to fetch from origin >> "%LOG_FILE%"
    pause
    exit /b 1
)

for /f "tokens=*" %%i in ('git branch --show-current') do set "CURRENT_BRANCH=%%i"
git pull origin %CURRENT_BRANCH%
if errorlevel 1 (
    echo [ERROR] Failed to pull changes
    echo [%date% %time%] [ERROR] Failed to pull changes >> "%LOG_FILE%"
    pause
    exit /b 1
)

REM Enable maintenance mode
echo.
echo [STEP] Enabling maintenance mode...
echo [%date% %time%] [STEP] Enabling maintenance mode... >> "%LOG_FILE%"
php artisan down --refresh=15 --retry=60 --secret=update-%RANDOM%

REM Update Composer dependencies
if "%NEEDS_COMPOSER%"=="true" (
    echo.
    echo [STEP] Updating Composer dependencies...
    echo [%date% %time%] [STEP] Updating Composer dependencies... >> "%LOG_FILE%"
    composer install --optimize-autoloader --no-dev --no-interaction
    if errorlevel 1 (
        echo [ERROR] Composer update failed
        echo [%date% %time%] [ERROR] Composer update failed >> "%LOG_FILE%"
        goto :rollback
    )
    composer dump-autoload --optimize
) else (
    echo [INFO] Skipping Composer update (no changes detected)
    echo [%date% %time%] [INFO] Skipping Composer update (no changes detected) >> "%LOG_FILE%"
)

REM Build frontend assets
if "%NEEDS_NPM%"=="true" (
    echo.
    echo [STEP] Building frontend assets...
    echo [%date% %time%] [STEP] Building frontend assets... >> "%LOG_FILE%"
    npm ci --production
    if errorlevel 1 (
        echo [ERROR] NPM install failed
        echo [%date% %time%] [ERROR] NPM install failed >> "%LOG_FILE%"
        goto :rollback
    )
    npm run build
    if errorlevel 1 (
        echo [ERROR] Frontend build failed
        echo [%date% %time%] [ERROR] Frontend build failed >> "%LOG_FILE%"
        goto :rollback
    )
) else (
    echo [INFO] Skipping frontend build (no changes detected)
    echo [%date% %time%] [INFO] Skipping frontend build (no changes detected) >> "%LOG_FILE%"
)

REM Run database migrations
if "%NEEDS_MIGRATION%"=="true" (
    echo.
    echo [STEP] Running database migrations...
    echo [%date% %time%] [STEP] Running database migrations... >> "%LOG_FILE%"
    php artisan migrate --force
    if errorlevel 1 (
        echo [ERROR] Database migration failed
        echo [%date% %time%] [ERROR] Database migration failed >> "%LOG_FILE%"
        goto :rollback
    )
) else (
    echo [INFO] Skipping migrations (no changes detected)
    echo [%date% %time%] [INFO] Skipping migrations (no changes detected) >> "%LOG_FILE%"
)

REM Clear and rebuild caches
if "%NEEDS_CACHE_CLEAR%"=="true" (
    echo.
    echo [STEP] Clearing and rebuilding caches...
    echo [%date% %time%] [STEP] Clearing and rebuilding caches... >> "%LOG_FILE%"
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan event:clear
    
    REM Rebuild caches
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
) else (
    echo [INFO] Skipping cache operations (no changes detected)
    echo [%date% %time%] [INFO] Skipping cache operations (no changes detected) >> "%LOG_FILE%"
)

REM Restart queue workers
if "%NEEDS_QUEUE_RESTART%"=="true" (
    echo.
    echo [STEP] Restarting queue workers...
    echo [%date% %time%] [STEP] Restarting queue workers... >> "%LOG_FILE%"
    php artisan queue:restart
) else (
    echo [INFO] Skipping queue restart (no changes detected)
    echo [%date% %time%] [INFO] Skipping queue restart (no changes detected) >> "%LOG_FILE%"
)

REM Update storage links
echo.
echo [STEP] Updating storage links...
echo [%date% %time%] [STEP] Updating storage links... >> "%LOG_FILE%"
php artisan storage:link

REM Disable maintenance mode
echo.
echo [STEP] Disabling maintenance mode...
echo [%date% %time%] [STEP] Disabling maintenance mode... >> "%LOG_FILE%"
php artisan up

REM Run basic health checks
echo.
echo [STEP] Running health checks...
echo [%date% %time%] [STEP] Running health checks... >> "%LOG_FILE%"

REM Test database connection
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database: OK'; } catch(Exception $e) { echo 'Database: FAILED'; }" 2>nul

REM Save current commit for next deployment
echo %CURRENT_COMMIT% > "%COMMIT_FILE%"

echo.
echo ==========================================
echo TimeKeeper Production Update Completed
echo ==========================================
echo [%date% %time%] ========================================== >> "%LOG_FILE%"
echo [%date% %time%] TimeKeeper Production Update Completed >> "%LOG_FILE%"
echo [%date% %time%] ========================================== >> "%LOG_FILE%"

goto :end

:rollback
echo.
echo [ERROR] Update failed! Initiating rollback...
echo [%date% %time%] [ERROR] Update failed! Initiating rollback... >> "%LOG_FILE%"

if exist "%BACKUP_PATH%" (
    echo [STEP] Restoring from backup...
    echo [%date% %time%] [STEP] Restoring from backup... >> "%LOG_FILE%"
    
    if exist "%BACKUP_PATH%\app" xcopy "%BACKUP_PATH%\app" "app\" /E /I /Y /Q >nul 2>&1
    if exist "%BACKUP_PATH%\config" xcopy "%BACKUP_PATH%\config" "config\" /E /I /Y /Q >nul 2>&1
    if exist "%BACKUP_PATH%\database" xcopy "%BACKUP_PATH%\database" "database\" /E /I /Y /Q >nul 2>&1
    if exist "%BACKUP_PATH%\resources" xcopy "%BACKUP_PATH%\resources" "resources\" /E /I /Y /Q >nul 2>&1
    if exist "%BACKUP_PATH%\routes" xcopy "%BACKUP_PATH%\routes" "routes\" /E /I /Y /Q >nul 2>&1
    if exist "%BACKUP_PATH%\.env" copy "%BACKUP_PATH%\.env" "." /Y >nul 2>&1
    if exist "%BACKUP_PATH%\composer.json" copy "%BACKUP_PATH%\composer.json" "." /Y >nul 2>&1
    if exist "%BACKUP_PATH%\package.json" copy "%BACKUP_PATH%\package.json" "." /Y >nul 2>&1
    
    REM Clear caches after rollback
    php artisan cache:clear >nul 2>&1
    php artisan config:clear >nul 2>&1
    php artisan route:clear >nul 2>&1
    php artisan view:clear >nul 2>&1
    
    echo [INFO] Rollback completed
    echo [%date% %time%] [INFO] Rollback completed >> "%LOG_FILE%"
) else (
    echo [ERROR] Backup directory not found for rollback
    echo [%date% %time%] [ERROR] Backup directory not found for rollback >> "%LOG_FILE%"
)

REM Disable maintenance mode
php artisan up >nul 2>&1

pause
exit /b 1

:end
echo.
echo Update process completed successfully!
echo Check the log file for details: %LOG_FILE%
echo.
pause