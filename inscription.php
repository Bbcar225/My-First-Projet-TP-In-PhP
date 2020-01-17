<?php
	
	require 'panier.class.php';
	
	$panier = new panier();

	header("content-type:text/html; charset=iso-8859-1");
// Connexion la base de donnée
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

	// session_start();
	if (isset($_SESSION['Id'])) {
		
		header('Location: errosfatal.php');
	}
	
// Verifie si on vient d'arriver si c'est le cas post en vide et n'existe pas
	if (isset($_POST['valide_form'])) {

		// On vérifie si les post sont non vide si c'est le cas on fait traitement sinon on vas dans le else 
		if (!empty($_POST['Nom']) AND !empty($_POST['Prenom']) AND !empty($_POST['Sexe']) AND !empty($_POST['Date_Naissance']) AND !empty($_POST['Telephone1']) AND !empty($_POST['Mail']) AND !empty($_POST['Ville']) AND !empty($_POST['Quartier']) AND !empty($_POST['Lieu_Retrait']) AND !empty($_POST['Mot_de_passe']) AND !empty($_POST['Confirmation'])) {

			function securisation($value){

				// htmlspecialchars_decode($value);
				htmlspecialchars($value);
			 	htmlentities($value);
			 	return $value;
			}

			$nom = securisation($_POST['Nom']);
			$prenom = securisation($_POST['Prenom']);
			$sexe = securisation($_POST['Sexe']);
			$date_naissance = securisation($_POST['Date_Naissance']);
			$tel1 = securisation($_POST['Telephone1']);
			$tel2 = securisation($_POST['Telephone2']);
			$tel3 = securisation($_POST['Telephone3']);
			$mail = securisation($_POST['Mail']);
			$ville = securisation($_POST['Ville']);
			$quartier = securisation($_POST['Quartier']);
			$retrait = securisation($_POST['Lieu_Retrait']);
			$mdp = sha1($_POST['Mot_de_passe']);
			$confirm = sha1($_POST['Confirmation']);
			$errors = array();

			$nom = strtolower($nom);
			$prenom = strtolower($prenom);
			$quartier = strtolower($quartier);
			$retrait = strtolower($retrait);

			$nom = ucwords($nom);
			$prenom = ucwords($prenom);
			$quartier = ucwords($quartier);
			$retrait = ucwords($retrait);

		// Pour le nom
			if (empty($_POST['Nom']) OR !preg_match('/^[a-zA-Z-éèêçîïâô ]+$/', $nom)) {

				$errors['Nom'] = "Nom vide ou format incorect !";
			} else {

				$sizenom = strlen($nom);
				if ($sizenom >=100) {
					
					$errors['sizenom'] = "Le nom trop long pour un nom !";
				}
			}

		// Pour le prenom
			if (empty($_POST['Prenom']) OR !preg_match('/^[a-zA-Z-éèêçîïâô ]+$/', $prenom)) {

				$errors['formprenom'] = "Prénom vide ou format incorrect !";
			} else {
				$sizeprenom = strlen($prenom);
				if ($sizeprenom >= 255) {
					
					$errors['sizeprenom'] = "Le prénom est trop long pour un prénom !";
				}
			}

		// Pour le sexe
			if ($_POST['Sexe'] == 'VOUS ÊTES ?') {

				$errors['errorsexe'] = "Sexe non renseigné !";
			}

		// Pour la date
			if (empty($_POST['Date_Naissance'])) {

				$errors['errordate'] = "Date de naissance vide ou invalide !";
			}

		// Pour telephone1
			if (empty($_POST['Telephone1']) OR !preg_match('/^[0-9]+$/', $tel1)) {

				$errors['formetel1'] = "Numéro principal vide ou format invalide !";
			} else {
				$sizetel1 = strlen($tel1);
				if ($sizetel1 != 8) {

					$errors['sizetel1'] = "Le numéro principal est trop cours ou trop long, 8 chiffres!";
				}
			}

		// Pour telephone2
			if (!empty($tel2)) {
			 	
			 	if (!preg_match('/^[0-9]+$/', $tel2)) {
			 		
			 		$errors['formetel2'] = "Deuxième numéro vide ou format invalide !";
			 	} else {

			 		$sizetel2 = strlen($tel2);
			 		if ($sizetel2 != 8) {
			 			
			 			$errors['sizetel2'] = "Deuxième numéro est trop cours ou trop long, 8 chiffres!";
			 		}
			 	}
			 } 

		// Pour telephone3
			 if (!empty($tel3)) {
			 	
			 	if (!preg_match('/^[0-9]+$/', $tel3)) {
			 		
			 		$errors['formetel3'] = "Deuxième numéro vide ou format invalide !";
			 	} else {

			 		$sizetel3 = strlen($tel3);
			 		if ($sizetel3 != 8) {
			 			
			 			$errors['sizetel3'] = "Deuxième numéro est trop cours ou trop long, 8 chiffres!";
			 		}
			 	}
			 }

		// Pour mail
			if (empty($_POST['Mail']) OR !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
				
				$errors['formatmail'] = "Mail vide ou format invalide !";
			} else {

				$req = $bdd->prepare('SELECT Id FROM inscript WHERE Mail = ?');
				$req->execute([$mail]);
				$mailsearch = $req->fetch();

				if ($mailsearch) {

					$errors['existemail'] = "Cette adresse mail est déjà utilisée !";
				}
			}

		// Pour ville
			if ($_POST['Ville'] == 'VILLE') {
				
				$errors['formville'] = "Ville vide !";
			}

		// Pour quartier
			if (empty($_POST['Quartier']) OR !preg_match('/^[a-zA-Z-éèêçîïâô1234567890 ]+$/', $quartier)) {
				
				$errors['formquartier'] = "Quartier vide ou format incorrect !";
			} else {
				$sizequartier = strlen($quartier);
				if ($sizequartier >= 100) {
					
					$errors['sizequartier'] = "Quartier trop long pour le nom d'un quartier";
				}
			}

		// Pour point retrait
			if (empty($_POST['Lieu_Retrait']) OR !preg_match('/^[a-zA-Z-éèêçîïâô ]+$/', $retrait)) {
				
				$errors['formretrait'] = "Point de retrait vide ou format incorrect !";
			} else {
				$sizeretrait = strlen($retrait);
				if ($sizeretrait >= 200) {
					
					$errors['sizeretrait'] = "Point de rétrait trop long pour un lieu de rendez-vous !";
				}
			}

		// Pour mot de passe

			if ($mdp != $confirm) {
				
				$errors['differentmdp'] = "Mot de passe different de Confirmation !";
			} else {
				$sizemdp = strlen($mdp);
				if ($sizemdp < 6 AND $sizemdp >20) {
					
					$errors['sizemdp'] = "Mot de passe trop court ou trop long !";
				}
			}

			if (empty($errors)) {
				
				$time = date("Y-m-d H:i:s");
				$insertion = $bdd->prepare('INSERT INTO inscript SET Nom = ?, Prenom = ?, Sexe = ?, Date_Naissance = ?, Telephone1 = ?, Telephone2 = ?, Telephone3 = ?, Mail = ?, Ville = ?, Quartier = ?, Lieu_Retrait = ?, Mot_de_passe = ?, Date_Inscription = ?');
				$insertion->execute([$nom, $prenom, $sexe, $date_naissance, $tel1, $tel2, $tel3, $mail, $ville, $quartier, $retrait, $mdp, $time]);
				session_start();

				$id = $bdd->lastInsertId();

				session_start();

				$_SESSION['Id'] = $id;
				$_SESSION['Nom'] = $nom;
				$_SESSION['Prenom'] = $prenom;
				$_SESSION['Sexe'] = $sexe;
				$_SESSION['Date_Naissance'] = $date_naissance;
				$_SESSION['Telephone1'] = $tel1;
				$_SESSION['Telephone2'] = $tel2;
				$_SESSION['Telephone3'] = $tel3;
				$_SESSION['Mail'] = $mail;
				$_SESSION['Ville'] = $ville;
				$_SESSION['Quartier'] = $quartier;
				$_SESSION['Lieu_Retrait'] = $retrait;
				
				header("Location: new_inscript.php");

			}

		} else {

			$errors['errorall'] = 'Tous les champs doivent être rempli !';
			
		}
	}
 ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Inscription</title>
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
					<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">INSCRIPTION SUR "NOM DU SITE"</h3>
				</div>
				<br>
				<div class="formulaire">
					<form method="post" action="#">

						<fieldset class="form_inscription">

							<legend style="font-weight: bolder;">INSCRIPTION</legend>
							<br>

							<!-- <label for="nom"></label> --><input type="text" name="Nom" id="Nom" required="required" placeholder="NOM" value="<?php if(isset($nom)) {echo $nom;} ?>"><br><br>

							<!-- <label for="prenom"></label> --><input type="text" name="Prenom" id="prenom" required="required" placeholder="PRÉNOM" value="<?php if(isset($prenom)) {echo $prenom;} ?>"><br><br>

							<select name="Sexe" required="required">

								<option>VOUS ÊTES ?</option>
								<option <?php if (isset($_POST['Sexe']) AND $_POST['Sexe'] == 'Homme') { echo "selected=''"; } ?> value="Homme">Homme</option>
								<option <?php if (isset($_POST['Sexe']) AND $_POST['Sexe'] == 'Femme') { echo "selected=''"; } ?> value="Femme">Femme</option>

							</select><br><br>

							<input type="date" name="Date_Naissance" required="required" id="naissance" value="<?php if(isset($date_naissance)) {echo $date_naissance;} ?>"><br><br>

							<input type="tel" name="Telephone1" id="tel" required="required" placeholder="NUMÉRO PRINCIPAL" value="<?php if(isset($tel1)) {echo $tel1;} else { echo "";} ?>">
							<br><br>
							<input type="tel" name="Telephone2" placeholder="DEUXIÈME NUMÉRO" value="<?php if(isset($tel2)) {echo $tel2;} ?>">
							<br><br>

							<input type="tel" name="Telephone3" placeholder="TROISIÈME NUMÉRO" value="<?php if(isset($tel3)) {echo $tel3;} else { echo ""; } ?>"><br><br>

							<input type="email" name="Mail" id="mail" required="required" placeholder="ADRESSE MAIL" value="<?php if(isset($mail)) {echo $mail;} ?>"><br><br>

							<select name="Ville" id="ville" required="required">

								<option >VILLE</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Abengourou') { echo "selected=''"; } ?> value="Abengourou">Abengourou</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Abidjan') { echo "selected=''"; } ?> value="Abidjan">Abidjan</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Adzopé') { echo "selected=''"; } ?> value="Adzopé">Adzopé</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Agboville') { echo "selected=''"; } ?> value="Agboville">Agboville</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Agnibilékrou') { echo "selected=''"; } ?> value="Agnibilékrou">Agnibilékrou</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Akoupé') { echo "selected=''"; } ?> value="Akoupé">Akoupé</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Bingerville') { echo "selected=''"; } ?> value="Bingerville">Bingerville</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Bondoukou') { echo "selected=''"; } ?> value="Bondoukou">Bondoukou</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Bouaflé') { echo "selected=''"; } ?> value="Bouaflé">Bouaflé</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Bouaké') { echo "selected=''"; } ?> value="Bouaké">Bouaké</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Boundiali') { echo "selected=''"; } ?> value="Boundiali">Boundiali</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Dabou') { echo "selected=''"; } ?> value="Dabou">Dabou</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Daloa') { echo "selected=''"; } ?> value="Daloa">Daloa</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Danané') { echo "selected=''"; } ?> value="Danané">Danané</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Daoukro') { echo "selected=''"; } ?> value="Daoukro">Daoukro</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Dimbokro') { echo "selected=''"; } ?>  value="Dimbokro">Dimbokro</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Divo') { echo "selected=''"; } ?>  value="Divo">Divo</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Duékoué') { echo "selected=''"; } ?>  value="Duékoué">Duékoué</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Ferkessedougou') { echo "selected=''"; } ?>  value="Ferkessedougou">Ferkessedougou</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Gagnoa') { echo "selected=''"; } ?>  value="Gagnoa">Gagnoa</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Guiglo') { echo "selected=''"; } ?>  value="Guiglo">Guiglo</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Issia') { echo "selected=''"; } ?>  value="Issia">Issia</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Katiola') { echo "selected=''"; } ?>  value="Katiola">Katiola</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Korhogo') { echo "selected=''"; } ?>  value="Korhogo">Korhogo</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Lakota') { echo "selected=''"; } ?>  value="Lakota">Lakota</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Man') { echo "selected=''"; } ?>  value="Man">Man</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Odienné') { echo "selected=''"; } ?>  value="Odienné">Odienné</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Oumé') { echo "selected=''"; } ?>  value="Oumé">Oumé</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Sassandra') { echo "selected=''"; } ?>  value="Sassandra">Sassandra</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'San-Pédro') { echo "selected=''"; } ?>  value="San-Pédro">San-Pédro</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Sinfra') { echo "selected=''"; } ?>  value="Sinfra">Sinfra</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Soubré') { echo "selected=''"; } ?>  value="Soubré">Soubré</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Séguéla') { echo "selected=''"; } ?>  value="Séguéla">Séguéla</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Tiassalé') { echo "selected=''"; } ?>  value="Tiassalé">Tiassalé</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Tingréla') { echo "selected=''"; } ?>  value="Tingréla">Tingréla</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Toumodi') { echo "selected=''"; } ?>  value="Toumodi">Toumodi</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Vavoua') { echo "selected=''"; } ?>  value="Vavoua">Vavoua</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Yamoussoukro') { echo "selected=''"; } ?>  value="Yamoussoukro">Yamoussoukro</option>
								<option <?php if (isset($_POST['Ville']) AND $_POST['Ville'] == 'Zuénoula') { echo "selected=''"; } ?>  value="Zuénoula">Zuénoula</option>

							</select><br><br>

							<input type="text" name="Quartier" id="quartier" required="required" placeholder="COMMUNE / QUARTIER" value="<?php if(isset($quartier)) {echo $quartier;} ?>"><br><br>

							<input type="text" name="Lieu_Retrait" id="retrait" required="required" placeholder="LIEU DE RENDEZ-VOUS POUR LE RÉTRAIT" value="<?php if(isset($retrait)) {echo $retrait;} ?>"><br><br>

							<input type="password" name="Mot_de_passe" required="required" id="motdepasse" placeholder="NOUVEAU MOT DE PASSE"><br><br>

							<input type="password" name="Confirmation" id="motdepasse2" required="required" placeholder="CONFIRMATION DU NOUVEAU MOT DE PASSE">
							
							<br><br><br>

							<input type="submit" name="valide_form" value="VALIDER !">

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