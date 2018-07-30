
	<?php
include 'view/header.php';
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
		case 'patch':
			require 'models/patch.php';
			break;
		case 'actualite':
			require 'view/sections/actualite.php';
			break;

		case 'homepage':
			require 'models/homepageModels.php';
			break;
		default:
			require 'index.php';
			break;
	}
}
else{
	?>
		<div class="transparent">
		<h1>Search and Destroy</h1>
		<div class="grid">
			<div><a href="?section=mysql"><button>MySQL</button></a></div>
			<div><a href="?section=mssqlsrv"><button>MS SQL server</button></a></div>
			<div><a href="?section=savedb"><button>Save MySQL Database</button></a></div>
			<div><a href="?section=search"><button>Search</button></a></div>
			<div><a href="?section=occurence"><button>Occurence</button></a></div>
			<div><a href="?section=patch"><button>Category Test</button></a></div>
			<div><a href="?section=actualite"><button>Actualite</button></a></div>
			<div><a href="?section=homepage"><button>HomepageModels</button></a></div>
		</div>
	</div>
	<?php 
}
include 'view/footer.php';
	 ?>
