<h1>SAVED MySQL DATABASE 'media'</h1><br><br>
<?php 
require '../vendor/autoload.php';
try {
	$db = new mysqli('localhost', 'root', '', 'rss');
	$dump = new MySQLDump($db);
	$dump->save('../data/'.date("y.m.d").'-SQLsave.sql.gz');
	echo exec(escapeshellcmd('git add .
git commit -m "Automated rss to DB => `date +"%Y-%m-%d %H:%M:%S"`"
git push origin automatedRSS
'), $output);
	print_r($output);
	echo "Saved without errors : " . date("y.m.d");

} catch (Exception $e) {
	echo "Errors : " . $e;
}

 ?>