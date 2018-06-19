<form action="?section=search" method="POST">
	<input type="text" name="entry">
	<input type="submit" name="submitEntry">
</form>

<?php

$db = new PDO('mysql:dbname=rss;host=localhost;charset=utf8','root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$entry = isset($_REQUEST['entry']) ? $_REQUEST['entry'] : null; 

$selectDescription = 
'
SELECT nom, titre, description, date, lien, categorie FROM media where description like "%'. $entry .'%" ORDER BY date
';
$stmt = $db->prepare($selectDescription);
$stmt->execute();
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
		<td><?= $row['nom']." <br> ". $row['date']?></td>
		<td><?= "<a href='".$row['lien']."'>" . $row['titre'] . "</a>" ?></td>
		<td><?= $row['description'] ?></td>
	</tr>
	<?php
}
?>
</table>


<hr>	
<h1>Occurences mots</h1>
<?php 
$selectOccurence = 'SELECT nom, titre, description FROM media';

$stmt = $db->prepare($selectOccurence);
$stmt->execute();
?>
<table>
	<tr>
		<td>description</td>
		<td>occurence</td>
	</tr>

<?php
$oneWordArray = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	?>
	<tr>
		<td><?= $row['nom'] ?></td>
		<td><?php $explode = explode(" ", $row['description']);
		array_push($oneWordArray, $explode);
		  ?></td>
	</tr>
	<?php
}
var_dump($oneWordArray);
?>
</table>
<hr>