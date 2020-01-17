<!-- <!DOCTYPE html>
<html>
	<head>
		<title>Top</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../style.css">
	</head>
	<body> -->
		<div class="edition">
				<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">TOP EDITIONS</h3><br>
			<?php
				// Connexion à ma BDD
				try{

					$bdd = new PDO("mysql:host=localhost;dbname=commerce", 'root', '');
					$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}

				catch(PDOEcxeption $e){

					echo 'Echec de la connexion : ' . $e->getMessage();
				}

				$reqtopedi = $bdd->prepare('
												SELECT 
														commande.Id_Livre AS Id_Livre,
														livre.nom AS Nom_Livre,
														livre.Id_Edition AS Id_Edition,
														edition.Logo AS Logo_Edition
												FROM commande
												INNER JOIN livre
												ON livre.Id = commande.Id_Livre
												INNER JOIN edition
												ON edition.Id = livre.Id_Edition
												GROUP BY commande.Id_Livre
												ORDER BY COUNT(commande.Id_Livre) DESC
												LIMIT 0,5
										   ');
				$reqtopedi->execute();
				while ($reqtopedidata = $reqtopedi->fetch()) {
			?>
				<a href="edition.php?id_edition=<?php echo $reqtopedidata['Id_Edition']; ?>">
					<div class="logo_edition">
						<img src="imgs/editeur/<?php echo $reqtopedidata['Logo_Edition']; ?>" alt="LOGO EDITIONS">
					</div><br>
				</a>
			<?php
				}
			?>
		</div>
<!-- 	</body>

</html> -->