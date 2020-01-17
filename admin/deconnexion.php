<?php
	
	session_start();

	if (isset($_SESSION['Id'])) {

		session_destroy();

		header('Location: http://librelivre.ci/Admin/deconnexion/');

	}
	else{

		header('Location: errorsfatal.php');

	}

?>