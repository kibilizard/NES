<?php
	$db_hostname = 'localhost';
	$db_database = '****';
	$dsn = "mysql:host=$db_hostname;dbname=$db_database;charset=utf8";
	$opt = array(
		PDO::ATTR_ERRMODE 			 => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		);
    $pdo = new PDO($dsn, '****', '****', $opt);
	$x = $_GET['i'];
	$rezult = '[';
	$stmt = $pdo->prepare('SELECT name, addr, bdate, phones.number FROM abonents LEFT JOIN phones ON (phones.abonid = abonents.id) where abonents.id > ? AND abonents.id <= ?');
	$stmt->execute(array(($x*100),(($x+1)*100)));
	$row = $stmt->fetch();
	if ($row['name'] != '') $rezult = $rezult.'{"name":"'.$row['name'].'","addr":"'.$row['addr'].'","bdate":"'.$row['bdate'].'","phones":['.$row['number'];
	$f = $row['name'].$row['bdate'].$row['addr'];
	while($row = $stmt->fetch())
	{
		if ($f != $row['name'].$row['bdate'].$row['addr'])
		{
			$rezult = $rezult.']},{"name":"'.$row['name'].'","addr":"'.$row['addr'].'","bdate":"'.$row['bdate'].'","phones":['.$row['number'];
			$f = $row['name'].$row['bdate'].$row['addr'];
		}
		else $rezult = $rezult.','.$row['number'];
	}
	if ($rezult != '[') $rezult = $rezult.']}]';
	else $rezult = $rezult.']';
	echo $rezult;
?>