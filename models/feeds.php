<?php 
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




//  ....................................................................
// $politiciansList = 
// [
// 	'https://www.cumuleo.be/province/province.php?p=bruxelles',
// 	'https://www.cumuleo.be/province/province.php?p=brabant-wallon',
// 	'https://www.cumuleo.be/province/province.php?p=hainaut',
// 	'https://www.cumuleo.be/province/province.php?p=namur',
// 	'https://www.cumuleo.be/province/province.php?p=liege',
// 	'https://www.cumuleo.be/province/province.php?p=luxembourg',
// 	'https://www.cumuleo.be/province/province.php?p=anvers',
// 	'https://www.cumuleo.be/province/province.php?p=brabant-flamand',
// 	'https://www.cumuleo.be/province/province.php?p=flandre-occidentale',
// 	'https://www.cumuleo.be/province/province.php?p=flandre-orientale',
// 	'https://www.cumuleo.be/province/province.php?p=limbourg'
// ];
//  $db = new PDO("sqlsrv:Server=$serverName;Database=rss","greenline", "test1234=");
//     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     $db->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
// $i = 1;
// foreach ($politiciansList as $key) {
// 	$link = $key;
// 	$contentURL =  file_get_html($link);
// 	foreach($contentURL->find('span.listingnom') as $name) {
// 		$politiciansName =  strip_tags($name);
// 		echo $politiciansName;
// 		$i++;

// 		$sqlINSERT = "INSERT INTO rss.politician (nom) VALUES (:nom)";
// 		$stmt = $db->prepare($sqlINSERT);
// 		$stmt->bindvalue(':nom', $politiciansName);
// 		$stmt->execute();
// 	}
// 	// foreach ($contentURL->find('span.listingnom b') as $article) {
// 	// 	$politiciansParty = 
// 	// }
// }
// echo $i;




// ........................

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
?>