<?php 
//API SimpleHTMLDom
include_once 'simpleHtmlDom/simple_html_dom.php';

include 'header.php';

//Instanciation de la BDD
try {
    $db = new PDO('mysql:dbname=test_rss;host=localhost','root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
	die('<span style="color:black">Erreur :  : ' . $e->getMessage()) . '</span>';
}

//ANALYSE DES ARTICLES DE MEDIAS

function rssToDB($url, $className)
{
	$db = new PDO('mysql:dbname=test_rss;host=localhost','root', '');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        //VA RECHERCHER LES FLUX RSS EN FONCTION DU LIEN 
	        	//Charge le fichier xml
                $xml = simplexml_load_file($url);
                if ($xml == false) {
                	return false;
                }
                //Affectation des données issues du fichier xml
                $titleMediaRSS =  strip_tags($xml->channel->title);
                $descriptionRSS = strip_tags($xml->channel->item->description);
                $linkRSS = $xml->channel->item->link;
                $linkFormatRSS = '<a href="'.$linkRSS.'" target="_blank">Lien vers l\'article original</a>';
                $datePublicationRSS = strip_tags($xml->channel->item->pubDate);
                $content; $article; $categorie;

            //VA ASPIRER LA PAGE WEB
                $contentLink =  file_get_html($linkRSS);
				foreach($contentLink->find($className) as $article) {
					$content['article'] =  $article;
				}
				if (isset($content['article'])) {
					foreach($content['article']->find('a') as $categorie) {
						$content['categorie'] =  $categorie;
					}
				}
				else{
					$content = null;
				}
				$article = strip_tags($content['article']);
				$categorie = strip_tags($content['categorie']);

//VERIFICATION SI PAS DEJA EN BDD
				$sqlVERIFICATIONtitre = $db->prepare("SELECT description FROM media");
				$sqlVERIFICATIONtitre->execute([$descriptionRSS]);
				$sqlVERIFICATION = $sqlVERIFICATIONtitre->fetch(PDO::FETCH_ASSOC);

				if ($sqlVERIFICATION) {
					$GLOBALS['alreadyInDB'] = 'Déjà en base de données : ' . date(DATE_RFC2822);
				}
				else{
					//INSERTION EN BDD
					$sqlINSERT = "INSERT INTO media (titre, description, date, lien, article, categorie) VALUES(:titre, :description, :date, :lien, :article, :categorie)";
					$stmt = $db->prepare($sqlINSERT);
					$stmt->bindParam(':titre', $titleMediaRSS);
					$stmt->bindParam(':description', $descriptionRSS);
					$stmt->bindParam(':date', $datePublicationRSS);
					$stmt->bindParam(':lien', $linkRSS);
					$stmt->bindParam(':article', $article);
					$stmt->bindParam(':categorie', $categorie);
					$stmt->execute();
				}
//--------------end function			
}


rssToDB('http://www.lesoir.be/rss/31867/cible_principale', 'article');
rssToDB('http://www.dhnet.be/rss/section/actu.xml', 'div.article-text');
rssToDB('http://www.lavenir.net/rss.aspx?foto=1&intro=1&section=info&info=df156511-c24f-4f21-81c3-a5d439a9cf4b', 'article');
rssToDB('http://www.lalibre.be/rss/section/actu/politique-belge.xml', 'div.article-text');

include 'viewRSS.php';

include 'footer.php';