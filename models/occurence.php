<hr>	
<h1>Occurences mots</h1>
<?php 

$db = new PDO('mysql:dbname=rss;host=localhost;charset=utf8','root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$selectOccurence = 'SELECT nom, titre, description FROM media';

$stmt = $db->prepare($selectOccurence);
$stmt->execute();




$oneWordArray = [];
$explode = '';

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$replacedElement = [',', ';',' "', '" ', '.', '', '’', ':', '«', '»', '?', '!', '_', '|', '+', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ')', '(', '°', '/', '%', '€', '$', '•', '–', ' le ', ' la ', ' les ', ' l ', ' a ', ' à ', ' A ', 'À ', ' du ', ' des ', ' s ', ' d&# ', ' l&# ', 'La ', 'Le ', 'Les ', ' il ', 'Il ', ' avec ', ' que ', ' se ', ' ne ', ' ce ', ' son ', ' sont ', 'On ', ' cette ', ' d ', ' une ', ' qui ', ' que ', ' quoi ', ' pour ', ' par ', ' au ', ' sur ', ' et ', ' de ', ' un ', ' en ', ' … ', ' nos ', ' e ', ' ça ', ' quand ', ' dans ', ' est ', ' ont ', ' pas '];

	$explode .= str_replace($replacedElement, " ", trim($row['description']));
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

$arrayDix = [];
foreach ($arCount as $key => $value) {
	if ($value > 1) {
		if ($value < 10) {
			$dix .= $key . " : " . $value ."<br>";
			array_push($arrayDix, $key);
		}
		else if ($value < 50) {
			$cinquante .= $key . " : " . $value ."<br>";
		}
		else if ($value < 100) {
			$cent .= $key . " : " . $value ."<br>";
		}
		else if ($value < 500) {
			$cinqcent .= $key . " : " . $value ."<br>";
		}
		else if ($value < 1000) {
			$mille .= $key . " : " . $value ."<br>";
		}
		else if ($value < 2000) {
			$deuxmille .= $key . " : " . $value ."<br>";
		}
		else{
			$plusdeuxmille .= $key . " : " . $value ."<br>";
		}


	}
}
?>
<!-- </table> -->
<table>
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
		<td><?= $dix ?></td>
		<td><?= $cinquante ?></td>
		<td><?= $cent ?></td>
		<td><?= $cinqcent ?></td>
		<td><?= $mille ?></td>
		<td><?= $deuxmille ?></td>
		<td><?= $plusdeuxmille ?></td>
	</tr>
</table>

<?php
// var_dump($arrayDix);

function wordsMostOccurences($number, $array, $boolReverseArray, $bool)
{
	$data = $array;
	//Trie les données 
	rsort($data);
	$data = array_unique($data);
	$maxValues = array_slice($data, 0, $number);
	foreach ($maxValues as $key => $value) {
		// Inverse les clés-valeurs du tableau
		$arCountReverse = ($boolReverseArray) ? array_flip($array) : $data;
		echo " ' " . $arCountReverse[$value] . " ' " .' a la plus grande occurence : ' .(($bool) ? $value : $key) . "<br>";
	}
}

// var_dump($arCount);
wordsMostOccurences(100, $arCount, true, true);
 echo '<hr>';

$arrayDix2 = [];
$arrayDix = array_flip($arrayDix);
foreach($arrayDix as $val)
{
	$arrayDix2[$val] = (empty($arrayDix2[$val])) ? 1 : $arrayDix2[$val]+1;
}
wordsMostOccurences(10, $arrayDix, true, false);

// print_r($arrayDix);
echo "<hr>";
// print_r($arCount);