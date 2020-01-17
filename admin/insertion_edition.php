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

	if (isset($_POST) AND !empty($_POST)) {
		
		if (isset($_POST['valide_form'])) {
			
			if (!empty($_POST['nom']) AND !empty($_POST['directeur']) AND !empty($_POST['telephone1']) AND !empty($_POST['telephone2']) AND !empty($_POST['cellulaire']) AND !empty($_POST['fax']) AND !empty($_POST['adresse']) AND !empty($_POST['mail']) AND !empty($_POST['site_web']) AND !empty($_POST['bio']) AND !empty($_FILES['logo_edition'])) {

				function securisation($value){

					htmlspecialchars($value);
				 	htmlentities($value);
				 	strip_tags($value);
				 	return $value;

				}

				$nom = securisation($_POST['nom']);
				$directeur = securisation($_POST['directeur']);
				$telephone1 = securisation($_POST['telephone1']);
				$telephone2 = securisation($_POST['telephone2']);
				$cellulaire = securisation($_POST['cellulaire']);
				$fax = securisation($_POST['fax']);
				$adresse = securisation($_POST['adresse']);
				$mail = securisation($_POST['mail']);
				$site_web = securisation($_POST['site_web']);
				$bio = securisation($_POST['bio']);
				$errors = array();

			// Vérification sur le nom
				if (empty($nom) OR !preg_match('/^[a-zA-Z-éèêçîïâô1234567890& ]+$/', $nom)) {
					
					$errors['nomFormat'] = "Nom vide ou format incorect !";

				}
				else{

					$sizename = strlen($nom);

					if ($sizename >= 225) {

						$errors['nomSize'] = "Le nom est trop long !";

					}

				}

			// Vérification sur le directeur
				if (empty($directeur) OR !preg_match('/^[a-zA-Z-éèêçîïâô ]+$/', $directeur)) {
					
					$errors['directeurFormat'] = "Nom du directeur vide ou format incorect !";

				}
				else{

					$sizedirector = strlen($directeur);

					if ($sizedirector >= 225) {

						$errors['directeurSize'] = "Nom du directeur est trop long !";

					}

				}

			// Vérification sur le téléphone1
				if (empty($telephone1) OR !preg_match('/^[0-9]+$/', $telephone1)) {

					$errors['telephone1Format'] = "Numéro de téléphone 1 vide ou format invalide !";

				}
				else {

					$sizetelephone1 = strlen($telephone1);

					if (($sizetelephone1 < 8) OR ($sizetelephone1 > 20)) {

						$errors['telephone1Size'] = "Le numéro de téléphone 1 est trop cours ou trop long !";

					}

				}

			// Vérification sur le téléphone 2
				if (!empty($telephone2) AND !preg_match('/^[0-9]+$/', $telephone2)) {

					$errors['telephone2Format'] = "Numéro de téléphone 2 vide ou format invalide !";

				}
				else {

					$sizetelephone2 = strlen($telephone2);

					if (($sizetelephone2 < 8) OR ($sizetelephone2 > 20)) {

						$errors['telephone2Size'] = "Le numéro de téléphone 2 est trop cours ou trop long !";

					}

				}

			// Vérification sur le céllulaire
				if (!empty($cellulaire) AND !preg_match('/^[0-9]+$/', $cellulaire)) {

					$errors['cellulaireFormat'] = "Céllulaire vide ou format invalide !";

				}
				else {

					$sizecellulaire = strlen($cellulaire);

					if (($sizecellulaire) < 8 OR ($sizecellulaire > 20)) {

						$errors['cellulaireSize'] = "Céllulaire est trop cours ou trop long !";

					}

				}

			// Vérification sur le fax
				if (!empty($fax) AND !preg_match('/^[0-9]+$/', $fax)) {

					$errors['faxFormat'] = "Fax vide ou format invalide !";

				}
				else {

					$sizefax = strlen($cellulaire);

					if (($sizefax) < 8 OR ($sizefax > 20)) {

						$errors['faxSize'] = "Fax est trop cours ou trop long !";

					}

				}

			// Vérification sur l'adresse
				if (empty($adresse)) {
					
					$errors['adresseFormat'] = "Adresse vide !";

				}
				else{

					$sizeadresse = strlen($adresse);

					if ($sizeadresse >= 255) {
						
						$errors['adresseSize'] = "Adresse trop long !";

					}

				}

			// Vérification sur le mail
				if (empty($mail) OR !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
				
					$errors['mailFormat'] = "Mail vide ou format invalide !";

				}

			// Vérification sur le site web
				if (empty($site_web) OR !filter_var($site_web, FILTER_VALIDATE_URL)) {
					
					$errors['siteWebFormat'] = "Site Web vide ou format invalide !";

				}

			// Vérification sur le bio
				if (empty($bio)) {
					
					$errors['bioFormat'] = "Bio vide !";

				}

			// Vérification sur le fichier
				if (isset($_FILES['logo_edition'])) {

					$destination = '../imgs/editeur/';
					$fichier = basename($_FILES['logo_edition']['name']);
					$tailleMaxi = 2000000;
					$tailleFichier = filesize($_FILES['logo_edition']['tmp_name']);
					$extensionAutorise = array('.png', '.jpg', '.jpeg', '.gif');
					$extensionFichier = strrchr($_FILES['logo_edition']['name'], '.');

					if (!in_array($extensionFichier, $extensionAutorise)) {

						$errors['imageExtension'] = "Erreur sur l'extension du fichier ou pas de fichier chargé !";

					}

					if ($tailleFichier > $tailleMaxi) {

						$errors['imageSize'] = "Taille du trop grande !";

					}

					if (!isset($errors['imageExtension']) AND !isset($errors['imageSize'])) {

						$fichier = strtr($fichier, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
						$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

						if (move_uploaded_file($_FILES['logo_edition']['tmp_name'], $destination.$fichier)) {

						}
						else{

							$errors['imageUpload'] = "Echec de téléchargement de l'image !";

						}

					}

				}

				if (empty($errors)) {
					
					$insertion = $bdd->prepare('INSERT INTO edition SET Nom = ?, Directeur = ?, Telephone1 = ?, Telephone2 = ?, Cellulaire = ?, Fax = ?, Adresse_Localite = ?, Mail = ?, Site_Web = ?, Bio = ?, Logo = ?');
					$insertion->execute([$nom, $directeur, $telephone1, $telephone2, $cellulaire, $fax, $adresse, $mail, $site_web, $bio, $fichier]);

					$id = $bdd->lastInsertId();

					$reqUpdatIdImage = $bdd->prepare('UPDATE edition SET Logo = ? WHERE Id = ?');
					$reqUpdatIdImage->execute([$id.$extensionFichier, $id]);

					rename($destination.$fichier, $destination.$id.$extensionFichier);

					$ok = "INSERTION REUSSIE !";

					header("Location: editions.php");

				}

				
			}
			else {

				$errors['errorall'] = 'Tous les champs doivent être rempli !';

			}

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

	<body>

		<div>

			<?php
				include 'include/head.php';
			?>

			<div class="formulaire">

				<form method="POST" action="#" enctype="multipart/form-data">

					<fieldset>

						<legend>
							INSERTION D'UNE EDITION
						</legend> <br>

						<input type="text" name="nom" placeholder="NOM" autofocus="autofocus" value="<?php if (isset($nom)) { echo $nom; } ?>"> <br><br>

						<input type="text" name="directeur" placeholder="DIRECTEUR" value="<?php if (isset($directeur)) { echo $directeur; } ?>"> <br><br>

						<input type="tel" name="telephone1" placeholder="TÉLÉPHONE 1" value="<?php if (isset($telephone1)) { echo $telephone1; } ?>"> <br><br>

						<input type="tel" name="telephone2" placeholder="TÉLÉPHONE 2" value="<?php if (isset($telephone2)) { echo $telephone2; } ?>"> <br><br>

						<input type="tel" name="cellulaire" placeholder="CÉLLULAIRE" value="<?php if (isset($cellulaire)) { echo $cellulaire; } ?>"> <br><br>

						<input type="tel" name="fax" placeholder="FAX" value="<?php if (isset($fax)) { echo $fax; } ?>"> <br><br>

						<input type="text" name="adresse" placeholder="ADRESSE" value="<?php if (isset($adresse)) { echo $adresse; } ?>"> <br><br>

						<input type="text" name="mail" placeholder="MAIL" value="<?php if (isset($mail)) { echo $mail; } ?>"> <br><br>

						<input type="text" name="site_web" placeholder="SITE WEB" value="<?php if (isset($site_web)) { echo $site_web; } ?>"> <br><br>

						<textarea name="bio" rows="5" cols="60" placeholder="Bio"><?php if (isset($bio)) { echo $bio; } ?></textarea> <br><br>

						<label class="img_insertion_auteur" for="img_insertion_auteur">
							LOGO EDITION ( FORMAT PNG, JPEG, JPG, GIF ) :
						</label><br>
						<input type="hidden" name="MAX_FILE_SIZE" value="3000000">
						<input type="file" name="logo_edition" id="img_insertion_auteur"> <br><br>

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

				<br>
				<br>

			</div>

		</div>
		
	</body>

</html>