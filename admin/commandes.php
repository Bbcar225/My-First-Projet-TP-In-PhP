<?php

	session_start();
	
	header("content-type:text/html; charset=iso-8859-1");

// Connexion à ma BDD
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

	if (isset($_SESSION['Poste']) AND $_SESSION['Poste'] == 'Insereur') {

		header("Location: errorsfatal.php");

	}

?>
<!DOCTYPE html>
<html>

	<head>
		<title>COMMANDES</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

	<body style="background-color: rgba(152, 245, 255, 0.17);">

		<?php
			include 'include/head.php';
		?>

		<div>

			<div class="search">
				<input type="search" name="">
			</div>

			<div>
				<table class="table_commandes" style="border-collapse: collapse;">
					<thead>
						<tr>
							<th class="date_commandes">
								DATE COMMANDE
							</th>
							<th class="nombre_commandes">
								NOMBRE DE COMMANDE
							</th>
						</tr>
					</thead>
					<tbody class="tbody_commandes">

						<?php

							$reqcommandes = $bdd->prepare('
															SELECT 
																DISTINCT SUBSTRING(Date_Commande, 1,10) 
															FROM commande
															ORDER BY SUBSTRING(Date_Commande, 1,10) DESC
														 ');
							$reqcommandes->execute();

							$reqcommandesnbres = $bdd->query('SELECT COUNT( DISTINCT Id_Commande) FROM commande GROUP BY SUBSTRING(Date_Commande, 1,10) ORDER BY SUBSTRING(Date_Commande, 1,10) DESC');

							while ($reqcommandesdata = $reqcommandes->fetch() AND $reqcommandesnbresdata = $reqcommandesnbres->fetch()) {

						?>

						<tr>
							<td>
								<a href="liste_commandes.php?date_commande=<?php echo $reqcommandesdata[0]; ?>">
									<?php
										echo $reqcommandesdata[0];
									?>
								</a>
							</td>
							<td>
								<span style="font-weight: bold;">
									<?php
										echo $reqcommandesnbresdata[0];
									?>
								</span>
							</td>
						</tr>

						<?php
						}
						?>

					</tbody>
				</table>
			</div>

		</div>
	</body>

</html>