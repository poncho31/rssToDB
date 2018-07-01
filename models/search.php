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
<?php
// var_dump($_REQUEST['categorie']);

if (isset($_POST['submitEntry']) && !empty($_POST['submitEntry'])) {

	$entry = isset($_POST['entry']) ? $_POST['entry'] : null; 
	$categorie = isset($_POST['categorie']) ? $_POST['categorie']: '';
	$selectDescription = 
	'
	SELECT nom, titre, description, categorie, date, lien FROM media where description like "%'. $entry .'%" and categorie like "%'.$categorie.'%" order by date LIMIT 20
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
	// date_format($date,"Y/m/d H:i:s")
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		?>
		<tr>

			<td><?= $row['categorie']." --<br> ". strftime("%Y-%m-%d %H:%M:%S", strtotime($row['date']))?></td>
			<td><?= "<a href='".$row['lien']."' target='_blank'>" . $row['titre'] . "</a>" ?></td>
			<td><?= substr($row['description'], 0, 508) . "... " ?></td>
		</tr>
		<?php
	}
}
?>
</table>
<?php 
// Select txt
$fp = fopen("data/politiciansNamesListFORMATED.txt", 'r');
$fr = fread($fp, filesize("data/politiciansNamesListFORMATED.txt"));
//Explode txt in an array
$arraycsv = explode(';', $fr);

$politiciansName = [];
//Pour chaque ligne de politicien, retrouver son nom et prénom grâce à un délimiteur 'espace' en inversant la ligne (car prénom est à la fin)
//
foreach ($arraycsv as $key) {
	$politicians = explode(' ', strrev($key), 2);
	$firstname = strrev($politicians[0]);
	$lastname = strrev($politicians[1]);
	$politiciansName[$lastname] = $firstname;
}
// Insérer chaque politicien dans Table 'politicians'
foreach ($politiciansName as $key => $value) {
	$sql = "INSERT INTO politicians (lastname, firstname) VALUES(:lastname, :firstname)";
	// $stmt = $db->prepare($sql);
	// $stmt->bindparam(':lastname', $key);
	// $stmt->bindparam(':firstname', $value);
	// $stmt->execute();
}
 ?>