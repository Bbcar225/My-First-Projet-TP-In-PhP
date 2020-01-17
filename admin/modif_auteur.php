<?php

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

	if (isset($_POST['valide_form']) AND !empty($_POST)) {
		
		if (!empty($_POST['nom_prenom']) AND !empty($_POST['date_naissance']) AND !empty($_POST['date_deces']) AND !empty($_POST['lieu_naissance']) AND !empty($_POST['nationnalite']) AND !empty($_POST['bio'])) {

			function securisation($value){

				htmlspecialchars($value);
			 	htmlentities($value);
			 	return $value;

			}

			$nomPrenomAuteur = securisation($_POST['nom_prenom']);
			$dateNaissance = securisation($_POST['date_naissance']);
			$dateDeces = securisation($_POST['date_deces']);
			$lieuNaissance = securisation($_POST['lieu_naissance']);
			$nationnalite = securisation($_POST['nationnalite']);
			$bio = securisation($_POST['bio']);
			$errors = array();

		// Vérification sur le nom et prénom
			if (empty($nomPrenomAuteur) OR !preg_match('/^[a-zA-Z-éèêçîïâô ]+$/', $nomPrenomAuteur)) {

				$errors['nomPrenomAuteurFormal'] = "Nom et Prenom vide ou format incorect !";

			}
			else {

				$sizename = strlen($nomPrenomAuteur);

				if ($sizename >= 225) {

					$errors['nomPrenomAuteurSize'] = "Le nom et prénom est trop long !";

				}

			}

		// Vérification sur la date de naissance
			if (empty($dateNaissance) OR !preg_match("/^(\d{4}\-)(\d{1,2}\-)(\d{1,2})$/", $dateNaissance)) {

		 		$errors['dateNaissanceFormat'] = "Date de naissance vide ou format invalide !";

			}


		// Vérification sur la date de décès
			if (empty($dateDeces) OR !preg_match("/^(\d{4}\-)(\d{1,2}\-)(\d{1,2})$/", $dateDeces)) {

		 		$errors['dateDecesFormat'] = "Date de décès vide ou format invalide !";

			}

		// Vérification sur le lieu de naissance
			if (empty($lieuNaissance) OR !preg_match('/^[a-zA-Z-éèêçîïô\'() ]+$/', $lieuNaissance)) {

				$errors['lieuNaissanceFormat'] = "Lieu de naissance vide ou format incorect !";

			}
			else {

				$sizelieunaissance = strlen($lieuNaissance);

				if ($sizelieunaissance >= 225) {

					$errors['lieuNaissanceSize'] = "Le lieu de naissance est trop long !";

				}

			}

		// Vérification sur la nationnalité
			if (empty($nationnalite) OR !preg_match('/^[ a-zA-Z-éèêçîï]+$/', $nationnalite)) {

				$errors['nationnaliteFormat'] = "Nationnalité vide ou format incorect !";

			}
			else{

				$sizenationnalite = strlen($nationnalite);

				if ($sizenationnalite >= 255) {
					
					$errors['nationnaliteSize'] = "Nationnalité trop long !";

				}

			}

		// Vérification sur l'image de l'auuteur
			if ($_FILES['image_auteur']['error'] == 4) {

			}
			else{

				$destination = '../imgs/auteur/';
				$fichier = basename($_FILES['image_auteur']['name']);
				$tailleMaxi = 2000000;
				$tailleFichier = filesize($_FILES['image_auteur']['tmp_name']);
				$extensionAutorise = array('.png', '.jpg', '.jpeg', '.gif');
				$extensionFichier = strrchr($_FILES['image_auteur']['name'], '.');

				if (!in_array($extensionFichier, $extensionAutorise)) {

					$errors['imageExtension'] = "Erreur sur l'extension du fichier ou pas de fichier chargé !";

				}

				if ($tailleFichier > $tailleMaxi) {

					$errors['imageSize'] = "Taille du trop grande !";

				}

				if (!isset($errors['imageExtension']) AND !isset($errors['imageSize'])) {

					$fichier = strtr($fichier, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
					$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

					if (move_uploaded_file($_FILES['image_auteur']['tmp_name'], $destination.$fichier)) {

					}
					else{
						$errors['imageUpload'] = "Echec de téléchargement de l'image !";
					}

				}

			}

			if (isset($_GET) AND !empty($_GET)) {

				if (isset($_GET['id_auteur'])) {

					$idauteur = $_GET['id_auteur'];
							
					$reqidauteur = $bdd->prepare('SELECT Id FROM auteur WHERE Id = ?');
					$reqidauteur->execute([$idauteur]);
					$reqidauteurdata = $reqidauteur->fetch();

					if ($idauteur == $reqidauteurdata['Id']) {

						$Req_all_inf_auteur = $bdd->prepare('SELECT * FROM auteur WHERE Id = ?');
						$Req_all_inf_auteur->execute([$reqidauteurdata['Id']]);
						$Req_all_inf_auteur_data = $Req_all_inf_auteur->fetch();
					}
				}
			}

			if (empty($errors)) {

				$updatedata = $bdd->prepare('UPDATE auteur SET Nom_Prenom = ?, Date_Naissance = ?, Date_Deces = ?, Lieu_Naissance = ?, Nationnalite = ?, Bio = ?, Image = ? WHERE Id = ?');
				$updatedata->execute([$nomPrenomAuteur, $dateNaissance, $dateDeces, $lieuNaissance, $nationnalite, $bio, $Req_all_inf_auteur_data['Image'], $Req_all_inf_auteur_data['Id']]);

				if ($_FILES['image_auteur']['error'] == 4) {

				}
				else{

					$id = $Req_all_inf_auteur_data['Id'];

					$reqUpdatIdImage = $bdd->prepare('UPDATE auteur SET Image = ? WHERE Id = ?');
					$reqUpdatIdImage->execute([$id.$extensionFichier, $id]);

					rename($destination.$fichier, $destination.$id.$extensionFichier);
				}

				$ok = "MISE À JOUR REUSSIE !";

				header("Location: auteurs.php");

			}

		}
		else {
			$errors['errorall'] = 'Tous les champs doivent être rempli !';
		}

	}

?>
<!DOCTYPE html>
<html>

	<head>
		<title>INSERTION AUTEURS</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body style="background-color: rgba(152, 245, 255, 0.17);">

		<?php
			include 'include/head.php';
		?>

		<div>
			
			<div style="height: 650px;" class="formulaire">

				<?php

					if (isset($_GET) AND !empty($_GET)) {
						
						if (isset($_GET['id_auteur'])) {

							$idauteur = $_GET['id_auteur'];
							
							$reqidauteur = $bdd->prepare('SELECT Id FROM auteur WHERE Id = ?');
							$reqidauteur->execute([$idauteur]);
							$reqidauteurdata = $reqidauteur->fetch();

							if ($idauteur == $reqidauteurdata['Id']) {

								$Req_all_inf_auteur = $bdd->prepare('SELECT * FROM auteur WHERE Id = ?');
								$Req_all_inf_auteur->execute([$reqidauteurdata['Id']]);
								$Req_all_inf_auteur_data = $Req_all_inf_auteur->fetch();
				?>

				<form method="POST" action="#" enctype="multipart/form-data">

					<fieldset>

						<legend>
							MODIFICATION D'UN AUTEUR
						</legend> <br>

						<p>
							<?php
								echo $Req_all_inf_auteur_data['Id'];
							?>
						</p>

						<input type="text" name="nom_prenom" placeholder="NOM ET PRÉNOMS" value="<?php echo $Req_all_inf_auteur_data['Nom_Prenom']; ?>"> <br><br>

						<input type="text" name="date_naissance" placeholder="DATE DE NAISSANCE" value="<?php echo $Req_all_inf_auteur_data['Date_Naissance']; ?>"> <br><br>

						<input type="text" name="date_deces" placeholder="DATE DÉCÈS" value="<?php echo $Req_all_inf_auteur_data['Date_Deces']; ?>"> <br><br>

						<input type="text" name="lieu_naissance" placeholder="LIEU DE NAISSANCE" value="<?php echo $Req_all_inf_auteur_data['Lieu_Naissance']; ?>"> <br><br>

						<input type="" name="nationnalite" placeholder="NATIONNALITÉ" value="<?php echo $Req_all_inf_auteur_data['Nationnalite']; ?>"> <br><br>

						<textarea name="bio" rows="5" cols="60" placeholder="Bio"><?php echo $Req_all_inf_auteur_data['Bio'];
						?></textarea> <br><br>

						<label class="img_avant" for="img_av">
							IMAGE AUTEUR ( FORMAT PNG, JPEG, JPG, GIF ) :
						</label> <br>
						<input type="hidden" name="MAX_FILE_SIZE" value="3000000">
						<input type="file" name="image_auteur" id="img_av"> <br><br>

						<input type="submit" name="valide_form" value="MÀJ"> <br><br>

						<div style="color:red; font-weight: bold;">
	   						<?php if(isset($errors)): ?>
	     						<ul>
	       							<?php foreach($errors as $error): ?>
	         							<li><?php echo $error; ?></li><br>
	       		 					<?php endforeach; ?>
	     						</ul>
	    					<?php endif; ?>
	    					<?php if(isset($GLOBALS['ok'])): ?>
	     						<ul>
	         						<li><?php echo $GLOBALS['ok']; ?></li><br>
	     						</ul>
	    					<?php endif; ?>
  						</div>

					</fieldset>

				</form>

				<?php
				}
				else{
					echo "AUTEUR INNEXSTANT !";
				}

				}
				else{
					echo "AUTEUR INNEXSTANT !";
				}

				}
				else{
					echo "AUTEUR INNEXSTANT !";
				}
				
				?>

			</div>

		</div>

	</body>

</html>