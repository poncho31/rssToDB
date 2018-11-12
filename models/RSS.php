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
		// include 'serverName.php';
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
						$sql = "SELECT lien FROM media WHERE lien = :lien ORDER BY date DESC limit 0, 1000 ";
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
							$stmt = $db->getQuery($sqlINSERT, $param);
							$newArticle++;
							$totalNewArticles++;
						}
					}
		    	}
			}
		$timestamp_fin = microtime(true);
		$alreadyInDB .= ($newArticle > 0)? '<tr><td>'. $titleMediaRSS .'</td><td>'.$newArticle. '</td><td>'. date(DATE_RFC850) ."</td></tr>" : '';

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
// UPDATE MEDIA NOM
			  	"UPDATE media SET nom = 'rtl' where nom like '%rtl%'",
			  	"UPDATE media SET nom = 'dh' where nom like '%dh%'",
			  	"UPDATE media SET nom = 'lecho' where nom like '%lecho%'
				  			   or nom like '%fair trade%' 
							   or nom like '%t-zine%'
							   or nom like '%tijd%'
							   or nom like '%cracks%'
				",
			  	"UPDATE media SET nom = 'lesoir' where nom like '%lesoir%'",
			  	"UPDATE media SET nom = 'lalibre' where nom like '%lalibre%'",
			  	"UPDATE media SET nom = 'levif' where nom like '%vif%'",
			  	"UPDATE media SET nom = 'rtbf' where nom like '%rtbf%'",
			  	"UPDATE media SET nom = 'rtbf' where nom like '%la Première%'",
				"UPDATE media SET nom = 'sudinfo' where nom like '%sudinfo%'",

// UPDATE MEDIA CATEGORIES
			//POLITIQUE
				"UPDATE media SET categorie = 'Politique' 
					 where categorie like '%politique%'
					    or categorie like '%Politique%'
					    or categorie like '%jeudi en prime%'
					    or categorie like '%enseignement%'
					    or categorie like '%Communales 2018%'
				",

			//ECONOMIE
				"UPDATE media SET categorie = 'Economie' 
					 where categorie like '%economie%'
					    or categorie like '%entreprise%'
					    or categorie like '%entrepreneuriat%'
						or categorie like '%eco-débat%'
						or categorie like '%placements%'
						or categorie like '%conjoncture%'
						or categorie like '%Conjoncture%'
						or categorie like '%finances%'
						or categorie like '%finance%'
						or categorie like '%prix energie%'
						or categorie like '%Consommation%'
						or categorie like '%marchésFonds%'
						or categorie like '%immo%'
						or categorie like '%Immo%'
						or categorie like '%immobilier%'
						or categorie like '%cracks%'
						or categorie like '%top stories%'
						or categorie like '%Mon Argent%'
						or categorie like '%Argent%'
						or categorie like '%arnaques%'
						or categorie like '%Emploi%'
						or categorie like '%Assurance%'
						or categorie like '%Entreprise%'
				",

			//ACTUALITE
				"UPDATE media SET categorie = 'Actualités'
					 where categorie like '%actualite%'
					 	or categorie like '%belgique%'
					 	or categorie like '%actu%'
					 	or categorie like '%edito%'
					 	or categorie like '%dossier%'
					 	or categorie like '%centre%'
					 	or categorie like '%societe%'
					 	or categorie like '%dernières dépêches%'
					 	or categorie like '%ETCETERA%'
					 	or categorie like '%Cartes blanches%'
					 	or categorie like '%La personnalité%'
					 	or categorie like '%La une%'
					 	or categorie like '%MEDIAS%'
				",

			//EUROPE
				"UPDATE media SET categorie = 'Europe' 
					 where categorie like '%europe%'
					    or categorie like '%union européenne%'
				",

			//REGIONS
				"UPDATE media SET categorie = 'Régions' 
				     where categorie like '%régions%'
						or categorie like '%VotreRegion %'
						or categorie like '%bruxelles%'
						or categorie like '%Bruxelles%'
						or categorie like '%liège%'
						or categorie like '%hainaut%'
						or categorie like '%brabant%'
						or categorie like '%Tournai%'
						or categorie like '%Luxembourg%'
						or categorie like '%Mons%'
						or categorie like '%Namur%'
						or categorie like '%Charleroi%'
						or categorie like '%flandre%'
						or categorie like '%wallonie%'
				",

			//MONDE
				"UPDATE media SET categorie = 'Monde'
					 where categorie like '%monde%'
						or categorie like '%Afrique%'
						or categorie like '%Allemagne%'
						or categorie like '%Ameriques%'
						or categorie like '%Angleterre%'
						or categorie like '%Asie%'
						or categorie like '%Pacifique%'
						or categorie like '%Espagne%'
						or categorie like '%italie%'
						or categorie like '%france%'
						or categorie like '%Pays-Bas%'
						or categorie like '%USA%'
						or categorie like '%proche-orient%'
						or categorie like '%international%'
						or categorie like '%International%'
				",

			//SPORTS
				"UPDATE media SET categorie = 'Sport'
					 where categorie like '%cyclisme%'
						or categorie like '%football%'
						or categorie like '%Jeu de Balle%'
						or categorie like '%foot%'
						or categorie like '%Piste%'
						or categorie like '%mercato%'
						or categorie like '%rugby%'
						or categorie like '%voile%'
						or categorie like '%division%'
						or categorie like '%omnisports%'
						or categorie like '%running%'
						or categorie like '%sport%'
						or categorie like '%auto%'
						or categorie like '%formule 1%'
						or categorie like '%moto%'
						or categorie like '%Cyclo-cross%'
						or categorie like '%tennis%'
						or categorie like '%hockey%'
						or categorie like '%wrc%'
						or categorie like '%endurance%'
						or categorie like '%rallye%'
						or categorie like '%moteurs%'
						or categorie like '%anderlecht%'
						or categorie like '%athlétisme%'
						or categorie like '%jo%'
						or categorie like '%judo%'
						or categorie like '%Boxe%'
						or categorie like '%mondial 2018%'
						or categorie like '%Euro 2016%'
						or categorie like '%route%'
						or categorie like '%Volley%'
						or categorie like '%Handball%'
						or categorie like '%Handball%'
						or categorie like '%Tour de france%'
						or categorie like '%ATP - WTA%'
						or categorie like '%grands chelems%'
						or categorie like '%Basket%'
						or categorie like '%nba%'
						or categorie like '%c1%'
						or categorie like '%c2%'
						or categorie like '%championnat%'
						or categorie like '%Mondiaux%'
						or categorie like '%golf%'
						or categorie like '%jeux olympiques%'
						or categorie like '%coupe davis%'
						or categorie like '%philippe gilbert%'
						or categorie like '%diables rouges%'
						or categorie like '%europa league%'
						or categorie like '%union saint-gilloise%'
						or categorie like '%tubize%'
						or categorie like '%standard%'
						or categorie like '%mouscron%'
						or categorie like '%eupen%'
						or categorie like '%f.c. bruges%'
						or categorie like '%Equipes nationales%'
						or categorie like '%League%'
						or categorie like '%Champions%'
				",

			// SCIENCES	
				"UPDATE media SET categorie = 'Sciences & santé' 
					 where categorie like '%sciences%'
					 	or categorie like '%Sciences%'
					 	or categorie like '%Sciences - Santé%'
					    or categorie like '%santé%'
					    or categorie like '%Santé%'
					    or categorie like '%Science & nature%'
					    or categorie like '%Psycho%'
						or categorie like '%sexualite%'
					 	or categorie like '%love & sex%'
					    or categorie like '%sexo%'
					    or categorie like '%Relations%'
				",

			// ENVIRONNEMENT	
				"UPDATE media SET categorie = 'Environnement' 
					 where categorie like '%environnement%'
					 	or categorie like '%planète%'
					 	or categorie like '%Planète%'
					 	or categorie like '%Agriculture%'
					    or categorie like '%developpement durable%'
					    or categorie like '%Animaux%'
					    or categorie like '%meteo%'
				",

			// TECHNOLOGIES	
				"UPDATE media SET categorie = 'Technologies & Innovation' 
					 where categorie like '%techno%'
					    or categorie like '%technologies%'
					    or categorie like '%digital%'
					    or categorie like '%new tech%'
					    or categorie like '%new-tech%'
					    or categorie like '%sites%'
					    or categorie like '%apps%'
					    or categorie like '%MagazineHi-Tech%'
					    or categorie like '%jeux video%'
					    or categorie like '%inspire%'
					    or categorie like '%GENERAL%'
				",

			// LOISIRS	
				"UPDATE media SET categorie = 'Loisirs' 
					 where categorie like '%musique%'
					    or categorie like '%festivals%'
					    or categorie like '%concert%'
					    or categorie like '%livre%'
					    or categorie like '%Kroll%'
					    or categorie like '%culture%'
					    or categorie like '%Mode%'
					    or categorie like '%art%'
					    or categorie like '%arts%'
					    or categorie like '%Arts%'
					    or categorie like '%cinema%'
					    or categorie like '%Cinema%'
					    or categorie like '%werchter%'
					    or categorie like '%rock%'
					    or categorie like '%Chanson%'
					    or categorie like '%scènes%'
					    or categorie like '%concours reine elisabeth%'
					    or categorie like '%séries%'
					    or categorie like '%lifestyle%'
					    or categorie like '%voyages%'
					    or categorie like '%spectacles%'
					    or categorie like '%food%'
					    or categorie like '%Expos en cours%'
					    or categorie like '%Les racines élémentaires%'
					    or categorie like '%Vous avez de ces mots...%'
				",
			// TELEVISION	
				"UPDATE media SET categorie = 'Télévision & radio' 
					 where categorie like '%télé%'
					 	or categorie like '%Télé%'
					 	or categorie like '%Médias%'
					    or categorie like '%telecom%'
					    or categorie like '%television%'
					    or categorie like '%série tv%'
					    or categorie like '%radio%'
				",


			// Opinions	
				"UPDATE media SET categorie = 'Opinions' 
					 where categorie like '%opinions%'
					 	or categorie like '%forum%'
					 	or categorie like '%ripostes%'
					 	or categorie like '%debats%'
					 	or categorie like '%chroniques%'
					 	or categorie like '%La chronique de la rédaction%'
					 	or categorie like '%La diplomatie pour les nuls%'
				",

			// Monarchie	
				"UPDATE media SET categorie = 'Monarchie' 
					 where categorie like '%monarchie%'
					    or categorie like '%royals%'
					    or categorie like '%Famille royale%'
				",

			// Archives	
				"UPDATE media SET categorie = 'Archives' 
					 where categorie like '%archive%'
					 	or categorie like '%Grands Formats%'
				",
			// AUTRES
				"UPDATE media SET categorie = 'Autres' 
					 where categorie like '%auvio%'
					    or categorie like '%buzz%'
					    or categorie like '%sexy%'
					    or categorie like '%divers%'
					    or categorie like '%vacances%'
					    or categorie like '%undefined%'
					    or categorie like '%Sorties%'
					    or categorie like '%bebe%'
					    or categorie like '%TRANSVERSALES%'
					    or categorie like '%7 a la une%'
					    or categorie like '%beaute & mode%'
					    or categorie like '%mode et beaute%'
					    or categorie like '%concours%'
					    or categorie like '%people%'
					    or categorie like '%vip%'
					    or categorie like '%sabato%'
					    or categorie like '%Gaffes%'
					    or categorie like '%MAD%'
					    or categorie like '%Magazine%'
					    or categorie like '%insolite%'
					    or categorie like '%Hommes%'
					    or categorie like '%personnalité%'
					    or categorie like '%Soirmag%'
				",
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
function foreachOneResult($array){
	foreach ($array as $key) {
		return $key;
	}
}
function updateMedpolTable($db){
	$medPolBefore = foreachOneResult($db->getQuery('SELECT count(*) as cnt FROM medpol'))->cnt;

	$sql = 
	   "INSERT INTO medpol (medpol.fk_pol, medpol.fk_media)
		SELECT p.idPol, m.idMedia
		FROM politicians p, media m
		WHERE m.description
		LIKE CONCAT('% ', p.lastname, ' %')
		and
		m.description LIKE CONCAT('% ', p.firstname, ' %')
		and m.idMedia > (SELECT fk_media FROM medpol ORDER BY fk_media DESC LIMIT 0, 1)
	";
	$stmt = $db->getQuery($sql);
	$medPolAfter = foreachOneResult($db->getQuery('SELECT count(*) as cnt FROM medpol'))->cnt;
	$medPolNew = $medPolAfter - $medPolBefore;

	$sqlPolName = "SELECT p.lastname lastname, p.firstname firstname FROM politicians p
				   INNER JOIN medpol m ON m.fk_pol = p.idpol
				   ORDER BY m.id DESC
				   LIMIT ". $medPolNew;
	$polName = '';
	foreach ($db->getQuery($sqlPolName) as $val) {
		$polName .= $val->firstname ." ". $val->lastname . " | ";
	}
	if ($stmt) {
		echo 'Mise à jour table MedPol : ' . $medPolNew . " nouvelles liaisons (".$medPolAfter.")<br>";
		echo $polName . "<br>";
	}
}
function updateMedpartiTable($db){
	$medpartiBefore = foreachOneResult($db->getQuery('SELECT count(*) as cnt FROM medparti'))->cnt;

	$sql = 
	   "INSERT INTO medparti (medparti.fk_parti, medparti.fk_media)
	    SELECT mp.id, m.idMedia
		FROM parti mp, media m
		WHERE 
		m.idMedia > (SELECT fk_media FROM medparti ORDER BY fk_media DESC LIMIT 0, 1)
		and m.description LIKE CONCAT('% ', mp.nom, ' %')
		or m.description LIKE BINARY CONCAT('%', mp.nom, ' %')
		or m.description LIKE CONCAT('% ', mp.nomComplet, ' %')
		or m.description LIKE BINARY CONCAT('%', mp.nomComplet, ' %')
	";
	$stmt = $db->getQuery($sql);
	$medpartiAfter = foreachOneResult($db->getQuery('SELECT count(*) as cnt FROM medparti'))->cnt;
	$medpartiNew = $medpartiAfter - $medpartiBefore;
	if ($stmt) {
		echo 'Mise à jour table Medparti : ' . $medpartiNew . " nouvelles liaisons (".$medpartiAfter.")<br>";
	}

}
include 'feeds.php';
$timestamp_debut = microtime(true);
rssToDB($feeds, $db);
updateMedpolTable($db);
// updateMedpartiTable($db);
$timestamp_fin = microtime(true);
$difference_ms = $timestamp_fin - $timestamp_debut;
echo 'Exécution du script : ' . number_format($difference_ms / 60, 2) . ' minutes.<br>';




// AFFICHAGE

$sqlSELECT = "SELECT * FROM media order by idMedia DESC";
$sqlCount = "SELECT count(idMedia) as count FROM media";

$stmt = $db->getQuery($sqlSELECT);
$stmt2 = $db->getQuery($sqlCount);

while($row = $stmt2->fetch()){
	echo  "Nombre d'articles dans BDD : " . $row->count ."<br>";
};
$number = 1;

//Saved DB
$dbMysqli = new mysqli('localhost', 'root', '', 'rss');
$dump = new MySQLDump($dbMysqli);
if ($dump) {
	$dump->save('data/'.date("y.m.d").'-SQLsave.sql.gz');
	echo "Sauvegarde de la base de données : " . date("y.m.d") . "<br>";
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
