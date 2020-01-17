<?php

// Connexion à la base de donnée
	try{

		$bdd = new PDO("mysql:host=localhost;dbname=commerce", 'root', '');
		$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	catch(PDOEcxeption $e){

		echo 'Echec de la connexion : ' . $e->getMessage();
	}

	session_start();

	if (isset($_SESSION['Id'])) {

		header("Location: admin.php");
		
	}

	if (!empty($_POST) AND isset($_POST)) {

		if (isset($_POST['valide_form'])) {

			if (!empty($_POST['Nom']) AND !empty($_POST['Prenom']) AND !empty($_POST['Mail']) AND !empty($_POST
				['Mot_De_Passe'])) {

				function securisation($value){

					htmlspecialchars($value);
					htmlentities($value);
					strip_tags($value);

				 	return $value;
				}

				$nom = securisation($_POST['Nom']);
				$prenom = securisation($_POST['Prenom']);
				$mail = securisation($_POST['Mail']);
				$mdp = sha1($_POST['Mot_De_Passe']);
				$errors = array();

			// Pour le nom
				if (empty($nom) OR !preg_match('/^[a-zA-Z-éèêçîïâô ]+$/', $nom)) {

					$errors['FormatName'] = 'Nom vide ou format invalide';

				}
				else{

					$SizeName = strlen($nom);

					if ($SizeName >= 255) {

						$errors['FullName'] = 'Nom trop long';

					}

					$ReqNom = $bdd->prepare('SELECT Nom FROM admin WHERE Nom = ?');
					$ReqNom->execute([$nom]);
					$ReqNomData = $ReqNom->fetch();

					if (!$ReqNomData['Nom']) {

						$errors['NoName'] = 'Nom incorrect';

					}

				}

			// Pour le prénom
				if (empty($prenom) OR !preg_match('/^[a-zA-Z-éèêçîïâô ]+$/', $prenom)) {

					$errors['FormatFirstName'] = 'Prénom vide ou format invalide';

				}
				else {

					$SizeFirstName = strlen($prenom);

					if ($SizeFirstName >= 255) {

						$errors['FullFirstName'] = 'Prénom trop long';

					}

					$ReqPrenom = $bdd->prepare('SELECT Prenom FROM admin WHERE Prenom = ?');
					$ReqPrenom->execute([$prenom]);
					$ReqPrenomData = $ReqPrenom->fetch();

					if (!$ReqPrenomData) {

						$errors['NoFirstName'] = 'Prénom incorrect';

					}

				}

			// Pour le mail
				if (empty($mail) OR !filter_var($mail, FILTER_VALIDATE_EMAIL)) {

					$errors['FormatMail'] = 'Mail vide ou format invalide';

				}
				else {

					$SizeMail = strlen($mail);

					if ($SizeMail >= 255) {

						$errors['FullMail'] = 'Mail trop long';

					}

					$ReqMail = $bdd->prepare('SELECT Mail FROM admin WHERE Mail = ?');
					$ReqMail->execute([$mail]);
					$ReqMailData = $ReqMail->fetch();

					if (!$ReqMailData) {

						$errors['NoMail'] = 'Mail incorrect';

					}

				}

			// Pour le mot de passe
				if (empty($mdp)) {

					$errors['EmptyPasseWord'] = 'Mot de passe vide';

				}
				else{

					$ReqMdp = $bdd->prepare('SELECT Mot_De_Passe FROM admin WHERE Mot_De_Passe = ? AND Mail = ?');
					$ReqMdp->execute([$mdp, $mail]);
					$ReqMdpData = $ReqMdp->fetch();

					if (!$ReqMdpData) {

						$errors['NoPassWord'] = 'Mot de passe incorrect';

					}

				}

				if (empty($errors)) {

					$ReqInfAll = $bdd->prepare('SELECT * FROM admin WHERE Nom = ? AND Prenom = ? AND Mail = ? AND Mot_De_Passe = ?');
					$ReqInfAll->execute([$nom, $prenom, $mail, $mdp]);
					$ReqInfAllData = $ReqInfAll->fetch();

					$_SESSION['Id'] = $ReqInfAllData['Id'];
					$_SESSION['Nom'] = $ReqInfAllData['Nom'];
					$_SESSION['Prenom'] = $ReqInfAllData['Prenom'];
					$_SESSION['Mail'] = $ReqInfAllData['Mail'];
					$_SESSION['Ville'] = $ReqInfAllData['Ville'];
					$_SESSION['Poste'] = $ReqInfAllData['Poste'];

					if ($ReqMdpData['Mot_De_Passe'] == '70352f41061eda4ff3c322094af068ba70c3b38b') {

						$_SESSION['Mot_De_Passe'] = $ReqMdpData['Mot_De_Passe'];

						header("Location: new_agent.php");

					}
					else{

						header("Location: admin.php");

					}

				}

			}
			else{

				$errors['emptyall'] = "Tout les champs doivent être rempli !";

			}

		}

	}

?>
<!DOCTYPE html>

<html>

	<head>
		<title>Admin</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body style="background-color: rgba(152, 245, 255, 0.17);">
		<?php
			include 'include/head.php';
		?>

		<div class="formulaire" style=" margin: auto; margin-top: 20px;">

			<form method="POST" action="#">
				
				<fieldset>
						
					<legend>
						CONNEXION
					</legend>

					<br>

					<input class="connexion_nom" type="text" name="Nom" placeholder="NOM" autofocus="autofocus" value="<?php if (isset($_POST['Nom'])) { echo $_POST['Nom']; } ?>">

					<br><br>

					<input class="connexion_prenom" type="text" name="Prenom" placeholder="PRÉNOM" value="<?php if (isset($_POST['Prenom'])) { echo $_POST['Prenom']; } ?>">

					<br><br>

					<input class="connexion_mail" type="email" name="Mail" placeholder="MAIL" value="<?php if (isset($_POST['Mail'])) { echo $_POST['Mail']; } ?>">

					<br><br>

					<input class="connexion_password" type="password" name="Mot_De_Passe" placeholder="MOT DE PASSE" value="00000000">

					<br><br>

					<input type="submit" name="valide_form" value="CONNEXION">

					<div style="color:red; font-weight: bold;">

						<?php if(isset($errors)): ?>
							<ul>
								<?php foreach($errors as $error): ?>
									<li style="list-style: initial;"><?php echo $error; ?></li><br>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

					</div>

				</fieldset>

			</form>

		</div>

	</body>

</html>