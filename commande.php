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
		<title>Liste De Mes Commandes</title>
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
					<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">MES COMMANDES EN COURS OU REGLER</h3>
				</div>

				<br>
				<div class="content_commande">
					<table style="border-collapse: collapse; width: 100%;">
						<thead>
							<tr>
								<th class="nom_livre_panierx">COMMANDE Nº :</th>
								<th class="prix_panierx">PRIX</th>
								<th style="width: 150px;">COMMANDÉ</th>
								<th>LIVRAISON</th>
							</tr>
						</thead>

						<?php

							if (isset($_SESSION['Id'])) {
								
								$reqcommande = $bdd->prepare('
																SELECT DISTINCT Id_Commande, Date_Commande, Prix_Total, Date_Livraison 
																FROM commande
																WHERE Id_Inscript = ?
																ORDER BY Date_Commande DESC
															');

								$reqcommande->execute([$_SESSION['Id']]);
								while ($reqcommandedata = $reqcommande->fetch()) {
						?>

									<tbody>
										<tr>
											<td class="nom_livre_commande">
												<a href="infcommande.php?commande=<?php echo $reqcommandedata['Id_Commande'];?>">
													<?php echo $reqcommandedata['Id_Commande']; ?>
												</a>
											</td>
											<td class="prix_commandes">
													<?php echo number_format($reqcommandedata['Prix_Total'],0,',',' '); ?>
													F CFA
											</td>
											<td class="date_commande_commande">
													<?php echo substr($reqcommandedata['Date_Commande'], 0, 10); ?>
											</td>
											<td class="date_livraison_commande">
													<?php
														if ($reqcommandedata['Date_Livraison'] == NULL) {
															echo '	
																	<img class="img_en_cours_et_no_ok" src="imgs/commande/en_cours.png">
																';
															echo '
																	<span class="date_livraison_no_ok">
																		En Cours
																	</span>
																';
														}
														else{
															echo '
																	<img class="img_en_cours_et_no_ok" src="imgs/commande/pret2.png">
																';
															echo '
																	<span class="date_livraison_ok">
																		' .$reqcommandedata['Date_Livraison'] .'
																	</span>
																';
														}
													?>
											</td>
										</tr>
									</tbody>

						<?php
								}

							}
						?>
						
					</table>

					<?php

						if (!isset($_SESSION['Id'])) {

							echo '
								<table style="border-collapse: collapse; width: 100%; margin: auto;">
									<tr>
										<th class="empty_panier"><br>
											<p>CONNECTEZ VOUS D\'ABORD POUR LISTÉ VOS COMMANDES<p/>
											<p>
												<a href="connexion.php">CONNECTEZ-VOUS ICI</a>
											<p/>
										</th>
									</tr>
								</table>
								';
						}
						else{

							$reqcommande = $bdd->prepare('
															SELECT Id_Inscript 
															FROM commande 
															WHERE Id_Inscript = ?
														');
								$reqcommande->execute([$_SESSION['Id']]);
								$reqcommandedata = $reqcommande->fetch();

								if ($reqcommandedata[0] != $_SESSION['Id']) {
									
									echo '
										<table style="border-collapse: collapse; width: 100%; margin: auto;">
											<tr>
												<th class="empty_panier"><br>
													<p>
														VOUS N\'AVEZ PAS ENCORE PASSÉ DE COMMANDE CHEZ NOUS !
													<p/>
													<p>
														<a href="connexion.php">À L\'ACCUEIL POUR DES ACHATS</a>
													<p/>
												</th>
											</tr>
										</table>
								';

								}
						}
					?>

				</div>
			</div>
		<?php
			include 'include/edition.php';
			include 'include/footer.php';
		 ?>
	</body>

</html>