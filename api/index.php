<?php

// Ensure writable directories exist on Vercel's serverless environment.
// Vercel provides /tmp as the only writable directory.
if (getenv('VERCEL')) {
    $storageDirs = [
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/bootstrap/cache',
        '/tmp/storage/logs',
    ];

    foreach ($storageDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    // Ensure we don't use stale local caches
    putenv('APP_STORAGE_PATH=/tmp/storage');
}

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    // Log to stderr for Vercel
    error_log('Critical Error during bootstrap: ' . $e->getMessage());
    error_log($e->getTraceAsString());

    // Output to screen for immediate debugging
    header('Content-Type: text/plain');
    echo "Bootstrap Error:\n";
    echo $e->getMessage() . "\n\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo "Stack Trace:\n";
    echo $e->getTraceAsString();
    exit(1);
}