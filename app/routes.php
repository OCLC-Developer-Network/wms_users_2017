<?php
// Add routes

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