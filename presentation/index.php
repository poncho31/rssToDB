<?php 

//VIEW
include 'MVC/view/header.php';
include 'MVC/view/nav.php';

if (isset($_GET['section'])) {
	switch ($_GET['section']) {
		case 'intro':
			include 'MVC/view/sections/intro.php';
			break;

		case 'objectifs':
			include 'MVC/view/sections/objectifs.php';
			break;

		case 'processus':
			include 'MVC/view/sections/processus.php';
			break;

		case 'principes':
			include 'MVC/view/sections/principes.php';
			break;

		case 'perspectives':
			include 'MVC/view/sections/perspectives.php';
			break;

		case 'limites':
			include 'MVC/view/sections/limites.php';
			break;

		case 'audela':
			include 'MVC/view/sections/audela.php';
			break;

		case 'homepage':
			include 'MVC/view/homepage.php';
			break;
			
		default:
			include 'MVC/view/homepage.php';
			break;
	}
}
else{
	include 'MVC/view/homepage.php';
}

include 'MVC/view/footer.php';
?>