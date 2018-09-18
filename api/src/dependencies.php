<?php
// DIC configuration  依赖注入

$container = $app->getContainer();
$container['coffee'] = function ($c) {
	$settings = $c->get('settings')['coffee'];
	$database = new \Medoo\Medoo($settings);
	return $database;
};


?>
