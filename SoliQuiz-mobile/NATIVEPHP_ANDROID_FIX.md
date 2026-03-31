# 🛠️ Fix: NativePHP for Android Setup Guide

If `php artisan native:install` is failing with "PHP binaries not available" or if your build is failing due to a missing `libphp.a`, follow these steps to manually configure your project.

## 🚀 The Problem
1. **Network Outage/Timeout**: The official NativePHP script often fails to reach `bin.nativephp.com` to fetch the binary manifest.
2. **PHP Version Mismatch**: If you run PHP 8.4 on your CLI, the script tries (and fails) to find Android binaries for 8.4.
3. **Destructive Reset**: Running `native:install` wipes the `nativephp/android` folder, deleting any manual fixes like `local.properties`.

---

## ✅ The Fix (Step-by-Step)

### 1. Pin your PHP Version
Create a `nativephp.json` file at the root of your project (`c:\Mobileprojects\Lab-NativePHP2\app\nativephp.json`). This tells the build tool which engine to use regardless of your CLI version.

```json
{
  "php": {
    "version": "8.4",
    "icu": false
  }
}
```

### 2. Configure Android SDK
Ensure your `nativephp/android/local.properties` file has the correct path to your Android SDK. If it's empty, NativePHP won't know where to find the NDK and build tools.

```properties
sdk.dir=C\:\\Users\\Solicode\\AppData\\Local\\Android\\Sdk
```
*(Note: Use double backslashes and escape the colon as shown above.)*

### 3. Use the Automated Setup Script
Since `native:install` is destructive, we created a custom PowerShell script `setup-android.ps1` that performs the installation "safely."

**What this script does:**
- Runs `native:install android --skip-php` (creates the folder structure without deleting binaries).
- Correctly sets your `local.properties`.
- Manually downloads the PHP engine directly via PowerShell (bypassing the failing manifest).
- Unzips the libraries into the correct NDK folders for CMake.

#### How to run it:
Open PowerShell in your project directory and run:

```powershell
.\setup-android.ps1
```

### 4. Build and Run
Once the script confirms `libphp.a` is found, you can run the standard build command. Avoid running `native:install` again.

```bash
php artisan native:run
```

---

## 📂 Key Files & Locations

- **PHP Binaries**: `nativephp/android/app/src/main/staticLibs/arm64-v8a/libphp.a`
- **JNI Bridge**: `nativephp/android/app/src/main/cpp/`
- **App SDK Settings**: `nativephp/android/local.properties`

## 💡 Pro-Tip for Classmates
If you get a "PHP Version Mismatch" error when running `native:run`, it means your terminal's PHP version (e.g., 8.4) doesn't match the one you pinned in `nativephp.json`. Either change the JSON to match your CLI or update your system environment variables to point to the correct PHP version.

---
⚡ *Built with NativePHP — Documented by Antigravity*
