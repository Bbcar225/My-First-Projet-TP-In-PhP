<!-- <!DOCTYPE html>
<html>
	<head>
		<title>Menu</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../style.css">
	</head>
	<body> -->
		<nav>
			<div class="divnav">
				<ul>
					<li class="home">
						<a class="les_a" href="index.php"><img src="imgs/menu/home2.png" alt="HOME">ACCUEIL</a>
					</li>
					<li class="commande">
						<a class="les_a" href="commande.php"><img src="imgs/menu/commande2.png" alt="MES COMMANDES">MES COMMANDES</a>
					</li>
					<li class="about">
						<a class="les_a" href=""><img src="imgs/menu/about.png" alt="ABOUT">A PROPOS</a>
					</li>
					<li class="contact">
						<a class="les_a" href=""><img src="imgs/menu/contact.png" alt="CONTACT">CONTACT</a>
					</li>
				</ul>
			</div>
		</nav>

										<!------------------------------------------------->
										<!--------------------- FLASH --------------------->
										<!------------------------------------------------>
		<div class="flash">
			<img src="imgs/flash/flash.png" alt="LE FLASH">
			<span><p>DEPECHEZ-VOUS JUSQU'EN DECEMBRE -10% SUR LA LIVRAISON PARTOUT A ABIDJAN</p></span>
		</div>

										<!------------------------------------------------->
										<!--------------------- SEARCH -------------------->
										<!------------------------------------------------->
										
		<?php
		
		?>								

		<form action="../search.php" method="GET">									
			<div class="search">

				<select class="liste_search" name="objet">

					<option value="empty" selected="selected">
						 CHERCHEZ-VOUS ?
					</option>

					<option value="auteur" <?php if (isset($_GET['objet']) AND $_GET['objet'] == 'auteur') { echo 'selected="selected"'; } ?>>
						AUTEUR
					</option>

					<option value="edition" <?php if (isset($_GET['objet']) AND $_GET['objet'] == 'edition') { echo 'selected="selected"'; } ?>>
						ÉDITION
					</option>

					<option value="livre" <?php if (isset($_GET['objet']) AND $_GET['objet'] == 'livre') { echo 'selected="selected"'; } ?>>
						LIVRE
					</option>

					<option value="genre" <?php if (isset($_GET['objet']) AND $_GET['objet'] == 'genre') { echo 'selected="selected"'; } ?>>
						GENRE
					</option>

				</select>

				<input class="recherche" type="search" name="terme" placeholder="CE QUE VOUS CHERCHEZ" value="<?php if (isset($_GET['terme'])) { echo $_GET['terme']; } ?>">
				<p>
					<img src="imgs/search/Search.png" alt="Search">
				</p>
			</div>
		</form>

<!-- 	</body>

</html> -->