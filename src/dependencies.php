<?php

$container = $app->getContainer();

// Establish a database connection 
$container['db'] = function () {
	try { 
		$db = new PDO("sqlite:".__DIR__."/../../blog.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
    } 
    catch (Exception $e) {
		echo 'Sorry, friend! '; 
        echo $e->getMessage();
		exit;
	}
	return $db;
};
// Using monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};
// Renderer
$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    $view = new \Slim\Views\Twig($settings['template_path'], [
        'debug' => true]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));
    return $view;
};