<?php 
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
	header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
	header('Content-Type', 'application/json; charset=utf-8');	

	$dbfile = getcwd().DIRECTORY_SEPARATOR."sqlite.db";
	if(!file_exists($dbfile)) {
		$db = new SQLite3($dbfile);
		$db->exec('CREATE TABLE casts (records json)');
		$db->exec('CREATE TABLE episodes (key_md5 varchar(255) primary key, cast_id varchar(255), episode json)');
	}
	$db = new SQLite3($dbfile);
	$db->busyTimeout(5000);
	$db->exec('PRAGMA journal_mode = wal;');
	$paths = explode('/', trim($_SERVER['PATH_INFO'],'/'));
	$tab = $paths[0];
	$id = null;
	$reqJSON = file_get_contents('php://input');
	if(count($paths) > 1) {
		$id = $paths[1];
	}
	switch($_SERVER['REQUEST_METHOD']) {
		case "GET":
			if($tab === 'casts') {
				$res = $db->querySingle('select records from casts', true);
				echo $res['records'];
			} else {
				$qry = "select episode from ".$tab." where cast_id = '".$paths[1]."'";
				$res = $db->query($qry);
				$results = [];
				while($row = $res->fetchArray()) {
					$results[] = json_decode($row['episode']);
				}
				if(count($results) > 0) {
					echo json_encode($results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_SLASHES);
				}
			}
		break;
		case "PUT":
			if($id === null) {
				$db->exec('DELETE FROM '.$tab);
				$stmt = $db->prepare('INSERT INTO '.$tab." VALUES(?)");
				$stmt->bindValue(1, $reqJSON, SQLITE3_TEXT);
				$res = $stmt->execute();
				print_r($res);
			}
		break;
		case "POST":
			$req = json_decode($reqJSON, true);
			foreach($req['episodes'] as $row) {
				$md5key = md5($row['mediaURI']);
				$db->exec("DELETE FROM ".$tab." WHERE key_md5 = '".$md5key."'");
				$stmt = $db->prepare('INSERT INTO '.$tab.' (key_md5, cast_id, episode) VALUES(?, ?, ?)');
				$stmt->bindValue(1, $md5key, SQLITE3_TEXT);
				$stmt->bindValue(2, $req['cast_id'], SQLITE3_TEXT);
				$stmt->bindValue(3, json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), SQLITE3_TEXT);
				$stmt->execute();
			}
			http_response_code(200);
		break;
	}
	$db->close();
?>