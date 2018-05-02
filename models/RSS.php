<?php 
//API SimpleHTMLDom
include_once '../API/simpleHtmlDom/simple_html_dom.php';

include '../view/header.php';

?>
<section>
	<h1>MySQL</h1>
	<hr>
</section>
<?php

//VA RECHERCHER LES FLUX RSS EN FONCTION DU LIEN
function rssToDB($feeds)
{
	try {
		include 'serverName.php';
		//Instanciation de la BDD
		$db = new PDO('mysql:dbname=rss;host=localhost;charset=utf8','root', '');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$alreadyInDB = '<table id="already"><tr><td>Média</td><td>Nouveaux articles</td><td>Date</td></tr>';
		$newArticle = 0;
		//Parcours le tableau de FEEDS
		foreach ($feeds as $feed) {
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

			    //Si le dernier article posté est dans la BDD
			    if (false) {}

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
							}
						}	
					}
				}
			}
		    $alreadyInDB .= '<tr><td>'.$titleMediaRSS .'</td><td>'.$newArticle. '</td><td>'. date(DATE_RFC850) ."</td></tr>";	
		}
		// $alreadyInDB .= '<tr><td>'.$titleMediaRSS .'</td><td>'.$newArticle.'</td><td>'. date(DATE_RFC2822) ."</tr>";
		$alreadyInDB .='</table>';
		echo isset($alreadyInDB) ? $alreadyInDB : null;
	}

	
	catch (Exception $e) {
		die('<span style="color:black">Erreur :  : ' . $e->getMessage()) . '</span>';
	}

	// //Attribue pour chaque article sa class/div/id pour récupérer le contenu html
	// if(stristr($feed, 'lesoir.be') || stristr($feed, 'lavenir.net')){
	// 	$className = 'article';
	// }
	// elseif(stristr($feed, 'dhnet.be') || stristr($feed, 'lalibre.be')){
	// 	$className = 'div.article-text';
	// }
	// else{
	// 	echo $feed . ' : feed non chargé<br><br>';
	//    	$feed = false;
	// }
    //VA ASPIRER LA PAGE WEB
        // foreach ($linkArticleRSS as $link) {
        // 	$contentURL =  file_get_html($link);
        // 	$first_step = explode( '<div id="article-text">' , $contentURL);
        // 	$second_step = explode("</div>" , $first_step[1] );
        // 	echo $second_step[0];
        // }
        // var_dump($contentURL);
		// foreach($contentURL->find($className) as $article) {
		// 	echo $article;
		// 		// if (!empty($article)) {
		// 		// 	// $content[] =  $article;
		// 		// 	// print_r($article);
		// 		// }
		// 		// else{
		// 		// 	// $content[] = null;
		// 		// }
		// }
//--------------end function	
}

		

$feeds = 
[
'http://www.lalibre.be/rss/section/actu/politique-belge.xml',
'http://www.lesoir.be/rss/31867/cible_principale',
'http://www.dhnet.be/rss/section/actu.xml',
'http://www.dhnet.be/rss.xml',
'http://feeds.feedburner.com/rtlinfo/belgique',
'https://www.lecho.be/rss/politique_belgique.xml',
'https://www.levif.be/actualite/feed.rss',
'http://rss.rtbf.be/article/rss/highlight_rtbfinfo_info-accueil.xml',
'http://www.sudinfo.be/rss/2023/cible_principale_gratuit',
'http://feeds.feedburner.com/Rtlinfo/VotreRegion'
];

rssToDB($feeds);


include '../view/viewRSS.php';

include '../view/footer.php';