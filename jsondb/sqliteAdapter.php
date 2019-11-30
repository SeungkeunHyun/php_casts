<?php 
	header('Content-Type', 'application/json; charset=utf-8');
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
	$dbfile = getcwd().DIRECTORY_SEPARATOR."sqlite.db";
	if(!file_exists($dbfile)) {
		$db = new SQLite3($dbfile);			
		$db->exec('CREATE TABLE casts (records json)');
		$db->exec('CREATE TABLE episodes (key_md5 varchar(255) primary key, cast_id varchar(255), episode json)');
	}
	$db = new SQLite3($dbfile);
	$paths = explode('/', trim($_SERVER['PATH_INFO'],'/'));
	$tab = $paths[0];
	$id = null;
	$reqJSON = file_get_contents('php://input');
	if(count($paths) > 1) {
		$id = $paths[1];
	}
	switch($_SERVER['REQUEST_METHOD']) {
		case "GET":
			$res = $db->querySingle('select records from casts', true);
			echo $res['records'];
			return;
		break;
		case "PUT":
			if($id === null) {
				$db->exec('DELETE FROM '.$tab);
				$stmt = $db->prepare('INSERT INTO '.$tab." VALUES(?)");
				print_r($stmt);
				$stmt->bindValue(1, $reqJSON, SQLITE3_TEXT);
				$res = $stmt->execute();
				print_r($res);
			}
		break;
		case "POST":
			$req = json_decode($reqJSON, true);
			print_r($req);
			foreach($req['episodes'] as $row) {
				$db->exec("DELETE FROM ".$tab." WHERE key_md5 = '".md5($row['mediaURI'])."'");
				$stmt = $db->prepare('INSERT INTO '.$tab.' (key_md5, cast_id, episode) VALUES(?, ?, ?)');
				$stmt->bindValue(1, md5($row['mediaURI']), SQLITE3_TEXT);
				$stmt->bindValue(2, $req['cast_id'], SQLITE3_TEXT);
				$stmt->bindValue(2, json_encode($row), SQLITE3_TEXT);
				$stmt->execute();
			}
			http_response_code(200);
		break;
	}
	$db->close();
?>