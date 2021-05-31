<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(E_ALL);

	include_once('../module/FctGenerales.php');
	include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqJoueur.php');
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../BDD/reqEquipeMatchT.php');
	include_once('../module/TasMax.php');


	session_start();
	if(!isset($_SESSION['login']))
		{
			trigger_error("Vous ne pouvez pas accéder à cette page.");
			header('Location: Tournois.php');
			exit();
		}
	$ut = getUtilisateurWithEmail($_SESSION['login']);
	$estAdministrateur = ($ut->getRole() === "Administrateur");
	$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
	$id = $ut->getIdUtilisateur();

	if(!$estGestionnaire)
	{
		if(!$estAdministrateur)
		{
			trigger_error("Vous n'avez pas les droits !");
			header('Location: Tournois.php');
			exit();
		}
	}


	$id = $_SESSION['tournoi'] ;
	$tournoi = getTournoi($id);
	$tabEquipesTournoi = getEquipeTournoiWithIdTournoi($tournoi->getIdTournoi());
	$nbEquipesInscrites = 0 ;
	$tabEquipes = array();
	for($i=0;$i<sizeof($tabEquipesTournoi);++$i)
	{
		if($tabEquipesTournoi[$i]->getEstInscrite())
			++$nbEquipesInscrites;
		array_push($tabEquipes,getEquipe($tabEquipesTournoi[$i]->getIdEquipe()));
	}

	$nbEquipesTotal = $tournoi->getNombreTotalEquipes() ;
	
	$tabMatchs = getAllMatchT($tournoi->getIdTournoi()) ;

	$tabEquipesMatchT = getAllEquipeMatchT($tournoi->getIdTournoi()) ;
	
	$index = 0 ;
	if(isset($_POST['Saisir']) && sizeof($tabEquipesMatchT)!= (sizeof($tabEquipes)*(sizeof($tabEquipes)-1)) / 2)
	{
		for($i=0;$i<count($tabEquipes);++$i)
		{
			for($j=$i+1;$j<count($tabEquipes);++$j)
			{
				insertEquipeMatchT($tabMatchs[$index]->getIdMatchT(),$tabEquipes[$i]->getIdEquipe(),$tabEquipes[$j]->getIdEquipe());
				++$index;
			}
		}
		header('Refresh:0; url=SaisieMatchsChampionnat.php');
		unset($_POST);
	}



?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="../css/styleStatut.css" />
	<title> Saisie Matchs </title>
	<style>
		select {
			background-color:#333333;
			color:white;
			font-family:Helvetica Neue,Helvetica,Arial,sans-serif;
			width:70%;
			height:25px;
			text-align: center;
			font-size:18px;
		}
	</style>
</head>
<body>
	<div class="bandeau-haut">
		<?php 
			echo'<h1>'.$tournoi->getNom().'</h1>';
		?>
	</div>
	<hr>
	<hr>

	<div class="container-main2">
		<h1 style="font-size:35px"></h1>
		<?php
			echo '<div id="tab1">';
			echo'<form action="SaisieMatchsChampionnat.php" method="post">
				<table>
				<tr>
				<th rowspan="1">Matchs</th>
				<th>Equipe A</th>
				<th>Equipe B</th>
				<th>Date/Horaire</th>
				<th>Statut match</th>
				</tr>';
				if(count($tabEquipesMatchT)>0)
				{
					$indexMatch = 1 ;
					for($i=0;$i<count($tabEquipesMatchT);$i = $i + 2)
					{
						$e1 = getEquipe($tabEquipesMatchT[$i]->getIdEquipe());
						$e2 = getEquipe($tabEquipesMatchT[$i+1]->getIdEquipe());
						$nom1=$e1->getNomEquipe();
						$nom2=$e2->getNomEquipe();

						echo '<tr><td style="font-weight: bold">Match n°'.$indexMatch.'</td><td>'.$nom1.'</td><td>'.$nom2.'</td><td>'.date("d/m/Y",strtotime($tabMatchs[$indexMatch-1]->getDate())).' '.$tabMatchs[$indexMatch-1]->getHoraire().'</td><td>Validé<td></tr>';		
						++$indexMatch;
					}
					
				}
				
			echo '</table>
			</form>
			</div>';


			if(sizeof($tabEquipesMatchT)!= ($nbEquipesTotal*($nbEquipesTotal-1)) )
			{
				echo '
				<form action="SaisieMatchsChampionnat.php" method="post">
					<button type="submit" id="btn2" name="Saisir" value="" style="margin:auto">Saisir</button>
				</form>';
			}
		?>
	<form action="StatutTournoisAVenir_Championnat.php" method="post">
		<button type="submit" id="btn2" value="">Retour</button>
	</form>
	</div>

</body>
</html>