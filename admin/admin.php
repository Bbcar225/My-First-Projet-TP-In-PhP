<?php

	session_start();
	// var_dump($_SESSION);

	if (!isset($_SESSION['Id'])) {

		header("Location: errorsfatal.php");

	}
	else{

		if (isset($_SESSION['Mot_De_Passe'])) {

			if ($_SESSION['Mot_De_Passe'] == '70352f41061eda4ff3c322094af068ba70c3b38b') {

				header("Location: new_agent.php");

			}

		}

	}

?>
<!DOCTYPE html>

<html>

	<head>
		<title>Admin</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body style="background-color: rgba(152, 245, 255, 0.17);">
		<?php
			include 'include/head.php';
			include 'include/menu.php';
		?>
	</body>

</html>