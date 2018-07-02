<?php 

// *******************PATCH FOR Table media COLUMN 'Catégorie'************
include_once 'API/simpleHtmlDom/simple_html_dom.php';

try {
	
echo "<b>PATCH column 'categorie' </b><br>";
$db = new PDO('mysql:dbname=rss;host=localhost;charset=utf8','root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// RTL INFO
$selectCat = 
	'
	SELECT categorie, lien, nom, idMedia as id FROM media where categorie IS NULL and nom NOT like "%Levif.be%"
	';
$stmt = $db->prepare($selectCat);
$stmt->execute();
	while ($row = $stmt->fetch()) {
	    echo $row['nom'] . " => Categorie : ' <span style='color:red;'>" . $row['categorie'] . "</span> '  => " . $row['id'] . " <br>";

		$link = $row['lien'];
		$contentURL =  file_get_html($link);
		if ($contentURL == true) {
			$rtlCategory = "";
			foreach($contentURL->find('.w-content-details-article-breadcrumb li:nth-child(4) > a') as $name) {
				$rtlCategory .=  strip_tags($name);
			}
			$replacedElement = array("Home", "Actu", "Belgique", "/\s+/");
			$rtlCategory = trim(str_replace($replacedElement, "", $rtlCategory));
			echo $rtlCategory;

			$sqlINSERT = "UPDATE media SET categorie = :nomCategorieAinserer WHERE idMedia = :idCatNull ";
			$stmtUpdate = $db->prepare($sqlINSERT);
			$stmtUpdate->bindvalue(':nomCategorieAinserer', $rtlCategory);
			$stmtUpdate->bindvalue(':idCatNull', $row['id'])
			;
			if ($stmtUpdate->execute()) {
				$stmtUpdate->execute();
				echo " OK :D <br>";
			}
			else{
				echo "Pas OK :'( <br>";
			}
		}
		else{
			echo "Erreur";
		}
	}
	echo "End RTL";
// LE VIF

$selectCat = 
	'
	SELECT categorie, lien, nom, idMedia as id FROM media where categorie IS NULL and nom like "%Levif.be%"
	';
$stmt = $db->prepare($selectCat);
$stmt->execute();
	while ($row = $stmt->fetch()) {
	    echo $row['nom'] . " => Categorie : ' <span style='color:red;'>" . $row['categorie'] . "</span> '  => " . $row['id'] . " <br>";
	    $id = $row['id'];
	    $link= $row['lien'];
	    $tab = explode('/', $link);
	    $categoryVif = $tab[3] . " " . $tab[4];

		$sqlINSERT = "UPDATE media SET categorie = :nomCategorieAinserer WHERE idMedia = :idCatNull ";
		$stmt = $db->prepare($sqlINSERT);
		$stmt->bindvalue(':nomCategorieAinserer', $categoryVif);
		$stmt->bindvalue(':idCatNull', $id);
		if ($stmt) {
			$stmt->execute();
			echo $categoryVif;
			echo " OK :D <br>";
		}
		else{
			echo "Pas OK :'( <br>";
		}
	}
	echo 'End Vif<br>';
}

catch (PDOException $e) {
	echo $e->getMessage();
}





//**********************PATCH for Table media COLUMN 'NOM'******************

echo "<hr><b>PATCH column 'NOM' </b><br>";
//SELECT FROM DB - AFFICHAGE DES DONNEES
try {
    $db = new PDO("mysql:host=localhost;dbname=rss;charset=utf8","root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $db->exec("set names utf8");
}
catch (PDOException $e)
{
	die('<span style="color:black">Erreur :  : ' . $e->getMessage()) . '</span>';
}
$sqlSelectMedia = "SELECT nom, count(nom) as numb FROM media GROUP BY nom";
$tot = 0;
foreach ($db->query($sqlSelectMedia) as $row) {
	echo $row['nom'] . " => ". $row['numb'] . "<br>";
	$tot += $row['numb'];
}
echo 'Total : ' . $tot;


$updateTable =
			  [
			  	"UPDATE media SET nom = 'rtl' where nom like '%rtl%'",
			  	"UPDATE media SET nom = 'dh' where nom like '%dh%'",
			  	"UPDATE media SET nom = 'lecho' where nom like '%lecho%' or nom like '%fair trade%' or nom like '%t-zine%' or nom like '%tijd%'",
			  	"UPDATE media SET nom = 'lesoir' where nom like '%lesoir%'",
			  	"UPDATE media SET nom = 'lalibre' where nom like '%lalibre%'",
			  	"UPDATE media SET nom = 'levif' where nom like '%levif%'",
			  	"UPDATE media SET nom = 'rtbf' where nom like '%rtbf%'",
			  	"UPDATE media SET nom = 'rtbf' where nom like '%la Première%'",
			  	"UPDATE media SET nom = 'sudinfo' where nom like '%sudinfo%'",
			  ];
foreach ($updateTable as $key) {
	$db->query($key);
}

// ****************PATCH for table media COLUMN 'DATE' ***********************************************

// $sqlSelectDate = "SELECT idMedia, date FROM media";

// $stmt = $db->query($sqlSelectDate);
// while ($row = $stmt->fetch()) {
// 	$sqlUpdateDate = "UPDATE media SET date = '".strftime('%Y-%m-%d %H:%M:%S', strtotime($row['date']))."' WHERE idMedia = '".$row['idMedia']."'";

// 	$db->query($sqlUpdateDate);
// }





// *****************INSERT politicians firstname/lastname intto TABLE Politicians ***************************

// Select txt
// $fp = fopen("data/politiciansNamesListFORMATED.txt", 'r');
// $fr = fread($fp, filesize("data/politiciansNamesListFORMATED.txt"));
//Explode txt in an array
// $arraycsv = explode(';', $fr);

// $politiciansName = [];
//Pour chaque ligne de politicien, retrouver son nom et prénom grâce à un délimiteur 'espace' en inversant la ligne (car prénom est à la fin)
//
// foreach ($arraycsv as $key) {
// 	$politicians = explode(' ', strrev($key), 2);
// 	$firstname = strrev($politicians[0]);
// 	$lastname = strrev($politicians[1]);
// 	$politiciansName[$lastname] = $firstname;
// }
// Insérer chaque politicien dans Table 'politicians'
// foreach ($politiciansName as $key => $value) {
// 	$sql = "INSERT INTO politicians (lastname, firstname) VALUES(:lastname, :firstname)";
// 	$stmt = $db->prepare($sql);
// 	$stmt->bindparam(':lastname', $key);
// 	$stmt->bindparam(':firstname', $value);
// 	$stmt->execute();
// }

//INSERT idPol from table politicians where firstname or lastname found in description table Media in table medpol fkPol and fkmedia
//1) Select name from politician
//2) Check if name match with description'
// SELECT p.idPol, p.lastname, m.description
// FROM politicians p
// LEFT JOIN media as m
// ON p.idPol like '%%'
// WHERE TA.ID IS NULL
// SELECT p.idPol, p.lastname, m.description
// FROM politicians p, media m
// WHERE EXISTS
// (
//    SELECT * FROM media as m
//    WHERE m.description like '%p.lastname%'
// )


// $fp = fopen("data/politiciansNamesListFORMATED.txt", 'r');
// $fr = fread($fp, filesize("data/politiciansNamesListFORMATED.txt"));
// $arraycsv = explode(';', $fr);
// $politiciansName = [];
// foreach ($arraycsv as $key) {
// 	$politicians = explode(' ', strrev($key), 2);
// 	$firstname = strrev($politicians[0]);
// 	$lastname = strrev($politicians[1]);
// 	$politiciansName[$lastname] = $firstname;
// }
// $i = 0;
// foreach ($politiciansName as $key) {
// 	$sqlMatchName = "
// 	SELECT nom
// 	FROM media m
// 	WHERE m.description like '%".$key."%'
// 	";
// 	$stmt = $db->prepare($sqlMatchName);
// 	$stmt->execute();
// 	$row;
// 	while ($row = $stmt->fetch()) {
// 		echo $row['nom'] ." <span style='color:red;'>".$row['nom'] ."</span><br>";
// 	$i++;
// 	echo $i ." / " .(count($politiciansName) * count($row))."<hr>";
// 	}
// }
// echo $i;

// SELECT p.idPol, p.lastname, m.description FROM politicians p, media m where m.description like CONCAT('% ', CONCAT(UCASE(LEFT(p.lastname, 1)), SUBSTRING(p.lastname, 2)), ' %')


// SELECT m.description FROM media m
// INNER JOIN medpol mp ON m.idMedia  = mp.fk_media
// INNER JOIN politicians p ON p.idPol = mp.fk_pol
// WHERE p.lastname = 'di rupo'

// SELECT m.description, p.lastname, p.firstname FROM media m
// INNER JOIN medpol mp ON m.idMedia  = mp.fk_media
// INNER JOIN politicians p ON p.idPol = mp.fk_pol
// WHERE p.lastname = 'di rupo' or p.firstname = 'elio'

