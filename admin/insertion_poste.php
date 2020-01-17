<?php
	
	session_start();

// Connexion à la base de donnée
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

	if (isset($_POST) AND !empty($_POST)) {

		if (isset($_POST['valide_form'])) {

			if (!empty($_POST['Nom']) AND !empty($_POST['Prenom']) AND !empty($_POST['Mail']) AND !empty($_POST['Ville']) AND !empty($_POST['Poste'])){

				function securisation($value){

					htmlspecialchars($value);
				 	htmlentities($value);
				 	strip_tags($value);
				 	return $value;

				}

				$nom = securisation($_POST['Nom']);
				$prenom = securisation($_POST['Prenom']);
				$mail = securisation($_POST['Mail']);
				$ville = securisation($_POST['Ville']);
				$poste = securisation($_POST['Poste']);
				$errors = array();

			// Pour le nom
				if (empty($nom) OR !preg_match('/^[a-zA-Z-éèêçîïâô ]+$/', $nom)) {

					$errors['EmptyNom'] = "Nom vide ou format incorect";
				}
				else{

					$SizeNom = strlen($nom);

					if ($SizeNom >=255) {
						
						$errors['sizenom'] = "Nom trop long";

					}

				}

			// Pour le prenom
				if (empty($prenom) OR !preg_match('/^[a-zA-Z-éèêçîïâô ]+$/', $prenom)) {

					$errors['EmptyPrenom'] = "Prénom vide ou format incorrect !";

				}
				else{

					$SizePrenom = strlen($prenom);

					if ($SizePrenom >= 255) {
						
						$errors['sizeprenom'] = "Prénom trop long";
					}
				}

			// Pour mail
				if (empty($mail) OR !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
					
					$errors['EmptyMail'] = "Mail vide ou format invalide !";

				}
				else{

					$req = $bdd->prepare('SELECT Id FROM admin WHERE Mail = ?');
					$req->execute([$mail]);
					$mailsearch = $req->fetch();

					if ($mailsearch) {

						$errors['existemail'] = "Cette adresse mail est déjà utilisée !";

					}

				}

			// Pour ville
				if (!empty($ville) AND $ville == 'VILLE') {
					
					$errors['formville'] = "Ville vide !";
				}

			// Pour le poste
				if (!empty($poste) AND $poste == 'POSTE') {

					$errors['NoPoste'] = 'Poste vide';

				}

				if (empty($errors)) {

					$mdp = '00000000';

					$reqInsertion = $bdd->prepare('INSERT INTO admin SET Nom = ?, Prenom = ?, Mail = ?, Ville = ?, Poste = ?, Mot_De_Passe = ?');
					$reqInsertion->execute([$nom, $prenom, $mail, $ville, $poste, sha1($mdp)]);

					$ok = "INSERTION REUSSIE !";

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
		<title>Insertion d'un poste</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body>

		<body style="background-color: rgba(152, 245, 255, 0.17);">

		<?php
			include 'include/head.php';
		?>

		<div style="margin-top: 15px;">

			<div class="formulaire">

				<form method="POST" action="#">

					<fieldset>
						
						<legend>
							INSERTION D'UN POSTE
						</legend>

						<input class="connexion_nom" type="text" name="Nom" placeholder="NOM" autofocus="autofocus" value="<?php if (isset($_POST['Nom'])) { echo $_POST['Nom']; } ?>">

						<br><br>

						<input class="connexion_prenom" type="text" name="Prenom" placeholder="PRÉNOM" value="<?php if (isset($_POST['Prenom'])) { echo $_POST['Prenom']; } ?>">

						<br><br>

						<input class="connexion_mail" type="email" name="Mail" placeholder="MAIL" value="<?php if (isset($_POST['Mail'])) { echo $_POST['Mail']; } ?>">

						<br><br>

						<select class="connexion_ville" name="Ville">
							<option>VILLE</option>

							<?php
								$reqVille = $bdd->prepare('SELECT DISTINCT Ville FROM inscript');
								$reqVille->execute();
								while ($reqVilleData = $reqVille->fetch()) {
							?>
							<option value="<?php echo $reqVilleData['Ville']; ?>" <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == $reqVilleData['Ville']) { echo "selected=''"; } ?>><?php echo strtoupper($reqVilleData['Ville']); ?></option>
							<?php
							}
							?>

						</select>

						<br><br>

						<select class="connexion_poste" name="Poste">
							<option>POSTE</option>
							<option value="Livreur" <?php if (isset($_POST['Poste']) AND $_POST['Poste'] == 'Livreur') { echo "selected=''"; } ?>>LIVREUR / LIVREUSE</option>
							<option value="Insereur" <?php if (isset($_POST['Poste']) AND $_POST['Poste'] == 'Insereur') { echo "selected=''"; } ?>>INSÉREUR / INSÉREUSE</option>
							<option value="Super Admin" <?php if (isset($_POST['Poste']) AND $_POST['Poste'] == 'Super Admin') { echo "selected=''"; } ?>>SUPER ADMIN</option>
						</select>

						<br><br>

						<input type="submit" name="valide_form" value="INSÉRER">

						<br>

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