<?php
// Copy this file to config.php and edit the values.
return [
    // Full public address of the site, without a trailing slash.
    'base_url' => 'https://rastingroup.ir',

    // Database. SQLite needs no setup (the file is created automatically in storage/).
    // For MySQL set driver to 'mysql' and fill in the mysql block.
    'db' => [
        'driver' => 'sqlite',
        'sqlite_path' => __DIR__ . '/storage/rastin.sqlite',
        'mysql' => [
            'host' => 'localhost',
            'port' => 3306,
            'name' => 'rastin',
            'user' => 'rastin',
            'pass' => '',
        ],
    ],

    'default_lang' => 'fa',
    'languages' => ['fa', 'en', 'ar', 'tr'],

    // Show PHP errors in the browser. Keep false on the live site.
    'debug' => false,

    // Admin panel address: https://rastingroup.ir/admin
    'admin_path' => 'admin',
];
