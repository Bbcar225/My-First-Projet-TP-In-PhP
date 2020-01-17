<?php

	session_start();

// Connexion à ma BDD
	try{

		$bdd = new PDO("mysql:host=localhost;dbname=commerce", 'root', '');
		$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	catch(PDOEcxeption $e){

		echo 'Echec de la connexion : ' . $e->getMessage();
	}

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

	if (isset($_SESSION['Poste']) AND $_SESSION['Poste'] == 'Livreur') {

		header("Location: errorsfatal.php");

	}

	if (isset($_GET) AND !empty($_GET)) {
		
		if (isset($_GET['id_agent']) AND isset($_GET['action'])) {

			$idAgent = $_GET['id_agent'];
			$action = $_GET['action'];

			if ($action == 'oui') {

			$reqIdAuteur = $bdd->prepare('SELECT Id FROM admin WHERE Id = ?');
			$reqIdAuteur->execute([$idAgent]);
			$reqIdAuteurData = $reqIdAuteur->fetch();

			if ($reqIdAuteurData['Id'] == $idAgent AND $action == 'oui') {

				$reqIdAuteurSupp = $bdd->prepare('DELETE FROM admin WHERE Id = ?');
				$reqIdAuteurSupp->execute([$reqIdAuteurData['Id']]);

				echo "Suppression Ok !";

			}

			}

		}

	}
?>
<!DOCTYPE html>
<html>
	<head>

		<title>Suppression poste</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">

	</head>

	<body style="text-align: center; font-weight: bold; font-size: 30px;">

		<a style="text-decoration: none;" href="supp_poste.php?action=oui&id_agent=<?php if (isset($_GET['id_agent'])) { echo $_GET['id_agent']; } ?>">
			<p style="margin-top: 200px;">
				OK
			</p>
		</a>

		<a style="text-decoration: none;" href="poste.php">
			<p>
				ANNULER
			</p>
		</a>

	</body>

</html>