<?php

	session_start();

	header("content-type:text/html; charset=iso-8859-1");

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

?>
<!DOCTYPE html>
<html>

	<head>
		<title>Liste des agents</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body style="background-color: rgba(152, 245, 255, 0.17);">

		<?php
			include 'include/head.php';
		?>

		<div>
			
			<div class="search">
				<input type="search" name="">
			</div>

			<div style="margin-top: 20px;">

				<table class="table_poste" style="border-collapse: collapse;">

					<thead>
						<tr>
							<th class="action_poste">
								ACTION
							</th>
							<th class="id_poste">
								ID
							</th>
							<th class="nom_poste">
								NOM
							</th>
							<th class="prenom_poste">
								PRÉNOM
							</th>
							<th class="mail_poste">
								MAIL
							</th>
							<th class="ville_poste">
								VILLE
							</th>
							<th class="poste_poste">
								POSTE
							</th>
							<th class="compte_poste">
								COMPTE
							</th>
						</tr>
					</thead>

					<tbody>

						<?php

							$reqAgent = $bdd->prepare('SELECT * FROM admin');
							$reqAgent->execute();

							while ($reqAgentData = $reqAgent->fetch()) {
							?>

						<tr>
							<td>
								<a href="modif_poste.php?id_agent=<?php echo $reqAgentData['Id'];?>" target="_blank">
									MODIFIER
								</a>
								<a href="supp_poste.php?id_agent=<?php echo $reqAgentData['Id']; ?>" target="_blank">
									SUPPRIMER
								</a>
							</td>
							<td style="color: blue; font-weight: bold;">
								<?php echo $reqAgentData['Id']; ?>
							</td>
							<td>
								<?php echo $reqAgentData['Nom']; ?>
							</td>
							<td>
								<?php echo $reqAgentData['Prenom']; ?>
							</td>
							<td>
								<?php echo $reqAgentData['Mail']; ?>
							</td>
							<td>
								<?php echo $reqAgentData['Ville']; ?>
							</td>
							<td>
								<?php echo $reqAgentData['Poste'];?>
							</td>
							<td>
								<?php

									if ($reqAgentData['Mot_De_Passe'] == '70352f41061eda4ff3c322094af068ba70c3b38b') {
										
										echo "Pas encore activé";

									}
									else{

										echo "Activé";

									}

								?>
							</td>
						</tr>

						<?php
						}
						?>

					</tbody>

				</table>

			</div>

		</div>

	</body>

</html>