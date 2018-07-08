<?php

use Poncho\Database\Search;
use Poncho\Database\Database;
include('vendor/autoload.php');

$db = new Search();


$action = '?section=search';
$inputName = 'entry';
$inputValue = isset($_REQUEST['entry'])? $_REQUEST['entry'] : '';
$selectName = 'categorie';
$columnName = 'categorie';
$submitName = 'submitSearch';
$db->searchHTML($action, $inputName, $inputValue, $selectName, $columnName, $submitName);

// $db = new PDO('mysql:dbname=rss;host=localhost;charset=utf8','root', '');
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>


<?php
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
	$stmt = $db->getQuery($selectDescription);
	$stmt->execute();
echo "<a href='?section=search&entry=".$entry."&categorie=".$category."&limit=".$previous."'>Previous</a>";
echo "<a href='?section=search&entry=".$entry."&categorie=".$category."&limit=".$next."'>Next</a>";
	?>

	<table>
		<tr>
			<td>Nom</td>
			<td>Titre</td>
			<td>Description</td>
		</tr>

	<?php

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		?>
		<tr>

			<td><?= $row['nom']." -<br> ". $row['categorie'] ." -<br> ". $row['date']?></td>
			<td><?= "<a href='".$row['lien']."' target='_blank'>" . $row['titre'] . "</a>" ?></td>
			<td><?= substr($row['description'], 0, 508) . "... " ?></td>
		</tr>
		<?php
	}
// }
?>
</table>

