<!-- <!DOCTYPE html>
<html>
	<head>
		<title>Header</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../style.css">
	</head>
	<body> -->
		<header>
			<div class="logo">
				<p>
					<a href="index.php">
						<img class="img_logo" src="imgs/header/logo_site.png" alt="LOGO DU SITE">
					</a>
				</p>
			</div>
			<div class="connex_panier">
				<div class="inscrip_connex">
					<a class="a_inscript" href="inscription.php">
						<?php 
							if (isset($_SESSION['Nom'])) {
						?>
						<a class="a_inscript" href="new_inscript.php">
							<div class="inscripafter">
								<img class="imgafterconnex" src="<?php if($_SESSION['Sexe'] == 'Femme') {echo "imgs/header/femaleconnexion.png";} else {echo "imgs/header/maleconnexion.png";}?>"><span class="spanconnexionafter">
									<?php
										if (isset($_SESSION['Nom']) AND isset($_SESSION['Prenom'])) {
											echo strtoupper(substr($_SESSION['Nom'], 0, 12)). ' ' .strtoupper(substr($_SESSION['Prenom'], 0, 11));
									} ?>
								</span>
							</div>
						</a>
						<?php 
						} else {
							echo '<div class="inscrip">
									<img class="imgafterconnex" src="imgs/header/inscription.png"><span>INSCRIPTION</span>
								</div>';
						}

						?>
					</a>
						<?php
							if (isset($_SESSION['Nom'])) {
						?>
						<a href="deconnexion.php">
							<div class="connexafter">
								<img class="imgafterconnex" src="imgs/header/deconnexion.png"><span>DECONNEXION</span>
							</div>
						<?php  
						} else{
							echo '<a href="connexion.php">
									<div class="connex">
										<img class="imgafterconnex" src="imgs/header/connexion.png"><span>CONNEXION</span>
									</div>
								   <a/>';
						}
						?>
				</div>
				<div class="panier">
					<a href="panier.php" title="VALIDER VOS ACHATS">
						<div class="log_pan">
							<p>
								<img src="imgs/header/log_pan.png" alt="PANIER">
								<span class="count" id="countjs">
									<?php echo $panier->count(); ?>
								</span>
							</p>
						</div>
						<div class="inf_pan">
							<p class="p1_inf_pan">MON PANIER</p>
							<p>
								<span style="margin-left: 10px; color: #E31B36; font-weight: bold;">
									<span id="totaljs">
										<?php echo number_format($panier->total1(),0,',',' '); ?>
									</span>
								</span><img style="float: right; margin-right: 35px; margin-top: -7px; background-color: rgba(227, 27, 54, 0.8); border-radius: 100%;" src="imgs/header/incrementation_argent.png" alt="SOMME ACHAT">
							</p>
						</div>
					</a>
				</div>
			</div>
		</header>

<!-- 	</body>
</html> -->