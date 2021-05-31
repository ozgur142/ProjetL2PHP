<?php
	include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqJoueur.php');
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../BDD/reqEquipeMatchT.php');
	include_once('../module/TasMax.php');


	include_once('../module/MatchPoule.php');
	include_once('../module/EquipePoule.php');
	include_once('../module/Poule.php');
	include_once('../module/ClassementPoule.php');

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

	$retour = "";
	if(isset($_SESSION['login']))
	{
		$ut = getUtilisateurWithEmail($_SESSION['login']);
		$estAdministrateur = ($ut->getRole() === "Administrateur");
		$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
		$idU = $ut->getIdUtilisateur();

		if(($idU == $tournoi->getIdGestionnaire()) || $estAdministrateur)
		{
			$retour = "<form action=\"StatutTournoiEnCours_PhasesFinales.php\" method=\"post\">
			<button type=\"submit\" id=\"btn1\" name=\"\" >Retour</button>
			</form>";
		}

	}

	

	$tabPoules = getAllPouleTournoi($id);
	$nbMatchT = 0;
	$nbEqGagnantes = 0;
	
	for($i=0;$i<sizeof($tabPoules);++$i)
	{
		$nbEq = $tabPoules[$i]->getNbEquipes();
		
		$nbMatchT += ((($nbEq - 1) * $nbEq) / 2);
		
		$nbEqGagnantes += 2;
	}
	
	while(!puissanceDe2($nbEqGagnantes))
		++$nbEqGagnantes;
	
	$nbMatchsGagnants = $nbEqGagnantes - 1 ;
	
	$nbMatchT += $nbMatchsGagnants;


	$tabEquipesFinales = array();

	for($i=0;$i<sizeof($tabPoules);++$i)
	{
		array_push($tabEquipesFinales, getAllEquipePouleWithIdPoule($tabPoules[$i]->getIdPoule()));
	}

	$tabEquipesGagnantes = array() ;
	

	$max = $nbEqGagnantes ;

	$index = 0 ;
	for($i=0;$i<sizeof($tabEquipesFinales);++$i)
	{
		$tabTrie = new ClassementPoule($tabPoules[$i]->getIdPoule());
		$tab = $tabTrie->getTabEq() ;
		for($j=0;$j<sizeof($tabEquipesFinales[$i])/2;++$j)
		{
			$nbEqPoule = sizeof($tabEquipesFinales[$i]) ;
			$tabEquipesGagnantes[$index] = $tab[$nbEqPoule-$j-1] ;
			++$index ;
		}
	}


	$tabMatchTPhasesFinales = getAllMatchTPhasesFinales($id) ;

	$tabEquipes1 = array() ;

	for($i=0;$i<sizeof($tab);++$i)
	{
		array_push($tabEquipes1, getEquipe($tab[$i]->getIdEquipe()));
	}

	$tabEquipesMatchsTemp = getAllEquipeMatchT($id);

	$tabEquipesBonSens = array();


	for($i=0;$i<$nbEqGagnantes;++$i)
	{
		$ide = $tabEquipesMatchsTemp[$i]->getIdEquipe();
		$tabEquipesBonSens[$i] = getEquipe($ide);
	}

	$tabEquipes = array();
	
	for($i=0;$i<sizeof($tabEquipesBonSens);++$i)
	{
		array_push($tabEquipes, getEquipe($tabEquipesBonSens[$i]->getIdEquipe() ));
	}

	$tasMax = new TasMax(sizeof($tabEquipes));
	$tasMax->insererAuxFeuilles($tabEquipes);
	$tabMatchs = getAllEquipeMatchT($_SESSION['tournoiEnCours']);
	$tasMax->UpdatePhasesFinales($id);

	$tabMatchs = $tasMax->getTabMatchs();

	$z = sizeof($tabMatchs)-1;

	while(($z != 0) && ($tabMatchs[(($z / 2) - 1)] != null))
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
<body>
	<div class="bandeau-haut">
		<?php 
			echo'<h1>'.$tournoi->getNom().'</h1>';
		?>
	</div>
	<hr>
	<hr>
	<?php
		$tasMax->afficherArbre();	
	?>
</body>
<form action="Tournois.php" method="post">
	<button type="submit" id="btn1" name="" value="">Liste Tournois</button>
</form>
<?php
	echo $retour ;
?>
</html>