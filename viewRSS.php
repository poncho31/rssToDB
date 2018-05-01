<hr>
<?php
//Instanciation de la BDD
try {
    $db = new PDO("mysql:host=localhost;dbname=rss;charset=utf8","root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
catch (PDOException $e)
{
	die('<span style="color:black">Erreur :  : ' . $e->getMessage()) . '</span>';
}

//SELECT FROM DB - AFFICHAGE DES DONNEES
echo  "<p>ARTICLE ISSU DE LA BDD</p>";
$sqlSELECT = "SELECT * FROM media";
foreach ($db->query($sqlSELECT) as $row) {
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
			<td><?php   echo $row['idMedia'] ?></td>
			<td><?php   echo $row['nom'] ?></td>
			<td><?php 	echo $row['titre']; ?></td>
			<td><?php 	echo $row['description']; ?></td>
			<td><?php 	echo $row['date']; ?></td>
			<td><?php 	echo $row['lien']; ?></td>
			<td><?php 	echo $row['categorie']; ?></td>
		</tr>
	</table>
	<?php 
	
}
?>