<!-- -->

<?php 
include_once 'API/simpleHtmlDom/simple_html_dom.php';

try {
	

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
		$contentURL =  (file_get_html($link))?file_get_html($link):false;
		if ($contentURL != false) {
			$rtlCategory = "";
			foreach($contentURL->find('.w-content-details-article-breadcrumb li:nth-child(4) > a') as $name) {
				$rtlCategory .=  strip_tags($name);
			}
			$replacedElement = array("Home", "Actu", "Belgique", "/\s+/");
			$rtlCategory = trim(str_replace($replacedElement, "", $rtlCategory));
			echo $rtlCategory;

			$sqlINSERT = "UPDATE media SET categorie = :nomCategorieAinserer WHERE idMedia = :idCatNull ";
			$stmt = $db->prepare($sqlINSERT);
			$stmt->bindvalue(':nomCategorieAinserer', $rtlCategory);
			$stmt->bindvalue(':idCatNull', $row['id']);
			if ($stmt) {
				$stmt->execute();
				echo " OK :D <br>";
			}
			else{
				echo "Pas OK :'( <br>";
			}
		}
	}
	echo "end";
}

catch (PDOException $e) {
	echo $e->getMessage();
}




try {

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
	echo '<hr>Vif<br>';
}

catch (PDOException $e) {
	echo $e->getMessage();
}

