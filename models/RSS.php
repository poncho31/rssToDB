<?php 
use Poncho\Database;
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
	@ob_end_flush();
	flush();
}

function rssToDB($feeds)
{
	try {
		include 'serverName.php';
		//Instanciation de la BDD
		$dbClass = new Poncho\Database();
		$db = $dbClass->getDatabase();
    	$alreadyInDB = '<table id="already"><tr><td>Media</td><td>Nouveaux articles</td><td>Date</td></tr>';
				
		
		//Parcours le tableau de FEEDS
		$current = 0;
		$totalNewArticles= 0;
		foreach ($feeds as $feed) {
			$timestamp_debut = microtime(true);
			
			$current++;
			outputProgress($current, count($feeds));

			$newArticle = 0;
			$notNewArticle = 1; $goingToDB = true;
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
				    	if ($goingToDB == true) {
					       	//VARIABLES : affectation des données issues du fichier xml + vérification
					       	$titleMediaRSS =  (isset($attributes->title)) ? strip_tags($attributes->title) : null;
							$titleArticleRSS =  (isset($key->title)) ?strip_tags($key->title) : null;
							$descriptionArticleRSS =  (isset($key->description)) ? strip_tags($key->description) : null;
							$publicationDateArticleRSS =  (isset($key->pubDate)) ? strip_tags($key->pubDate) : null;
							$linkArticleRSS =  (isset($key->link)) ? strip_tags($key->link) : null;
							$categoryArticleRSS =  (isset($key->category)) ? strip_tags($key->category) : null;

							//Si la catégorie est NULL (rtlinfo + VifLexpress)
								//Vérifie si pas Levif
							if ($categoryArticleRSS == null && !stristr($titleMediaRSS, 'Levif')) {
								$link = $linkArticleRSS;

								$contentURL =  file_get_html($link);
								if ($contentURL != false) {
									$rtlCategory = "";
									foreach($contentURL->find('.w-content-details-article-breadcrumb li:nth-child(4) > a') as $name) {
										$rtlCategory .=  strip_tags($name);
									}
									$replacedElement = array("Home", "Actu", "Belgique", "/\s+/");
									$rtlCategory = trim(str_replace($replacedElement, "", $rtlCategory));
									$categoryArticleRSS = $rtlCategory;
								}
								else{
									$categoryArticleRSS = 'Undefined category';
								}
							}
								//Vérifie si Levif
							else if ($categoryArticleRSS == null && stristr($titleMediaRSS, 'Levif')){
								$link= $linkArticleRSS;
								$tab = explode('/', $link);
								$categoryArticleRSS = $tab[3] . " " . $tab[4];
							}


							//BDD : vérification si pas déjà en bdd
							$sql = "SELECT lien FROM media WHERE lien = :lien";
							$stmt = $db->prepare($sql);
							$stmt->execute(array(':lien'=>$linkArticleRSS));
							$sameLinkArticle = $stmt->fetch();
				    	}
				    	else{
				    		$sameLinkArticle = true;
				    	}


						if (!$sameLinkArticle) {
							//Insertion en bdd
							$sqlINSERT = "INSERT INTO media (nom, titre, description, date, lien, categorie) VALUES (:nom, :titre, :description, :date, :lien, :categorie)";
							$stmt = $db->prepare($sqlINSERT);
							$stmt->bindvalue(':nom', $titleMediaRSS);
							$stmt->bindvalue(':titre', $titleArticleRSS);
							$stmt->bindvalue(':description', $descriptionArticleRSS);
							$stmt->bindvalue(':date', strftime("%Y-%m-%d %H:%M:%S", strtotime($publicationDateArticleRSS)));
							$stmt->bindvalue(':lien', $linkArticleRSS);
							$stmt->bindvalue(':categorie', $categoryArticleRSS);
							$stmt->execute();
							$newArticle++;
							$totalNewArticles++;
							
						}
						else{
							
							if ($notNewArticle < 2) {
								$notNewArticle++;
							}
							else{
								$goingToDB = false;
							}
						}	
					}
		    	}
			}
$timestamp_fin = microtime(true);
		$alreadyInDB .= '<tr><td>'.$titleMediaRSS .'</td><td>'.$newArticle. '</td><td>'. date(DATE_RFC850) ."</td></tr>";

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


include 'feeds.php';
$timestamp_debut = microtime(true);
rssToDB($feeds);
$timestamp_fin = microtime(true);
$difference_ms = $timestamp_fin - $timestamp_debut;
echo 'Exécution du script : ' . number_format($difference_ms, 2) . ' secondes.<br>';

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

$sqlSELECT = "SELECT * FROM media order by idMedia DESC";
$sqlCount = "SELECT count(idMedia) FROM media";
$stmt = $db->prepare($sqlSELECT);
$stmt->execute();
$stmt2 = $db->query($sqlCount);
echo  "Nombre d'articles dans BDD : " . $stmt2->fetch()[0];
$number = 1;
foreach ($stmt as $row) {
	
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
			<td><?= $number; $number++; ?></td>
			<td><?= $row['nom'] ?></td>
			<td><?= $row['titre']; ?></td>
			<td><?= $row['description']; ?></td>
			<td><?= $row['date']; ?></td>
			<td><?= $row['lien']; ?></td>
			<td><?= $row['categorie']; ?></td>
		</tr>
	</table>
	<?php 
	if($number > 20) {echo 'and so on ...'; break; }
}
