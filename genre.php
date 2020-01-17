<?php

	require 'panier.class.php';
	
	$panier = new panier();

	header("content-type:text/html; charset=iso-8859-1");

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

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Genre : <?php if (isset($_GET['nom_genre'])) { echo $_GET['nom_genre']; } ?></title>
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
				<?php

				// Connexion à ma BDD
					try{

						$bdd = new PDO("mysql:host=localhost;dbname=commerce", 'root', '');
						$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					}

					catch(PDOEcxeption $e){

						echo 'Echec de la connexion : ' . $e->getMessage();
					}

					if (isset($_GET['nom_genre']) AND !empty($_GET['nom_genre'])) {

						$nomgenre = $_GET['nom_genre'];

						$reqgenre = $bdd->prepare('SELECT Genre FROM livre WHERE Genre = ?');
						$reqgenre->execute([$nomgenre]);
						$reqgenredata = $reqgenre->fetch();

						if ($reqgenredata['Genre'] == $nomgenre) {

							$reqgenreinfall = $bdd->prepare('
																SELECT livre.*, auteur.Nom_Prenom 
																FROM livre
																INNER JOIN auteur
																ON livre.Id_Auteur = auteur.Id 
																WHERE Genre = ?
															');
							$reqgenreinfall->execute([$nomgenre]);
							echo '
									<div style="width: 100%; text-align: center; margin: auto;">
										<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">
											' .$nomgenre. '
										</h3>
									</div>
									<br>
								';
							while ($reqgenreinfalldata = $reqgenreinfall->fetch()) {
							?>
								<div class="livre">
									<div class="un_livre">
										<div class="logo_livre">
											<div class="avant">
												<a href="achat.php?id_livre=<?php echo $reqgenreinfalldata['Id'] ?>">
												<img src="imgs/logo_livre/Avant/<?php echo $reqgenreinfalldata['Image_Avant'] ?>">
												</a>
											</div>
										</div>
										<div class="info_livre">
											<a href="achat.php?id_livre=<?php echo $reqgenreinfalldata['Id']; ?>">
												<?php echo $reqgenreinfalldata['Nom']; ?>
											</a>
											<a href="auteur.php?id_auteur=<?php echo $reqgenreinfalldata['Id_Auteur']; ?>">
												<?php echo $reqgenreinfalldata['Nom_Prenom']; ?>
											</a>
											<a href="genre.php?nom_genre=<?php echo $reqgenreinfalldata['Genre']; ?>">
												<?php echo $reqgenreinfalldata['Genre']; ?>
											</a>
											<a href="achat.php?id_livre=<?php echo $reqgenreinfalldata['Id']; ?>">
												Description Livre
											</a>
											<a id="fond" class="addPanier" href="addpanier.php?id_livre=<?php echo $reqgenreinfalldata['Id']; ?>">
												<img src="imgs/panier/add_panier.png" alt="Mettre dans le panier" title="METTRE DANS LE PANIER">
											</a>
										</div>
									</div>
								</div>
					<?php
							}
						} else {
						?>
							<div style="width: 100%; text-align: center; margin: auto;">
								<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">ERREUR SUR LE GENRE</h3>
							</div>
							<div style="text-align: center;">
								<p style="margin-top: 100px;  font-weight: bold; font-size: 30px; color: red;">GENRE NON TROUVER, VERIFIEZ LE GENRE FOURNI !</p>
								<a style="font-weight: bold; font-size: 22px; color: blue;" href="index.php">RETOUR PAGE D'ACCEUIL ?</a>
							</div>
					<?php
						}
					} else {
					?>
						<div style="width: 100%; text-align: center; margin: auto;">
								<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">ERREUR SUR LE GENRE</h3>
						</div>
						<div style="text-align: center;">
							<p style="margin-top: 100px;  font-weight: bold; font-size: 30px; color: red;">GENRE NON TROUVER, VERIFIEZ LE GENRE FOURNI !</p>
							<a style="font-weight: bold; font-size: 22px; color: blue;" href="index.php">RETOUR PAGE D'ACCEUIL ?</a>
						</div>
					<?php
					}
				?>
			</div
		<?php
			include 'include/edition.php';
			include 'include/footer.php';
		 ?>
	</body>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

	<script type="text/javascript" src="main.js"></script>

</html>