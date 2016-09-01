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
	$rezult = '[';
	//echo $rezult.'<br>';
	switch ($type)
	{
		case 'main':{
			$stmt = $pdo->query('SELECT * FROM localities ORDER BY id');
			while($row = $stmt->fetch())
				$rezult = $rezult.'{"id":'.$row['id'].',"name":"'.$row['name'].'","set":true,"streets":[]},';
	//echo $rezult.'<br>';
		$rezult = $rezult.'{"name":"","set":false,"streets":[]}]';
			//echo $rezult.'<br>';
			break;
		}
		case 'locality':{
			$stmt = $pdo->prepare('SELECT * FROM streets WHERE locid = ? ORDER BY id');
			$stmt->execute(array($id));
			while($row = $stmt->fetch())
				$rezult = $rezult.'{"id":'.$row['id'].',"name":"'.$row['name'].'","set":true,"homes":[]},';
	//echo $rezult.'<br>';
			$rezult = $rezult.'{"name":"","set":false,"homes":[]}]';
			//echo $rezult.'<br>';
			break;
		}
		case 'street':{
			$stmt = $pdo->prepare('SELECT * FROM homes WHERE streetid = ? ORDER BY id');
			$stmt->execute(array($id));
			while($row = $stmt->fetch())
				$rezult = $rezult.'{"id":'.$row['id'].',"name":'.$row['number'].',"set":true,"aparts":[]},';
	//echo $rezult.'<br>';
			$rezult = $rezult.'{"name":"","set":false,"aparts":[]}]';
	//echo $rezult.'<br>';
			break;
		}
		case 'home':{
			$stmt = $pdo->prepare('SELECT * FROM apartments WHERE hid = ? ORDER BY id');
			$stmt->execute(array($id));
			while($row = $stmt->fetch())
				$rezult = $rezult.'{"id":'.$row['id'].',"name":'.$row['number'].',"set":true},';
	//echo $rezult.'<br>';
			$rezult = $rezult.'{"name":"","set":false}]';
	//echo $rezult.'<br>';
			break;
		}
	}
	echo $rezult;
?>