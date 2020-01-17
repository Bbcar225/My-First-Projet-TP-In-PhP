<?php

	class panier{

	// FONCTION POUR LA CREATION DU PANIER
		public function __construct(){

			if (!isset($_SESSION)) {

				session_start();

			}

			if (!isset($_SESSION['panier'])) {

				$_SESSION['panier'] = array();

			}
		}

	// FONCTION POUR LA SOMME DES LIVRES DU PANIER
		public function count(){

			return array_sum($_SESSION['panier']);

		}

	// FONCTION POUR LE TOTAL DU PRIX AVEC PRIX DE LA LIVRAISON
		public function total1(){

		// Connexion à ma BDD
			try{

				$bdd = new PDO("mysql:host=localhost;dbname=commerce", 'root', '');
				$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			}

			catch(PDOEcxeption $e){

				echo 'Echec de la connexion : ' . $e->getMessage();

			}

			$total = 0;
			$ids = array_keys($_SESSION['panier']);

			if (empty($ids)) {

				$reqinfallbooksdata = array();

			}
			else{

				$reqinfallbooks = $bdd->prepare('SELECT Id, Prix, Prix_Livraison FROM livre WHERE Id IN ('.implode(',',$ids).')');
				$reqinfallbooks->execute();

				while ($reqinfallbooksdata = $reqinfallbooks->fetch()) {

					$total += ($reqinfallbooksdata['Prix'] + $reqinfallbooksdata['Prix_Livraison']) * $_SESSION['panier'][$reqinfallbooksdata['Id']];

					}
			}

			return $total;

		}

	// FONCTION POUR LE TOTAL DU PRIX SANS LA LIVRAISON
		public function total2(){

		// Connexion à ma BDD
			try{

				$bdd = new PDO("mysql:host=localhost;dbname=commerce", 'root', '');
				$bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			}

			catch(PDOEcxeption $e){

				echo 'Echec de la connexion : ' . $e->getMessage();

			}

			$total = 0;
			$ids = array_keys($_SESSION['panier']);

			if (empty($ids)) {

				$reqinfallbooksdata = array();

			}
			else{

				$reqinfallbooks = $bdd->prepare('SELECT Id, Prix, Prix_Livraison FROM livre WHERE Id IN ('.implode(',',$ids).')');
				$reqinfallbooks->execute();

				while ($reqinfallbooksdata = $reqinfallbooks->fetch()) {

					$total += $reqinfallbooksdata['Prix'] * $_SESSION['panier'][$reqinfallbooksdata['Id']];

				}
			}

			return $total;
		}

	// FONCTION POUR L'AJOUT D'UN LIVRE DANS LE PANIER
		public function add($id_livre){

			if (isset($_SESSION['panier'][$id_livre])) {

				$_SESSION['panier'][$id_livre]++;

			}
			else{

				$_SESSION['panier'][$id_livre] = 1;

			}
		}

	// FONCTION POUR LA DECREMENTATION DE LA QUANTITE D'UN LIVRE
		public function decremente($id_livre){

			if (isset($_SESSION['panier'][$id_livre]) AND $_SESSION['panier'][$id_livre] > 0) {

				$_SESSION['panier'][$id_livre]--;

				if ($_SESSION['panier'][$id_livre] == 0) {

					unset($_SESSION['panier'][$id_livre]);

				}
			}
		}

	// FONCTION POUR L'INCREMENTATION DE LA QUANTITE D'UN LIVRE
		public function incremente($id_livre){

			if (isset($_SESSION['panier'][$id_livre]) AND $_SESSION['panier'][$id_livre] > 0) {

				$_SESSION['panier'][$id_livre]++;

				if ($_SESSION['panier'][$id_livre] == 0) {

					unset($_SESSION['panier'][$id_livre]);

				}
			}
		}
	}
	
?>