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
$selectOccurence = 'SELECT nom, titre, description, idMedia FROM media';

// $stmt = $db->prepare($selectOccurence);
// $stmt->execute();

$oneWordArray = [];
$explode = '';

$stmt = $db->getQuery($selectOccurence);
$wordSayArray = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {


	// $wordSay = substr($row['description'], ($p = strpos($row['description'], '"')), strrpos($row['description'], '"')-$p);
	preg_match_all('/".*?"/', $row['description'], $out);
	// array_push($wordSayArray, $wordSay);
	// var_dump($out);
	foreach ($out as $key) {
		if(!empty($key)) { $wordSayArray[$row['idMedia']] = $key;}
	}
	$replacedElement = ["N-VA", 'nv-a'];
	$replace = str_replace($replacedElement, "NVA", trim($row['description']));

	$replacedElement = [',', ';', '-', ' - ',' -', '- ', '"', ' "', '" ', '.', '', '’', ':', '«', '»', '?', '“', '!', '_', '|', '+', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ')', '(', '°', '/', '%', '€', '$', '•', '–', ' le ', ' la ', ' les ', 'Les', ' l ', ' a ', ' à ', ' A ', 'À ', ' du ', ' des ', ' s ', ' il ', ' avec ', ' que ', ' se ', ' ne ', ' ce ', ' son ', ' sont ', 'On ', ' cette ', ' d ', ' une ', ' qui ', ' que ', ' quoi ', ' pour ', ' par ', ' au ', ' sur ', ' et ', ' de ', ' un ', ' en ', ' … ', ' nos ', ' e ', ' ça ', ' quand ', ' dans ', ' est ', ' ont ', ' pas ', 'ex-', '...', "s'", "d'", "l'", "n'", ' c ', ' n ', ' y ', ' un ', 'une ', ' elle ', ' nous ', ' sa ', ' ca ', ' ou ', ' h ', ' je ', ' ses ', ' on ', 'l&#', 'd&#', 's&#', 'n&#', 'o&#', 'c&#', 'qu&#'];

	$explode .= str_replace($replacedElement, " ", mb_strtolower(trim($replace)));

}
// var_dump($wordSayArray);
foreach ($wordSayArray as $idMedia => $key) {
	foreach ($key as $citation) {
		// echo $idMedia . "<br>";
		// echo $citation . "<br>";
		$sql = "INSERT INTO citations (citation, FK_idMedia)
				VALUES (".$citation.", ".$idMedia.")";
		$stmt = $db->getQuery($sql);
		$stmt->execute();
		// $row = $stmt->fetch(PDO::FETCH_ASSOC);

	}
}

//DELETE DUPLICATE
// DELETE a
// FROM
//     citations AS a,
//     citations AS b
// WHERE
//     a.id < b.id
//     AND a.citation <=> b.citation
//     AND a.FK_idMedia <=> b.FK_idMedia
// $oneWordArray = explode(" ", trim($explode));
// $arCount = [];


// foreach($oneWordArray as $val)
// {
// 	//Si mot se termine par 'S' et que longueur mot > 3 ALORS enlève le s
// 	//Si mot ne se termine pas par 'S' alors ajoute s
// 	if (substr($val,-1) != 's' && strlen($val) > 3)
// 	{	
// 		// && strlen($val) > 4
// 		// $val = substr($val, 0, -1);
// 		$val = $val . "s";
// 	}
// 	$arCount[$val] = (empty($arCount[$val]) ) ? 1 : $arCount[$val]+1;
// }
// 		$timestamp_fin = microtime(true);
// 		$difference_ms = $timestamp_fin - $timestamp_debut;
// 		echo "<span class='progression' style='float: right; width: 70%;'>"  . " : " . number_format($difference_ms,2) . ' secondes.'."<br></span>";

//     function array_max_r($arr) {
//         $max = null;
//         if (!is_array($arr)) return;
 
//         foreach ($arr as $key => $value) {
//             if (is_array($value)) {
//                 $max = max(array_max_r($value), $max);
//             } else {
//                 $max = max($value, $max);
//             }
//         }
//         return $max;
//     }


// function wordsMostOccurences($number, $array, $min, $max, $uniqueOccurence = false)
// {

// 	$newArray = [];
// 	foreach ($array as $key => $value) {
// 		if ($value >= $min && $value < $max) {
// 			$newArray[$key] = $value;
// 		}
// 	}
// 	$data = $newArray;
// 	//Occurence unique : mot=>10, mot=>9, mot=>8, ... | Occurence pas unique : mot=>10, mot=>10, mot=> 10, mot=>9, mot=>9, mot=>9, mot=>9, ...
// 	$data = ($uniqueOccurence)?array_unique($data) : $data;
// 	//Trie un tableau en ordre inverse et conserve l'association des index
// 	arsort($data);
// 	//Extrait une portion de tableau
// 	$maxValues = array_slice($data, 0, $number);
// 	foreach ($maxValues as $mot => $occurence) {
// 		// $arCountReverse = ($boolReverseArray) ? array_flip($newArray) : $data;
// 		if (strlen($mot) > 1 && $occurence > 30) {
// 			echo $mot . " : " . $occurence . "<br>";
// 		}
// 	}
// }
// function wordOccurence($word,$word2, $array){
// 	$wordOccurence = array();
// 	if (isset($word) || $word2){
// 		foreach ($array as $key => $value) {
// 			if (isset($word) && stristr($key, $word)) {
// 				if ($key == $word || $key.'s' == $word || $key == $word."s" || $key."s" == $word."s"){
// 					$wordOccurence [$key] = $value;
// 				}
// 			}
// 			else if (isset($word2) && !empty(stristr($key, $word2))) {
// 				if ($key == $word2 || $key.'s' == $word2 || $key == $word2."s" || $key."s" == $word2."s"){
// 					$wordOccurence [$key] = $value;
// 				}
// 			}
// 		}
// 		$wordOccurenceJSON = json_encode($wordOccurence);
// 		echo "<input type=hidden id=occurenceValues value='".$wordOccurenceJSON."'/>";
// 		echo "<canvas id='myChart'></canvas>";
// 	}

// }
// $word1 = isset($_POST['word'])?$_POST['word']:false;
// $word2 = isset($_POST['word2'])?$_POST['word2']:false;
// // wordOccurence($word1, $word2, $arCount);
?>
<!-- <canvas id="myChart" width="400" height="400"></canvas> -->
<!-- </table> -->
<table id="occurenceTable">
	<tr>
<!-- 		<td>10 < x < 50</td>
		<td>50 < x < 100</td>
		<td>100 < x < 500</td>
		<td>500 < x < 1000</td>
		<td>1000 < x < 2000</td>
		<td>+2000</td> -->
	</tr>
	<tr>
		<td>
			<?php 
		// wordsMostOccurences(5000, $arCount, 200, 2000); 
		?></td>
		<td>
			<?php 
		// wordsMostOccurences(5000, $arCount, 50, 100); 
		?></td>
		<td>
			<?php 
		// wordsMostOccurences(5000, $arCount, 100, 500); 
		?></td>
		<td>
			<?php 
		// wordsMostOccurences(5000, $arCount, 500, 1000); 
		?></td>
		<td>
			<?php 
		// wordsMostOccurences(5000, $arCount, 1000, 2000); 
		?></td>
		<td>
			<?php 
		// wordsMostOccurences(5000, $arCount, 2000, 1000000); 
		?></td>
	</tr>
</table>