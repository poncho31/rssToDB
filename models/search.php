<?php
$db = new PDO('mysql:dbname=rss;host=localhost;charset=utf8','root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<form action="?section=search" method="POST">
	<input type="text" name="entry" value="<?= isset($_POST['entry'])? $_POST['entry'] : ''; ?>"placeholder="Politician's name">
	<select name="categorie">
		<option name="categorie" value >Toutes les categories</option>
		<?php 
			$selectDescription = 'SELECT categorie FROM media WHERE categorie != " "GROUP BY categorie';
			$stmt = $db->prepare($selectDescription);
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				if (isset($_REQUEST['categorie']) && $_REQUEST['categorie'] == $row['categorie']) {
					?>
					<option value="<?= $_REQUEST['categorie'] ?>" selected ><?= $row['categorie']; ?></option>
					<?php
				}
				else{
					?>
					<option value="<?= $row['categorie'] ?>" ><?= $row['categorie']; ?></option>
					<?php	
				}
			}
		 ?>
	</select>
	<input type="submit" name="submitEntry">
</form>
<!-- <a href="?section=search&previous=0">Previous</a>
<a href="?section=search&next=20">Next</a> -->

<?php


if (isset($_POST['submitEntry']) && !empty($_POST['submitEntry'])) {
	// $previous = isset($_GET['previous']) ? $_GET['previous'] : 0;
	// $next = isset($_GET['next']) ? $_GET['next'] : 20;
	$entry = isset($_POST['entry']) ? $_POST['entry'] : null; 
	$categorie = isset($_POST['categorie']) ? $_POST['categorie']: '';
	$selectDescription = 
	'
	SELECT nom, titre, description, categorie, date, lien FROM media where description like "%'. $entry .'%" and categorie like "%'.$categorie.'%" order by date LIMIT '.$previous.', '.$next.'
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

			<td><?= $row['categorie']." --<br> ". $row['date']?></td>
			<td><?= "<a href='".$row['lien']."' target='_blank'>" . $row['titre'] . "</a>" ?></td>
			<td><?= substr($row['description'], 0, 508) . "... " ?></td>
		</tr>
		<?php
	}
}
?>
</table>
