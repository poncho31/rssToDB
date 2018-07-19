<?php 
use Poncho\Database\Database;
include('vendor/autoload.php');
//API SimpleHTMLDom
include_once 'API/simpleHtmlDom/simple_html_dom.php';

?>
<section>
	<h1>MySQL</h1>
	<hr>
</section>

<?php
//VA RECHERCHER LES FLUX RSS EN FONCTION DU LIEN
function highlight($needles, $haystack){
	foreach ($needles as $needle) {
	    $ind = stripos($haystack, $needle);
	    $len = strlen($needle);
	    if($ind !== false){
	        return "<b>" . substr($haystack, $ind, $len) . "</b>";
	    }
    }
} 

function outputProgress($current, $total){
	$pourcentage = round($current / $total * 100);
	echo " <div class='progression' style='position: absolute;'><progress class='progression' value='".$pourcentage."' max='100'></progress></div> ";
	// if (ob_get_contents()) {
	// }
		@ob_end_flush();
	@flush();
}
$db = new Database();
function rssToDB($feeds, $db)
{
	try {
		include 'serverName.php';
		//Instanciation de la BDD
		
		//HTML table
    	$alreadyInDB = '<table id="already"><tr><td>Media</td><td>Nouveaux articles</td><td>Date</td></tr>';
				
		
		//Parcours le tableau de FEEDS
		$current = 0;
		$totalNewArticles= 0;
		foreach ($feeds as $feed) {
			$timestamp_debut = microtime(true);
			
			$current++;
			outputProgress($current, count($feeds));

			$newArticle = 0;
			//Charge le fichier xml
			$xml = (simplexml_load_file($feed, null, LIBXML_NOCDATA) == true) ? simplexml_load_file($feed, null, LIBXML_NOCDATA) : false;
			//SI XML FALSE
			libxml_use_internal_errors(true);
		    if ($xml === false) {
			    echo "Failed loading XML '".$feed."': ";
			    foreach(libxml_get_errors() as $error) {
			        echo "<br>", $error->message;
			    }
			}
			
		    //SI XML TRUE
		    else{
			    foreach ($xml as $attributes) {
				    foreach ($attributes->item as $key) {
						//VARIABLES : affectation des données issues du fichier xml + vérification
						$linkArticleRSS =  (isset($key->link)) ? strip_tags($key->link) : null;
						$titleMediaRSS =  (isset($attributes->title)) ? strip_tags($attributes->title) : null;
						
						//BDD : vérification si pas déjà en bdd
						$sql = "SELECT lien FROM media WHERE lien = :lien";
						$stmt = $db->getQuery($sql, [':lien'=>$linkArticleRSS]);
						$sameLinkArticle = $stmt->fetch();
						
				    	if ($sameLinkArticle == false) {
						//VARIABLES : affectation des données issues du fichier xml + vérification
							$titleArticleRSS =  (isset($key->title)) ?strip_tags($key->title) : null;
							$descriptionArticleRSS =  (isset($key->description)) ? strip_tags($key->description) : null;
							$publicationDateArticleRSS =  (isset($key->pubDate)) ? strip_tags($key->pubDate) : null;
							$categoryArticleRSS =  (isset($key->category)) ? strip_tags($key->category) : null;
							
							//Si la catégorie est NULL 
							if ($categoryArticleRSS == null && !stristr($titleMediaRSS, 'Levif')) {
								$tab = explode('/', $linkArticleRSS);
								if (stristr($titleMediaRSS, 'rtl')) {
									$categoryArticleRSS = $tab[4] . " " . $tab[5] . " " . $tab[6];
								}
								else{
									$categoryArticleRSS = (count($tab) < 6) ? $tab[3] . " " . $tab[4] : $tab[3] . " " . $tab[4] . " " . $tab[5];
								}
							}
							else if ($categoryArticleRSS == null && stristr($titleMediaRSS, 'Levif')){
								$tab = explode('/', $linkArticleRSS);
								$categoryArticleRSS = $tab[3] . " " . $tab[4];
							}

							//Insertion en bdd
							$sqlINSERT = "INSERT INTO media (nom, titre, description, date, lien, categorie) VALUES (:nom, :titre, :description, :date, :lien, :categorie)";
	
							$param = 
									[':nom' => $titleMediaRSS,
									':titre' => $titleArticleRSS,
									':description'=> $descriptionArticleRSS,
									':date' => strftime("%Y-%m-%d %H:%M:%S", strtotime($publicationDateArticleRSS)),
									':lien' => $linkArticleRSS,
									':categorie' => $categoryArticleRSS]
								;
							// $stmt = $db->prepare($sqlINSERT);
							$stmt = $db->getQuery($sqlINSERT, $param);
							// $stmt->execute();
							$newArticle++;
							$totalNewArticles++;

						}
					}
		    	}
			}
		$timestamp_fin = microtime(true);
		$alreadyInDB .= '<tr><td>'. $titleMediaRSS .'</td><td>'.$newArticle. '</td><td>'. date(DATE_RFC850) ."</td></tr>";

		$mediaName = array('rtl', 'dh','lalibre', 'lesoir', 'lecho', 'levif', 'rtbf', 'sudinfo');

		
		$difference_ms = $timestamp_fin - $timestamp_debut;
		echo "<span class='progression' style='float: right; width: 70%;'>" .  highlight($mediaName, $feed ) . " : " . number_format($difference_ms,2) . ' secondes.'."<br></span>";
		}
		$alreadyInDB .='</table>';
		echo isset($alreadyInDB) ? $alreadyInDB : null;
		echo "Nombres de flux RSS vérifiés : " . $current ."<br>";
		echo "Nombres de nouveux articles : " . $totalNewArticles ."<br>";
	}

	
	catch (Exception $e) {
		die('<span style="color:black">Erreur :  : ' . $e->getMessage()) . '</span>';
	}


//--------------end function	
}

function updateMediaName($db){
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
		$db->getQuery($key);
	}
	$sqlSelectMedia = "SELECT nom, count(nom) as numb FROM media GROUP BY nom";
	$tot = 0;
	echo "Nombres d'article par Media <br>";
	foreach ($db->getQuery($sqlSelectMedia) as $row) {
		echo $row->nom . " => ". $row->numb . "<br>";
		$tot += $row->numb;
	}
}


include 'feeds.php';
$timestamp_debut = microtime(true);
rssToDB($feeds, $db);
$timestamp_fin = microtime(true);
$difference_ms = $timestamp_fin - $timestamp_debut;
echo 'Exécution du script : ' . number_format($difference_ms, 2) . ' secondes.<br>';




// AFFICHAGE

$sqlSELECT = "SELECT * FROM media order by idMedia DESC";
$sqlCount = "SELECT count(idMedia) as count FROM media";

$stmt = $db->getQuery($sqlSELECT);
$stmt2 = $db->getQuery($sqlCount);

while($row = $stmt2->fetch()){echo  "Nombre d'articles dans BDD : " . $row->count ."<br>";};
$number = 1;

//Saved DB
$dbMysqli = new mysqli('localhost', 'root', '', 'rss');
$dump = new MySQLDump($dbMysqli);
if ($dump) {
	$dump->save('data/'.date("y.m.d").'-SQLsave.sql.gz');
	echo "Saved without errors : " . date("y.m.d") . "<br>";
}
updateMediaName($db);
while($row = $stmt->fetch()) {
	
	?>
	<table>
		<tr>
			<td>ID</td>
			<td>Media</td>
			<td>Titre</td>
			<td>Description</td>
			<td>Date</td>
			<td>Lien</td>
			<td>Categorie</td>
		</tr>
		<tr>
			<td><?= $row->idMedia; $number++ ?></td>
			<td><?= $row->nom ?></td>
			<td><?= $row->titre; ?></td>
			<td><?= $row->description; ?></td>
			<td><?= $row->date; ?></td>
			<td><?= $row->lien; ?></td>
			<td><?= $row->categorie; ?></td>
		</tr>
	</table>
	<?php 
	if($number > 20) {echo 'and so on ...'; break; }
}
