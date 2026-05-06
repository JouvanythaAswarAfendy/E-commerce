<?php

// Ensure writable directories exist on Vercel's serverless environment.
// Vercel provides /tmp as the only writable directory.
if (getenv('VERCEL')) {
    $storageDirs = [
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/logs',
    ];

    foreach ($storageDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    // Point Laravel's storage path to /tmp
    $_ENV['APP_STORAGE_PATH'] = '/tmp/storage';
    putenv('APP_STORAGE_PATH=/tmp/storage');
}

require __DIR__ . '/../public/index.php';