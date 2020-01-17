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

	if (isset($_POST) AND !empty($_POST) AND isset($_GET) AND !empty($_GET)) {

		if (isset($_POST['valide_date_livraison']) AND isset($_GET['id_commande'])) {

			if (!empty($_POST['date_livraison'])) {
				
				$dateLivraison = $_POST['date_livraison'];
				$idCommandeLivraison = $_GET['id_commande'];

				$reqIdCommandeLivraison = $bdd->prepare('
															SELECT Id_Commande 
															FROM commande 
															WHERE Id_Commande = ?
														');
				$reqIdCommandeLivraison->execute([$idCommandeLivraison]);
				$reqIdCommandeLivraisonData = $reqIdCommandeLivraison->fetch();

				if ($idCommandeLivraison == $reqIdCommandeLivraisonData['Id_Commande']) {
					
					$reqUpdateLivraison = $bdd->prepare('
															UPDATE commande 
															SET Date_Livraison = ? 
															WHERE Id_Commande = ?
														');

					$reqUpdateLivraison->execute([$dateLivraison, $reqIdCommandeLivraisonData['Id_Commande']]);

					$ok = "EFFECTUÉ";

				}

			}

		}

	}

?>
<!DOCTYPE html>
<html>

	<head>
		<title>COMMANDE Nº</title>
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

				<table class="info_client" style="border-collapse: collapse;">

					<?php

						if (isset($_GET) AND !empty($_GET)) {
							
							if (isset($_GET['id_commande'])) {

								$idCommandeClient = $_GET['id_commande'];

								$reqIdCommandeClient = $bdd->prepare('
																		SELECT Id_Commande 
																		FROM commande 
																		WHERE Id_Commande = ?
																	');

								$reqIdCommandeClient->execute([$idCommandeClient]);
								$reqIdCommandeClientData = $reqIdCommandeClient->fetch();

							}

							if ($idCommandeClient == $reqIdCommandeClientData['Id_Commande']) {

								$reqInfClient = $bdd->prepare('
																SELECT DISTINCT 
																	commande.Id_Inscript AS Id_Inscript,
																	inscript.Nom AS Nom_Acheteur,
																	inscript.Prenom AS Prenom_Acheteur,
																	inscript.Telephone1 AS Telephone_Acheteur,
																	inscript.Ville AS Ville_Acheteur,
																	inscript.Quartier AS Quartier_Acheteur,
																	inscript.Lieu_Retrait AS Lieu_Retrait_Acheteur 
																FROM commande
																INNER JOIN inscript
																ON inscript.Id = commande.Id_Inscript
																WHERE Id_Commande = ?
															');

								$reqInfClient->execute([$reqIdCommandeClientData['Id_Commande']]);
								$reqInfClientData = $reqInfClient->fetch();

					?>

					<tr>
						<th style="background-color: #98F5FF;">
							INFO CLIENT
						</th>
					</tr>
					<tr>
						<td>
							<?php
								echo $reqInfClientData['Nom_Acheteur'];
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
								echo $reqInfClientData['Prenom_Acheteur'];
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
								echo $reqInfClientData['Telephone_Acheteur'];
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
								echo $reqInfClientData['Ville_Acheteur'];
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
								echo $reqInfClientData['Quartier_Acheteur'];
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?php
								echo $reqInfClientData['Lieu_Retrait_Acheteur'];
							?>
						</td>
					</tr>
					<tr>
						<td>
							<a style="text-decoration: none; color: black;" href="membres.php?id_membre=<?php echo $reqInfClientData['Id_Inscript'] ?>">
								PLUS
							</a>
						</td>
					</tr>
					<tr>
						<td><br>
							<form method="POST" action="#">
								
							<p style="font-weight: bold; color: red;">
								<?php if(isset($GLOBALS['ok'])): ?>
									<?php echo $GLOBALS['ok']; ?>
		    					<?php endif; ?>
	    					</p>

								<label style="font-weight: bold;">PROGRAMMER UNE DATE DE LIVRAISON :</label>

								<br><br>

								<input type="date" name="date_livraison">

								<br><br>

								<input type="submit" name="valide_date_livraison" value="VALIDER">

								<br><br>

							</form>
						</td>
					</tr>

					<?php
					}
					}
					?>

				</table>

				<table class="info_commande" style="border-collapse: collapse;">

					<thead>
						<tr>
							<th class="titre_commandes_number">
								TITRE
							</th>
							<th class="auteur_commandes_number">
								AUTEUR
							</th>
							<th class="quantite_commandes_number">
								QUANTITÉ
							</th>
							<th class="prix_commandes_number">
								PRIX
							</th>
							<th class="prx_lvrson_commandes_number">
								PRX LIVRAISON
							</th>
							<th class="dt_lvrson_commandes_number">
								DATE LIVRAISON
							</th>
						</tr>
					</thead>
					<tbody class="tbody_commandes_number">

					<?php

						if (isset($_GET) AND !empty($_GET)) {
							
							if (isset($_GET['id_commande'])) {
								
								$idCommandeCommande = $_GET['id_commande'];

								$reqIdCommandeCommande = $bdd->prepare('
																			SELECT
																				livre.Nom AS Nom_Livre,
																				livre.Id_Auteur AS Id_Auteur,
																				livre.Prix AS Prix_Livre,
																				livre.Prix_Livraison AS Prix_Livraison_Livre,
																				commande.Id_Livre AS Id_Livre,
																				commande.Quantite AS Quantite,
																				commande.Date_Livraison AS Date_Livraison
																			FROM commande
																			INNER JOIN livre
																			ON livre.Id = commande.Id_Livre 
																			WHERE Id_Commande = ?
																		');
								$reqIdCommandeCommande->execute([$idCommandeCommande]);

								while ($reqIdCommandeCommandeData = $reqIdCommandeCommande->fetch()) {

					?>

						<tr>
							<td>
								<a href="livres.php?id_livre=<?php echo $reqIdCommandeCommandeData['Id_Livre'] ?>" target="_blank">
									<?php
										echo $reqIdCommandeCommandeData['Nom_Livre'];
									?>
								</a>
							</td>
							<td>
								<a href="auteurs.php?id_auteur=<?php echo $reqIdCommandeCommandeData['Id_Auteur'] ?>" target="_blank">
									<?php
										$reqauteurname = $bdd->prepare('
																			SELECT Nom_Prenom
																			FROM auteur
																			WHERE Id = ?
																		');
										$reqauteurname->execute([$reqIdCommandeCommandeData['Id_Auteur']]);
										$reqauteurnamedata = $reqauteurname->fetch();
										echo $reqauteurnamedata['Nom_Prenom'];
									?>
								</a>
							</td>
							<td>
								<?php
									echo $reqIdCommandeCommandeData['Quantite'];
								?>
							</td>
							<td>
								<?php
									echo $reqIdCommandeCommandeData['Prix_Livre'];
								?>
								F CFA
							</td>
							<td>
								<?php
									echo $reqIdCommandeCommandeData['Prix_Livraison_Livre'];
								?>
								F CFA
							</td>
							<td>
								<?php
									echo $reqIdCommandeCommandeData['Date_Livraison'];
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