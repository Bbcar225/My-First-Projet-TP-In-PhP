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
		<title>Achat du livre : 
			<?php if (isset($_GET['id_livre'])) {

					$idlivre = $_GET['id_livre'];
					
					$reqbooks = $bdd->prepare('SELECT Id, Nom FROM livre WHERE Id = ?');
					$reqbooks->execute([$idlivre]);
					$reqbooksdataname = $reqbooks->fetch();

					if ($idlivre == $reqbooksdataname['Id']) {

						echo $reqbooksdataname['Nom'];

					}
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

			<?php

				if (isset($_GET['id_livre']) AND !empty($_GET['id_livre'])) {

					$idlivre = $_GET['id_livre'];
					
					$reqbooks = $bdd->prepare('SELECT Id FROM livre WHERE Id = ?');
					$reqbooks->execute([$idlivre]);
					$reqbooksdataname = $reqbooks->fetch();

					if ($idlivre == $reqbooksdataname['Id']) {

						$reqbooksinfall = $bdd->prepare('
															SELECT 
																livre.*,
																auteur.Nom_Prenom AS Auteur,
																edition.Nom AS Edition
															FROM livre
															INNER JOIN auteur
															ON livre.Id_Auteur = auteur.Id
															INNER JOIN edition
															ON livre.Id_Edition = edition.Id
															WHERE livre.Id = ?
														');
						
						$reqbooksinfall->execute([$idlivre]);
						$reqbooksdatainfall = $reqbooksinfall->fetch();
						
			?>

						<div style="width: 100%; text-align: center; margin: auto;">

							<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">ACHAT DU LIVRE :
								<?php echo $reqbooksdatainfall['Nom']; ?>	
							</h3>

						</div>

						<br>

						<div class="bodyachat">

							<div class="logo_av_ar">

								<div class="logo_avant_livre">
									<img src="imgs/logo_livre/Avant/<?php echo $reqbooksdatainfall['Image_Avant']; ?>">
								</div>

								<div class="logo_arriere_livre">
									<img src="imgs/logo_livre/Arriere/<?php echo $reqbooksdatainfall['Image_Arriere']; ?>">
								</div>

							</div>

							<div class="info_livre_description_livre">

								<div class="info_livre_achat">

									<a class="addPanier" title="METTRE DANS LE PANIER" href="addpanier.php?id_livre=<?php echo $reqbooksdatainfall['Id']; ?>">
										METTRE DANS LE PANIER : 
										<img src="imgs/panier/add_panier.png" alt="mettre dans le panier" title="METTRE DANS LE PANIER">
									</a>

									<br>

									<span>
										PRIX : <span class="prix"> <?php echo number_format($reqbooksdatainfall['Prix'],0,',',' '); ?> F CFA </span>
									</span>

									<br>

									<span>
										PRIX DE LIVRAISON : <span class="prix"> <?php echo number_format($reqbooksdatainfall['Prix_Livraison'],0,',',' '); ?> F CFA </span>
									</span>

									<br>
									<span>
										LIVRE : <span class="nom_livre_page_achat"> <?php echo $reqbooksdatainfall['Nom']; ?> </span>
									</span>

									<br>

									<span>
										AUTEUR : <a class="nom_auteur_page_achat" href="auteur.php?id_auteur=<?php echo $reqbooksdatainfall['Id_Auteur']; ?>"> <?php echo $reqbooksdatainfall['Auteur']; ?> </a>
									</span>

									<br>

									<span>
										EDITION : <a class="nom_auteur_page_achat" href="edition.php?id_edition=<?php echo $reqbooksdatainfall['Id_Edition']; ?>"> <?php echo $reqbooksdatainfall['Edition']; ?> </a>
									</span>

									<br>

									<span>
										GENRE LITTERAIRE : <a class="nom_auteur_page_achat" href="genre.php?nom_genre=<?php echo $reqbooksdatainfall['Genre'] ?>"> <?php echo $reqbooksdatainfall['Genre']; ?> </a>
									</span>

									<br>

									<a class="resume" href="#resumelivre">
										RÉSUMÉ DU LIVRE
									</a>

									<br>

								</div>

								<div class="description_livre">

									<p id="resumelivre">
										<?php echo $reqbooksdatainfall['Resume']; ?>
									</p>

								</div>
						</div>

					</div>

				<?php 

					}
					else {

					?>
						<div style="width: 100%; text-align: center; margin: auto;">

							<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">
								ERREUR
							</h3>

						</div>

						<div style="text-align: center;">

							<p style="margin-top: 100px;  font-weight: bold; font-size: 30px; color: red;">
								LIVRE NON TROUVÉ !
							</p>

							<a style="font-weight: bold; font-size: 22px; color: blue;" href="index.php">
								RETOUR À LA PAGE D'ACCEUIL
							</a>

						</div>

				<?php

					}

					}
					else {

				?>

						<div style="width: 100%; text-align: center; margin: auto;">

							<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">
								ERREUR
							</h3>

						</div>

						<div style="text-align: center;">

							<p style="margin-top: 100px;  font-weight: bold; font-size: 30px; color: red;">
								LIVRE NON TROUVÉ !
							</p>

							<a style="font-weight: bold; font-size: 22px; color: blue;" href="index.php">
								RETOUR À LA PAGE D'ACCEUIL
							</a>
						</div>

				<?php
				}
				?>

			</div>

		<?php
			include 'include/edition.php';
			include 'include/footer.php';
		 ?>
		 
	</body>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

		<script type="text/javascript" src="main.js"></script>

</html>