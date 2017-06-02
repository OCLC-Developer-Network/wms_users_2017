<?php
require_once('vendor/autoload.php');
session_start();

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
	if (isset($_SERVER['HTTPS'])):
	$redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . "/catch_auth_code";
	else:
	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . "/catch_auth_code";
	endif;
	
	$services = array('WorldCatMetadataAPI');
	$options = array('services' => $services, 'redirectUri' => $redirect_uri);
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
	$view->getEnvironment()->addGlobal('session', $_SESSION);
	
	return $view;
};

$auth_mw = function ($request, $response, $next) {
	if ($request->getAttribute('route')->getArgument('oclcnumber')){
		$oclcnumber = $request->getAttribute('route')->getArgument('oclcnumber');
		$_SESSION['route'] = $this->get('router')->pathFor($request->getAttribute('route')->getName(), ['oclcnumber' => $oclcnumber]);
	} elseif ($request->getParam('oclcnumber')) {
		$oclcnumber = $request->getParam('oclcnumber');
		$_SESSION['route'] = $this->get('router')->pathFor($request->getAttribute('route')->getName()) ."?" . http_build_query($request->getQueryParams());
	} else {
		$oclcnumber = null;
		$_SESSION['route'] = $this->get('router')->pathFor($request->getAttribute('route')->getName());
	}
	
	if (empty($_SESSION['accessToken']) || ($_SESSION['accessToken']->isExpired() && (empty($_SESSION['accessToken']->getRefreshToken()) || $_SESSION['accessToken']->isExpired()))){
		$response = $response->withRedirect($this->get("wskey")->getLoginURL($this->get("config")['prod']['institution'], $this->get("config")['prod']['institution']));
	} else {
		$response = $next($request, $response);
	}
	
	return $response;
};

// Add route callbacks
//display form
$app->get('/', function ($request, $response, $args) {
	return $this->view->render($response, 'search_form.html');
})->setName('display_search_form');

//display bib route
$app->get('/bib[/{oclcnumber}]', function ($request, $response, $args){
	if (isset($args['oclcnumber'])){
		$oclcnumber = $args['oclcnumber'];
		$_SESSION['route'] = $this->get('router')->pathFor($request->getAttribute('route')->getName(), ['oclcnumber' => $args['oclcnumber']]);
	} elseif ($request->getParam('oclcnumber')) {
		$oclcnumber = $request->getParam('oclcnumber');
		$_SESSION['route'] = $this->get('router')->pathFor($request->getAttribute('route')->getName()) ."?" . http_build_query($request->getQueryParams());
	} else {
		return $this->view->render($response, 'error.html', [
				'error' => 'No OCLC Number present',
				'error_message' => 'Sorry you did not pass in an OCLC Number'
		]);
	}
	$bib = Bib::find($oclcnumber, $_SESSION['accessToken']);
	
	if (is_a($bib, "Bib")){
	
		return $this->view->render($response, 'bib.html', [
				'bib' => $bib
		]);
	}else {
		return $this->view->render($response, 'error.html', [
				'error' => $bib->getStatus(),
				'error_message' => $bib->getMessage(),
				'oclcnumber' => $args['oclcnumber']
		]);
	}
})->setName('display_bib')->add($auth_mw);

$app->get('/catch_auth_code', function ($request, $response, $args) {
	if ($request->getParam('code') && $_SESSION['route']){
		$_SESSION['accessToken'] = $this->get("wskey")->getAccessTokenWithAuthCode($request->getParam('code'), $this->get("config")['prod']['institution'], $this->get("config")['prod']['institution']);
		return $response->withRedirect($_SESSION['route']);
	}elseif ($request->getParam('error')){
		return $this->view->render($response, 'error.html', [
				'error' => $request->getParam('error'),
				'error_description' => $request->getParam('error_description')
		]);
	}else {
		return $response->withRedirect('/');
	}
})->setName('catch_auth_code');

$app->get('/logoff', function ($request, $response, $args) {
	$this->session->destroy();
	return $response->withRedirect('/');
})->setName('logoff');
	
// Run application
$app->run();

