<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>DB</title>
	<!-- <meta http-equiv="refresh" content="0.3; URL=?section=categoryvif"> -->
	<link rel="stylesheet" href="design/css/style.css">

</head>
<body class="interface">

	<?php
if (isset($_GET['section'])) {

	switch ($_GET['section']) {
		case 'mysql':
			require 'models/RSS.php';
			break;

		case 'mssqlsrv':
			require 'models/RSStoMsSQLserver.php';
			break;

		case 'savedb':
			require 'models/MySQLsaveDB.php';
			break;
		case 'search':
			require 'models/search.php';
			break;
		case 'occurence':
			require 'models/occurence.php';
			break;
		case 'categoryTest':
			require 'models/categoryToDB.php';
			break;
		default:
			require 'index.php';
			break;
	}
}
else{
	?>
		<div class="transparent">
		<h1>Application's Name</h1>
		<div class="grid">
			<div><a href="?section=mysql"><button>MySQL</button></a></div>
			<div><a href="?section=mssqlsrv"><button>MS SQL server</button></a></div>
			<div><a href="?section=savedb"><button>Save MySQL Database</button></a></div>
			<div><a href="?section=search"><button>Search</button></a></div>
			<div><a href="?section=occurence"><button>Occurence</button></a></div>
			<div><a href="?section=categoryTest"><button>Category Test</button></a></div>
		</div>
	</div>
	<?php 
}
	 ?>
</body>
</html>
