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

			if (!empty($_POST['nom_livre']) AND !empty($_POST['auteur']) AND !empty($_POST['edition']) AND !empty($_POST['genre']) AND !empty($_POST['prix']) AND !empty($_POST['prix_livraison']) AND !empty($_POST['resume']) AND !empty($_FILES['image_avant']) AND !empty($_FILES['image_arriere'])) {
				
				function securisation($value){

					htmlspecialchars($value);
				 	htmlentities($value);
				 	strip_tags($value);
				 	return $value;

				}

				$nomlivre = securisation($_POST['nom_livre']);
				$auteur = securisation($_POST['auteur']);
				$edition = securisation($_POST['edition']);
				$genre = securisation($_POST['genre']);
				$prix = securisation($_POST['prix']);
				$prixLivraison = securisation($_POST['prix_livraison']);
				$resume = securisation($_POST['resume']);
				$errors = array();

			// Vérification sur le nom du livre
				if (empty($nomlivre) OR !preg_match('/^[a-zA-Z-éèêçîïâô:\'1234567890\- ]+$/', $nomlivre)) {
					
					$errors['nomlivreFormat'] = "Nom du livre vide ou format invalide !";

				}
				else {

					$sizeNomlivre = strlen($nomlivre);

					if ($sizeNomlivre >= 255) {

						$errors['nomlivreSize'] = "Nom du livre trop long !";

					}

				}

			// Vérification sur le nom de l'auteur
				if (empty($auteur) OR !preg_match('/^[a-zA-Z-éèêçîïâô ]+$/', $auteur)) {

					$errors['auteurFormat'] = "Nom de l'auteur vide ou format invalide !";

				}
				else{

					$sizeAuteur = strlen($auteur);

					if ($sizeAuteur >= 255) {

						$errors['auteurSize'] = "Nom de l'auteur trop long !";

					}

					$reqauteur = $bdd->prepare('SELECT Id FROM auteur WHERE Nom_Prenom = ?');
					$reqauteur->execute([$auteur]);
					$reqauteurdata = $reqauteur->fetch();

					if ($reqauteurdata) {

						$idauteur = $reqauteurdata['Id'];

					}
					else{

						$reqauteur = $bdd->prepare('INSERT INTO auteur SET Nom_Prenom = ?');
						$reqauteur->execute([$auteur]);

						$idauteur = $bdd->lastInsertId();

					}

				}

			// Vérification sur l'édition
				if (empty($edition) OR !preg_match('/^[a-zA-Z-éèêçîïâô ]+$/', $edition)) {

					$errors['editionFormat'] = "Édition vide ou format invalide !";

				}
				else{

					$sizeEdition = strlen($edition);

					if ($sizeEdition >= 255) {

						$errors['editionSize'] = "Édition trop long !";

					}

					$reqedition = $bdd->prepare('SELECT Id FROM edition WHERE Nom = ?');
					$reqedition->execute([$edition]);
					$reqeditiondata = $reqedition->fetch();

					if ($reqeditiondata) {

						$idedition = $reqeditiondata['Id'];

					}
					else{

						$reqedition = $bdd->prepare('INSERT INTO edition SET Nom = ?');
						$reqedition->execute([$edition]);

						$idedition = $bdd->lastInsertId();

					}

				}

			// Vérification sur le genre
				if (empty($genre) OR !preg_match('/^[a-zA-Z-éèêçîïâô ]+$/', $genre)) {

					$errors['genreFormat'] = "Genre vide ou format invalide !";

				}
				else{

					$sizeGenre = strlen($genre);

					if ($sizeGenre >= 255) {

						$errors['genreSize'] = "Genre trop long !";

					}

				}

			// Vérification sur le prix
				if (empty($prix) OR !preg_match('/^[0-9]+$/', $prix)) {

					$errors['prixFormat'] = "Prix vide ou format invalide !";

				}
				else{

					$sizePrix = strlen($prix);

					if ($sizePrix >= 7) {

						$errors['prixSize'] = "Prix trop grand !";

					}

				}

			// Vérification sur le prix de livraison
				if (empty($prixLivraison) OR !preg_match('/^[0-9]+$/', $prixLivraison)) {

					$errors['prixlivraisonFormat'] = "Prix de livraison vide ou format invalide !";

				}
				else{

					$sizePrixLivraison = strlen($prixLivraison);

					if ($sizePrixLivraison >= 7) {

						$errors['prixlivraisonSize'] = "Prix trop grand !";

					}

				}

			// Vérification sur l'image avant
				if (isset($_FILES['image_avant'])) {

					$destinationimageavant = '../imgs/logo_livre/Avant/';
					$imageavant = basename($_FILES['image_avant']['name']);
					// $fichier = basename($_FILES['image_avant']['name']);
					$tailleMaxi = 2000000;
					$tailleFichier = filesize($_FILES['image_avant']['tmp_name']);
					$extensionAutorise = array('.png', '.jpg', '.jpeg', '.gif');
					$extensionImageAvant = strrchr($_FILES['image_avant']['name'], '.');

					if (!in_array($extensionImageAvant, $extensionAutorise)) {

						$errors['imageExtension'] = "Erreur sur l'extension de l'image avant ou pas de fichier chargé !";

					}

					if ($tailleFichier > $tailleMaxi) {

						$errors['imageSize'] = "Taille du trop grande !";

					}

					if (!isset($errors['imageExtension']) AND !isset($errors['imageSize'])) {

						$imageavant = strtr($imageavant, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
						$imageavant = preg_replace('/([^.a-z0-9]+)/i', '-', $imageavant);

						if (move_uploaded_file($_FILES['image_avant']['tmp_name'], $destinationimageavant.$imageavant)) {

						}
						else{

							$errors['imageUpload'] = "Echec de téléchargement de l'image avant !";

						}

					}

				}

			// Vérification sur l'image arrière
				if (isset($_FILES['image_arriere'])) {

					$destinationimagearriere = '../imgs/logo_livre/Arriere/';
					$imagearriere = basename($_FILES['image_arriere']['name']);
					// $fichier = basename($_FILES['image_arriere']['name']);
					$tailleMaxi = 2000000;
					$tailleFichier = filesize($_FILES['image_arriere']['tmp_name']);
					$extensionAutorise = array('.png', '.jpg', '.jpeg', '.gif');
					$extensionImageArriere = strrchr($_FILES['image_arriere']['name'], '.');

					if (!in_array($extensionImageArriere, $extensionAutorise)) {

						$errors['imageExtension'] = "Erreur sur l'extension de l'image arrière ou pas de fichier chargé !";

					}

					if ($tailleFichier > $tailleMaxi) {

						$errors['imageSize'] = "Taille du trop grande !";

					}

					if (!isset($errors['imageExtension']) AND !isset($errors['imageSize'])) {

						$imagearriere = strtr($imagearriere, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
						$imagearriere = preg_replace('/([^.a-z0-9]+)/i', '-', $imagearriere);

						if (move_uploaded_file($_FILES['image_arriere']['tmp_name'], $destinationimagearriere.$imagearriere)) {

						}
						else{

							$errors['imageUpload'] = "Echec de téléchargement de l'image arrière !";

						}

					}

				}

				if (empty($errors)) {

					$insertion = $bdd->prepare('INSERT INTO livre SET Nom = ?, Id_Auteur = ?, Id_Edition = ?, Genre = ?, Prix = ?, Prix_Livraison = ?, Resume = ?, Image_Avant = ?, Image_Arriere = ?');
					$insertion->execute([$nomlivre, $idauteur, $idedition, $genre, $prix, $prixLivraison, $resume, $imageavant, $imagearriere]);

					$id = $bdd->lastInsertId();

					$reqUpdatIdImageAvant = $bdd->prepare('UPDATE livre SET Image_Avant = ? WHERE Id = ?');
					$reqUpdatIdImageAvant->execute([$id.$extensionImageAvant, $id]);

					rename($destinationimageavant.$imageavant, $destinationimageavant.$id.$extensionImageAvant);

					$reqUpdatIdImageArriere = $bdd->prepare('UPDATE livre SET Image_Arriere = ? WHERE Id = ?');
					$reqUpdatIdImageArriere->execute([$id.$extensionImageArriere, $id]);

					rename($destinationimagearriere.$imagearriere, $destinationimagearriere.$id.$extensionImageArriere);

					$ok = "INSERTION REUSSIE !";

					header("Location: livres.php");

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
		<title>INSERTION LIVRE</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body style="background-color: rgba(152, 245, 255, 0.17);">

		<?php
			include 'include/head.php';
		?>

		<div style="margin-top: 20px; height: 720px;">

			<div class="formulaire">

				<form method="POST" action="#" enctype="multipart/form-data">

					<fieldset>

						<legend>
							INSERTION D'UN LIVRE
						</legend><br>

						<input type="text" name="nom_livre" placeholder="TITRE" autofocus="autofocus" value="<?php if (isset($nomlivre)) { echo $nomlivre; } ?>"> <br><br>

						<input type="text" name="auteur" placeholder="AUTEUR" value="<?php if (isset($auteur)) { echo $auteur; } ?>"> <br><br>

						<input type="text" name="edition" placeholder="ÉDITION" value="<?php if (isset($edition)) { echo $edition; } ?>"> <br><br>

						<input type="text" name="genre" placeholder="GENRE" value="<?php if (isset($genre)) { echo $genre; } ?>"> <br><br>

						<input type="text" name="prix" placeholder="PRIX" value="<?php if (isset($prix)) { echo $prix; } ?>"> <br><br>

						<input type="text" name="prix_livraison" placeholder="PRIX LIVRAISON" value="<?php if (isset($prixLivraison)) { echo $prixLivraison; } ?>"> <br><br>

						<textarea name="resume" id="resume" rows="5" cols="60" placeholder="RÉSUMÉ"><?php if (isset($resume)) { echo $resume; } ?></textarea> <br><br>

						<label class="img_avant" for="img_av">
							IMAGE AVANT ( FORMAT PNG, JPEG, JPG, GIF ) :
						</label><br>
						<input type="hidden" name="MAX_FILE_SIZE" value="3000000">
						<input type="file" name="image_avant" id="img_av"> <br><br>

						<label class="img_arriere" for="img_ar">
							IMAGE ARRIÈRE ( FORMAT PNG, JPEG, JPG, GIF ) :
						</label><br>
						<input type="hidden" name="MAX_FILE_SIZE" value="3000000">
						<input type="file" name="image_arriere" id="img_ar"> <br><br>

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