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
	$pid = $_GET['pid'];
	$name = $_GET['name'];
	//echo $rezult.'<br>';
	switch ($type)
	{
		case 'locality':{
			$stmt = $pdo->prepare('INSERT INTO `localities`(`name`) VALUES (?)');
			$stmt->execute(array($name));
			$stmt = $pdo->prepare('SELECT `id` FROM localities WHERE name = ?');
			$stmt->execute(array($name));
			$row = $stmt->fetch();
			echo $row['id'];
			break;
		}
		case 'street':{
			$stmt = $pdo->prepare('INSERT INTO `streets`(`locid`, `name`) VALUES (?,?)');
			$stmt->execute(array($pid,$name));
			$stmt = $pdo->prepare('SELECT `id` FROM streets WHERE name = ?');
			$stmt->execute(array($name));
			$row = $stmt->fetch();
			echo $row['id'];
			break;
		}
		case 'home':{
			$stmt = $pdo->prepare('INSERT INTO `homes`(`streetid`, `number`) VALUES (?,?)');
			$stmt->execute(array($pid,$name));
			$stmt = $pdo->prepare('SELECT `id` FROM homes WHERE number = ?');
			$stmt->execute(array($name));
			$row = $stmt->fetch();
			echo $row['id'];
			break;
		}
		case 'apart':{
			$stmt = $pdo->prepare('INSERT INTO `apartments`(`hid`, `number`) VALUES (?,?)');
			$stmt->execute(array($pid,$name));
			$stmt = $pdo->prepare('SELECT `id` FROM apartments WHERE number = ?');
			$stmt->execute(array($name));
			$row = $stmt->fetch();
			echo $row['id'];
			break;
		}
	}
?>