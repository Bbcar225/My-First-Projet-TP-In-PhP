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

	if (isset($_SESSION['Poste']) AND $_SESSION['Poste'] == 'Livreur') {

		header("Location: errorsfatal.php");

	}

?>

<!DOCTYPE html>
<html>

	<head>
		<title>AUTEURS</title>
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

			<div class="all_auteurs" style="margin-top: 20px; margin-left: 10px;">

				<?php

					if (!isset($_GET['id_auteur'])) {

						$reqallauteurs = $bdd->prepare('SELECT * FROM auteur');
						$reqallauteurs->execute();

						while ($reqallauteursdata = $reqallauteurs->fetch()) {
				?>

				<div class="un_auteur">

					<div class="img_auteur">
						<img src="../imgs/auteur/
						<?php 
							echo $reqallauteursdata['Image'];
						?>
						">

					</div>

					<div class="info_auteur">
						<p style="color: blue; font-weight: bold;">
							<?php
								echo $reqallauteursdata['Id'];
							?>
						</p>
						<p>
							<?php
								echo $reqallauteursdata['Nom_Prenom'];
							?>
						</p>
						<p>
							<?php
								echo $reqallauteursdata['Date_Naissance'];
							?>
						</p>
						<p>
							<?php
								echo $reqallauteursdata['Date_Deces'];
							?>
						</p>
						<p>
							<?php
								echo $reqallauteursdata['Lieu_Naissance'];
							?>
						</p>
						<p>
							<?php
								echo $reqallauteursdata['Nationnalite'];
							?>
						</p>
						<p>
							<?php
								echo substr($reqallauteursdata['Bio'], 0, 50);
							?>
						</p>
						<p>
							<a href="modif_auteur.php?id_auteur=<?php echo $reqallauteursdata['Id']; ?>">
								MODIFIER
							</a>
						</p>
						<p>
							<a target="_blank" href="supp_auteur.php?id_auteur=<?php echo $reqallauteursdata['Id']; ?>">
								SUPPRIMER
							</a>
						</p>
					</div>

				</div>

				<?php

				}

				}
				else{

					if (isset($_GET['id_auteur'])) {

						$idauteur = $_GET['id_auteur'];

						$reqidauteur = $bdd->prepare('
														SELECT Id
														FROM auteur
														WHERE Id = ?
													');
						$reqidauteur->execute([$idauteur]);
						$reqidauteurdata = $reqidauteur->fetch();

						if ($reqidauteurdata['Id'] == $idauteur) {

							$reqinfauteur = $bdd->prepare('
															SELECT *
															FROM auteur
															WHERE Id = ?
														');
							$reqinfauteur->execute([$reqidauteurdata['Id']]);
							while ($reqinfauteurdata = $reqinfauteur->fetch()) {
				?>

				<div style="margin-left: 40%; margin-top: -45px;" class="un_auteur">

					<div class="img_auteur">
						<img src="../imgs/auteur/
						<?php 
							echo $reqinfauteurdata['Image'];
						?>
						">

					</div>

					<div class="info_auteur">
						<p style="color: blue; font-weight: bold;">
							<?php
								echo $reqinfauteurdata['Id'];
							?>
						</p>
						<p>
							<?php
								echo $reqinfauteurdata['Nom_Prenom'];
							?>
						</p>
						<p>
							<?php
								echo $reqinfauteurdata['Date_Naissance'];
							?>
						</p>
						<p>
							<?php
								echo $reqinfauteurdata['Date_Deces'];
							?>
						</p>
						<p>
							<?php
								echo $reqinfauteurdata['Lieu_Naissance'];
							?>
						</p>
						<p>
							<?php
								echo $reqinfauteurdata['Nationnalite'];
							?>
						</p>
						<p>
							<?php
								echo substr($reqinfauteurdata['Bio'], 0, 50);
							?>
						</p>
						<p>
							<a href="modif_auteur.php?id_auteur=<?php echo $reqinfauteurdata['Id']; ?>">
								MODIFIER
							</a>
						</p>
						<p>
							<a target="_blank" href="supp_auteur.php?id_auteur=<?php echo $reqinfauteurdata['Id']; ?>">
								SUPPRIMER
							</a>
						</p>
					</div>

				</div>

				<?php
				}
				}
				}
				}
				?>

			</div>

		</div>

	</body>

</html>