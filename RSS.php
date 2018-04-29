<?php 
//API SimpleHTMLDom
// include_once 'simpleHtmlDom/simple_html_dom.php';

include 'header.php';

//Instanciation de la BDD
try {
    $db = new PDO('mysql:dbname=rss;host=localhost','root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
	die('<span style="color:black">Erreur :  : ' . $e->getMessage()) . '</span>';
}

//ANALYSE DES ARTICLES DE MEDIAS

function rssToDB($url, $className)
{
try {
	
	$db = new PDO('mysql:dbname=rss;host=localhost','root', '');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//VA RECHERCHER LES FLUX RSS EN FONCTION DU LIEN 
		//Charge le fichier xml
        $xml = simplexml_load_file($url);
        if ($xml == false) {
        	return false;
        }
        //Si le dernier article posté est dans la BDD
        if (false) {
        	# code...
        }
        //TABLEAUX contenant les données issues du flux RSS
        $titleMediaRSS;
        $titleArticleRSS;
        $categoryArticleRSS;
        $descriptionArticleRSS;
        $publicationDateArticleRSS;
        $linkArticleRSS;

        $contentURL;
        $content;
        //Affectation des données issues du fichier xml
        foreach ($xml as $attributes) {
        	foreach ($attributes->item as $key) {

        		$titleMediaRSS =  strip_tags($attributes->title);
				$titleArticleRSS =  strip_tags($key->title);
				$descriptionArticleRSS =  strip_tags($key->description);
				$publicationDateArticleRSS =  strip_tags($key->pubDate);
				$linkArticleRSS =  strip_tags($key->link);
				$categoryArticleRSS =  strip_tags($key->category);

				$sqlINSERT = "INSERT INTO media (nom, titre, description, date, lien, categorie) VALUES (:nom, :titre, :description, :date, :lien, :categorie)";
				$stmt = $db->prepare($sqlINSERT);
				$stmt->bindvalue(':nom', $titleMediaRSS);
				$stmt->bindvalue(':titre', $titleArticleRSS);
				$stmt->bindvalue(':description', $descriptionArticleRSS);
				$stmt->bindvalue(':date', $publicationDateArticleRSS);
				$stmt->bindvalue(':lien', $linkArticleRSS);
				$stmt->bindvalue(':categorie', $categoryArticleRSS);
				$stmt->execute();
		
		// $key->title
		// $key->description
		// $key->pubDate
		// $key->link
		// $key->category

		// echo "
		// '".strip_tags($key->title)."', 
  //       '".strip_tags($key->description=htmlspecialchars(trim($key->description)))."', 
  //       '".strip_tags($key->link)."', 
  //       '".strip_tags($key->pubdate)."')"; 

				// $db->exec($sqlINSERT);
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

	//VERIFICATION SI PAS DEJA EN BDD
 //        foreach ($titleMediaRSS as $key) {
 //        	echo $key;
 //        }
	// foreach ($descriptionArticleRSS as $key) {

	// 	$sqlDescription = $db->prepare("SELECT description FROM media");
	// 	$sqlDescription->execute([$key]);
	// 	$sqlVERIFICATION = $sqlDescription->fetch(PDO::FETCH_ASSOC);
	// 	if ($sqlVERIFICATION) {
	// 		$GLOBALS['alreadyInDB'] = 'Déjà en base de données : ' . date(DATE_RFC2822);
	// 	}
	// 	else{
	// 		}
// try {
	
// 			//INSERTION EN BDD
// 			$sqlINSERT = "INSERT INTO media (description) VALUES(:description)";
// 			$stmt = $db->prepare($sqlINSERT);
// 			$stmt->bindParam(':description', $descriptionArticleRSS);
// 			$stmt->execute();
// }
// catch (Exception $e) {
// 	die('<span style="color:black">Erreur :  : ' . $e->getMessage()) . '</span>';
// }
		// }

	// }
//--------------end function			
}


rssToDB('http://www.lesoir.be/rss/31867/cible_principale', 'article');
rssToDB('http://www.dhnet.be/rss/section/actu.xml', 'div.article-text');
// rssToDB('http://www.lavenir.net/rss.aspx?foto=1&intro=1&section=info&info=df156511-c24f-4f21-81c3-a5d439a9cf4b', 'article');
// rssToDB('http://www.lalibre.be/rss/section/actu/politique-belge.xml', 'div.article-text');


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