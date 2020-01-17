<?php

	require 'panier.class.php';
	
	$panier = new panier();

	header("content-type:text/html; charset=iso-8859-1");
	// session_start();
	if (!isset($_SESSION['Id'])) {
			
		header('Location: index.php');
	}
	else{
		header('Location: index.php');
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