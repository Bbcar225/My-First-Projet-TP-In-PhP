<?php /*var_dump($_SESSION);*/ header("content-type:text/html; charset=iso-8859-1"); ?>
<!DOCTYPE html>
<html>

	<head>
		<title>Menu</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body>

		<div>

		<?php
			
			if ($_SESSION['Poste'] == 'Insereur' OR $_SESSION['Poste'] == 'Super Admin') {

				echo '
						<div class="auteurs">
							<p class="p_auteurs">
								AUTEURS
							</p>
							<div class="detail_auteurs">
								<p>
									<a style="text-decoration: none;" href="insertion_auteurs.php" target="_blank">
										- INSÉRER
									</a>
								</p>
								<p>
									<a style="text-decoration: none;" href="auteurs.php" target="_blank">
										- TOUT LES AUTEURS
									</a>
								</p>
							</div>
						</div>
					 ';
			}

		?>

		<?php

			if ($_SESSION['Poste'] == 'Livreur' OR $_SESSION['Poste'] == 'Super Admin') {
				
				echo '
						<div class="commandes">
							<p class="p_commandes">
								COMMANDES
							</p>
							<a style="text-decoration: none;" href="commandes.php" target="_blank">
								<div class="detail_commandes">
									<p>
										- TOUTE LES COMMANDES
									</p>
								</div>
							</a>
						</div>
					 ';
			}

		?>

		<?php

			if ($_SESSION['Poste'] == 'Insereur' OR $_SESSION['Poste'] == 'Super Admin') {
				
				echo '
						<div class="editions">
							<p class="p_editions">
								ÉDITIONS
							</p>
							<div class="detail_editions">
								<p>
									<a style="text-decoration: none;" href="insertion_edition.php" target="_blank">
										- INSÉRER
									</a>
								</p>
								<p>
									<a style="text-decoration: none;" href="editions.php" target="_blank">
										- TOUTE LES ÉDITIONS
									</a>
								</p>
							</div>
						</div>
				  	 ';
			}

		?>

		<?php

			if ($_SESSION['Poste'] == 'Insereur' OR $_SESSION['Poste'] == 'Super Admin') {
				
				echo '
						<div class="livres">
							<p class="p_livres">
								LIVRES
							</p>
							<div class="detail_livres">
								<p>
									<a style="text-decoration: none;" href="insertion_livre.php" target="_blank">
										- INSÉRER
									</a>
								</p>
								<p>
									<a style="text-decoration: none;" href="livres.php" target="_blank">
										- TOUT LES LIVRES
									</a>
								</p>
							</div>
						</div>
					 ';
			}

		?>

		<?php

			if ($_SESSION['Poste'] == 'Super Admin') {
				
				echo '
						<div class="membres">
							<p class="p_membres">
								MEMBRES
							</p>
							<a style="text-decoration: none;" href="membres.php" target="_blank">
								<div class="detail_membres">
									<p>
										- TOUT LES MEMBRES
									</p>
								</div>
							</a>
						</div>
					 ';
			}

		?>


		<?php

			if ($_SESSION['Poste'] == 'Super Admin') {
				
				echo '
						<div class="poste">
							<p class="p_poste">
								AGENTS
							</p>
							<div class="detail_poste">
								<p>
									<a style="text-decoration: none;" href="insertion_poste.php" target="_blank">
										- INSÉRER
									</a>
								</p>
								<p>
									<a style="text-decoration: none;" href="poste.php" target="_blank">
										- TOUT LES POSTES
									</a>
								</p>
							</div>
						</div>
					';
			}
			
		?>

		</div>

	</body>

</html>