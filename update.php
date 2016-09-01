<?php
	$db_hostname = 'localhost';
	$db_database = '****';
	$dsn = "mysql:host=$db_hostname;dbname=$db_database;charset=utf8";
	$opt = array(
		PDO::ATTR_ERRMODE 			 => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		);
    $pdo = new PDO($dsn, '****', '****', $opt);
	$type = $_GET['type'];
	$id = $_GET['id'];
	$name = $_GET['name'];
	switch ($type)
	{
		case 'locality':{
			$stmt = $pdo->prepare('UPDATE `localities` SET `name`=? WHERE id = ?');
			$stmt->execute(array($name,$id));
			break;
		}
		case 'street':{
			$stmt = $pdo->prepare('UPDATE `streets` SET `name`=? WHERE id = ?');
			$stmt->execute(array($name,$id));
			break;
		}
		case 'home':{
			$stmt = $pdo->prepare('UPDATE `homes` SET `number`=? WHERE id = ?');
			$stmt->execute(array($name,$id));
			break;
		}
		case 'apart':{
			$stmt = $pdo->prepare('UPDATE `apartments` SET `number`=? WHERE id = ?');
			$stmt->execute(array($name,$id));
			break;
		}
	}
?>