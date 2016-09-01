<?php
	$db_hostname = 'localhost';
	$db_database = '****';
	$dsn = "mysql:host=$db_hostname;dbname=$db_database;charset=utf8";
	$opt = array(
		PDO::ATTR_ERRMODE 			 => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		);
    $pdo = new PDO($dsn, '****', '****', $opt);
	$nab = false;
	$na = false;
	$nb = false;
	$ba = false;
	$b = false;
	$a = false;
	$n = false;
	if (!isset($_GET['name']))
	{
		if(!isset($_GET['bdate']))
			$a = true;
		else if(isset($_GET['addr']))
			$ba = true;
		else $b = true;
	}
	else if (!isset($_GET['bdate'])){
		if (isset($_GET['addr']))
			$na = true;
		else $n = true;
	}
	else if (!isset ($_GET['addr'])) $nb = true;
	else $nab = true;
	if($n) {
		$stmt = $pdo->prepare('SELECT name, addr, bdate, phones.number FROM abonents LEFT JOIN phones ON (phones.abonid = abonents.id) WHERE name LIKE ?');
		$stmt->execute(array('%'.$_GET['name'].'%'));
	}
	if($a) {
		$stmt = $pdo->prepare('SELECT name, addr, bdate, phones.number FROM abonents LEFT JOIN phones ON (phones.abonid = abonents.id) WHERE LIKE ?');
		$stmt->execute(array('%'.$_GET['addr'].'%'));
	}
	if($b) {
		$stmt = $pdo->prepare('SELECT name, addr, bdate, phones.number FROM abonents LEFT JOIN phones ON (phones.abonid = abonents.id) WHERE bdate LIKE ?');
		$stmt->execute(array('%'.$_GET['bdate'].'%'));
	}
	if($na) {
		$stmt = $pdo->prepare('SELECT name, addr, bdate, phones.number FROM abonents LEFT JOIN phones ON (phones.abonid = abonents.id) WHERE name LIKE ? AND addr LIKE ?');
		$stmt->execute(array('%'.$_GET['name'].'%','%'.$_GET['addr'].'%'));
	}
	if($nb) {
		$stmt = $pdo->prepare('SELECT name, addr, bdate, phones.number FROM abonents LEFT JOIN phones ON (phones.abonid = abonents.id) WHERE name LIKE ? AND bdate LIKE ?');
		$stmt->execute(array('%'.$_GET['name'].'%', '%'.$_GET['bdate'].'%'));
	}
	if($ba) {
		$stmt = $pdo->prepare('SELECT name, addr, bdate, phones.number FROM abonents LEFT JOIN phones ON (phones.abonid = abonents.id) WHERE addr LIKE ? AND bdate LIKE ?');
		$stmt->execute(array('%'.$_GET['addr'].'%', '%'.$_GET['bdate'].'%'));
	}
	if($nab) {
		$stmt = $pdo->prepare('SELECT name, addr, bdate, phones.number FROM abonents LEFT JOIN phones ON (phones.abonid = abonents.id) WHERE name LIKE ? AND addr LIKE ? AND bdate LIKE ?');
		$stmt->execute(array('%'.$_GET['name'].'%', '%'.$_GET['addr'].'%', '%'.$_GET['bdate'].'%'));
	}
	$rezult = '[';
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