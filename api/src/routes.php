<?php
$app->get('/', \Controllers\BaseController::class.':index');
$app->post('/image',\Controllers\ImageController::class.':file');