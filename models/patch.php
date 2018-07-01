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
			  	"UPDATE media SET nom = 'lecho' where nom like '%lecho%'",
			  	"UPDATE media SET nom = 'lesoir' where nom like '%lesoir%'",
			  	"UPDATE media SET nom = 'lalibre' where nom like '%lalibre%'",
			  	"UPDATE media SET nom = 'levif' where nom like '%levif%'",
			  	"UPDATE media SET nom = 'rtbf' where nom like '%rtbf%'",
			  	"UPDATE media SET nom = 'sudinfo' where nom like '%sudinfo%'",
			  	"UPDATE media SET nom = 'tijd' where nom like '%tijd%'"
			  ];
foreach ($updateTable as $key) {
	$db->query($key);
}
