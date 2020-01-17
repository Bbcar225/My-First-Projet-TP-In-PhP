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
		<title>EDITIONS</title>
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

			<div class="all_editions" style="margin-top: 20px;">

				<?php

					$reqalledition = $bdd->prepare('SELECT * FROM edition');
					$reqalledition->execute();

					while ($reqalleditiondata = $reqalledition->fetch()) {

				?>

				<div class="une_edition">

					<div class="img_edition">
						<img src="../imgs/editeur/<?php echo $reqalleditiondata['Logo'] ?>">
					</div>

					<div class="info_edition">

						<p style="color: blue; font-weight: bold;">
							<?php
								echo $reqalleditiondata['Id'];
							?>
						</p>
						<p>
							<?php
								echo $reqalleditiondata['Nom'];
							?>
						</p>
						<p>
							<?php
								echo $reqalleditiondata['Directeur'];
							?>
						</p>
						<p>
							<?php
								echo $reqalleditiondata['Telephone1'];
							?>
						</p>
						<p>
							<?php
								echo $reqalleditiondata['Telephone2'];
							?>
						</p>
						<p>
							<?php
								echo $reqalleditiondata['Cellulaire']; 
							?>
						</p>
						<p>
							<?php
								echo $reqalleditiondata['Fax'];
							?>
						</p>
						<p>
							<?php
								echo $reqalleditiondata['Adresse_Localite'];
							?>
						</p>
						<p>
							<a href="mailto:<?php echo $reqalleditiondata['Mail']; ?>">
								<?php
									echo $reqalleditiondata['Mail'];
								?>
							</a>
						</p>
						<p>
							<a href="<?php echo $reqalleditiondata['Site_Web'];?>" target="_blank">
								<?php
									echo $reqalleditiondata['Site_Web'];
								?>
							</a>
						</p>
						<p>
							<?php
								echo substr($reqalleditiondata['Bio'], 0, 50);
							?>
						</p>
						<p>
							<a href="modif_edition.php?id_edition=<?php echo $reqalleditiondata['Id']; ?>">
								MODIFIER
							</a>
						</p>
						<p>
							<a href="supp_edition.php?id_edition=<?php echo $reqalleditiondata['Id']; ?>">
								SUPPRIMER
							</a>
						</p>
					</div>
				</div>

				<?php
				}
				?>

			</div>

		</div>
	</body>

</html>