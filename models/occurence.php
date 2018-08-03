<?php 
use Poncho\Database\Database;
require 'vendor/autoload.php';
$db = new Database();

 ?>
<hr>	
<h1>Occurence des mots</h1>
<form action="?section=occurence" method="POST">
	<input type="text" name="word" placeholder="Variable 1">
	<input type="text" name="word2" placeholder="Variable 2">
	<input type="submit" name="submit">
</form>
<?php 
function highlight($needle, $haystack){
	$ind = stripos($haystack, $needle);
	$len = strlen($needle);
	if($ind !== false){
	    return "<b>" . substr($haystack, $ind, $len) . "</b>";
	}
} 
$timestamp_debut = microtime(true);




$sqlLexique = "SELECT orthographe FROM `lexique` 
			   WHERE grammaire like '%PRO%'
			   	  or grammaire like '%PRE%'
				  or grammaire like '%ART%'
			  ";
$stmt = $db->getQuery($sqlLexique);
$lexiquePronoms = [];
  $replacedElements = [',', ';', ' - ',' -', '- ', '"', ' "', '" ', '...', '.', ' .', '’', ':', '«', '»', '?', '“', '!', '_', '|', '+', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ')', '(', '°', '/', '%', '€', '$', '•', '–', 'l\'', ' l ', ' s\'', ' d ', ' d\'', 'C\'est ', ' n\'est ', ' ces ', ' sera ', ' d\'un ', ' t\'', ' on ', ' tout ', ' été ', ' sont ', ' ayant ', ' ont ', ' son ',' ne ', ' pas ', ' d\'abord ', ' être ',  ' sa ', ' est ', ' et ', ' a ', ' A ', ' qu ', ' qu\'', 'a-t-on', 'L&#', 'l&#', 'd&#', 's&#', 'n&#', 'o&#', 'c&#', 'qu&#',
  ' vendredi ', ' samedi ', ' dimanche ', ' lundi ', ' mardi ', ' mercredi ', ' jeudi ', ' juillet ', ' aout ', ' septembre ', ' octobre ', ' novembre ', ' décembre ', ' janvier ', ' février ', ' mars ', ' avril ', ' mai ', ' juin ', ' plus ', ' ans ', ' fait ', ' mois ', ' était ',
  'Le ', 'La ', ' deux ', 'Un ', 'Les ', ' faire ', ' France ', 'L\'', ' monde ', ' cette ', ' Belgique ', ' pays ', ' lors ', ' avoir ', 'Une ', ' mais ', ' jours ', ' soir ', ' personnes ', ' avait ', ' comme ', ' encore ',
  ' moins ', ' annoncé ', ' dernier ', ' trois ', ' temps ', ' homme ', ' très ', ' année '];
foreach ($replacedElements as $key) {
	array_push($lexiquePronoms, $key);
}
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	array_push($lexiquePronoms, " " .$row['orthographe'] . " ");
	array_push($lexiquePronoms, " ".ucfirst($row['orthographe']) . " ");
}


$selectOccurence = 'SELECT nom, titre, description, idMedia
					FROM media
					WHERE date > CURDATE() - INTERVAL 7 DAY
				   ';

$oneWordArray = [];
$explodeArticle = [];

$stmt = $db->getQuery($selectOccurence);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$explodeArticle[$row['idMedia']] = explode(" ", str_replace($lexiquePronoms, " ", $row['description']));
}
$occurenceTotal = [];
$mediaOccurenceCount = [];
foreach ($explodeArticle as $idMedia => $value) {
	foreach ($value as $mot) {
		if ($mot !== '' && strlen($mot) > 1) {	
			$occurenceTotal[$mot] = (empty($occurenceTotal[$mot]) ) ? 1 : $occurenceTotal[$mot]+1;
			$occurenceArticle[$mot] = (empty($occurenceArticle[$mot]) ) ? 1 : $occurenceArticle[$mot]+1;
			$occurenceArticleForIdMedia = $occurenceArticle;
		}
	}
	// Remet a zero l'occurence pour chaque article
	$occurenceArticle = [];
	// Occurence mot par media tab[idMedia d'un article] = [mot => occurence]
	$mediaOccurenceCount[$idMedia] = $occurenceArticleForIdMedia;
}
// var_dump($mediaOccurenceCount);
// var_dump($occurenceTotal);
arsort($occurenceTotal);

$i = 0;
$topTenWords = [];
foreach ($occurenceTotal as $key => $value) {
	if ($i < 10) {
		// echo $key . "<br>";
		// echo $value . "<br>";
		array_push($topTenWords, $key);
		// echo "<hr>";
		$i++;
	}
	else{
		break;
	}
}
var_dump($topTenWords);

$sqlTopArticle = 
	'SELECT date, description FROM media 
	 WHERE description like "%'.$topTenWords[0].'%"
	 and description like "%'.$topTenWords[1].'%"
	 and description like "%'.$topTenWords[2].'%"
	 order by date
	 DESC
	 LIMIT 0, 10
	 ';
$stmt = $db->getQuery($sqlTopArticle);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	echo $row['date'] . "<br>";
	echo $row['description'] ."<hr>";
}




die();









$oneWordArray = explode(" ", trim($explode));
$arCount = [];

foreach($oneWordArray as $val)
{
	//Si mot se termine par 'S' et que longueur mot > 3 ALORS enlève le s
	//Si mot ne se termine pas par 'S' alors ajoute s
	if (substr($val,-1) != 's' && strlen($val) > 3)
	{	
		// && strlen($val) > 4
		// $val = substr($val, 0, -1);
		$val = $val . "s";
	}
	$arCount[$val] = (empty($arCount[$val]) ) ? 1 : $arCount[$val]+1;
}
		$timestamp_fin = microtime(true);
		$difference_ms = $timestamp_fin - $timestamp_debut;
		echo "<span class='progression' style='float: right; width: 70%;'>"  . " : " . number_format($difference_ms,2) . ' secondes.'."<br></span>";

    function array_max_r($arr) {
        $max = null;
        if (!is_array($arr)) return;
 
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $max = max(array_max_r($value), $max);
            } else {
                $max = max($value, $max);
            }
        }
        return $max;
    }


function wordsMostOccurences($number, $array, $min, $max, $uniqueOccurence = false)
{

	$newArray = [];
	foreach ($array as $key => $value) {
		if ($value >= $min && $value < $max) {
			$newArray[$key] = $value;
		}
	}
	$data = $newArray;
	//Occurence unique : mot=>10, mot=>9, mot=>8, ... | Occurence pas unique : mot=>10, mot=>10, mot=> 10, mot=>9, mot=>9, mot=>9, mot=>9, ...
	$data = ($uniqueOccurence)?array_unique($data) : $data;
	//Trie un tableau en ordre inverse et conserve l'association des index
	arsort($data);
	//Extrait une portion de tableau
	$maxValues = array_slice($data, 0, $number);
	foreach ($maxValues as $mot => $occurence) {
		// $arCountReverse = ($boolReverseArray) ? array_flip($newArray) : $data;
		if (strlen($mot) > 1 && $occurence > 30) {
			echo $mot . " : " . $occurence . "<br>";
		}
	}
}
function wordOccurence($word,$word2, $array){
	$wordOccurence = array();
	if (isset($word) || $word2){
		foreach ($array as $key => $value) {
			if (isset($word) && stristr($key, $word)) {
				if ($key == $word || $key.'s' == $word || $key == $word."s" || $key."s" == $word."s"){
					$wordOccurence [$key] = $value;
				}
			}
			else if (isset($word2) && !empty(stristr($key, $word2))) {
				if ($key == $word2 || $key.'s' == $word2 || $key == $word2."s" || $key."s" == $word2."s"){
					$wordOccurence [$key] = $value;
				}
			}
		}
		$wordOccurenceJSON = json_encode($wordOccurence);
		echo "<input type=hidden id=occurenceValues value='".$wordOccurenceJSON."'/>";
		echo "<canvas id='myChart'></canvas>";
	}

}
$word1 = isset($_POST['word'])?$_POST['word']:false;
$word2 = isset($_POST['word2'])?$_POST['word2']:false;
// wordOccurence($word1, $word2, $arCount);
?>
<!-- <canvas id="myChart" width="400" height="400"></canvas> -->
<!-- </table> -->
<table id="occurenceTable">
	<tr>
		<td>10 < x < 50</td>
		<td>50 < x < 100</td>
		<td>100 < x < 500</td>
		<td>500 < x < 1000</td>
		<td>1000 < x < 2000</td>
		<td>+2000</td>
	</tr>
	<tr>
		<td>
			<?php 
		wordsMostOccurences(5000, $arCount, 200, 2000); 
		?></td>
		<td>
			<?php 
		wordsMostOccurences(5000, $arCount, 50, 100); 
		?></td>
		<td>
			<?php 
		wordsMostOccurences(5000, $arCount, 100, 500); 
		?></td>
		<td>
			<?php 
		wordsMostOccurences(5000, $arCount, 500, 1000); 
		?></td>
		<td>
			<?php 
		wordsMostOccurences(5000, $arCount, 1000, 2000); 
		?></td>
		<td>
			<?php 
		wordsMostOccurences(5000, $arCount, 2000, 1000000); 
		?></td>
	</tr>
</table>