<?php
	include('../module/TasMax.php');
	
	$j1 = new Joueur(0, "Machin", "Truc", "M@T.com", "unMotDePasse", "Utilisateur", 0, true);
	$j2 = new Joueur(1, "Jean", "Dupont", "J@D.com", "unMotDePasse", "Utilisateur", 1, false);
	$j3 = new Joueur(2, "Henri", "Guibet", "H@G.com", "unMotDePasse", "Utilisateur", 2, false);
	$j4 = new Joueur(3, "Louis", "De Funès", "L@F.com", "unMotDePasse", "Utilisateur", 3, true);
	$j5 = new Joueur(4, "Jean", "Gabin", "J@G.com", "unMotDePasse", "Utilisateur", 4, false);
	$j6 = new Joueur(5, "Robert", "Redford", "R@R.com", "unMotDePasse", "Utilisateur", 5, false);
	$j7 = new Joueur(6, "Lino", "Ventura", "L@V.com", "unMotDePasse", "Utilisateur", 6, true);
	$j8 = new Joueur(7, "Francis", "Blanche", "F@B.com", "unMotDePasse", "Utilisateur", 7, false);
	$j9 = new Joueur(8, "Venantino", "Venantini", "V@V.com", "unMotDePasse", "Utilisateur", 8, false);
	$j10 = new Joueur(9, "Jean", "Lefevre", "J@L.com", "unMotDePasse", "Utilisateur", 9, true);
	$j11 = new Joueur(10, "Bernard", "Blier", "B@B.com", "unMotDePasse", "Utilisateur", 10, false);
	$j12 = new Joueur(11, "Line", "Renaud", "M@T.com", "unMotDePasse", "Utilisateur", 11, false);
	
	$tabE1 = array($j1, $j2, $j3);
	$tabE2 = array($j4, $j5, $j6);
	$tabE3 = array($j7, $j8, $j9);
	$tabE4 = array($j10, $j11, $j12);
	
	$e1 = new Equipe(0, "Équipe 1", 0, "Une adresse 1", "04-06-04-06-04", $tabE1);
	$e2 = new Equipe(0, "Équipe 2", 0, "Une adresse 2", "04-06-04-06-04", $tabE2);
	$e3 = new Equipe(0, "Équipe 3", 0, "Une adresse 3", "04-06-04-06-04", $tabE3);
	$e4 = new Equipe(0, "Équipe 4", 0, "Une adresse 4", "04-06-04-06-04", $tabE4);
	
	$tableau = array($e1, $e2, $e3, $e4);
	
	$tasTest = new TasMax(4);
	
	$tasTest->insererAuxFeuilles($tableau);
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
		<title> Tests </title>
	</head>
	
	<body>
		<h1>Page de tests</h1>
		<p>Ceci est un paragraphe important contenant des informations importantes.</p>
		<?php
			$tasTest->afficher();
		?>
	</body>
</html>