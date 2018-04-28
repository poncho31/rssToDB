<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="refresh" content="50; URL=RSS.php">
	<title>RSS TEST</title>
	<link rel="stylesheet" href="style.css">
</head>
<body >
	        <?php 
	        //API SimpleHTMLDom
	        include_once 'simpleHtmlDom/simple_html_dom.php';

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
                echo "
            
            	<p>RSS</p>

                <h1> $titleMediaRSS </h1>
                <p> $descriptionRSS </p>
                <a> $linkFormatRSS </a>
                <h5 style='float:right;'> $datePublicationRSS </h5><br><br><br><hr><br>

                <p>ARTICLE ASPIRé</p>

                <p> $article </p>
                <p> $categorie </p>
                <br><br><br><hr><br>";

//VERIFICATION SI PAS DEJA EN BDD
				$sqlVERIFICATIONtitre = $db->prepare("SELECT description FROM media WHERE description= ?");
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


//SELECT FROM DB - AFFICHAGE DES DONNEES
echo  "<p>ARTICLE ISSU DE LA BDD</p>";
$sqlSELECT = "SELECT * FROM media";
echo (!empty($alreadyInDB)) ? $alreadyInDB .'<br><br>' : null;
foreach ($db->query($sqlSELECT) as $row) {
	?><table>
		<tr>
			<td>ID</td>
			<td>Titre</td>
			<td>Description</td>
			<td>Date</td>
			<td>Lien</td>
			<td>Article</td>
			<td>Categorie</td>
		</tr>
		<tr>
			<td><?php   echo $row['idMedia'] ?></td>
			<td><?php 	echo $row['titre']; ?></td>
			<td><?php 	echo $row['description']; ?></td>
			<td><?php 	echo $row['date']; ?></td>
			<td><?php 	echo $row['lien']; ?></td>
			<td><?php 	echo $row['article']; ?></td>
			<td><?php 	echo $row['categorie']; ?></td>
		</tr>
	</table><?php 
	
}
?>

</script>
</body>
</html>