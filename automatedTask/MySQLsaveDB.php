<?php 
require '../vendor/autoload.php';
	$db = new mysqli('localhost', 'root', '', 'rss');
	$dump = new MySQLDump($db);
	$dump->save('../data/'.date("y.m.d").'-SQLsave.sql.gz');
 ?>