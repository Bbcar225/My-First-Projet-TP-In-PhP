<?php
	
	require 'panier.class.php';
	
	$panier = new panier();

	header("content-type:text/html; charset=iso-8859-1");

// Connexion à la base de donnée
	try{

		$bdd = new PDO("mysql:host=localhost;dbname=commerce", 'root', '');
		$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	catch(PDOEcxeption $e){

		echo 'Echec de la connexion : ' . $e->getMessage();
	}

	if (isset($_COOKIE['Mail_Client'])) {

		$MailClient = $_COOKIE['Mail_Client'];

		$reqMailCookie = $bdd->prepare('
											SELECT *
											FROM inscript
											WHERE Mail = ?
										');
		$reqMailCookie->execute([$MailClient]);
		$reqMailCookieData = $reqMailCookie->fetch();

		$_SESSION['Id'] = $reqMailCookieData['Id'];
		$_SESSION['Nom'] = $reqMailCookieData['Nom'];
		$_SESSION['Prenom'] = $reqMailCookieData['Prenom'];
		$_SESSION['Sexe'] = $reqMailCookieData['Sexe'];
		$_SESSION['Date_Naissance'] = $reqMailCookieData['Date_Naissance'];
		$_SESSION['Telephone1'] = $reqMailCookieData['Telephone1'];
		$_SESSION['Telephone2'] = $reqMailCookieData['Telephone2'];
		$_SESSION['Telephone3'] = $reqMailCookieData['Telephone3'];
		$_SESSION['Mail'] = $reqMailCookieData['Mail'];
		$_SESSION['Ville'] = $reqMailCookieData['Ville'];
		$_SESSION['Quartier'] = $reqMailCookieData['Quartier'];
		$_SESSION['Lieu_Retrait'] = $reqMailCookieData['Lieu_Retrait'];

	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Commande Nº : 

			<?php

				if (isset($_GET['commande'])) {

					echo $_GET['commande'];
					
				}

			?>

		</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body>
		<?php 
			include 'include/head.php';
			include 'include/menu.php';
			include 'include/top_genre.php';
		?>
		<div class="rech_livre">
			<div style="width: 100%; text-align: center; margin: auto;">
					<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">
						INFO SUR LA COMMANDE : 
						<?php

							if (isset($_GET['commande']) AND !empty($_GET['commande'])) {

								$reqcommande = $bdd->prepare('
																SELECT Id_Commande 
																FROM commande 
																WHERE Id_Commande = ?
															');

								$reqcommande->execute([$_GET['commande']]);
								$reqcommandedata = $reqcommande->fetch();

								if ($reqcommandedata['Id_Commande'] == $_GET['commande']) {

									echo $reqcommandedata['Id_Commande'];

								}

							}

						?>

					</h3>
				</div>
				<br>
				<div class="content_commande">
					<table style="border-collapse: collapse; width: 100%;">
						<thead>
							<tr>
								<th class="nom_livre_panierx">LIVRE</th>
								<th class="nom_auteur_panierx">AUTEUR</th>
								<th class="prix_panierx">PRIX</th>
								<th class="prix_livraison_panierx">LIVRAISON</th>
								<th class="quantite_panierx">QUANTITE</th>
							</tr>
						</thead>

						<?php

							if (isset($_GET['commande']) AND !empty($_GET['commande'])) {

								$reqcommande = $bdd->prepare('
																SELECT Id_Commande, livre.Id_Auteur AS Auteur
																FROM commande 
																INNER JOIN livre
																ON livre.Id = commande.Id_Livre
																WHERE Id_Commande = ?
															');

								$reqcommande->execute([$_GET['commande']]);
								$reqcommandedata = $reqcommande->fetch();

								if ($reqcommandedata['Id_Commande'] == $_GET['commande']) {

									$reqcommandeall = $bdd->prepare('
																		SELECT 
																			commande.*, 
																			livre.Nom AS Nom_Livre, 
																			livre.Id_Auteur AS Auteur,
																			livre.Prix AS Prix_Livre,
																			livre.Prix_Livraison AS Prix_Livraison_Livre
																		FROM commande
																		INNER JOIN livre
																		ON livre.Id = commande.Id_Livre
																		WHERE Id_Commande = ?
																	');

									$reqcommandeall->execute([$reqcommandedata['Id_Commande']]);
									while ($reqcommandealldata = $reqcommandeall->fetch()) {
						?>

						<tbody>
							<tr>
								<td class="nom_livre_panier">
									<a href="achat.php?id_livre=<?php echo $reqcommandealldata['Id_Livre']; ?>">
										<?php
											echo $reqcommandealldata['Nom_Livre'];
										?>
									</a>
								</td>
								<td class="nom_auteur_panier">
									<a href="auteur.php?id_auteur=<?php echo $reqcommandealldata['Auteur'] ?>">
										<?php
											$reqauteur = $bdd->prepare('
																			SELECT Nom_Prenom
																			FROM auteur
																			WHERE Id = ?
																		');
											$reqauteur->execute([$reqcommandealldata['Auteur']]);
											$reqauteurdata = $reqauteur->fetch();
											echo $reqauteurdata['Nom_Prenom'];
										?>
									</a>
								</td>
								<td class="prix_panier">
									<span>
										<?php
											echo number_format($reqcommandealldata['Prix_Livre'],0,',',' ');
										?>
										F CFA
									</span>
								</td>
								<td class="prix_livraison_panier">
									<span>
										<?php
											echo number_format($reqcommandealldata['Prix_Livraison_Livre'],0,',',' ');
										?>
										F CFA
									</span>
								</td>
								<td class="quantite_panierc">
									<span>
										<?php
											echo $reqcommandealldata['Quantite'];
										?>
									</span>
								</td>
						</tbody>

						<?php
						}
						}
						}
						?>

					</table>
				</div>
		</div>
		<?php
			include 'include/edition.php';
			include 'include/footer.php';
		 ?>
	</body>

</html>