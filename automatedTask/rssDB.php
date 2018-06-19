<?php 
//API SimpleHTMLDom
require 'simpleHtmlDom/simple_html_dom.php';

//VA RECHERCHER LES FLUX RSS EN FONCTION DU LIEN
function rssToDB($feeds)
{
	try {
	$serverName = "GREENLINE";
	$dbo = "dbo";
    $db = new PDO("sqlsrv:Server=$serverName;Database=rss","greenline", "test1234=");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
		
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
			if ($goingToDB == false) {
				continue;
			}
		}
	}

	
	catch (Exception $e) {
	}


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
'https://www.lecho.be/rss/politique_economie.xml',
'https://www.lecho.be/rss/politique_europe.xml',
'https://www.lecho.be/rss/politique_internationale.xml',
'https://www.levif.be/actualite/feed.rss',
'http://rss.rtbf.be/article/rss/highlight_rtbfinfo_info-accueil.xml',
'http://www.sudinfo.be/rss/2023/cible_principale_gratuit',
'http://feeds.feedburner.com/Rtlinfo/VotreRegion'
];

rssToDB($feeds);