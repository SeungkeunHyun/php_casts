<?php

ini_set('upload_max_filesize', '200M');
ini_set('post_max_size', '250M');
ini_set('memory_limit', '-1');
error_reporting(E_ALL ^ E_DEPRECATED);
set_time_limit(0);
//ini_set('mbstring.http_input', 'pass');
//ini_set('mbstring.http_output', 'pass');
$root = realpath(dirname(__FILE__));

function download_remotefile($surl, $sfname) {
    // read the file from remote location
    $timeout = 0;
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_BUFFERSIZE, 12000);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_ENCODING,  '');
    curl_setopt($ch, CURLOPT_URL, $surl);

    $fp = fopen($sfname, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);

    curl_exec($ch);

    curl_close($ch);
    fclose($fp);
} 

function download_url($surl, $sfname) {
	file_put_contents($sfname, fopen($surl, 'r'));
}

function getFileNameFromPath($path) {
    $partsURL = pathinfo($path);
    return $partsURL["filename"].".".preg_replace("/\?.+$/", "", $partsURL["extension"]);
}


$tmpDir = dirname(__FILE__).DIRECTORY_SEPARATOR."temp";

foreach (glob($tmpDir.DIRECTORY_SEPARATOR."*.{mp3,tmp}", GLOB_BRACE) as $file) {
    if (filemtime($file) < time() - 600) {
        unlink($file);
    }
}

if(DIRECTORY_SEPARATOR == "/") {
    require_once($root."/getid3/getid3.php");
    require_once($root."/getid3/write.php");
} else {
    require_once($root."\\getid3\\getid3.php");
    require_once($root."\\getid3\\write.php");
}


$rmtFile = $_REQUEST["lnk"];
$fname = getFileNameFromPath($rmtFile);
$lclFile = $tmpDir.DIRECTORY_SEPARATOR.$fname;
if(!file_exists($lclFile)) {
    //download_remotefile($rmtFile, $lclFile);
	download_remotefile($rmtFile, $lclFile);
}


$tagwriter = new getid3_writetags;
$tagwriter->filename = $lclFile;
$tagwriter->tag_encoding  = "UTF-8"; 
$tagwriter->tagformats = array('id3v1', 'id3v2.3');

$gid3 = new getID3;
$gid3->setOption(array('encoding'=>'UTF-8'));
	//$gid3->tag_encoding = 'UTF-8';
//$finfo = $gid3->analyze($lclFile);

$finfo['title'] = array($_REQUEST["title"]);
$finfo['artist'] = array($_REQUEST["artist"]);
$finfo["album"] = array($_REQUEST["ttl"]);
$imgUrl = $_REQUEST["img"];

$imgFile = $tmpDir.DIRECTORY_SEPARATOR.getFileNameFromPath($imgUrl);
if(!file_exists($imgFile)){
    download_remotefile($imgUrl, $imgFile);
}
$imgBytes = file_get_contents($imgFile);


if(isset($_REQUEST["summary"])) {
    $finfo["comment"] = array($_REQUEST["summary"]);
}

$finfo += array("attached_picture" => array(0 => array(
    "picturetypeid" => 2,
    "picturetype" => "Cover (front)",
    "description" => "cover",
    "mime" => "image/jpeg",
    "data" => $imgBytes,
    "encoding" => "UTF-8",
    "datalength" => filesize($imgFile))
));
$tagwriter->tag_data = $finfo;
copy($lclFile, $lclFile.".bak");
if($tagwriter->WriteTags()) {

} else {
    //echo 'Failed to write tags!<br>'.implode('<br><br>', $tagwriter->errors);
    print_r($tagwriter);
    copy($lclFile.".bak", $lclFile);
}
unlink($lclFile.".bak");

$filesize = filesize($lclFile);
	//$partsURL = pathinfo($tgtFile);
	//$fname = $partsURL["filename"].".".$partsURL["extension"];
$dnFname = $fname;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, Accept");
header('Content-Description: File Transfer');
header("Expires: 0");
header("Content-Type: application/octet-stream");
//header('Content-Type: application/vnd.android.package-archive');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header("Content-Disposition: attachment; filename=\"".$dnFname."\"");
header("Pragma: public");
header("Content-Transfer-Encoding: binary");
header("Content-Length: $filesize");
readfile($lclFile);

//flush();

//ignore_user_abort(true);
/* set_time_limit(0);
$file = @fopen($lclFile, "rb");
while(!feof($file)) {
	print(@fread($file, 1024*8));
	ob_flush();
	flush();
}
/*
$gid3 = new getID3;
$gid3->setOption(array('encoding'=>'UTF-8'));
//$gid3->tag_encoding = 'UTF-8';
$finfo = $gid3->analyze($lclFile);
print_r($finfo);
*/
?>