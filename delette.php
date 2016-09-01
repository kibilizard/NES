<?php
	$db_hostname = 'localhost';
	$db_database = '*****';
	$dsn = "mysql:host=$db_hostname;dbname=$db_database;charset=utf8";
	$opt = array(
		PDO::ATTR_ERRMODE 			 => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		);
    $pdo = new PDO($dsn, '****', '****', $opt);
	$type = $_GET['type'];
	$id = $_GET['id'];
	//echo $rezult.'<br>';
	switch ($type)
	{
		case 'locality':{
			$stmt = $pdo->prepare('DELETE FROM `localities` WHERE `id`= ?');
			$stmt->execute(array($id));
			$stmt = $pdo->prepare('SELECT `id` FROM streets WHERE locid = ?');
			$stmt->execute(array($id));
			while($row = $stmt->fetch()){
				$stm2 = $pdo->prepare('SELECT `id` FROM homes WHERE streetid = ?');
				$stm2->execute(array($row['id']));
				while ($r2= $stm2->fetch()){
					$stm3 = $pdo->prepare('DELETE FROM apartments WHERE hid = ?');
					$stm3->execute(array($r2['id']));
				}
				$stm2 = $pdo->prepare('DELETE FROM homes WHERE streetid = ?');
				$stm2->execute(array($row['id']));
			}
			$stmt = $pdo->prepare('DELETE FROM streets WHERE locid = ?');
			$stmt->execute(array($id));
			break;
		}
		case 'street':{
			$stmt = $pdo->prepare('DELETE FROM streets WHERE `id`= ?');
			$stmt->execute(array($id));
			$stmt = $pdo->prepare('SELECT `id` FROM homes WHERE streetid = ?');
			$stmt->execute(array($id));
			while($row = $stmt->fetch()){
				$stm2 = $pdo->prepare('DELETE FROM apartments WHERE hid = ?');
				$stm2->execute(array($row['id']));
			}
			$stmt = $pdo->prepare('DELETE FROM homes WHERE streetid = ?');
			$stmt->execute(array($id));
			break;
		}
		case 'home':{
			$stmt = $pdo->prepare('DELETE FROM homes WHERE `id`= ?');
			$stmt->execute(array($id));
			$stmt = $pdo->prepare('DELETE FROM apartments WHERE hid = ?');
			$stmt->execute(array($id));
			break;
		}
		case 'apart':{
			$stmt = $pdo->prepare('DELETE FROM apartments WHERE id = ?');
			$stmt->execute(array($id));
			break;
		}
	}
	echo 'success';
?>