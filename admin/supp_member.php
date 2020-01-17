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

	if (isset($_SESSION['Poste']) AND $_SESSION['Poste'] == 'Livreur' OR $_SESSION['Poste'] == 'Insereur') {

		header("Location: errorsfatal.php");

	}

	if (isset($_GET) AND !empty($_GET)) {
		
		if (isset($_GET['id_membre']) AND isset($_GET['action'])) {

			$idMembre = $_GET['id_membre'];
			$action = $_GET['action'];

			if ($action == 'oui') {

			$reqIdMembre = $bdd->prepare('SELECT Id FROM inscript WHERE Id = ?');
			$reqIdMembre->execute([$idMembre]);
			$reqIdMembreData = $reqIdMembre->fetch();

			if ($reqIdMembreData['Id'] == $idMembre AND $action == 'oui') {

				$reqIdMembreSupp = $bdd->prepare('DELETE FROM inscript WHERE Id = ?');
				$reqIdMembreSupp->execute([$reqIdMembreData['Id']]);

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
		<a style="text-decoration: none;" href="supp_member.php?action=oui&id_membre=<?php if (isset($_GET['id_membre'])) { echo $_GET['id_membre']; } ?>">
			<p style="margin-top: 200px;">
				OK
			</p>
		</a>

		<a style="text-decoration: none;" href="membres.php">
			<p>
				ANNULER
			</p>
		</a>
	</body>

</html>