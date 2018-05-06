<h1>SAVED MySQL DATABASE 'media'</h1><br><br>
<?php 
require '../vendor/autoload.php';
try {
	$db = new mysqli('localhost', 'root', '', 'rss');
	$dump = new MySQLDump($db);
	$dump->save('../data/'.date("y.m.d").'-SQLsave.sql.gz');
	echo "Saved without errors : " . date("y.m.d");

} catch (Exception $e) {
	echo "Errors : " . $e;
}

 ?>