<hr>	
<h1>Occurences des mots</h1>
<?php 

$db = new PDO('mysql:dbname=rss;host=localhost;charset=utf8','root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$selectOccurence = 'SELECT nom, titre, description FROM media';

$stmt = $db->prepare($selectOccurence);
$stmt->execute();




$oneWordArray = [];
$explode = '';

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$replacedElement = ["N-VA", 'nv-a'];
	$replace = str_replace($replacedElement, "NVA", trim($row['description']));
	$replacedElement = [',', ';', '-', ' - ',' -', '- ', '"', ' "', '" ', '.', '', '’', ':', '«', '»', '?', '“', '!', '_', '|', '+', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ')', '(', '°', '/', '%', '€', '$', '•', '–', ' le ', ' la ', ' les ', 'Les', ' l ', ' a ', ' à ', ' A ', 'À ', ' du ', ' des ', ' s ', ' il ', ' avec ', ' que ', ' se ', ' ne ', ' ce ', ' son ', ' sont ', 'On ', ' cette ', ' d ', ' une ', ' qui ', ' que ', ' quoi ', ' pour ', ' par ', ' au ', ' sur ', ' et ', ' de ', ' un ', ' en ', ' … ', ' nos ', ' e ', ' ça ', ' quand ', ' dans ', ' est ', ' ont ', ' pas ', 'ex-', '...', "s'", "d'", "l'", "n'", ' c ', ' n ', ' y ', ' un ', 'une ', ' elle ', ' nous ', ' sa ', ' ca ', ' ou ', ' h ', ' je ', ' ses ', ' on ', 'l&#', 'd&#', 's&#', 'n&#', 'o&#', 'c&#', 'qu&#'];

	$explode .= str_replace($replacedElement, " ", mb_strtolower(trim($replace)));
}

$oneWordArray = explode(" ", trim($explode));
// var_dump($oneWordArray);
$arCount = [];

foreach($oneWordArray as $val)
{
	$arCount[$val] = (empty($arCount[$val]) ) ? 1 : $arCount[$val]+1;
}
// var_dump($arCount);


?>
 <hr>
<?php

$dix = "";
$cinquante = "";
$cent = "";
$cinqcent = "";
$mille = "";
$deuxmille = "";
$plusdeuxmille = "";
$temp = 0;


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


// $dix10 = [];
// $dix50 = "";
// $dix100 = "";
// $dix500 = "";
// $dix1000 = "";
// $dix2000 = "";
// $dix2000p = "";
// foreach ($arCount as $key => $value) {
// 	if ($value > 1) {

// 		if ($value < 10) {
// 			$dix .= $key . " : " . $value ."<br>";

// 			$dix10[$key] = $value;
// 		}
// 		else if ($value < 50) {
// 			$cinquante .= $key . " : " . $value ."<br>";
// 		}
// 		else if ($value < 100) {
// 			$cent .= $key . " : " . $value ."<br>";
// 		}
// 		else if ($value < 500) {
// 			$cinqcent .= $key . " : " . $value ."<br>";
// 		}
// 		else if ($value < 1000) {
// 			$mille .= $key . " : " . $value ."<br>";
// 		}
// 		else if ($value < 2000) {
// 			$deuxmille .= $key . " : " . $value ."<br>";
// 		}
// 		else{
// 			$plusdeuxmille .= $key . " : " . $value ."<br>";
// 		}


// 	}
// }

function wordsMostOccurences($number, $array, $min, $max, $boolReverseArray = true, $uniqueOccurence = false)
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
	foreach ($maxValues as $key => $value) {
		$arCountReverse = ($boolReverseArray) ? array_flip($newArray) : $data;

		echo $key . " : " . $value . "<br>";
	}
}

?>
<!-- </table> -->
<table id="occurenceTable">
	<tr>
		<td>-10</td>
		<td>10 < x < 50</td>
		<td>50 < x < 100</td>
		<td>100 < x < 500</td>
		<td>500 < x < 1000</td>
		<td>1000 < x < 2000</td>
		<td>+2000</td>
	</tr>
	<tr>
		<td><?= wordsMostOccurences(50, $arCount, 0, 10); ?></td>
		<td><?= wordsMostOccurences(50, $arCount, 10, 50); ?></td>
		<td><?= wordsMostOccurences(50, $arCount, 50, 100); ?></td>
		<td><?= wordsMostOccurences(50, $arCount, 100, 500); ?></td>
		<td><?= wordsMostOccurences(50, $arCount, 500, 1000); ?></td>
		<td><?= wordsMostOccurences(50, $arCount, 1000, 2000); ?></td>
		<td><?= wordsMostOccurences(50, $arCount, 2000, 1000000); ?></td>
	</tr>
</table>
<?php



