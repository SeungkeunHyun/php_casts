<?php
	//header('Content-Type: text/html; charset=utf-8');
	ini_set('default_charset', 'euc-kr');
	ini_set('upload_max_filesize', '200M');
	ini_set('post_max_size', '250M');
	ini_set('memory_limit', '-1');
	//ini_set('mbstring.http_input', 'pass');
	//ini_set('mbstring.http_output', 'pass');
	/*
	mb_internal_encoding('UTF-8');
	mb_http_output('UTF-8');
	mb_http_input('UTF-8');
	mb_language('uni');
	mb_regex_encoding('UTF-8');
	ob_start('mb_output_handler');
	header('Content-Type:text/html; charset=UTF-8');
	*/
	set_time_limit(180);
		ini_set("display_errors", 1);
	error_reporting(-1);
	$root = realpath(dirname(__FILE__));
	if(DIRECTORY_SEPARATOR == "/") {
		require_once($root."/getid3/getid3.php");
		require_once($root."/getid3/write.php");
	} else {
		require_once($root."\\getid3\\getid3.php");
		require_once($root."\\getid3\\write.php");
	}
	$fname = "174141_1531125287637.mp3";
	$tmpDir = $root.DIRECTORY_SEPARATOR."temp";
	$lclFile = $tmpDir.DIRECTORY_SEPARATOR.$fname;
		
	$gid3 = new getID3;
	$gid3->encoding = 'UTF-8';
	$gid3->setOption(array('encoding'=>'euc-kr'));
	//$gid3->tag_encoding = 'UTF-8';
	$finfo = $gid3->analyze($lclFile);
	print_r($finfo);
?>