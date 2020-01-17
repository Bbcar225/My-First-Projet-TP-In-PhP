<?php

	require 'panier.class.php';
	
	$panier = new panier();
	
// Connexion à la base de donnée
	try{

		$bdd = new PDO("mysql:host=localhost;dbname=commerce", 'root', '');
		$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	catch(PDOEcxeption $e){

		echo 'Echec de la connexion : ' . $e->getMessage();
	}

	header("content-type:text/html; charset=iso-8859-1");

// Connexion à la base de donnée
	try{

		$bdd = new PDO("mysql:host=localhost;dbname=commerce", 'root', '');
		$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	catch(PDOEcxeption $e){

		echo 'Echec de la connexion : ' . $e->getMessage();
	}

	if (isset($_COOKIE['Mail_Client'])) {

		$MailClient = $_COOKIE['Mail_Client'];

		$reqMailCookie = $bdd->prepare('
											SELECT *
											FROM inscript
											WHERE Mail = ?
										');
		$reqMailCookie->execute([$MailClient]);
		$reqMailCookieData = $reqMailCookie->fetch();

		$_SESSION['Id'] = $reqMailCookieData['Id'];
		$_SESSION['Nom'] = $reqMailCookieData['Nom'];
		$_SESSION['Prenom'] = $reqMailCookieData['Prenom'];
		$_SESSION['Sexe'] = $reqMailCookieData['Sexe'];
		$_SESSION['Date_Naissance'] = $reqMailCookieData['Date_Naissance'];
		$_SESSION['Telephone1'] = $reqMailCookieData['Telephone1'];
		$_SESSION['Telephone2'] = $reqMailCookieData['Telephone2'];
		$_SESSION['Telephone3'] = $reqMailCookieData['Telephone3'];
		$_SESSION['Mail'] = $reqMailCookieData['Mail'];
		$_SESSION['Ville'] = $reqMailCookieData['Ville'];
		$_SESSION['Quartier'] = $reqMailCookieData['Quartier'];
		$_SESSION['Lieu_Retrait'] = $reqMailCookieData['Lieu_Retrait'];

	}

	if (isset($_SESSION['Id'])) {
	
		header('Location: errosfatal.php');
	}

	// Verifie si on a cliquez sur se connecter
	if (isset($_POST['valide_connex'])) {

		$redirection = htmlspecialchars($_POST['redirection']);

		// Verifier si tout les champs sont rempli
		if (!empty($_POST['Mail']) AND !empty($_POST['Mot_de_passe'])) {
			
			// Récupération des variables envoyées
			$mail = $_POST['Mail'];
			$mail = ucwords($mail);
			$mdp = sha1($_POST['Mot_de_passe']);
			$errors = array();

		// Verification sur le mail
			if (empty($_POST['Mail']) OR !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
				
				$errors['formatmail'] = "Mail vide ou incorrect !";
			} else {

				$reqmail = $bdd->prepare('SELECT Mail FROM inscript WHERE Mail = ?');
				$reqmail->execute([$mail]);
				$mailsearch = $reqmail->fetch();

				if (!$mailsearch) {
					
					$errors['noinbdd'] = "Pas encore inscript ou mot de passe incorrect !";
				}
			}

		// Verification mot de passe
			if (empty($_POST['Mot_de_passe'])) {
				
				$errors['formatmpd'] = "Mot de passe vide !";
			} else {

				$reqmdp = $bdd->prepare('SELECT Mot_de_passe FROM inscript WHERE Mot_de_passe = ?');
				$reqmdp->execute([$mdp]);
				$mdpsearch = $reqmdp->fetch();

				if (!$mdpsearch) {
					
					$errors['noinbdd'] = "Pas encore inscript ou mot de passe incorrect !";
				}
			}

			if (empty($errors)) {


				$reqall = $bdd->prepare('SELECT * FROM inscript WHERE Mail = ? AND Mot_de_passe = ?');
				$reqall->execute([$mail, $mdpsearch['Mot_de_passe']]);
				$dataall = $reqall->fetch();

				$_SESSION['Id'] = $dataall['Id'];
				$_SESSION['Nom'] = $dataall['Nom'];
				$_SESSION['Prenom'] = $dataall['Prenom'];
				$_SESSION['Sexe'] = $dataall['Sexe'];
				$_SESSION['Date_Naissance'] = $dataall['Date_Naissance'];
				$_SESSION['Telephone1'] = $dataall['Telephone1'];
				$_SESSION['Telephone2'] = $dataall['Telephone2'];
				$_SESSION['Telephone3'] = $dataall['Telephone3'];
				$_SESSION['Mail'] = $dataall['Mail'];
				$_SESSION['Ville'] = $dataall['Ville'];
				$_SESSION['Quartier'] = $dataall['Quartier'];
				$_SESSION['Lieu_Retrait'] = $dataall['Lieu_Retrait'];

				if (isset($_POST['Souvenir'])) {

					$dateexpiration = time() + 365 * 24 * 3600;

					setcookie('Mail_Client', $_SESSION['Mail'], $dateexpiration);

				}

				header("Location: $redirection");

			}

		} else {

			$errors['emptyall'] = "Tout les champs doivent être rempli !";

		}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Connexion</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body>
		<?php 
			include 'include/head.php';
			include 'include/menu.php';
			include 'include/top_genre.php';
		?>
		<div class="rech_livre">

				<div style="width: 100%; text-align: center; margin: auto;">
					<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">CONNEXION A MON COMPTE</h3>
				</div>
				<br>

				<div class="connexion">

					<form method="post" action="#">

						<fieldset class="form_connexion">

							<legend style="font-weight: bolder;">CONNEXION</legend>
							<br>

							<input type="email" name="Mail" id="user" required="" placeholder="ADRESSE MAIL" value="<?php if (isset($_POST['Mail'])) { echo $_POST['Mail']; } ?>">

							<br>
							<br>

							<input type="password" name="Mot_de_passe" id="password" required="" placeholder="MOT DE PASSE">

							<br>
							<br>

							<label for="autoconnect">Me Garder Connecter ?</label><input type="checkbox" name="Souvenir" id="autoconnect">

							<br>
							<br>

							<input type="hidden" name="redirection" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">

							<input type="submit" name="valide_connex" value=" SE CONNEXION">

							<br>

							<div style="color:red; font-weight: bold;">

	   							<?php if(isset($errors)): ?>
	     							<ul>
	       								<?php foreach($errors as $error): ?>
	         								<li style="list-style: initial;"><?php echo $error; ?></li><br>
	       		 						<?php endforeach; ?>
	     							 </ul>
	    						<?php endif; ?>

  							</div>
  							<br>

						</fieldset>

					</form>

				</div>

			</div>

		<?php
			include 'include/edition.php';
			include 'include/footer.php';
		 ?>

	</body>

</html>