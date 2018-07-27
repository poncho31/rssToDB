
<?php
use Poncho\Database\Database;
require 'vendor/autoload.php';
$db = new Database();


  $sql = 'SELECT count(*) as countMedia, nom FROM media GROUP BY nom';
  $stmt = $db->getQuery($sql);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $arr = [];
  foreach ($rows as $row) {
    $arr[$row['nom']] = $row['countMedia'];
    echo $row['nom'] ."<br>";
  }
  $jsonData = json_encode($arr);
  echo "<br>".$jsonData;
  // $fp = fopen('data/nombreArticleParMedia.json', 'w');
  // fwrite($fp, $jsonData);



echo  "<p>ARTICLE ISSU DE LA BDD</p>";
$sqlSELECT = "SELECT * FROM media order by idMedia DESC LIMIT 0, 20";

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
