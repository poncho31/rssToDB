<h1>SAVED MySQL DATABASE 'media'</h1><br><br>
<?php 
require 'vendor/autoload.php';
try {
	//Saved DB
	$db = new mysqli('localhost', 'root', '', 'rss');
	$dump = new MySQLDump($db);
	$dump->save('data/'.date("y.m.d").'-SQLsave.sql.gz');

	// $pathContents = file_get_contents('./GitPushRssToDB.sh');
	// exec("C:\wamp\bin\php\php5.6.35\php.exe C:\wamp\www\rssToDB\GitPushRssToDB.sh", $output);
	// print_r($output)
	// $output;
	// if ($output) {
	// 	echo "GIT PUSH DONE :" . $pathContents . "<br>";
	// }
	echo "Saved without errors : " . date("y.m.d");

} catch (Exception $e) {
	echo "Errors : " . $e;
}

 ?>