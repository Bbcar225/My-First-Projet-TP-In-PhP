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
		<title>MEMBRES</title>
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
				<table class="table_membres" style="border-collapse: collapse;">
					<thead>
						<tr>
							<th class="action_membres">
								ACTION
							</th>
							<th class="id_membres">
								ID
							</th>
							<th class="nom_membres">
								NOM
							</th>
							<th class="prnom_membres">
								PRÉNOMS
							</th>
							<th class="sexe_membres">
								SEXE
							</th>
							<th class="dt_nsscnce_membres">
								DATE NAISSANCE
							</th>
							<th class="tel1_membres">
								TÉLÉPHONE1
							</th>
							<th class="tel2_membres">
								TÉLÉPHONE2
							</th>
							<th class="tel3_membres">
								TÉLÉPHONE3
							</th>
							<th class="mail_membres">
								MAIL
							</th>
							<th class="ville_membres">
								VILLE
							</th>
							<th class="qrtr_membres">
								QUARTIER
							</th>
							<th class="lieu_rtrt_membres">
								LIEU RÉTRAIT
							</th>
						</tr>
					</thead>
					<tbody>

						<?php

							if (isset($_GET) AND empty($_GET)) {

								if (!isset($_GET['id_membre'])) {

									$reqallmember = $bdd->prepare('SELECT * FROM inscript');
									$reqallmember->execute();

									while ($reqallmemberdata = $reqallmember->fetch()) {

						?>

						<tr>
							<td>
								<a href="supp_member.php?id_membre=<?php echo $reqallmemberdata['Id']; ?>" target="_blank">
									Supprimé
								</a>
							</td>
							<td style="color: blue; font-weight: bold;">
								<?php
									echo $reqallmemberdata['Id'];
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqallmemberdata['Nom']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqallmemberdata['Prenom']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqallmemberdata['Sexe']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqallmemberdata['Date_Naissance']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqallmemberdata['Telephone1']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqallmemberdata['Telephone2']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqallmemberdata['Telephone3']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqallmemberdata['Mail']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqallmemberdata['Ville']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqallmemberdata['Quartier']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqallmemberdata['Lieu_Retrait']);
								?>
							</td>
						</tr>

						<?php
						}

						}
						

						}
						elseif (isset($_GET['id_membre'])) {

							$idmembre = $_GET['id_membre'];

							$reqmembre = $bdd->prepare('
															SELECT Id
															FROM inscript
															WHERE Id = ?
														');
							$reqmembre->execute([$idmembre]);
							$reqmembredata = $reqmembre->fetch();
							// var_dump($reqmembredata);
							if ($reqmembredata['Id'] == $idmembre) {

								$reqmembreinf = $bdd->prepare('
																SELECT *
																FROM inscript
																WHERE Id =?
															');
								$reqmembreinf->execute([$reqmembredata['Id']]);

								while ($reqmembreinfdata = $reqmembreinf->fetch()) {
						?>

						<tr>
							<td>
								<a href="supp_member.php?id_membre=<?php echo $reqmembreinfdata['Id']; ?>" target="_blank">
									Supprimé
								</a>
							</td>
							<td style="color: blue; font-weight: bold;">
								<?php
									echo $reqmembreinfdata['Id'];
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqmembreinfdata['Nom']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqmembreinfdata['Prenom']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqmembreinfdata['Sexe']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqmembreinfdata['Date_Naissance']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqmembreinfdata['Telephone1']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqmembreinfdata['Telephone2']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqmembreinfdata['Telephone3']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqmembreinfdata['Mail']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqmembreinfdata['Ville']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqmembreinfdata['Quartier']);
								?>
							</td>
							<td>
								<?php
									echo ucfirst($reqmembreinfdata['Lieu_Retrait']);
								?>
							</td>
						</tr>
						
						<?php
						}
						}	
						}
						?>

					</tbody>
				</table>
			</div>

		</div>

	</body>

</html>