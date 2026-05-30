# setup-android.ps1
# Run this script instead of 'php artisan native:install' to properly set up
# The script fixes common issues with NativePHP Android setup by:
# Skipping the default PHP install (which can be broken or mismatched)
# Manually downloading the correct PHP static binaries
# Ensuring Android SDK paths are correctly configured
# Usage: .\setup-android.ps1

$ErrorActionPreference = "Stop"

$SDK_DIR = "C:\Users\Solicode\AppData\Local\Android\Sdk"
$ANDROID_DIR = "$PSScriptRoot\nativephp\android"
$BINARY_URL = "https://bin.nativephp.com/main/8.4/android/android-3.1.0-php8.4.19.zip"
$ZIP_PATH = "$ANDROID_DIR\android.zip"
$EXTRACT_PATH = "$ANDROID_DIR\app\src\main"
$LOCAL_PROPS = "$ANDROID_DIR\local.properties"

Write-Host ""
Write-Host "==> Installing NativePHP for Android (PHP 8.4)" -ForegroundColor Cyan

# Step 1: Run native:install with --skip-php to create project skeleton only
Write-Host ""
Write-Host "[1/4] Creating Android project skeleton (--skip-php)..." -ForegroundColor Yellow
php artisan native:install android --skip-php --no-interaction

# Step 2: Fix local.properties (native:install blanks it out)
Write-Host ""
Write-Host "[2/4] Fixing local.properties (sdk.dir)..." -ForegroundColor Yellow
Set-Content -Path $LOCAL_PROPS -Value "sdk.dir=$($SDK_DIR.Replace('\', '\\').Replace(':', '\:'))"
Write-Host "      sdk.dir set to: $SDK_DIR"

# Step 3: Download PHP 8.4 static libraries
Write-Host ""
Write-Host "[3/4] Downloading PHP 8.4 Android static libraries..." -ForegroundColor Yellow
Write-Host "      URL: $BINARY_URL"
Invoke-WebRequest -Uri $BINARY_URL -OutFile $ZIP_PATH
Write-Host "      Download complete. Extracting..."
Expand-Archive -Path $ZIP_PATH -DestinationPath $EXTRACT_PATH -Force
Remove-Item $ZIP_PATH
Write-Host "      Extraction complete."

# Step 4: Verify libphp.a exists
Write-Host ""
Write-Host "[4/4] Verifying installation..." -ForegroundColor Yellow
$libphp = "$EXTRACT_PATH\staticLibs\arm64-v8a\libphp.a"
if (Test-Path $libphp) {
    $sizeMB = [math]::Round((Get-Item $libphp).Length / 1MB, 1)
    Write-Host "      libphp.a found! ($sizeMB MB)" -ForegroundColor Green
} else {
    Write-Host "      ERROR: libphp.a not found at $libphp" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "==> Android setup complete! Run 'php artisan native:run' to build and deploy." -ForegroundColor Green
Write-Host ""
