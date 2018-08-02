<?php
use Poncho\Database\Database;
require 'vendor/autoload.php';

$db = new Database();
$path = '../data/lexique/lexiqueMin.csv';
$table = 'lexique';
$query = <<<eof
    LOAD DATA LOCAL INFILE 'E:/wamp64/www/PROJETS/rssToDB/data/lexique/lexiqueMin.csv'
     INTO TABLE lexique  character set latin1
     FIELDS TERMINATED BY ';' OPTIONALLY ENCLOSED BY '"'
     LINES TERMINATED BY '\n'
    (orthographe,lemme,grammaire,genre,nombre,frequenceLivre,nombreLettre)
eof;
$db->getQuery($query, []);
if ($db->getQuery($query)) {
	echo 'Success : CSV to Database ';
}
else{
	echo 'Error : CSV to Database';
 }