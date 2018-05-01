<?php 
//API SimpleHTMLDom
// include_once 'simpleHtmlDom/simple_html_dom.php';

include 'header.php';


<<<<<<< HEAD
<<<<<<< HEAD
try {
    $db = new PDO('mysql:dbname=rss;host=localhost;charset=utf8','root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
	die('<span style="color:black">Erreur :  : ' . $e->getMessage()) . '</span>';
}
=======


>>>>>>> a2a05788c9c4bfbfadaf01bc770740b76a387a8e
=======


>>>>>>> 1cf8ce0bec0a7ce69e2704bc21be647d9f9a58ca


//VA RECHERCHER LES FLUX RSS EN FONCTION DU LIEN
function rssToDB($feeds)
{
	try {
<<<<<<< HEAD
<<<<<<< HEAD
		
=======
		//Instanciation de la BDD
>>>>>>> a2a05788c9c4bfbfadaf01bc770740b76a387a8e
=======
		//Instanciation de la BDD
>>>>>>> 1cf8ce0bec0a7ce69e2704bc21be647d9f9a58ca
		$db = new PDO('mysql:dbname=rss;host=localhost;charset=utf8','root', '');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		//Parcours le tableau de FEEDS
		foreach ($feeds as $feed) {
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
				$className; $alreadyInDB; $deja = 1; $nbrArticle = 1;
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

			    //Si le dernier article posté est dans la BDD
			    if (false) {}

			    foreach ($xml as $attributes) {
				    foreach ($attributes->item as $key) {
				       	//VARIABLES : affectation des données issues du fichier xml + vérification
				       	$titleMediaRSS =  (isset($attributes->title)) ? strip_tags($attributes->title) : null;
						$titleArticleRSS =  (isset($key->title)) ? strip_tags($key->title) : null;
						$descriptionArticleRSS =  (isset($key->description)) ? strip_tags($key->description) : null;
						$publicationDateArticleRSS =  (isset($key->pubDate)) ? strip_tags($key->pubDate) : null;
						$linkArticleRSS =  (isset($key->link)) ? strip_tags($key->link) : null;
						$categoryArticleRSS =  (isset($key->category)) ? strip_tags($key->category) : null;

						//BDD : vérification si pas déjà en bdd
						$sql = "SELECT lien FROM media WHERE lien = :lien";
						$stmt = $db->prepare($sql);
						$stmt->execute(array(':lien'=>$linkArticleRSS));
						$sqlVERIFICATION = $stmt->fetch();

						if (!$sqlVERIFICATION) {
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

							
						}
						else{
							$alreadyInDB = 'Déjà en base de données ('.$deja.'/'.$nbrArticle.')  => '. $titleMediaRSS . ' / ' . date(DATE_RFC2822);
							$deja++;
						}
						$nbrArticle++;
				    }
			    }
		    	echo isset($alreadyInDB) ? $alreadyInDB. '<br><br>' : null;
		    }
		}
	}
	
	catch (Exception $e) {
		die('<span style="color:black">Erreur :  : ' . $e->getMessage()) . '</span>';
	}


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


// $feeds = array('https://www.ictu.nl/rss.xml', 'http://www.vng.nl/smartsite.dws?id=97817');
// foreach( $feeds as $feed ) {
//     $xml = simplexml_load_file($feed);

//     foreach($xml->channel->item as $item)
//     {
//     $date_format = "j-n-Y"; // 7-7-2008
//     echo date($date_format,strtotime($item->pubDate));  
//              echo '<a href="'.$item->link.'" target="_blank">'.$item->title.'</a>';
//              echo '<div>' . $item->description . '</div>';

//     mysql_query("INSERT INTO rss_feeds (id, title, description, link, pubdate) 
//     VALUES (
//         '', 
//         '".mysql_real_escape_string($item->title)."', 
//         '".mysql_real_escape_string($item->description=htmlspecialchars(trim($item->description)))."', 
//         '".mysql_real_escape_string($item->link)."', 
//         '".mysql_real_escape_string($item->pubdate)."')");       
//     }
// }


include 'viewRSS.php';

include 'footer.php';