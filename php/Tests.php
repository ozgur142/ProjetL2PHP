<?php
	include_once('../module/TasMax.php');
	
	$j1 = new Joueur(0, "Machin", "Truc", "M@T.com", "unMotDePasse", "Utilisateur",0, 0, true);
	$j2 = new Joueur(1, "Jean", "Dupont", "J@D.com", "unMotDePasse", "Utilisateur",1, 1, false);
	$j3 = new Joueur(2, "Henri", "Guibet", "H@G.com", "unMotDePasse", "Utilisateur",2, 2, false);
	$j4 = new Joueur(3, "Louis", "De Funès", "L@F.com", "unMotDePasse", "Utilisateur",3, 3, true);
	$j5 = new Joueur(4, "Jean", "Gabin", "J@G.com", "unMotDePasse", "Utilisateur",4, 4, false);
	$j6 = new Joueur(5, "Robert", "Redford", "R@R.com", "unMotDePasse", "Utilisateur",5, 5, false);
	$j7 = new Joueur(6, "Lino", "Ventura", "L@V.com", "unMotDePasse", "Utilisateur",6, 6, true);
	$j8 = new Joueur(7, "Francis", "Blanche", "F@B.com", "unMotDePasse", "Utilisateur",7, 7, false);
	$j9 = new Joueur(8, "Venantino", "Venantini", "V@V.com", "unMotDePasse", "Utilisateur",8, 8, false);
	$j10 = new Joueur(9, "Jean", "Lefevre", "J@L.com", "unMotDePasse", "Utilisateur",9, 9, true);
	$j11 = new Joueur(10, "Bernard", "Blier", "B@B.com", "unMotDePasse", "Utilisateur",10, 10, false);
	$j12 = new Joueur(11, "Line", "Renaud", "M@T.com", "unMotDePasse", "Utilisateur",11, 11, false);
	
	$tabE1 = array($j1, $j2, $j3);
	$tabE2 = array($j4, $j5, $j6);
	$tabE3 = array($j7, $j8, $j9);
	$tabE4 = array($j10, $j11, $j12);
	
	//De préférence les noms des équipes <= 12 caractères
	$e1 = new Equipe(0, "bordeaux", 0, "Une adresse 1", "04-06-04-06-04", $tabE1);
	$e2 = new Equipe(1, "psg", 0, "Une adresse 2", "04-06-04-06-04", $tabE2);
	$e3 = new Equipe(2, "montpellier", 0, "Une adresse 3", "04-06-04-06-04", $tabE3);
	$e4 = new Equipe(3, "toulouse", 0, "Une adresse 4", "04-06-04-06-04", $tabE4);
	$e5 = new Equipe(4, "lyon", 0, "Une adresse 1", "04-06-04-06-04", $tabE1);
	$e6 = new Equipe(5, "om", 0, "Une adresse 2", "04-06-04-06-04", $tabE2);
	$e7 = new Equipe(6, "st-etienne", 0, "Une adresse 3", "04-06-04-06-04", $tabE3);
	$e8 = new Equipe(7, "barcelone", 0, "Une adresse 4", "04-06-04-06-04", $tabE4);
	$e9 = new Equipe(8, "real madrid", 0, "Une adresse 1", "04-06-04-06-04", $tabE1);
	$e10 = new Equipe(9, "manchester", 0, "Une adresse 2", "04-06-04-06-04", $tabE2);
	$e11 = new Equipe(10, "juvintus", 0, "Une adresse 3", "04-06-04-06-04", $tabE3);
	$e12 = new Equipe(11, "chelsea", 0, "Une adresse 4", "04-06-04-06-04", $tabE4);
	$e13 = new Equipe(12, "lille", 0, "Une adresse 1", "04-06-04-06-04", $tabE1);
	$e14 = new Equipe(13, "milan", 0, "Une adresse 2", "04-06-04-06-04", $tabE2);
	$e15 = new Equipe(14, "liverpool", 0, "Une adresse 3", "04-06-04-06-04", $tabE3);
	$e16 = new Equipe(15, "monaco", 0, "Une adresse 4", "04-06-04-06-04", $tabE4);

	$tableau = array($e1, $e2, $e3, $e4,$e5, $e6, $e7, $e8, $e9, $e10, $e11, $e12, $e13, $e14, $e15, $e16, $e1, $e2, $e3, $e4,$e5, $e6, $e7, $e8, $e9, $e10, $e11, $e12, $e13, $e14, $e15, $e16);
	$tasTest = new TasMax(32);
	
	$tasTest->insererAuxFeuilles($tableau);

	
	
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/Arbre.css" />
		<title> Tests </title>
	</head>
	<div>
			<a href="../index.php">
			<img src="../img/home.png">
			</a>
		</div>
		<style>
			body div img {
				width:50px;
				border:5px groove white;
				padding:5px;
				float:right;
	
			}
		</style>
	<form method="get">

		<input type="submit" value="JOUER" name="jouer" style="margin-right:50%;float:right">
		<input type="submit" value="EFFACER" name="effacer" style="float:right">
		<input type="submit" value="MELANGER" name="melanger" style="float:right">
	</form>
	<!--Les boutons créent un décallage sur l'arbre c'est normal -->

	
	<body>
		<?php
		//PROBLEME
		//Si la page est refresh les boutons restent actifs et les fonction sont quand même appelées.
		session_start();


		if(isset($_GET['effacer'])){
			session_destroy();
			echo $tasTest->afficherArbre(); 
		}
		else{
			if(!isset($_SESSION['historique'])) {
				$_SESSION['historique'][0]=$tasTest;
				if(isset($_GET['melanger'])){
					$_SESSION['historique'][0]->melangerEquipes();
				}
			}
			if(!isset($_GET['jouer'])){
				echo $_SESSION['historique'][0]->afficherArbre();
			}
		}

		if(isset($_GET['melanger'])){
			$_SESSION['historique'][0]->melangerEquipes();
		}

		if(isset($_GET['jouer'])){
			$_SESSION['historique'][0]->genereMatchs();
			$_SESSION['historique'][0]->prochainTour();
			echo $_SESSION['historique'][0]->afficherArbre();
		}
		?>
	</body>
</html>
