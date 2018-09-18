<?php
$app->get('/', \Controllers\BaseController::class.':index');
$app->post('/image',\Controllers\ImageController::class.':file');
$app->post('/purchase',\Controllers\PurchaseController::class.':add');
$app->get('/purchase',\Controllers\PurchaseController::class.':get');