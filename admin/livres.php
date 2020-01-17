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
		<title>Livre</title>
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

			<br>

			<?php

				if (!isset($_GET['id_livre'])) {

					$reqallbooks = $bdd->prepare('
													SELECT 
														livre.*,
														auteur.Nom_Prenom AS Auteur,
														edition.Nom AS Edition
													FROM livre
													INNER JOIN auteur
													ON livre.Id_Auteur = auteur.Id
													INNER JOIN edition
													ON livre.Id_Edition = edition.Id
												');
					
					$reqallbooks->execute();

					while ($reqallbooksdata = $reqallbooks->fetch()) {

			?>

			<div class="all_livres">

				<div class="un_livre">

					<div class="img_avant_et_arriere">
						<img src="../imgs/logo_livre/Avant/<?php echo $reqallbooksdata['Image_Avant']; ?>">
						<img src="../imgs/logo_livre/Arriere/<?php echo $reqallbooksdata['Image_Arriere']; ?>">
					</div>

					<div class="inf_livre">
						<p style="color: blue; font-weight: bold;">
							<?php
								echo $reqallbooksdata['Id'];
							?>
						</p>
						<p>
							<?php
								echo $reqallbooksdata['Nom'];
							?>
						</p>
						<p>
							<a href="auteurs.php?id_auteur=<?php echo $reqallbooksdata['Id_Auteur']; ?>">
								<?php
									echo $reqallbooksdata['Auteur'];
								?>
							</a>
						</p>
						<p>
							<a href="editions.php?id_edition=<?php echo $reqallbooksdata['Id_Edition']; ?>">
								<?php
								echo $reqallbooksdata['Edition'];
							?>
							</a>
						</p>
						<p>
							<?php
								echo $reqallbooksdata['Genre'];
							?>
						</p>
						<p>
							<?php
								echo number_format($reqallbooksdata['Prix'],0,',',' ');
							?>
							F CFA
						</p>
						<p>
							<?php
								echo number_format($reqallbooksdata['Prix_Livraison'],0,',',' ');
							?>
							F CFA
						</p>
						<p>
							<?php
								echo substr($reqallbooksdata['Resume'], 0, 25);
							?>
						</p>
						<p>
							<a href="modif_livre.php?id_livre=<?php echo $reqallbooksdata['Id']; ?>">
								MODIFIER
							</a>
						</p>
						<p>
							<a href="supp_livre.php?id_livre=<?php echo $reqallbooksdata['Id']; ?>">
								SUPPRIMER
							</a>
						</p>

					</div>

				</div>

			</div>

			<?php
			}
			}
			else{

				if (isset($_GET) AND !empty($_GET)) {
					
					if (isset($_GET['id_livre'])) {

						$idlivre = $_GET['id_livre'];

						$reqlivre = $bdd->prepare('SELECT Id FROM livre WHERE Id = ?');
						$reqlivre->execute([$idlivre]);
						$reqlivreiddata = $reqlivre->fetch();

						if ($idlivre == $reqlivreiddata['Id']) {
							
							$reqlivreallinf = $bdd->prepare('
																SELECT
																	livre.*,
																	auteur.Nom_Prenom AS Auteur,
																	edition.Nom AS Edition
																FROM livre
																INNER JOIN edition
																ON edition.Id = livre.Id_Edition
																INNER JOIN auteur
																ON auteur.Id = livre.Id_Auteur
																WHERE livre.Id = ?
															');
							
							$reqlivreallinf->execute([$reqlivreiddata['Id']]);

							while ($reqlivreallinfdata = $reqlivreallinf->fetch()) {
			?>

			<div style="margin-left: 40%;" class="un_livre">

					<div class="img_avant_et_arriere">
						<img src="../imgs/logo_livre/Avant/<?php echo $reqlivreallinfdata['Image_Avant']; ?>">
						<img src="../imgs/logo_livre/Arriere/<?php echo $reqlivreallinfdata['Image_Arriere']; ?>">
					</div>

					<div class="inf_livre">
						<p style="color: blue; font-weight: bold;">
							<?php
								echo $reqlivreallinfdata['Id'];
							?>
						</p>
						<p>
							<?php
								echo $reqlivreallinfdata['Nom'];
							?>
						</p>
						<p>
							<a href="auteurs.php?id_auteur=<?php echo $reqlivreallinfdata['Id_Auteur']; ?>">
								<?php
									echo $reqlivreallinfdata['Auteur'];
								?>
							</a>
						</p>
						<p>
							<a href="editions.php?id_edition=<?php echo $reqlivreallinfdata['Id_Edition']; ?>">
								<?php
								echo $reqlivreallinfdata['Edition'];
							?>
							</a>
						</p>
						<p>
							<?php
								echo $reqlivreallinfdata['Genre'];
							?>
						</p>
						<p>
							<?php
								echo number_format($reqlivreallinfdata['Prix'],0,',',' ');
							?>
							F CFA
						</p>
						<p>
							<?php
								echo number_format($reqlivreallinfdata['Prix_Livraison'],0,',',' ');
							?>
							F CFA
						</p>
						<p>
							<?php
								echo substr($reqlivreallinfdata['Resume'], 0, 25);
							?>
						</p>
						<p>
							<a href="modif_livre.php?id_livre=<?php echo $reqlivreallinfdata['Id']; ?>">
								MODIFIER
							</a>
						</p>
						<p>
							<a href="supp_livre.php?id_livre=<?php echo $reqlivreallinfdata['Id']; ?>">
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

			}

			?>

		</div>

	</body>

</html>