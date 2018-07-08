<?php

use Poncho\Database\Search;
use Poncho\Database\Database;
include('vendor/autoload.php');

$search = new Search();


//Formulaire de recherche
$action = '?section=search';
$inputName = 'entry';
$inputValue = isset($_REQUEST['entry'])? $_REQUEST['entry'] : '';
$selectName = 'categorie';
$columnName = 'categorie';
$submitName = 'submitSearch';
$sql = 'SELECT '.$columnName.' FROM media WHERE '.$columnName.' != "" GROUP BY '.$columnName;
echo $search->getHTML_form($action, $inputName, $inputValue, $selectName, $columnName, $submitName, $sql);



$limit = isset($_REQUEST['limit'])? $_REQUEST['limit'] : 0;
$next = $limit + 20;
$previous = ($limit == 0)? 0: $limit - 20;
$entry = isset($_REQUEST['entry']) ? $_REQUEST['entry'] : ''; 
$category = isset($_REQUEST['categorie']) ? $_REQUEST['categorie']: '';


// if (isset($_REQUEST['submitEntry']) && !empty($_REQUEST['submitEntry'])) {

	$selectDescription = 
	'
	SELECT nom, titre, description, categorie, date, lien FROM media where description like "%'. $entry .'%" and categorie like "%'.$category.'%" ORDER BY date DESC LIMIT '.$limit.',20
	';
echo "<a href='?section=search&entry=".$entry."&categorie=".$category."&limit=".$previous."'>Previous</a>";
echo "<a href='?section=search&entry=".$entry."&categorie=".$category."&limit=".$next."'>Next</a>";

echo $search->getHTML_table($selectDescription);

