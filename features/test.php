<?php
require_once(__DIR__ . '/../vendor/autoload.php');
\VCR\VCR::turnOn();
\VCR\VCR::configure()->setCassettePath(__DIR__ . '/mocks');
\VCR\VCR::configure()->enableRequestMatchers(array('method', 'url', 'host'));
\VCR\VCR::insertCassette('test_mocks');

session_start();

// instantiate the App object

$config = require __DIR__ . '/../app/settings.php';

$app = new \Slim\App($config);

// Set up dependencies
require __DIR__ . '/../app/dependencies.php';
// Register middleware
require __DIR__ . '/../app/middleware.php';
// Register routes
require __DIR__ . '/../app/routes.php';

// Get container
$container = $app->getContainer();
	
// Run application
$app->run();

