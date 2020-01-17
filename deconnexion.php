<?php
	
	require 'panier.class.php';
	
	$panier = new panier();

	if (isset($_SESSION['Id'])) {

		session_destroy();

		if (isset($_COOKIE['Mail_Client'])) {

			$dateexpiration = -1;
			
			setcookie('Mail_Client', '', $dateexpiration);

		}

		header('Location: index.php');


	}
	else{

		header('Location: errosfatal.php');

	}
	
?>