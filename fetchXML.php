<?php
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
/*
	// Create a curl handle
	$arrContextOptions=array(
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		),
	);  
	header('Content-type:application/xml');
	$conx = file_get_contents($_GET['q'], false, stream_context_create($arrContextOptions));
	echo $conx;
	// Execute
	//curl_exec($ch);
*/
	$path = $_GET['q'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$path);
	//curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/variant_div(left, right).epg.vrt.be.playlist_1.1+xml'));
	curl_setopt($ch, CURLOPT_FAILONERROR,1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$xmlData = curl_exec($ch);
	curl_close($ch);
	echo $xmlData;
?>