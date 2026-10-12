<?php
/**
 * Laravel Production Diagnostic Script
 * 
 * This script helps diagnose common production issues.
 * DELETE THIS FILE after fixing your issues!
 * 
 * Access: https://tukio360.co.tz/diagnose.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><title>Laravel Diagnostic</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5;}";
echo ".ok{color:green;}.error{color:red;}.warning{color:orange;}.section{margin:20px 0;padding:15px;background:white;border-radius:5px;}";
echo "h2{border-bottom:2px solid #ddd;padding-bottom:10px;}</style></head><body>";
echo "<h1>🔍 Laravel Production Diagnostic</h1>";

$issues = [];
$warnings = [];

// Check 1: PHP Version
echo "<div class='section'><h2>1. PHP Version</h2>";
$phpVersion = phpversion();
$requiredVersion = '8.2.0';
if (version_compare($phpVersion, $requiredVersion, '>=')) {
    echo "<p class='ok'>✅ PHP Version: {$phpVersion} (Required: {$requiredVersion}+)</p>";
} else {
    echo "<p class='error'>❌ PHP Version: {$phpVersion} (Required: {$requiredVersion}+)</p>";
    $issues[] = "PHP version must be 8.2 or higher";
}
echo "</div>";

// Check 2: Required Extensions
echo "<div class='section'><h2>2. Required PHP Extensions</h2>";
$requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'curl', 'fileinfo', 'json', 'bcmath'];
$missing = [];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p class='ok'>✅ {$ext}</p>";
    } else {
        echo "<p class='error'>❌ {$ext} - MISSING</p>";
        $missing[] = $ext;
    }
}
if (!empty($missing)) {
    $issues[] = "Missing PHP extensions: " . implode(', ', $missing);
}
echo "</div>";

// Check 3: File Permissions
echo "<div class='section'><h2>3. File Permissions</h2>";
$criticalDirs = [
    'storage' => '../storage',
    'bootstrap/cache' => '../../bootstrap/cache',
];
foreach ($criticalDirs as $name => $path) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        if (is_writable($path)) {
            echo "<p class='ok'>✅ {$name}/ is writable (perms: {$perms})</p>";
        } else {
            echo "<p class='error'>❌ {$name}/ is NOT writable (perms: {$perms})</p>";
            $issues[] = "{$name}/ directory must be writable (775 recommended)";
        }
    } else {
        echo "<p class='error'>❌ {$name}/ directory does not exist</p>";
        $issues[] = "{$name}/ directory is missing";
    }
}
echo "</div>";

// Check 4: .env File
echo "<div class='section'><h2>4. Environment Configuration</h2>";
$envPath = '../.env';
if (file_exists($envPath)) {
    echo "<p class='ok'>✅ .env file exists</p>";
    
    $envContent = file_get_contents($envPath);
    
    // Check APP_KEY
    if (preg_match('/APP_KEY=(.+)/', $envContent, $matches)) {
        $appKey = trim($matches[1]);
        if (!empty($appKey) && $appKey !== 'base64:') {
            echo "<p class='ok'>✅ APP_KEY is set</p>";
        } else {
            echo "<p class='error'>❌ APP_KEY is empty or not generated</p>";
            $issues[] = "APP_KEY must be generated: php artisan key:generate";
        }
    }
    
    // Check APP_DEBUG
    if (preg_match('/APP_DEBUG=(.+)/', $envContent, $matches)) {
        $debug = trim(strtolower($matches[1]));
        if ($debug === 'false' || $debug === '0') {
            echo "<p class='ok'>✅ APP_DEBUG=false (production safe)</p>";
        } else {
            echo "<p class='warning'>⚠️ APP_DEBUG=true (should be false in production)</p>";
            $warnings[] = "APP_DEBUG should be false in production";
        }
    }
    
    // Check Database Configuration
    if (preg_match('/DB_CONNECTION=(.+)/', $envContent, $matches)) {
        $dbConnection = trim($matches[1]);
        echo "<p class='ok'>✅ DB_CONNECTION={$dbConnection}</p>";
        
        if ($dbConnection === 'mysql' || $dbConnection === 'mariadb') {
            if (preg_match('/DB_DATABASE=(.+)/', $envContent, $dbMatches)) {
                $dbName = trim($dbMatches[1]);
                if (empty($dbName) || $dbName === 'rental' || $dbName === 'laravel') {
                    echo "<p class='error'>❌ DB_DATABASE is set to '{$dbName}' - should be your actual database name</p>";
                    $issues[] = "DB_DATABASE must be set to your actual database name";
                } else {
                    echo "<p class='ok'>✅ DB_DATABASE is configured</p>";
                }
            }
        }
    }
} else {
    echo "<p class='error'>❌ .env file does not exist</p>";
    $issues[] = ".env file is missing - copy from .env.example";
}
echo "</div>";

// Check 5: Database Connection
echo "<div class='section'><h2>5. Database Connection</h2>";
try {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        echo "<p class='ok'>✅ Database connection successful</p>";
        
        // Check if settings table exists
        if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            echo "<p class='ok'>✅ Settings table exists</p>";
        } else {
            echo "<p class='warning'>⚠️ Settings table does not exist - run migrations</p>";
            $warnings[] = "Run migrations: php artisan migrate";
        }
    } catch (\Exception $e) {
        echo "<p class='error'>❌ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
        $issues[] = "Database connection error: " . $e->getMessage();
    }
} catch (\Exception $e) {
    echo "<p class='error'>❌ Could not bootstrap Laravel: " . htmlspecialchars($e->getMessage()) . "</p>";
    $issues[] = "Laravel bootstrap failed: " . $e->getMessage();
}
echo "</div>";

// Check 6: Storage Link
echo "<div class='section'><h2>6. Storage Symbolic Link</h2>";
$storageLink = 'storage';
if (file_exists($storageLink)) {
    if (is_link($storageLink)) {
        echo "<p class='ok'>✅ Storage symbolic link exists</p>";
    } else {
        echo "<p class='warning'>⚠️ Storage directory exists but is not a symbolic link</p>";
        $warnings[] = "Run: php artisan storage:link";
    }
} else {
    echo "<p class='error'>❌ Storage symbolic link does not exist</p>";
    $issues[] = "Run: php artisan storage:link";
}
echo "</div>";

// Check 7: .htaccess
echo "<div class='section'><h2>7. .htaccess File</h2>";
if (file_exists('.htaccess')) {
    echo "<p class='ok'>✅ .htaccess file exists</p>";
} else {
    echo "<p class='error'>❌ .htaccess file is missing (critical for Laravel routing)</p>";
    $issues[] = ".htaccess file is missing in public/ directory";
}
echo "</div>";

// Summary
echo "<div class='section'><h2>📊 Summary</h2>";
if (empty($issues) && empty($warnings)) {
    echo "<p class='ok'><strong>✅ All checks passed! Your Laravel installation looks good.</strong></p>";
} else {
    if (!empty($issues)) {
        echo "<p class='error'><strong>❌ Critical Issues Found ({count($issues)}):</strong></p><ul>";
        foreach ($issues as $issue) {
            echo "<li class='error'>{$issue}</li>";
        }
        echo "</ul>";
    }
    if (!empty($warnings)) {
        echo "<p class='warning'><strong>⚠️ Warnings ({count($warnings)}):</strong></p><ul>";
        foreach ($warnings as $warning) {
            echo "<li class='warning'>{$warning}</li>";
        }
        echo "</ul>";
    }
}
echo "</div>";

echo "<hr><p><small>⚠️ <strong>SECURITY WARNING:</strong> Delete this file (diagnose.php) after fixing issues!</small></p>";
echo "</body></html>";
