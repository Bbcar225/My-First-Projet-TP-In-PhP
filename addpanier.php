<?php
	
	require 'panier.class.php';
	
	$panier = new panier();

	header("content-type:text/html; charset=iso-8859-1");

// Connexion à ma BDD
	try{

		$bdd = new PDO("mysql:host=localhost;dbname=commerce", 'root', '');
		$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	catch(PDOEcxeption $e){

		echo 'Echec de la connexion : ' . $e->getMessage();
	}

	$json = array('error'=>true);

// Requète pour les données de ma bdd
	if (isset($_GET['id_livre']) AND !empty($_GET['id_livre'])) {
							
		$reqinfbooks = $bdd->prepare('SELECT Id FROM livre WHERE Id = ?');
		$reqinfbooks->execute([$_GET['id_livre']]);
		$reqinfbooksdata = $reqinfbooks->fetch();

		if ($reqinfbooksdata['Id'] == $_GET['id_livre']) {

			$reqinfallbooks = $bdd->prepare('
												SELECT
													livre.*,
													auteur.Nom_Prenom AS Auteur
												FROM livre 
												INNER JOIN auteur
												ON livre.Id_Auteur = auteur.Id
												WHERE livre.Id = ?
											');

			$reqinfallbooks->execute([$reqinfbooksdata['Id']]);
			$reqinfallbooksdata = $reqinfallbooks->fetch();

			$idlivre = $reqinfallbooksdata['Id'];
			$nomlivre = $reqinfallbooksdata['Nom'];
			$nomauteur = $reqinfallbooksdata['Auteur'];
			$prix = $reqinfallbooksdata['Prix'];
			$prixlivraison = $reqinfallbooksdata['Prix_Livraison'];

			$panier->add($reqinfallbooksdata[0]);

			$json['totaljs'] = number_format($panier->total1(),0,',',' ');

			$json['countjs'] = $panier->count();

			$json['error'] = false;

			$json['message'] = "LIVRE BIEN AJOUTE AU PANIER .";

			// echo 'Livre ajouter au panier <a href="javascript:history.back()">Retoure ?<a/>';

			if ($json['message']) {

			}
			else{
				header("Location: achat.php?id_livre=$reqinfallbooksdata[0]");
			}

		}
		else{

			$json['message'] = "CE LIVRE N'EXISTE PAS";

			header("Location: errosfatal.php");

		}
		
	}
	else{

		$json['message'] = "RIEN AJOUTE AU PANIER";

		header("Location: errosfatal.php");
		
	}

	echo json_encode($json);

?>