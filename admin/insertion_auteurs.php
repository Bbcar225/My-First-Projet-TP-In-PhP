<?php

	session_start();
	
	header("content-type:text/html; charset=iso-8859-1");

// Connexion la base de donnée
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
		
		if (!empty($_POST['nom_prenom']) AND !empty($_POST['date_naissance']) AND !empty($_POST['date_deces']) AND !empty($_POST['lieu_naissance']) AND !empty($_POST['nationnalite']) AND !empty($_POST['bio']) AND !empty($_FILES['image_auteur'])) {

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

				$errors['nomPrenomAuteurFormat'] = "Nom et Prenom vide ou format incorect !";

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
			if (isset($_FILES['image_auteur'])) {

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

			if (empty($errors)) {

				$insertion = $bdd->prepare('INSERT INTO auteur SET Nom_Prenom = ?, Date_Naissance = ?, Date_Deces = ?, Lieu_Naissance = ?, Nationnalite = ?, Bio = ?, Image = ?');
				$insertion->execute([$nomPrenomAuteur, $dateNaissance, $dateDeces, $lieuNaissance, $nationnalite, $bio, $fichier]);

				$id = $bdd->lastInsertId();

				$reqUpdatIdImage = $bdd->prepare('UPDATE auteur SET Image = ? WHERE Id = ?');
				$reqUpdatIdImage->execute([$id.$extensionFichier, $id]);

				rename($destination.$fichier, $destination.$id.$extensionFichier);

				$ok = "INSERTION REUSSIE !";

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

		<div style="margin-top: 15px;">

			<div class="formulaire">

				<form method="POST" action="#" enctype="multipart/form-data">

					<fieldset>
						
						<legend>
							INSERTION D'UN AUTEUR
						</legend>

						<br>

						<input type="text" name="nom_prenom" placeholder="NOM ET PRÉNOMS" autofocus="autofocus" value="<?php if (isset($nomPrenomAuteur)) { echo $nomPrenomAuteur; } ?>">

						<br><br>

						<input type="" name="date_naissance" placeholder="DATE DE NAISSANCE AU FORMAT AAAAA-MN-JJ" value="<?php if (isset($dateNaissance)) { echo $dateNaissance; } ?>">

						<br><br>

						<input type="text" name="date_deces" placeholder="DATE DÉCÈS AU FORMAT AAAAA-MN-JJ" value="<?php if (isset($dateDeces)) { echo $dateDeces; } ?>">

						<br><br>

						<input type="text" name="lieu_naissance" placeholder="LIEU DE NAISSANCE" value="<?php if (isset($lieuNaissance)) { echo $lieuNaissance; } ?>">

						<br><br>

						<input type="text" name="nationnalite" placeholder="NATIONNALITÉ" value="<?php if (isset($nationnalite)) { echo $nationnalite; } ?>">

						<br><br>

						<textarea name="bio" rows="5" cols="60" placeholder="Bio"><?php if (isset($bio)) {
							echo $bio;
						} ?></textarea>

						<br><br>

						<label class="img_insertion_auteur" for="img_insertion_auteur">
							IMAGE AUTEUR ( FORMAT PNG, JPEG, JPG, GIF ) :
						</label><br>
						<input type="hidden" name="MAX_FILE_SIZE" value="3000000">
						<input type="file" name="image_auteur" id="img_insertion_auteur"> <br><br>

						<input type="submit" name="valide_form" value="INSÉRER"> <br><br>

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
			</div>
		</div>
	</body>

</html>