<?php
	ini_set('safe_mode', false);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	function get_data($url) {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		$timeout = 600;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		curl_setopt($ch, CURLOPT_HEADER, 1);		
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
    $uri = $_GET["uri"];
    try {
        $header = array(
        "Accept: */*",
        "Accept-Language: ko-kr,ko;q=0.7,en-us;q=0.5,en;q=0.3",
        "Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7",
        "Sec-Fetch-Mode: cors",
        "Sec-Fetch-Site: same-origin",
        "Keep-Alive: 300");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);        
        #curl_setopt($ch, CURLOPT_URL, $uri); 
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36');
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  0);
        #curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
        #curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_URL, $uri);
        #curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_2_0);
        #curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR,1); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $content = curl_exec($ch);
        if(!$content) {
            die('Error: "'.curl_error($ch).'" - Code: '.curl_errno($ch).":".$content);
        }
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $hdr_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $hdr = substr($content, 0, $hdr_len);
        $body = substr($content, $hdr_len);
        header("Content-Type: $content_type");
        if($responseCode == 200) {
            echo $body;
        } else {
            //header('HTTP/1.1 500 Internal Server Error');
            echo $body;
        }
        curl_close($ch);
    } catch(Exception $ex) {
        print_r($ex);
    }
?>
