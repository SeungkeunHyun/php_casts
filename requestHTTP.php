<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	$request = new http\Client\Request("GET", $_GET['uri'], ["User-Agent"=>"My Client/0.1"]);
	$request->setOptions(["timeout" => 5]);
	$client = new http\Client;
	$client->enqueue($request)->send();
	$response = $client->getResponse();
	$body = $request->getResponseBody();
	echo $body;
?>	