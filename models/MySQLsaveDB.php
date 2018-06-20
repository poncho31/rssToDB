<h1>SAVED MySQL DATABASE 'media'</h1><br><br>
<?php 
require 'vendor/autoload.php';
try {
	$db = new mysqli('localhost', 'root', '', 'rss');
	$dump = new MySQLDump($db);
	$dump->save('data/'.date("y.m.d").'-SQLsave.sql.gz');
	$pathContents = file_get_contents('./GitPushRssToDB.sh');
	$output = shell_exec("git add .");
	$output .= shell_exec('git commit -m "Automated rss to DB "');
	echo $output;

	if ($output) {
		echo "GIT PUSH DONE :" . $pathContents . "<br>";
	}
	echo "Saved without errors : " . date("y.m.d");

} catch (Exception $e) {
	echo "Errors : " . $e;
}

 ?>