<hr>
<?php
//SELECT FROM DB - AFFICHAGE DES DONNEES
try {
    $db = new PDO("mysql:host=localhost;dbname=rss;charset=utf8","root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $db->exec("set names utf8");
}
catch (PDOException $e)
{
	die('<span style="color:black">Erreur :  : ' . $e->getMessage()) . '</span>';
}
echo  "<p>ARTICLE ISSU DE LA BDD</p>";
$sqlSELECT = "SELECT * FROM media order by idMedia DESC";

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
foreach ($db->query($sqlSELECT) as $row) {
	?>
		<tr>
			<td><?php   echo $row['idMedia'] ?></td>
			<td><?php   echo $row['nom'] ?></td>
			<td><?php 	echo $row['titre']; ?></td>
			<td><?php 	echo $row['description']; ?></td>
			<td><?php 	echo $row['date']; ?></td>
			<td><?php 	echo $row['lien']; ?></td>
			<td><?php 	echo $row['categorie']; ?></td>
		</tr>
	
	<?php 
}
?>
</table>
<?php
$sqlSelectMedia = "SELECT nom, categorie FROM media where nom like '%sudinfo%'";
foreach ($db->query($sqlSelectMedia) as $row) {
	echo $row['nom'] . " => ". $row['categorie'] . "<br>";
}


$updateTable =
			  [
			  	"UPDATE media SET nom = 'rtl' where nom like '%rtl%'",
			  	"UPDATE media SET nom = 'dh' where nom like '%dh%'",
			  	"UPDATE media SET nom = 'lecho' where nom like '%lecho%'",
			  	"UPDATE media SET nom = 'lesoir' where nom like '%lesoir%'",
			  	"UPDATE media SET nom = 'lalibre' where nom like '%lalibre%'",
			  	"UPDATE media SET nom = 'levif' where nom like '%levif%'",
			  	"UPDATE media SET nom = 'rtbf' where nom like '%rtbf%'",
			  	"UPDATE media SET nom = 'sudinfo' where nom like '%sudinfo%'"
			  ];
?>