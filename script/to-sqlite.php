<?php
$f = 'clique.sqlite';
//unlink($f);

require '../cli-config.php';

$dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);


$sqlite = new PDO("sqlite:$f");
$sqlite->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
/*
$sql = "
create table node_events (
	node_id integer,
	event_id integer,
	event_ts integer,
	primary key (event_ts, event_id, node_id)
)
";
$sqlite->exec($sql);


$sql = "
create table nodes (
	id integer,
	name text key,
	primary key (id)
)
";
$sqlite->exec($sql);


$sql = "
create table events (
	id integer,
	name text key,
	primary key (id)
)
";
$sqlite->exec($sql);
*/

$sql = "
create table nodes (
	id integer,
	name text key,
	primary key (id)
)
";
$sqlite->exec($sql);
$sql = "
insert into node_events (node_id, event_id, event_ts) values (?,?,?)
";
$insertStmt = $sqlite->prepare($sql);

$sql = "
insert into nodes (id, name) values (?,?)
";
$insertNodeStmt = $sqlite->prepare($sql);

$sql = "
insert into events (id, name) values (?,?)
";
$insertEventStmt = $sqlite->prepare($sql);













$sql = "
select *
from new_chars
";

$stmt = $dbh->prepare($sql);

$stmt->execute();
$i = 0;
$b = 1000;
$c = 0;
$sqlite->beginTransaction();
foreach ($stmt as $row) {
	extract($row);
	$i++;
	if ($i % $b === 0) {
		$sqlite->commit();
		$sqlite->beginTransaction();
		echo $c++, "\n";
	}
	$insertNodeStmt->execute([$id, $name]);
}
if ($i !== 0) {
	$sqlite->commit();
}
$stmt->closeCursor();

exit;













$sql = "
select *
from new_events
";

$stmt = $dbh->prepare($sql);

$stmt->execute();
$sqlite->beginTransaction();
foreach ($stmt as $row) {
	extract($row);
	$insertNodeStmt->execute([$id, $name]);
}
$sqlite->commit();
$stmt->closeCursor();



$sql = "
select *
from new_ach
";

$stmt = $dbh->prepare($sql);

$stmt->execute();
$i = 0;
$b = 1000;
$c = 0;
$sqlite->beginTransaction();
foreach ($stmt as $row) {
	extract($row);
	$i++;
	if ($i % $b === 0) {
		$sqlite->commit();
		$sqlite->beginTransaction();
		echo $c++, "\n";
	}
	
	
	$insertStmt->execute([$node_id, $evt_id, $evt_ts]);
}
if ($i !== 0) {
	$sqlite->commit();
}





