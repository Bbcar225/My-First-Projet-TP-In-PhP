<!DOCTYPE html>
<html>

	<head>
		<title>Head</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body>
		<header style="padding-bottom: 100px;">
			<div>
				<div class="logo">
					<a href="http://librelivre.ci/Admin/">
						<img src="imgs/head/logo_site.png">
					</a>
				</div>

				<div class="connexion_deconnexion">
					<div class="nom_connexion">
						<p>
							<span style="font-weight: bold; color: blue; cursor: pointer;">
								<img src="imgs/head/connexion.png">
								<?php
									if (isset($_SESSION['Nom']) AND isset($_SESSION['Prenom'])) {
										echo strtoupper(substr($_SESSION['Nom'], 0, 7));
										echo " ";
										echo strtoupper(substr($_SESSION['Prenom'], 0, 12));
									}
								?>
							</span>
						</p>
					</div>
					<a href="deconnexion.php">
						<div class="deconnexion">
							<p>
								<span style="font-weight: bold;">
									<img src="imgs/head/deconnexion.png">DECONNEXION
								</span>
							</p>
						</div>
					</a>
				</div>

				<div class="home">
					<a href="http://librelivre.ci/Admin/">
						<img src="imgs/head/home.png">
					</a>
				</div>
			</div>
		</header>
	</body>

</html>