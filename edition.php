<?php
	
	require 'panier.class.php';

	header("content-type:text/html; charset=iso-8859-1");
	
	$panier = new panier();

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
		<title>Édition : 
			<?php
				if (isset($_GET['id_edition'])) {

					$idedition = $_GET['id_edition'];

					$reqedition = $bdd->prepare('SELECT Id, Nom FROM edition WHERE Id = ?');
					$reqedition->execute([$idedition]);
					$reqeditiondata = $reqedition->fetch();

					if ($idedition == $reqeditiondata['Id']) {

						echo $reqeditiondata['Nom'];
						
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

					if (isset($_GET['id_edition']) AND !empty($_GET['id_edition'])) {
						
						$idedition = $_GET['id_edition'];

						$reqedition = $bdd->prepare('SELECT Id FROM edition WHERE Id = ?');
						$reqedition->execute([$idedition]);
						$reqeditiondata = $reqedition->fetch();

						if ($idedition == $reqeditiondata['Id']) {
							
							$reqeditioninfall = $bdd->prepare('SELECT * FROM edition WHERE Id = ?');
							$reqeditioninfall->execute([$reqeditiondata['Id']]);
							$reqeditioninfalldata = $reqeditioninfall->fetch();

				?>
						<div style="width: 100%; text-align: center; margin: auto;">
							<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">
								<?php echo $reqeditioninfalldata['Nom']; ?>
							</h3>
						</div>
						<br>
					<div class="edition_edition">
						<div class="logo_edition_page_edition">
							<img src="imgs/editeur/<?php echo $reqeditioninfalldata['Logo'] ?>">
						</div>

						<aside class="info_edition">

							<p><strong>Directeur : 
								<span>
									<?php echo $reqeditioninfalldata['Directeur']; ?>
								</span>
							</strong></p>

							<p><strong>Téléphones :

								<span>
									<?php echo $reqeditioninfalldata['Telephone1']; ?>
								</span>
								/
								<span>
									<?php echo $reqeditioninfalldata['Telephone2']; ?>
								</span>

							</strong></p>

							<p><strong>Cellulaire :
								<span>
									<?php echo $reqeditioninfalldata['Cellulaire']; ?>
								</span>
							</strong></p>

							<p><strong>Fax : 
								<span>
									<?php echo $reqeditioninfalldata['Fax']; ?>
								</span>
							</strong></p>

							<p><strong>Mail :
								<span>
									<a style="color: black;" href="mailto:<?php echo $reqeditioninfalldata['Mail']; ?>">
										<?php echo $reqeditioninfalldata['Mail']; ?>
									</a>
								</span>
							</strong></p>

							<p class="site_web_edition"><strong>Site Web :
								<span>
									<a style="color: black;" href="<?php echo $reqeditioninfalldata['Site_Web']; ?>" target="_blank">
										<?php echo $reqeditioninfalldata['Site_Web']; ?>
									</a>
								</span>
							</strong></p>

							<p><strong>Adresse : 
								<span>
									<?php echo $reqeditioninfalldata['Adresse_Localite']; ?>
								</span>
							</strong></p>

						</aside>

						<article class="bio_edition">
							<p style="margin-top: 100px;">
								<?php echo $reqeditioninfalldata['Bio']; ?>
							</p>
						</article>

						<div class="livre_edition">
							<?php
								$reqlivreedition = $bdd->prepare('SELECT Id, Nom FROM livre WHERE Id_Edition = ?');
								$reqlivreedition->execute([$idedition]);
								while ($reqlivreeditiondata = $reqlivreedition->fetch()) {
							?>
								<a href="achat.php?id_livre=<?php echo $reqlivreeditiondata['Id']; ?>">
									<?php echo $reqlivreeditiondata['Nom']; ?>
								</a>
							<?php
								}
							?>
						</div>
					</div>
				<?php  
					} else {
					?>
					<div style="width: 100%; text-align: center; margin: auto;">
						<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">ERREUR SUR LE NOM DE L'EDITION</h3>
					</div>
					<div style="text-align: center;">
						<p style="margin-top: 100px;  font-weight: bold; font-size: 30px; color: red;">EDITION NON TROUVER, VERIFIEZ LE NOM FOURNI !</p>
						<a style="font-weight: bold; font-size: 22px; color: blue;" href="index.php">RETOUR PAGE D'ACCEUIL ?</a>
					</div>
				<?php
					}
				} else {
				?>
				<div style="width: 100%; text-align: center; margin: auto;">
						<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">ERREUR SUR LE NOM DE L'EDITION</h3>
					</div>
					<div style="text-align: center;">
						<p style="margin-top: 100px;  font-weight: bold; font-size: 30px; color: red;">EDITION NON TROUVER, VERIFIEZ LE NOM FOURNI !</p>
						<a style="font-weight: bold; font-size: 22px; color: blue;" href="index.php">RETOUR PAGE D'ACCEUIL ?</a>
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