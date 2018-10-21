<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'sessionAuth' => true,    // set to true in production
        'msgSessionAuth' => true,   // true meaning login is needed for message board
        // 微湖大db
        'coffee' => [
            'database_type' => 'mysql',
            'database_name' => 'purtchase',
            'server' => '120.79.197.217',
            'username' => 'PURTCHASE',
            'password' => 'PurtChase123',
            'port' => '3306',
            'charset' => 'utf8',
        ],
    ],
];
