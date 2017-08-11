<?php
$auth_mw = function ($request, $response, $next) {
	$_SESSION['accessToken'] = $this->get("wskey")->getAccessTokenWithClientCredentials($this->get("config")['prod']['institution'], $this->get("config")['prod']['institution'], $this->get("user"));
	$response = $next($request, $response);
	return $response;
};