<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	$ctx = stream_context_create(array(
		'http' => array(
				'timeout' => 180
			)
		)
	);
	$dat = file($_GET['q']);
	echo $dat;
?>