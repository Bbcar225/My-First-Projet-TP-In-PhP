<!-- <!DOCTYPE html>
<html>
	<head>
		<title>Top</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../style.css">
	</head>
	<body> -->
		<div class="achat_genre">
			<div class="achat">
				<h3 style="text-align: center; margin: 0px; background-color: #98F5FF;">TOP ACHAT</h3>
				<?php
					// Connexion à ma BDD
					try{

						$bdd = new PDO("mysql:host=localhost;dbname=commerce", 'root', '');
						$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					}

					catch(PDOEcxeption $e){

						echo 'Echec de la connexion : ' . $e->getMessage();
					}

					$reqbookstop = $bdd->prepare('
													SELECT 
														commande.Id_Livre AS Id_Livre,
														livre.nom AS Nom_Livre
													FROM commande
													INNER JOIN livre
													ON livre.Id = commande.Id_Livre
													GROUP BY Id_Livre
													ORDER BY COUNT(Id_Livre) DESC
													LIMIT 0,5
												');
					$reqbookstop->execute();
					
					while ($reqbooksalltop = $reqbookstop->fetch()) {
					?>
						<a href="achat.php?id_livre=<?php echo $reqbooksalltop['Id_Livre']; ?>"><?php echo $reqbooksalltop['Nom_Livre']; ?></a>
				<?php
				}
				?>
			</div>

			<div class="genre">
				<h3 style="text-align: center; margin-top: 5px; background-color: #98F5FF;">GENRES DE LIVRES</h3>
					<?php
						$reqgenre = $bdd->prepare('SELECT DISTINCT Genre FROM livre GROUP BY Genre');
						$reqgenre->execute();

						while ($reqgenredata = $reqgenre->fetch()) {
						?>
							<a href="genre.php?nom_genre=<?php echo $reqgenredata['Genre']; ?>">
								<?php echo $reqgenredata['Genre']; ?>
							</a>
					<?php
						}
					?>
				</div>
		</div>
			
<!-- 	</body>

</html> -->