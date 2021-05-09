<?php
	include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqJoueur.php');
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../BDD/reqEquipeMatchT.php');
	include_once('../module/TasMax.php');

	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(E_ALL);

	session_start();
	
	if(!isset($_SESSION['tournoiEnCours']))
	{
		trigger_error("ERREUR : Aucun tournoi n'a été sélectionné.");
	}
	
	$tournoi = getTournoi($_SESSION['tournoiEnCours']);
	$id = $tournoi->getIdTournoi();


	$tabEquipesTournoi = getAllEquipesWithMatchT($id);
	//echo sizeof($tabEquipesTournoi);
	$tabEquipes = array();
	for($i=0;$i<sizeof($tabEquipesTournoi);++$i)
	{
		array_push($tabEquipes, getEquipe($tabEquipesTournoi[$i]->getIdEquipe() ));
	}

	$tasMax = new TasMax(sizeof($tabEquipes));
	$tasMax->insererAuxFeuilles($tabEquipes);
	$tabMatchs = getAllEquipeMatchT($_SESSION['tournoiEnCours']);
	$tasMax->UpdateTabMatchs($tabMatchs);

	$tabMatchBis = $tasMax->getTabMatchs();

	$z = sizeof($tabMatchBis)-1;

	while(($z != 0) && ($tabMatchBis[(($z / 2) - 1)] != null))
	{
		$z = $z - 2;
	}
	$deb = $z;
	$fin = $z / 2;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="../css/Arbre.css" />
	<title> Statut </title>
</head>
<body style="background-color:white">
	<?php
		$tasMax->afficherArbre();	
	?>
</body>
<form action="Tournois.php" method="post">
	<button type="submit" id="btn1" name="" value="">Liste Tournois</button>
</form>
</html>