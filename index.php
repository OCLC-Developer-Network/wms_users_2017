<?php
require_once('vendor/autoload.php');

use OCLC\Auth\WSKey;
use OCLC\User;
use Symfony\Component\Yaml\Yaml;

// instantiate the App object
$config = [
		'settings' => [
				'displayErrorDetails' => true
		],
];


$app = new \Slim\App($config);

// Get container
$container = $app->getContainer();

// Get container
$container = $app->getContainer();

$container['config'] = function ($c) {
	return Yaml::parse(file_get_contents(__DIR__ . '/app/config/config.yml'));
};

$container['wskey'] = function ($c) {
	$services = array('WorldCatMetadataAPI');
	$options = array('services' => $services);
	return new WSKey($c->get("config")['prod']['wskey'], $c->get("config")['prod']['secret'], $options);
};

$container['user'] = function ($c) {
	return new User($c->get("config")['prod']['institution'], $c->get("config")['prod']['principalID'], $c->get("config")['prod']['principalIDNS']);
};

// Register component on container
$container['view'] = function ($container) {
	$view = new \Slim\Views\Twig('app/views', [
			'cache' => 'app/cache'
	]);
	
	// Instantiate and add Slim specific extension
	$basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
	$view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
	
	return $view;
};

// Add route callbacks
//display form
$app->get('/', function ($request, $response, $args) {
	return $this->view->render($response, 'search_form.html');
})->setName('display_search_form');

//display bib route
$app->post('/bib', function ($request, $response, $args) {
	$accessToken = $this->get("wskey")->getAccessTokenWithClientCredentials($this->get("config")['prod']['institution'], $this->get("config")['prod']['institution'], $this->get("user"));
	
	$bib = Bib::find($request->getParam('oclcnumber'), $accessToken);
	
	if (is_a($bib, "Bib")){
		
		return $this->view->render($response, 'bib.html', [
				'bib' => $bib
		]);
	}else {
		return $this->view->render($response, 'error.html', [
				'error' => $bib,
				'oclcnumber' => $args['oclcnumber']
		]);
	}
})->setName('search_bib');

//display bib route
$app->get('/bib/{oclcnumber}', function ($request, $response, $args) {
	$accessToken = $this->get("wskey")->getAccessTokenWithClientCredentials($this->get("config")['prod']['institution'], $this->get("config")['prod']['institution'], $this->get("user"));
	
	$bib = Bib::find($args['oclcnumber'], $accessToken);
	
	if (is_a($bib, "Bib")){
	
		return $this->view->render($response, 'bib.html', [
				'bib' => $bib
		]);
	}else {
		return $this->view->render($response, 'error.html', [
				'error' => $bib,
				'oclcnumber' => $args['oclcnumber']
		]);
	}
})->setName('display_bib');
	
// Run application
$app->run();

