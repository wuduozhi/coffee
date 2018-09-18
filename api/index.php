<?php
require __DIR__ . '/vendor/autoload.php';

// Instantiate the app
$settings = require __DIR__ . '/src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/src/dependencies.php';

// Register routes
require __DIR__ . '/src/routes.php';
//Start session
session_start();

//设置跨域访问
// header("Access-Control-Allow-Origin: *")

// Run app
$app->run();
