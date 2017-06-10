<?php
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