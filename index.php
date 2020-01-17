<?php

	require 'panier.class.php';
	
	$panier = new panier();

	header("content-type:text/html; charset=iso-8859-1");

// Connexion à ma BDD
	try{

		$bdd = new PDO("mysql:host=localhost;dbname=commerce", 'root', '');
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
		<title>Libre Livre</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="shortcut icon" href="imgs/favicon.ico" type="image/x-icon" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
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

					BIENVENUE
					<?php
						if (isset($_SESSION['Id'])) {

							if ($_SESSION['Sexe'] == 'Homme') {

								echo 'MR ' .$_SESSION['Nom']. ' ' .$_SESSION['Prenom'];

							}
							else {

								echo 'MME ' .$_SESSION['Nom']. ' ' .$_SESSION['Prenom'];

							}
						}
						else {

							echo "";

						}
					?>

				</h3>

			</div>

				<br>

				<?php

				$reqbooks = $bdd->prepare('
											SELECT livre.*, auteur.Nom_Prenom AS Auteur
											FROM livre 
											INNER JOIN auteur 
											ON livre.Id_Auteur = auteur.Id 
											ORDER BY RAND()'
										);
				
				$reqbooks->execute();

				while ($reqbooksall = $reqbooks->fetch()) {

				?>
				<div class="livre">

					<div class="un_livre">

						<div class="logo_livre">

							<div class="avant">
								<a href="achat.php?id_livre=<?php echo $reqbooksall['Id']; ?>">
									<img src="imgs/logo_livre/Avant/<?php echo $reqbooksall['Image_Avant'] ?>">
									<!-- <img class="price" title="<?php echo $reqbooksall['Prix'] ?>" src="imgs/logo_livre/prix.png"> -->
								</a>
							</div>

						</div>

						<div class="info_livre">

							<a href="achat.php?id_livre=<?php echo $reqbooksall['Id']; ?>">
								<?php echo $reqbooksall['Nom']; ?>
							</a>

							<a href="auteur.php?id_auteur=<?php echo $reqbooksall['Id_Auteur']; ?>">
								<?php echo $reqbooksall['Auteur']; ?>
							</a>

							<a href="genre.php?nom_genre=<?php echo $reqbooksall['Genre']; ?>">
								<?php echo $reqbooksall['Genre']; ?>
							</a>

							<a href="achat.php?id_livre=<?php echo $reqbooksall['Id']; ?>">
								Description Livre
							</a>
							
							<a id="fond" class="addPanier" href="addpanier.php?id_livre=<?php echo $reqbooksall['Id']; ?>">
								<img src="imgs/panier/add_panier.png" alt="Mettre dans le panier" title="METTRE DANS LE PANIER">
							</a>

						</div>

					</div>

				</div>

				<?php
				}
				?>

				</div>

			</div>

		<?php
			include 'include/edition.php';
			include 'include/footer.php';
		 ?>

	</body>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

	<script type="text/javascript" src="main.js"></script>
		
</html>