<?php
	
	require 'panier.class.php';
	
	$panier = new panier();

	header("content-type:text/html; charset=iso-8859-1");
	// session_start();
	
// Connexion à ma BDD
	try{

		$bdd = new PDO("mysql:host=localhost;dbname=commerce", 'root', '');
		$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	catch(PDOEcxeption $e){

		echo 'Echec de la connexion : ' . $e->getMessage();
	}

	if (!isset($_SESSION['Id'])) {
	
		header('Location: errosfatal.php');
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

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Mon Compte</title>
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
					<h3 style="text-align: left; margin: 0px; background-color: #98F5FF;">
						MON COMPTE
						<span style="float: right;"><a href="modif_profil.html">MODIFIER</a></span>
					</h3>
				</div>
				<br>
				<div>

				<?php
					if (isset($_SESSION['Id']) OR isset($_SESSION['Nom'])) {
				?>

					<p class="p_newInscript">
						<span>MON NOM : </span><span><?php echo $_SESSION['Nom']; ?></span></p>
					<p class="p_newInscript"><span>MON PRENOM : </span><span><?php echo $_SESSION['Prenom']; ?></span></P>
					<P class="p_newInscript"><span>SEXE : </span><span><?php echo $_SESSION['Sexe']; ?></span></P>
					<P class="p_newInscript"><span>DATE DE NAISSANCE : </span><span><?php echo $_SESSION['Date_Naissance']; ?></span></P>
					<P class="p_newInscript"><span>MES NUMEROS : </span><span><?php echo $_SESSION['Telephone1']; ?></span></P>
					<P class="p_newInscript"><span>MON EMAIL : </span><span><?php echo $_SESSION['Mail'] ?></span></P>
					<P class="p_newInscript"><span>MA VILLE : </span><span><?php echo $_SESSION['Ville'] ?></span></P>
					<P class="p_newInscript"><span>MON QUARTIER : </span><span><?php echo $_SESSION['Quartier'] ?></span></P>
					<P class="p_newInscript"><span>MON POINT DE RETRAIT : </span><span><?php echo $_SESSION['Lieu_Retrait'] ?></span></P>
				</div>
				<?php
				}
				?>
				<!-- <h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">BIENVENUE 
					<?php
						/*if (isset($_SESSION['Sexe'])) {
								
							if ($_SESSION['Sexe'] == 'Homme') {
								echo 'MR ' .$_SESSION['Nom']. ' ' .$_SESSION['Prenom'];
							} else {
								echo 'MME ' .$_SESSION['Nom']. ' ' .$_SESSION['Prenom'];
							}
						} else {
							echo "";
						}*/
					?>
				</h3> -->
			</div>
		<?php

		?>
		</div>
		<?php
			include 'include/edition.php';
			include 'include/footer.php';
		 ?>
	</body>
</html>