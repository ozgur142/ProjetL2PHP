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
	
	if(!isset($_SESSION['tournoiPasse']))
	{
		trigger_error("ERREUR : Aucun tournoi n'a été sélectionné.");
	}
	
	$tournoi = getTournoi($_SESSION['tournoiPasse']);
	$id = $tournoi->getIdTournoi();

	$retour = "";
	if(isset($_SESSION['login']))
	{
		$ut = getUtilisateurWithEmail($_SESSION['login']);
		$estAdministrateur = ($ut->getRole() === "Administrateur");
		$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
		$idU = $ut->getIdUtilisateur();
	}

	$tabEquipesTournoi = getAllEquipesByNiveau($id);
	$tabEquipeMatchT = getAllEquipeMatchT($id) ;


	$tab = array(sizeof($tabEquipesTournoi)) ;
	$tabClassement = array() ;
	$tabC = array() ;
	array_push($tabC,0);
	array_push($tabC,0);
	array_push($tabC,0);

	for($i=0;$i<sizeof($tabEquipesTournoi);++$i)
	{
		array_push($tabClassement, $tabC );
	}

	for($i=0;$i<sizeof($tabEquipeMatchT);$i+=2)
	{
		$id1 = $tabEquipeMatchT[$i]->getIdEquipe() ;
		$n1 = 0;
		$v1 = 0 ;
		$d1 = 0 ;

		$id2 = $tabEquipeMatchT[$i+1]->getIdEquipe() ;
		$n2 = 0;
		$v2 = 0 ;
		$d2 = 0 ;

		if(estMatchNull($tabEquipeMatchT[$i]->getIdMatchT()))
		{
			++$n1 ;
			++$n2 ;
		}
		elseif(getIdEquipeGagnante($tabEquipeMatchT[$i]->getIdMatchT()) == $id1)
		{
			++$v1;
			++$d2;
		}
		else
		{
			++$v2;
			++$d1;
		}

		$indiceTab = 0 ;

		$j = 0 ;
		$k = 0;


		for($z=0;$z<sizeof($tabEquipesTournoi);++$z)
		{
			if($tabEquipesTournoi[$j]->getIdEquipe()!=$id1)
				++$j;
			if($tabEquipesTournoi[$k]->getIdEquipe()!=$id2 )
				++$k;
		}
		if($tabEquipesTournoi[$j]->getIdEquipe()==$id1){
			$tabClassement[$j][0]+=$v1 ;
			$tabClassement[$j][1]+=$d1 ;
			$tabClassement[$j][2]+=$n1 ;
		}

		if($tabEquipesTournoi[$k]->getIdEquipe()==$id2){
			$tabClassement[$k][0]+=$v2 ;
			$tabClassement[$k][1]+=$d2 ;
			$tabClassement[$k][2]+=$n2 ;
		}
	}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="../css/styleTournoiEnCours.css" />
	<title> Statut </title>
</head>
<body>
	<div class="bandeau-haut">
		<?php 
			echo'<h1>'.$tournoi->getNom().' (Championnat)</h1>';
		?>
	</div>
	<hr>
	<hr>
	<div class="container-main1">
	<?php

	$tabCroissant = array() ;

		echo '
			<div id="tab">
			<table>
			<tr><th colspan=5>Classement</th></tr>
			<tr>
			<th>Equipes</th>
			<th>Points</th>
			<th>V</th>
			<th>D</th>
			<th>N</th>
			</tr>';
		for($i=0;$i<sizeof($tabEquipesTournoi);++$i)
		{
			$equipe = getEquipe($tabEquipesTournoi[$i]->getIdEquipe());
			$points = $tabClassement[$i][0]*4 + $tabClassement[$i][1] + $tabClassement[$i][2]*2 ;
			echo'<tr>
			<td>'.$equipe->getNomEquipe().'</td>
			<td>'.$points.'</td>
			<td>'.$tabClassement[$i][0].'</td>
			<td>'.$tabClassement[$i][1].'</td>
			<td>'.$tabClassement[$i][2].'</td>
			</tr>';
		}
		echo'</table>
			</div>
			';

	?>
	</div>
</body>
<form action="Tournois.php" method="post">
	<button type="submit" id="btn1" name="" value="">Liste Tournois</button>
</form>
</html>