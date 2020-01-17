<?php

	header("content-type:text/html; charset=iso-8859-1");

	require 'panier.class.php';

	$panier = new panier();

// Connexion à ma BDD
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

	if (isset($_GET['decremente'])) {

		$panier->decremente($_GET['decremente']);
		header("Location: panier.php");

	}

	if (isset($_GET['incremente'])) {

		$panier->incremente($_GET['incremente']);
		header("Location: panier.php");

	}

	$ids = array_keys($_SESSION['panier']);

	if (empty($ids) AND isset($_SESSION['panier']) AND empty($_SESSION['panier'])) {

		$reqinfallbooksdata = array();

	}
	else{

		$reqinfallbooks = $bdd->prepare('SELECT * FROM livre WHERE Id IN ('.implode(',',$ids).')');	
		$reqinfallbooks->execute();

		if (isset($_POST['validecommande']) AND $_SESSION['panier']) {

			$id = rand(00000000,99999999);
			$idcommande = $id;

			if (isset($idcommande)) {

				$reqIdCommande = $bdd->prepare('SELECT Id_Commande FROM commande');
				$reqIdCommande->execute();
				$reqIdCommandeData = $reqIdCommande->fetch();

				if ($idcommande == $reqIdCommandeData['Id_Commande']) {

					$id = rand(00000000,99999999);
					$idcommande = $id;

				}

			}

			if (isset($_SESSION['Nom']) AND $_SESSION['Prenom']) {

				while ($reqinfallbooksdata = $reqinfallbooks->fetch()) {

				$datecommande = date("Y-m-d H:i:s");
				$datelivraison = null;
				$livraison = '';

					if (isset($_POST[$reqinfallbooksdata['Id']])) {

						$livraison = 'Avec';

					}
					else{

						$livraison = 'Sans';

					}

						$insertion = $bdd->prepare('INSERT INTO commande SET Id_Livre = ?, Id_Inscript = ?, Id_Commande = ?, Date_Commande = ?, Date_Livraison = ?, Avec_Sans_LIvraison = ?, Quantite = ?, Prix_Total = ?, Prix_Total_Sans_Livraison = ?');

						$insertion->execute([$reqinfallbooksdata['Id'], $_SESSION['Id'], $idcommande, $datecommande, $datelivraison, $livraison, $_SESSION['panier'][$reqinfallbooksdata['Id']], $panier->total1(), $panier->total2()]);

				}
				
				unset($_SESSION['panier']);
				header("Location: commande.php");
				
	}
	else{

		$_SESSION['error_connexion'] = 'VEUILLEZ-VOUS CONNECTÉ D\'ABORD POUR VALIDER VOTRE COMMANDE !';

	}
	}
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Panier</title>
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
			<form method="POST" action="#">

				<div style="width: 100%; text-align: center; margin: auto;">
					<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">MON PANIER D'ACHAT</h3>
				</div>

				<br>

				<div class="panier_achat">

					<?php

						if (isset($_POST['validecommande']) AND !isset($_SESSION['Nom'])) {

							echo '<p class="not_connex">' .$_SESSION['error_connexion']. '
										<br><br>
									<a href="connexion.php">
										CONNECTEZ-VOUS ICI !
									</a>
								  </p>';

							unset($_SESSION['error_connexion']);
							
						}

					?>

					<table style="border-collapse: collapse;">
						<caption>CONTENU DU PANIER</caption>
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

							$ids = array_keys($_SESSION['panier']);

							if (empty($ids) AND isset($_SESSION['panier']) AND empty($_SESSION['panier'])) {

								$reqinfallbooksdata = array();
								$_SESSION['panier_vide'] = "VOTRE PANIER EST VIDE !";

							}
							else{

								$reqinfallbooks = $bdd->prepare('
																	SELECT livre.*, auteur.Nom_Prenom 
																	FROM livre 
																	INNER JOIN auteur
																	ON livre.Id_Auteur = auteur.Id
																	WHERE livre.Id 
																	IN ('.implode(',',$ids).') 
																');
								$reqinfallbooks->execute();

								while ($reqinfallbooksdata = $reqinfallbooks->fetch()) {
						?>
							
						<tbody>
							<tr>
								<td class="nom_livre_panier">
									<a href="achat.php?id_livre=<?php echo $reqinfallbooksdata['Id']; ?>">
										<?php
											echo $reqinfallbooksdata['Nom'];
										?>
									</a>
								</td>
								<td class="nom_auteur_panier">
									<a href="auteur.php?id_auteur=<?php echo $reqinfallbooksdata['Id_Auteur']; ?>">
										<?php
											echo $reqinfallbooksdata['Nom_Prenom'];
										?>		
									</a>
								</td>
								<td class="prix_panier">
									<?php
										echo number_format($reqinfallbooksdata['Prix'],0,',',' ');
									?> F CFA
								</td>
								<td class="prix_livraison_panier">
									<label for="<?php echo $reqinfallbooksdata['Id'] ?>">
										<?php
											echo number_format($reqinfallbooksdata['Prix_Livraison'],0,',',' ');
										?> F CFA
									</label>
									<input class="input_livraison" type="checkbox" checked="checked" name="<?php echo $reqinfallbooksdata['Id']; ?>" id="<?php echo $reqinfallbooksdata['Id'] ?>">
								</td>
								<td class="quantite_panier">
									<span>
										<?php
											echo $_SESSION['panier'][$reqinfallbooksdata['Id']];
										?>
									</span>
									<a href="panier.php?incremente=<?php echo $reqinfallbooksdata['Id'] ?>">
										<img src="imgs/panier/Increment.png">
									</a>
									<a href="panier.php?decremente=<?php echo $reqinfallbooksdata['Id'] ?>">
										<img src="imgs/panier/Decremente.png">
									</a>
								</td>
							</tr>
						</tbody>

						<?php
						}
						}
						?>

					</table>

					<?php

						if (isset($_SESSION['panier_vide']) AND empty($_SESSION['panier'])) {

							echo '
									<table style="border-collapse: collapse; width: 100%; margin: auto;">
										<tr>
											<th class="empty_panier">
												<br>
												<img class="img_empty_panier" src="imgs/panier/empty_panier.png">
												<br>
												'.$_SESSION['panier_vide'].'
												<br>
												<p>
													<a href="index.php">À L\'ACCUEIL POUR DES ACHATS</a>
												<p/>
											</th>
											
										</tr>
									</table>
								';

								unset($_SESSION['panier']);
						}
					?>

					<?php if (!empty($_SESSION['panier'])): ?>

						<br>
						<table style="border-collapse: collapse; width: 70%; margin: auto;">
							<tr>
								<th class="prix_totalx">PRIX TOTAL + PRIX LIVRAISON</th>
								<td class="prix_panier">
									<?php
										echo number_format($panier->total1(),0,',',' ');
									?> F CFA
								</td>
							</tr>
							<tr>
								<th class="prix_no_totalx">PRIX TOTAL - PRIX LIVRAISON</th>
								<td class="prix_livraison_panier">
									<?php
										echo number_format($panier->total2(),0,',',' ');
									?> F CFA
								</td>
							</tr>
						</table>

					<?php endif ?>

					<br>

					<?php if (!empty($_SESSION['panier'])): ?>

						<a class="valider_commande" href="commande.php">
							<input class="input_panier" type="submit" name="validecommande" value="&nbsp&nbsp&nbsp&nbsp VALIDER LA COMMANDE">
						</a>

					<?php endif ?>

				</div>
			</form>

			</div>

			<?php
				include 'include/edition.php';
				include 'include/footer.php';
			 ?>

	</body>

</html>