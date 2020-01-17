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
		
		if (isset($_GET['id_edition']) AND isset($_GET['action'])) {

			$idEdition = $_GET['id_edition'];
			$action = $_GET['action'];

			if ($action == 'oui') {

				$reqIdEdition = $bdd->prepare('SELECT Id FROM edition WHERE Id = ?');
				$reqIdEdition->execute([$idEdition]);
				$reqIdEditionData = $reqIdEdition->fetch();

				if ($reqIdEditionData['Id'] == $idEdition AND $action == 'oui') {

					$reqIdEditionSupp = $bdd->prepare('DELETE FROM edition WHERE Id = ?');
					$reqIdEditionSupp->execute([$reqIdEditionData['Id']]);

					echo "Suppression Ok !";

				}

			}

		}

	}
?>
<!DOCTYPE html>
<html>
	<head>

		<title>SUPRESSION AUTEUR</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">

	</head>

	<body style="text-align: center; font-weight: bold; font-size: 30px;">
		<a style="text-decoration: none;" href="supp_edition.php?action=oui&id_edition=<?php if (isset($_GET['id_edition'])) { echo $_GET['id_edition']; } ?>">
			<p style="margin-top: 200px;">
				OK
			</p>
		</a>

		<a style="text-decoration: none;" href="editions.php">
			<p>
				ANNULER
			</p>
		</a>
	</body>

</html>