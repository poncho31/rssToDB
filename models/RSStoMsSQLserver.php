<?php 
//API SimpleHTMLDom
include_once '../API/simpleHtmlDom/simple_html_dom.php';

include '../view/header.php';
// print_r(PDO::getAvailableDrivers());

?>
<section>
	<h1>Microsoft SQL Server</h1>
	<hr>
</section>
<?php


//VA RECHERCHER LES FLUX RSS EN FONCTION DU LIEN
function rssToDB($feeds)
{
	try {
	include 'serverName.php';
    $db = new PDO("sqlsrv:Server=$serverName;Database=rss","greenline", "test1234=");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
    	$alreadyInDB = '<table id="already"><tr><td>Média</td><td>Nouveaux articles</td><td>Date</td></tr>';
		
		//Parcours le tableau de FEEDS
		foreach ($feeds as $feed) {
			$newArticle = 0;
			$notNewArticle = 1; $goingToDB = true;
			//Charge le fichier xml
			$xml = simplexml_load_file($feed);
			//SI XML FALSE
		    if ($xml == false) {
		    	$xml = new DOMDocument;
				$xmlStr = $xml->load($feed);

		    	libxml_use_internal_errors(true);
		    	$xml = simplexml_load_string($xmlStr, 'SimpleXMLElement');


		    	if ($xml == false) {
		    		$errors = libxml_get_errors();
		    		echo 'XML non chargé : <br>
		    			<li>'.$feed.'</li>
		    			<li>Errors type : '.var_export($errors, true).'</li>
		    			<br><br>';
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

							//BDD : vérification si pas déjà en bdd
							$sql = "SELECT lien FROM $dbo.media WHERE lien = :lien";
							$stmt = $db->prepare($sql);
							$stmt->execute(array(':lien'=>$linkArticleRSS));
							$sameLinkArticle = $stmt->fetch();
				    	}
				    	else{
				    		$sameLinkArticle = true;
				    	}


						if (!$sameLinkArticle) {
							//Insertion en bdd
							$sqlINSERT = "INSERT INTO $dbo.media (nom, titre, description, date, lien, categorie) VALUES (:nom, :titre, :description, :date, :lien, :categorie)";
							$stmt = $db->prepare($sqlINSERT);
							$stmt->bindvalue(':nom', $titleMediaRSS);
							$stmt->bindvalue(':titre', $titleArticleRSS);
							$stmt->bindvalue(':description', $descriptionArticleRSS);
							$stmt->bindvalue(':date', $publicationDateArticleRSS);
							$stmt->bindvalue(':lien', $linkArticleRSS);
							$stmt->bindvalue(':categorie', $categoryArticleRSS);
							$stmt->execute();
							$newArticle++;
							
						}
						else{
							
							if ($notNewArticle < 2) {
								$notNewArticle++;
							}
							else{
								$goingToDB = false;
								break;
							}
						}	
					}
				}
			}
		    $alreadyInDB .= '<tr><td>'.$titleMediaRSS .'</td><td>'.$newArticle. '</td><td>'. date(DATE_RFC850) ."</td></tr>";
			if ($goingToDB == false) {
				continue;
			}
		}
		// $alreadyInDB .= '<tr><td>'.$titleMediaRSS .'</td><td>'.$newArticle.'</td><td>'. date(DATE_RFC2822) ."</tr>";
		$alreadyInDB .='</table>';
		echo isset($alreadyInDB) ? $alreadyInDB : null;
	}

	
	catch (Exception $e) {
		die('<span style="color:black">Erreur :  : ' . $e->getMessage()) . '</span>';
	}


//--------------end function	
}

include 'feeds.php';
rssToDB($feeds);

//SELECT FROM DB - AFFICHAGE DES DONNEES
include 'serverName.php';
try {
    $db = new PDO("sqlsrv:Server=$serverName;Database=rss","greenline", "test1234=");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
    // $db->exec("set names utf8");
}
catch (PDOException $e)
{
	die('<span style="color:black">Erreur :  : ' . $e->getMessage()) . '</span>';
}


$sqlSELECT = "SELECT * FROM $dbo.media order by idMedia DESC";
$sqlCount = "SELECT count(idMedia) FROM $dbo.media";
$stmt = $db->prepare($sqlSELECT);
$stmt->execute();
$stmt2 = $db->query($sqlCount);
echo  "<p>ARTICLE ISSU DE LA BDD : " . $stmt2->fetch()[0] ."</p>";
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
			<td><?php   echo $number; $number++; ?></td>
			<td><?php   echo $row['nom'] ?></td>
			<td><?php 	echo $row['titre']; ?></td>
			<td><?php 	echo $row['description']; ?></td>
			<td><?php 	echo $row['date']; ?></td>
			<td><?php 	echo $row['lien']; ?></td>
			<td><?php 	echo $row['categorie']; ?></td>
		</tr>
	</table>
	<?php 
	if($number > 20) {echo 'and so on ...'; break; }
}

include '../view/footer.php';