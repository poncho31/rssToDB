<?php 
use Poncho\Database\Database;
include('vendor/autoload.php');
// *******************PATCH FOR Table media COLUMN 'Catégorie'************
include_once 'API/simpleHtmlDom/simple_html_dom.php';

try {
	
echo "<b>PATCH column 'categorie' </b><br>";
$db = new PDO('mysql:dbname=rss;host=localhost;charset=utf8','root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// RTL INFO
								// $link = $linkArticleRSS;

								// $contentURL =  file_get_html($link);
								// if ($contentURL != false) {
								// 	$rtlCategory = "";
								// 	foreach($contentURL->find('.w-content-details-article-breadcrumb li:nth-child(4) > a') as $name) {
								// 		$rtlCategory .=  strip_tags($name);
								// 	}
								// 	$replacedElement = array("Home", "Actu", "Belgique", "/\s+/");
								// 	$rtlCategory = trim(str_replace($replacedElement, "", $rtlCategory));
								// 	$categoryArticleRSS = $rtlCategory;
								// }
								// else{
								// 	$categoryArticleRSS = 'Undefined category';
								// }
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
// UPDATE MEDIA NOM
			  	"UPDATE media SET nom = 'rtl' where nom like '%rtl%'",
			  	"UPDATE media SET nom = 'dh' where nom like '%dh%'",
			  	"UPDATE media SET nom = 'lecho' where nom like '%lecho%'
				  			   or nom like '%fair trade%' 
							   or nom like '%t-zine%'
							   or nom like '%tijd%'
							   or nom like '%cracks%'
				",
			  	"UPDATE media SET nom = 'lesoir' where nom like '%lesoir%'",
			  	"UPDATE media SET nom = 'lalibre' where nom like '%lalibre%'",
			  	"UPDATE media SET nom = 'levif' where nom like '%levif%'",
			  	"UPDATE media SET nom = 'rtbf' where nom like '%rtbf%'",
			  	"UPDATE media SET nom = 'rtbf' where nom like '%la Première%'",
				"UPDATE media SET nom = 'sudinfo' where nom like '%sudinfo%'",

// UPDATE MEDIA CATEGORIES
			//POLITIQUE
				"UPDATE media SET categorie = 'Politique' 
					 where categorie like '%politique%'
					    or categorie like '%jeudi en prime%'
				",

			//ECONOMIE
				"UPDATE media SET categorie = 'Economie' 
					 where categorie like '%economie%'
					    or categorie like '%entreprise%'
					    or categorie like '%entrepreneuriat%'
						or categorie like '%eco-débat%'
						or categorie like '%placements%'
						or categorie like '%conjoncture%'
						or categorie like '%finances%'
						or categorie like '%finance%'
						or categorie like '%prix energie%'
						or categorie like '%Consommation%'
						or categorie like '%marchésFonds%'
						or categorie like '%immo%'
						or categorie like '%immobilier%'
						or categorie like '%cracks%'
						or categorie like '%top stories%'
						or categorie like '%Mon Argent%'
						or categorie like '%Argent%'
						or categorie like '%arnaques%'
						or categorie like '%Emploi%'
				",

			//ACTUALITE
				"UPDATE media SET categorie = 'Actualités'
					 where categorie like '%actualite%'
					 	or categorie like '%belgique%'
					 	or categorie like '%actu%'
					 	or categorie like '%edito%'
					 	or categorie like '%dossier%'
					 	or categorie like '%centre%'
					 	or categorie like '%societe%'
					 	or categorie like '%dernières dépêches%'
					 	or categorie like '%ETCETERA%'
					 	or categorie like '%Cartes blanches%'
					 	or categorie like '%La personnalité%'
					 	or categorie like '%La une%'
					 	or categorie like '%MEDIAS%'
				",

			//EUROPE
				"UPDATE media SET categorie = 'Europe' 
					 where categorie like '%europe%'
					    or categorie like '%union européenne%'
				",

			//REGIONS
				"UPDATE media SET categorie = 'Régions' 
				     where categorie like '%régions%'
						or categorie like '%VotreRegion %'
						or categorie like '%bruxelles%'
						or categorie like '%liège%'
						or categorie like '%hainaut%'
						or categorie like '%brabant%'
						or categorie like '%Tournai%'
						or categorie like '%Luxembourg%'
						or categorie like '%Mons%'
						or categorie like '%Namur%'
						or categorie like '%Charleroi%'
						or categorie like '%flandre%'
						or categorie like '%wallonie%'
				",

			//MONDE
				"UPDATE media SET categorie = 'Monde'
					 where categorie like '%monde%'
						or categorie like '%Afrique%'
						or categorie like '%Allemagne%'
						or categorie like '%Ameriques%'
						or categorie like '%Angleterre%'
						or categorie like '%Asie%'
						or categorie like '%Pacifique%'
						or categorie like '%Espagne%'
						or categorie like '%italie%'
						or categorie like '%france%'
						or categorie like '%USA%'
						or categorie like '%proche-orient%'
						or categorie like '%international%'
				",

			//SPORTS
				"UPDATE media SET categorie = 'Sport'
					 where categorie like '%cyclisme%'
						or categorie like '%football%'
						or categorie like '%Jeu de Balle%'
						or categorie like '%foot%'
						or categorie like '%mercato%'
						or categorie like '%rugby%'
						or categorie like '%voile%'
						or categorie like '%division%'
						or categorie like '%omnisports%'
						or categorie like '%running%'
						or categorie like '%sport%'
						or categorie like '%auto%'
						or categorie like '%formule 1%'
						or categorie like '%moto%'
						or categorie like '%tennis%'
						or categorie like '%hockey%'
						or categorie like '%wrc%'
						or categorie like '%endurance%'
						or categorie like '%rallye%'
						or categorie like '%moteurs%'
						or categorie like '%anderlecht%'
						or categorie like '%athlétisme%'
						or categorie like '%jo%'
						or categorie like '%judo%'
						or categorie like '%mondial 2018%'
						or categorie like '%route%'
						or categorie like '%Tour de france%'
						or categorie like '%ATP - WTA%'
						or categorie like '%grands chelems%'
						or categorie like '%Basket%'
						or categorie like '%nba%'
						or categorie like '%c1%'
						or categorie like '%c2%'
						or categorie like '%championnat%'
						or categorie like '%golf%'
						or categorie like '%jeux olympiques%'
						or categorie like '%coupe davis%'
						or categorie like '%philippe gilbert%'
						or categorie like '%diables rouges%'
						or categorie like '%europa league%'
						or categorie like '%union saint-gilloise%'
						or categorie like '%tubize%'
						or categorie like '%standard%'
						or categorie like '%mouscron%'
						or categorie like '%eupen%'
						or categorie like '%f.c. bruges%'
						or categorie like '%Equipes nationales%'
				",

			// SCIENCES	
				"UPDATE media SET categorie = 'Sciences' 
					 where categorie like '%sciences%'
					    or categorie like '%santé%'
					    or categorie like '%Science & nature%'
				",

			// ENSEIGNEMENT	
				"UPDATE media SET categorie = 'Enseignement' 
					 where categorie like '%enseignement%'
				",
			// ENVIRONNEMENT	
				"UPDATE media SET categorie = 'Environnement' 
					 where categorie like '%environnement%'
					 	or categorie like '%planète%'
					 	or categorie like '%Agriculture%'
					    or categorie like '%developpement durable%'
					    or categorie like '%Animaux%'
					    or categorie like '%meteo%'
				",

			// TECHNOLOGIES	
				"UPDATE media SET categorie = 'Technologies & Innovation' 
					 where categorie like '%techno%'
					    or categorie like '%technologies%'
					    or categorie like '%digital%'
					    or categorie like '%new tech%'
					    or categorie like '%new-tech%'
					    or categorie like '%sites%'
					    or categorie like '%apps%'
					    or categorie like '%MagazineHi-Tech%'
					    or categorie like '%jeux video%'
					    or categorie like '%inspire%'
				",

			// LOISIRS	
				"UPDATE media SET categorie = 'Loisirs' 
					 where categorie like '%musique%'
					    or categorie like '%festivals%'
					    or categorie like '%concert%'
					    or categorie like '%livre%'
					    or categorie like '%culture%'
					    or categorie like '%arts%'
					    or categorie like '%cinema%'
					    or categorie like '%werchter%'
					    or categorie like '%rock%'
					    or categorie like '%scènes%'
					    or categorie like '%concours reine elisabeth%'
					    or categorie like '%séries%'
					    or categorie like '%lifestyle%'
					    or categorie like '%voyages%'
					    or categorie like '%food%'
					    or categorie like '%Les racines élémentaires%'
					    or categorie like '%Vous avez de ces mots...%'
				",
			// TELEVISION	
				"UPDATE media SET categorie = 'Télévision & radio' 
					 where categorie like '%ltélé%'
					    or categorie like '%telecom%'
					    or categorie like '%television%'
					    or categorie like '%série tv%'
					    or categorie like '%radio%'
				",

			// Sexualité	
				"UPDATE media SET categorie = 'Sexualité' 
					 where categorie like '%love & sex%'
					    or categorie like '%sexo%'
					    or categorie like '%sexualité%'
					    or categorie like '%Relations%'
				",

			// Opinions	
				"UPDATE media SET categorie = 'Opinions' 
					 where categorie like '%opinions%'
					 	or categorie like '%forum%'
					 	or categorie like '%ripostes%'
					 	or categorie like '%debats%'
					 	or categorie like '%chroniques%'
					 	or categorie like '%La chronique de la rédaction%'
					 	or categorie like '%La diplomatie pour les nuls%'
				",

			// Monarchie	
				"UPDATE media SET categorie = 'Monarchie' 
					 where categorie like '%monarchie%'
					    or categorie like '%royals%'
					    or categorie like '%Famille royale%'
				",

			// Archives	
				"UPDATE media SET categorie = 'Archives' 
					 where categorie like '%archive%'
				",
			// AUTRES
				"UPDATE media SET categorie = 'Autres' 
					 where categorie like '%auvio%'
					    or categorie like '%buzz%'
					    or categorie like '%sexy%'
					    or categorie like '%divers%'
					    or categorie like '%vacances%'
					    or categorie like '%undefined%'
					    or categorie like '%Sorties%'
					    or categorie like '%bebe%'
					    or categorie like '%TRANSVERSALES%'
					    or categorie like '%7 a la une%'
					    or categorie like '%beaute & mode%'
					    or categorie like '%mode et beaute%'
					    or categorie like '%concours%'
					    or categorie like '%people%'
					    or categorie like '%vip%'
					    or categorie like '%sabato%'
					    or categorie like '%Gaffes%'
					    or categorie like '%Bandeau Soirmag%'
					    or categorie like '%MAD%'
					    or categorie like '%Magazine%'
					    or categorie like '%insolite%'
				",
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

// // Select txt
// $fp = fopen("data/politiciansNamesListFORMATED.txt", 'r');
// $fr = fread($fp, filesize("data/politiciansNamesListFORMATED.txt"));
// //Explode txt in an array
// $arraycsv = explode(';', $fr);

// $politiciansName = [];
// //Pour chaque ligne de politicien, retrouver son nom et prénom grâce à un délimiteur 'espace' en inversant la ligne (car prénom est à la fin)

// foreach ($arraycsv as $key) {
// 	$politicians = explode(' ', strrev($key), 2);
// 	$firstname = strrev($politicians[0]);
// 	$lastname = strrev($politicians[1]);
// 	$politiciansName[$lastname] = $firstname;
// }
// //Insérer chaque politicien dans Table 'politicians'
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
// 	WHERE m.description like '% ".$key." %'
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

// $sql = "SELECT p.idPol, p.lastname as last, m.description FROM politicians p, media m where m.description like CONCAT('% ', p.lastname, ' %')";
// 	$stmt = $db->query($sql);
// 	// $stmt->execute();
// while ($row = $result->fetch()) {
// 	echo $row['last'];
// }


// INSERT INTO medpol (medpol.fk_pol, medpol.fk_media)
// SELECT p.idPol, m.idMedia
// FROM politicians p, media m
// where m.description
// like CONCAT('% ', p.lastname, ' %')
// and
// m.description LIKE concat('%', p.firstname, '%')
// SELECT p.idPol, m.idMedia FROM politicians p, media m where m.description like CONCAT('% ', p.lastname, ' %')
echo "<hr><br>";
$db = new Database();
$description = ' elio DefefefbarefftBartBffartiëls Di RUpo bart rhhfdh Lefèvre gsdgdsg fgsf';
$sql = "SELECT lastname, firstname, idPol
		FROM politicians";

$sql = "SELECT p.idPol, m.idMedia, p.lastname, p.firstname, m.description
FROM politicians p, media m
where m.description
like CONCAT('% ', p.lastname, ' %')
and
m.description LIKE concat('%', p.firstname, '%')";

$sql = 
"SELECT m.nom,
		GROUP_CONCAT(JSON_OBJECT(
			'mediaName', m.nom,
			'firstname', p.firstname,
			'lastname', p.lastname
		)) as politicsName
FROM media m
INNER JOIN medpol mp ON m.idMedia = mp.fk_media 
INNER JOIN politicians p ON p.idPol = mp.fk_pol 
WHERE p.idPol = mp.fk_pol
GROUP BY m.nom
";
$politiciansByMedia = [];
$stmt = $db->getQuery($sql);
var_dump($stmt->fetch()->politicsName);
echo $stmt->fetch()->politicsName;
while ($row = $stmt->fetch()) {
	// echo $row->politicsName ."<hr>";



	// echo $row->nom . "<br>";
	// $lastname =  explode(',',$row->politicsName);
	// $politiciansByMedia [$row->nom] = [];
	// foreach ($lastname as $polLastname) {
	// 	if (!in_array($polLastname, $politiciansByMedia [$row->nom])) {
	// 		array_push($politiciansByMedia [$row->nom], ['lastname' => $polLastname]);
	// 	}
	// }
	// // $politiciansByMedia [$row->nom] = $singleLastname;
	// echo $row->countL . "<br>";
}
// var_dump($politiciansByMedia);