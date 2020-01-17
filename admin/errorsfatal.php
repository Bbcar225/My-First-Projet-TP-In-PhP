<?php
	
	session_start();	

	header("content-type:text/html; charset=iso-8859-1");
	
	if (!isset($_SESSION['Id']) OR !isset($_SESSION['Mot_De_Passe'])) {
			
		header('Location: index.php');

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

		<title>ERROR</title>
		<meta charset="utf-8">

	</head>
	
	<body>

		<div style="color: red; font-weight: bold; font-size: 25px; text-align: center;">
			<p>PAGE MOMENTANEMENT INDISPONIBLE !</p>
			<a style="text-align: center;" href="index.php">RETOUR A LA PAGE D'ACCEUIL</a>
		</div>

	</body>

</html>