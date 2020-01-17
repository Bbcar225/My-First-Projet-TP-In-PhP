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

	var_dump($_SESSION);

	if (!isset($_SESSION['Id']) OR !isset($_SESSION['Mot_De_Passe'])) {

		header("Location: errorsfatal.php");

	}
	else{

		if (isset($_SESSION['Mot_De_Passe'])) {

			if ($_SESSION['Mot_De_Passe'] == '70352f41061eda4ff3c322094af068ba70c3b38b') {

			}
			else{

				header("Location: errorsfatal.php");

			}

		}

	}

	if (isset($_POST)) {

		if (isset($_POST['valide_form'])) {

			if (!empty($_POST['Ancien_Mot_De_Passe']) AND !empty($_POST['Nouveau_Mot_De_Passe']) AND !empty($_POST['Confirmation_Du_Nouveau_Mot_De_Passe']) AND isset($_SESSION['Mot_De_Passe'])) {

				$Ancien_Mot_De_Passe = sha1($_POST['Ancien_Mot_De_Passe']);
				$Nouveau_Mot_De_Passe = sha1($_POST['Nouveau_Mot_De_Passe']);
				$Confirmation_Du_Nouveau_Mot_De_Passe = sha1($_POST['Confirmation_Du_Nouveau_Mot_De_Passe']);
				$errors = array();

				if ($Ancien_Mot_De_Passe == $_SESSION['Mot_De_Passe']) {

					if ($Nouveau_Mot_De_Passe == $_SESSION['Mot_De_Passe']) {

						$errors['AncienEtNouveau'] = "Le nouveau mot de passe ne peut pas être pareil au mot de passe par défaut";

					}

					if ($Nouveau_Mot_De_Passe != $Confirmation_Du_Nouveau_Mot_De_Passe) {

						$errors['MdpDifferentConfirmation'] = "Nouveau mot de passe différent de la confirmation";

					}
					else{

						$Size_Mot_De_Passe = strlen($Nouveau_Mot_De_Passe);

						if ($Size_Mot_De_Passe < 6 AND $Size_Mot_De_Passe > 20) {

							$errors['MdpLongCourt'] = "Nouveau mot de passe trop court ou trop long";

						}

					}

				}
				else{

				$errors['ErreurAncien'] = "Le mot de passe par défaut n'est pas correct";

				}

				if (empty($errors)) {

					$updatemotdepasse = $bdd->prepare('UPDATE admin SET Mot_De_Passe = ? WHERE Id = ?');
					$updatemotdepasse->execute([$Nouveau_Mot_De_Passe, $_SESSION['Id']]);

					$_SESSION['Mot_De_Passe'] = $Nouveau_Mot_De_Passe;

					header("Location: admin.php");

				}

			}
			else{

				$errors['errorall'] = 'Tous les champs doivent être rempli !';

			}

		}

	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Nouveau Agent</title>
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
						MOT DE PASSE
					</legend>

					<br>

					<input class="connexion_password" type="password" name="Ancien_Mot_De_Passe" placeholder="MOT DE PASSE PAR DÉFAUT" autofocus="autofocus" value="00000000">

					<br><br>

					<input class="connexion_password" type="password" name="Nouveau_Mot_De_Passe" placeholder="NOUVEAU MOT DE PASSE">

					<br><br>

					<input class="connexion_password" type="password" name="Confirmation_Du_Nouveau_Mot_De_Passe" placeholder="CONFIRMATION DU NOUVEAU MOT DE PASSE">

					<br><br>

					<input type="submit" name="valide_form" value="CONFIRMER">

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