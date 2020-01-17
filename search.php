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

	// var_dump($_GET);

	if (!isset($_GET['objet']) AND !isset($_GET['terme'])) {

		header("Location: errosfatal.php");

	}
	elseif ($_GET['objet'] == '' AND $_GET['terme'] == '') {
		
		header("Location: errosfatal.php");

	}

	if (isset($_GET) AND !empty($_GET)) {

		if (isset($_GET['objet']) AND isset($_GET['terme'])) {

			function securisation($value){

					htmlspecialchars($value);
				 	htmlentities($value);
				 	strip_tags($value);
				 	strtolower($value);
					trim($value);
				 	return $value;

			}

			$terme = securisation($_GET['terme']);
			$terme = trim($terme);
			$objet = securisation($_GET['objet']);

			switch ($objet) {

				case 'empty':

						$emptyOject = "SÉLECTIONNER AU MOINS CE QUE VOUS CHERCHEZ .";

				break;

				case 'auteur':
					
					if (isset($terme) AND !empty($terme) AND $terme != '%') {

						$seachTerme = $bdd->prepare('SELECT Nom_Prenom FROM auteur WHERE Nom_Prenom LIKE ?');
						$seachTerme->execute(['%'.$terme.'%']);

						if ($seachTermeOk = $seachTerme->fetch()) {
							
						}
						else{

							$termeNull = 'RÉCHERCHE INTROUVABLE, VÉRIFIEZ VOTRE RÉCHERCHE .';

						}

						$searchAuteur = $bdd->prepare('
							SELECT Id, Nom_Prenom, Nationnalite, Image 
							FROM auteur
							WHERE Nom_Prenom
							LIKE ?
							ORDER BY Nom_Prenom
							');

						$searchAuteur->execute(['%'.$terme.'%']);

						$searchAuteurOk = '';

					}
					else{

						$emptyOject = "SÉLECTIONNER AU MOINS CE QUE VOUS CHERCHEZ .";

					}

				break;

				case 'edition':
				
					if (isset($terme) AND !empty($terme) AND $terme != '%') {

						$seachTerme = $bdd->prepare('SELECT Nom FROM edition WHERE Nom LIKE ?');
						$seachTerme->execute(['%'.$terme.'%']);

						if ($seachTermeOk = $seachTerme->fetch()) {

						}
						else{
							$termeNull = 'RÉCHERCHE INTROUVABLE, VÉRIFIEZ VOTRE RÉCHERCHE .';
						}

						$searchEdition = $bdd->prepare('
							SELECT *
							FROM edition
							WHERE Nom
							LIKE ?
							ORDER BY Nom
							');
						$searchEdition->execute(['%'.$terme.'%']);

						$searchEditionOk = '';

					}
					else{

						$emptyTerme = "SAISISSEZ AU MOINS CE QUE VOUS CHERCHEZ .";

					}

				break;

				case 'livre':
					
					if (isset($terme) AND !empty($terme) AND $terme != '%') {

					$seachTerme = $bdd->prepare('SELECT Nom FROM livre WHERE Nom LIKE ?');
					$seachTerme->execute(['%'.$terme.'%']);

					if ($seachTermeOk = $seachTerme->fetch()) {

					}
					else{
						$termeNull = 'RÉCHERCHE INTROUVABLE, VÉRIFIEZ VOTRE RÉCHERCHE .';
					}

					$searchLivre = $bdd->prepare('
													SELECT
														livre.*,
														auteur.Nom_Prenom AS Auteur,
														edition.Nom AS Edition
													FROM livre
													INNER JOIN auteur
													ON livre.Id_Auteur = auteur.Id
													INNER JOIN edition
													ON livre.Id_Edition = edition.Id
													WHERE livre.Nom
													LIKE ?
													ORDER BY livre.Nom
												');
					$searchLivre->execute(['%'.$terme.'%']);

					$searchLivreOk = '';

				}
				else{

					$emptyTerme = "SAISISSEZ AU MOINS CE QUE VOUS CHERCHEZ .";

				}

				break;

				case 'genre':
				
					if (isset($terme) AND !empty($terme) AND $terme != '%') {

						$seachTerme = $bdd->prepare('SELECT Nom FROM livre WHERE Genre LIKE ?');
						$seachTerme->execute(['%'.$terme.'%']);
						
						if ($seachTermeOk = $seachTerme->fetch()) {

						}
						else{
							$termeNull = 'RÉCHERCHE INTROUVABLE, VÉRIFIEZ VOTRE RÉCHERCHE .';
						}

						$searchGenre = $bdd->prepare('
							SELECT
							livre.*,
							auteur.Nom_Prenom AS Auteur,
							edition.Nom AS Edition
							FROM livre
							INNER JOIN auteur
							ON livre.Id_Auteur = Auteur.Id
							INNER JOIN edition
							ON livre.Id_Edition = Edition.Id
							WHERE Genre
							LIKE ?
							ORDER BY livre.Nom
							');
						$searchGenre->execute(['%'.$terme.'%']);

						$searchGenreOk = '';

					}
					else{

						$emptyTerme = "SAISISSEZ AU MOINS CE QUE VOUS CHERCHEZ .";

					}

				break;
				
				default:

					$emptyOject = "SÉLECTIONNER AU MOINS CE QUE VOUS CHERCHEZ .";

				break;
			}

		}

	}

?>
<!DOCTYPE html>
<html>

	<head>
		<title>Récherche</title>
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
					RECHERCHE
				</h3>

			</div>
			<br>

			<?php
				if (isset($searchAuteurOk)) {

					if (!isset($termeNull)) {

						while ($searchAuteurData = $searchAuteur->fetch()) {
			?>

							<div class="livre">

								<div class="un_livre">

									<div class="logo_livre">

										<div class="avant">
											<a href="auteur.php?id_auteur=<?php echo $searchAuteurData['Id']; ?>">
												<img src="imgs/auteur/<?php echo $searchAuteurData['Image'] ?>">
											</a>
										</div>

									</div>

									<div class="info_livre"><br><br>

										<a href="auteur.php?id_auteur=<?php echo $searchAuteurData['Id']; ?>"">
											<?php echo $searchAuteurData['Nom_Prenom']; ?>
										</a><br>

										<span style="display: block; text-align: center;">
											<?php echo $searchAuteurData['Nationnalite']; ?>
										</span>

									</div>

								</div>

							</div>

			<?php
						}

					}
					else{
						echo '
							<div style="text-align: center;">

								<p style="margin-top: 100px;  font-weight: bold; font-size: 30px; color: red;">
								' .$termeNull. '
								</p>

								<a style="font-weight: bold; font-size: 22px; color: blue;" href="index.php">
								RETOUR À LA PAGE D\'ACCEUIL
								</a>

					  		 </div>
					  		 ';

					}

				}

				elseif (isset($emptyOject)) {

			?>

					<div style="text-align: center;">

						<p style="margin-top: 100px;  font-weight: bold; font-size: 30px; color: red;">
							<?php echo $emptyOject; ?>
						</p>

						<a style="font-weight: bold; font-size: 22px; color: blue;" href="index.php">
							RETOUR À LA PAGE D'ACCEUIL
						</a>

					</div>

			<?php

				}
				elseif (isset($emptyTerme)) {

			?>

					<div style="text-align: center;">

						<p style="margin-top: 100px;  font-weight: bold; font-size: 30px; color: red;">
							<?php echo $emptyTerme; ?>
						</p>

						<a style="font-weight: bold; font-size: 22px; color: blue;" href="index.php">
							RETOUR À LA PAGE D'ACCEUIL
						</a>

					</div>

			<?php
				}

				if (isset($searchEditionOk)) {

					if (!isset($termeNull)) {

						while ($searchEditionData = $searchEdition->fetch()) {
			?>

							<div class="livre">

								<div class="un_livre">

									<div class="logo_livre">

										<div class="avant">
											<a href="edition.php?id_edition=<?php echo $searchEditionData['Id']; ?>">
												<img src="imgs/editeur/<?php echo $searchEditionData['Logo'] ?>">
											</a>
										</div>

									</div>

									<div class="info_livre">

										<a href="edition.php?id_edition=<?php echo $searchEditionData['Id']; ?>"">
											<?php echo $searchEditionData['Nom']; ?>
										</a><br>

										<a href="<?php echo $searchEditionData['Site_Web']; ?>" target="_blank">
											<?php echo $searchEditionData['Site_Web']; ?>
										</a><br>

										<a href="mailto:<?php echo $searchEditionData['Mail']; ?>">
											<?php echo $searchEditionData['Mail']; ?>
										</a>

									</div>

								</div>

							</div>

			<?php
						}

					}
					else{
						echo '
							<div style="text-align: center;">

								<p style="margin-top: 100px;  font-weight: bold; font-size: 30px; color: red;">
								' .$termeNull. '
								</p>

								<a style="font-weight: bold; font-size: 22px; color: blue;" href="index.php">
								RETOUR À LA PAGE D\'ACCEUIL
								</a>

					  		 </div>
					  		  ';

					}

				}

				if (isset($searchLivreOk)) {

					if (!isset($termeNull)) {

						while ($searchLivreData = $searchLivre->fetch()) {
			?>

							<div class="livre">

								<div class="un_livre">

									<div class="logo_livre">

										<div class="avant">
											<a href="achat.php?id_livre=<?php echo $searchLivreData['Id']; ?>">
												<img src="imgs/logo_livre/Avant/<?php echo $searchLivreData['Image_Avant'] ?>">
											</a>
										</div>

									</div>

									<div class="info_livre">

										<a href="achat.php?id_livre=<?php echo $searchLivreData['Id']; ?>">
											<?php echo $searchLivreData['Nom']; ?>
										</a>

										<a href="auteur.php?id_auteur=<?php echo $searchLivreData['Id_Auteur']; ?>">
											<?php echo $searchLivreData['Auteur']; ?>
										</a>

										<a href="genre.php?nom_genre=<?php echo $searchLivreData['Genre']; ?>">
											<?php echo $searchLivreData['Genre']; ?>
										</a>

										<a href="achat.php?id_livre=<?php echo $searchLivreData['Id']; ?>">
											Description Livre
										</a>

										<a class="addPanier" id="fond" href="addpanier.php?id_livre=<?php echo $searchLivreData['Id']; ?>">
											<img src="imgs/panier/add_panier.png" alt="Mettre dans le panier" title="METTRE DANS LE PANIER">
										</a>

									</div>

								</div>

							</div>				

			<?php
						}

					}
					else{
						echo '
							<div style="text-align: center;">

								<p style="margin-top: 100px;  font-weight: bold; font-size: 30px; color: red;">
								' .$termeNull. '
								</p>

								<a style="font-weight: bold; font-size: 22px; color: blue;" href="index.php">
								RETOUR À LA PAGE D\'ACCEUIL
								</a>

					 		 </div>
					 		 ';
					}

				}

				if (isset($searchGenreOk)) {

					if (!isset($termeNull)) {

						while ($searchGenreData = $searchGenre->fetch()) {
			?>

							<div class="livre">

								<div class="un_livre">

									<div class="logo_livre">

										<div class="avant">
											<a href="achat.php?id_livre=<?php echo $searchGenreData['Id']; ?>">
												<img src="imgs/logo_livre/Avant/<?php echo $searchGenreData['Image_Avant'] ?>">
											</a>
										</div>

									</div>

									<div class="info_livre">

										<a href="achat.php?id_livre=<?php echo $searchGenreData['Id']; ?>">
											<?php echo $searchGenreData['Nom']; ?>
										</a>

										<a href="auteur.php?id_auteur=<?php echo $searchGenreData['Id_Auteur']; ?>">
											<?php echo $searchGenreData['Auteur']; ?>
										</a>

										<a href="genre.php?nom_genre=<?php echo $searchGenreData['Genre']; ?>">
											<?php echo $searchGenreData['Genre']; ?>
										</a>

										<a href="achat.php?id_livre=<?php echo $searchGenreData['Id']; ?>">
											Description Livre
										</a>

										<a class="addPanier" id="fond" href="addpanier.php?id_livre=<?php echo $searchGenreData['Id']; ?>">
											<img src="imgs/panier/add_panier.png" alt="Mettre dans le panier" title="METTRE DANS LE PANIER">
										</a>

									</div>

								</div>

							</div>

			<?php
						}

					}
					else{

						echo '
							<div style="text-align: center;">

								<p style="margin-top: 100px;  font-weight: bold; font-size: 30px; color: red;">
								' .$termeNull. '
								</p>

								<a style="font-weight: bold; font-size: 22px; color: blue;" href="index.php">
								RETOUR À LA PAGE D\'ACCEUIL
								</a>

					  		</div>
					  		';
			}

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