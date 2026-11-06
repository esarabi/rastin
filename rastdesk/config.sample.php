<?php
// Copy this file to config.php and edit the values.
return [
    // Full public address of the site, without a trailing slash.
    'base_url' => 'https://rastdesk.ir',

    // Database. SQLite needs no setup (the file is created automatically in storage/).
    // For MySQL set driver to 'mysql' and fill in the mysql block.
    'db' => [
        'driver' => 'sqlite',
        'sqlite_path' => __DIR__ . '/storage/rastdesk.sqlite',
        'mysql' => [
            'host' => 'localhost',
            'port' => 3306,
            'name' => 'rastdesk',
            'user' => 'rastdesk',
            'pass' => '',
        ],
    ],

    'default_lang' => 'fa',
    'languages' => ['fa', 'en'],

    // Show PHP errors in the browser. Keep false on the live site.
    'debug' => false,

    // Admin panel address: https://rastdesk.ir/admin
    'admin_path' => 'admin',
];
