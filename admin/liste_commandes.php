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

	if (isset($_SESSION['Poste']) AND $_SESSION['Poste'] == 'Insereur') {

		header("Location: errorsfatal.php");

	}

?>
<!DOCTYPE html>
<html>

	<head>
		<title>COMMANDE DU : </title>
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

			<div>
				<table class="table_liste_commandes" style="border-collapse: collapse;">
					<thead>
						<tr>
							<th class="n_commande">
								Nº COMMANDE
							</th>
							<th class="nm_prnm_acheteur">
								NOM-PRENOMS ACHETEUR
							</th>
							<th class="prix_commandes">
								PRIX TOTAL
							</th>
							<th class="nbre_livres">
								NBRES LIVRES
							</th>
							<th class="heure_commande">
								HEURE COMMANDE
							</th>
						</tr>
					</thead>
					<tbody class="tbody_liste_commandes">

						<?php

							if (isset($_GET) AND !empty($_GET)) {
								
								if (isset($_GET['date_commande'])) {
									
									$dateCommande = $_GET['date_commande'];

									$reqdatecommande = $bdd->prepare('
																		SELECT DISTINCT SUBSTRING(Date_Commande, 1,10) 
																		FROM commande 
																		WHERE SUBSTRING(Date_Commande, 1,10) = ?
																	');

									$reqdatecommande->execute([$dateCommande]);
									$reqdatecommandedata = $reqdatecommande->fetch();

									if ($dateCommande == $reqdatecommandedata[0]) {

										$reqallinf = $bdd->prepare('
																		SELECT DISTINCT 
																			commande.Id_Commande AS Id_Commande,
																			inscript.Nom AS Nom_Acheteur,
																			inscript.Prenom AS Prenom_Acheteur,
																			commande.Prix_Total AS Prix_Total,
																			commande.Date_Commande AS Date_Commande,
																			commande.Id_Inscript AS Id_Inscript
																		FROM commande
																		INNER JOIN inscript
																		ON commande.Id_Inscript = inscript.Id
																		WHERE SUBSTRING(Date_Commande, 1,10) = ? 
																		ORDER BY Date_Commande DESC
																	');

										$reqallinf->execute([$reqdatecommandedata[0]]);

										while ($reqallinfdata = $reqallinf->fetch()) {

											$reqnbrelivre = $bdd->prepare('
																			SELECT COUNT(Id_Livre) 
																			FROM commande 
																			WHERE Id_Commande = ?
																		');

											$reqnbrelivre->execute([$reqallinfdata['Id_Commande']]);
											$reqnbrelivredata = $reqnbrelivre->fetch();

						?>

						<tr>
							<td>
								<a href="commandes_number.php?id_commande=<?php echo $reqallinfdata['Id_Commande']; ?>" target="_blank">
									<?php
										echo $reqallinfdata['Id_Commande'];
									?>
								</a>
							</td>
							<td>
								<a href="membres.php?id_membre=<?php echo $reqallinfdata['Id_Inscript']; ?>" target="_blank">
									<?php
										echo $reqallinfdata['Nom_Acheteur']. ' ' .$reqallinfdata['Prenom_Acheteur'];
									?>
								</a>
							</td>
							<td>
								<?php
									echo number_format($reqallinfdata['Prix_Total'],0,',',' ');
								?>
								F CFA
							</td>
							<td>
								<?php
									echo $reqnbrelivredata[0];
								?>
							</td>
							<td>
								<?php
									echo substr($reqallinfdata['Date_Commande'], 11, 15);
								?>
							</td>
						</tr>
						<?php 
						}
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