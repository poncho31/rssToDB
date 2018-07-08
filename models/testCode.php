<form action="?section=search" method="POST">
	<input type="text" name="entry" value="<?= isset($_REQUEST['entry'])? $_REQUEST['entry'] : ''; ?>"placeholder="Politician's name">
	<select name="categorie">
		<option value >Toutes les categories</option>
		<?php 
			$selectDescription = 'SELECT categorie FROM media WHERE categorie != " "GROUP BY categorie';
			$stmt = $db->getQuery($selectDescription);
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