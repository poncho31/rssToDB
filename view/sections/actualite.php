
<?php
use Poncho\Database\Database;
require 'vendor/autoload.php';
$db = new Database();

echo  "<p>ARTICLE ISSU DE LA BDD</p>";
$sqlSELECT = "SELECT * FROM media order by idMedia DESC LIMIT 1000";

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
<?php
$stmt = $db->getQuery($sqlSELECT);
while ($row = $stmt->fetch()) {
	?>
		<tr>
			<td><?= $row->idMedia ?></td>
			<td><?= $row->nom ?></td>
			<td><?= $row->titre ?></td>
			<td><?= $row->description; ?></td>
			<td><?= $row->date; ?></td>
			<td><?= $row->lien; ?></td>
			<td><?= $row->categorie; ?></td>
		</tr>
	
	<?php 
}
?>
</table>
