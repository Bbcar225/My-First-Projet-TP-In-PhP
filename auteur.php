<?php
	
	require 'panier.class.php';
	
	$panier = new panier();

	header("content-type:text/html; charset=iso-8859-1");
	// session_start();

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
		<title>AUTEUR : </title>
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

				if (isset($_GET['id_auteur']) AND !empty($_GET['id_auteur'])) {
					
					$idauteur = $_GET['id_auteur'];
					
					$reqauteur = $bdd->prepare('SELECT Id FROM auteur WHERE Id = ?');
					$reqauteur->execute([$idauteur]);
					$reqauteurdata = $reqauteur->fetch();
					
					if ($reqauteurdata['Id'] == $idauteur) {

						$reqauteurinfall = $bdd->prepare('SELECT * FROM auteur WHERE Id = ?');
						$reqauteurinfall->execute([$idauteur]);
						$reqauteurinfalldata = $reqauteurinfall->fetch();

					?>

						<div style="width: 100%; text-align: center; margin: auto;">

							<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">
								<?php echo $reqauteurinfalldata['Nom_Prenom']; ?>
							</h3>

						</div>

						<br>

						<div class="auteur">

							<div class="photo_auteur">
								<img src="imgs/auteur/<?php echo $reqauteurinfalldata['Image']; ?>">
							</div>

							<aside class="bio_resume_auteur">

								<p class="inf_bio">
									<strong>
										Nom Et Prénoms :
									</strong>
									<?php echo $reqauteurinfalldata['Nom_Prenom']; ?>
								</p>

								<p class="inf_bio">
									<strong>
										Date Et Lieu De Naissance :
									</strong>
									<?php echo $reqauteurinfalldata['Date_Naissance']. ' à '.$reqauteurinfalldata['Lieu_Naissance']; ?>
								</p>

								<p class="inf_bio">
									<?php
										if (!empty($reqauteurinfalldata['Date_Deces'])) {
											echo '<strong>Date De Décès :</strong> ' .$reqauteurinfalldata['Date_Deces'];
										}
									?>
								</p>

								<p class="inf_bio">
									<strong>
										Nationnalité :
									</strong>
									<?php echo $reqauteurinfalldata['Nationnalite'] ?>
								</p>

								<p class="inf_bio">
									<strong>
										<a href="#livretotal">Livres En Son Compte</a>
									</strong>
								</p>

							</aside>

							<article class="bio_auteur">

								<p>
									<?php echo $reqauteurinfalldata['Bio']; ?>
								</p>

							</article>

							<div class="livre_auteur">

								<span id="livretotal">

									<?php

										$reqlivreauteur = $bdd->prepare('
																			SELECT Id,Nom
																			FROM livre
																			WHERE Id_Auteur = ?
																		');

										$reqlivreauteur->execute([$reqauteurdata['Id']]);

										while ($reqlivreauteurdata = $reqlivreauteur->fetch()) {
									?>
											<a href="achat.php?id_livre=<?php echo $reqlivreauteurdata['Id']; ?>">
												<?php echo $reqlivreauteurdata['Nom']; ?>
											</a>
									<?php
										}
									?>

								</span>

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
								AUTEUR NON TROUVÉ !
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
							AUTEUR NON TROUVÉ !
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

</html>