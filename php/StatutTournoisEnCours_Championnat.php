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
	
	if(!isset($_SESSION['login']))
	{
		trigger_error("Vous n'êtes pas connecté.e !");
		header('Location: AffichageChampionnat.php');
	}
	
	$ut = getUtilisateurWithEmail($_SESSION['login']);
	$estAdministrateur = ($ut->getRole() === "Administrateur");
	$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
	$idU = $ut->getIdUtilisateur();
	
	$id = $_SESSION['tournoiEnCours'] ;
	$tournoi = getTournoi($id);

	if(!($idU === $tournoi->getIdGestionnaire()) && !$estAdministrateur)
	{
		header('Location: AffichageChampionnat.php');
		exit();
	}

//Quand une équipe participe à un nouveau championnat son niveau revient à 0
	$tabEquipesMatchT = getAllEquipeMatchT($id);

	if(isset($_POST['setScore']))
	{
		for($i=0;$i<sizeof($tabEquipesMatchT);++$i)
		{
			if(isset($_POST[$i]) && $_POST[$i]!="" && $tabEquipesMatchT[$i]->getScore()==-1)
			{
				$matchTemp = $tabEquipesMatchT[$i] ;
				$equipe = getEquipe($matchTemp->getIdEquipe());
				UpdateScore($equipe->getIdEquipe(), $matchTemp->getIdMatchT(),$_POST[$i]);			
			}
		}
		header('Refresh:0; url=StatutTournoisEnCours_Championnat.php');
		unset($_POST);
	}

	if(isset($_POST['setScoreRandom'])){
		for($i=0;$i<sizeof($tabEquipesMatchT);++$i)
		{
			if($tabEquipesMatchT[$i]->getScore()==-1)
			{
				$score = rand(0,10) ;
				$idEquipe = $tabEquipesMatchT[$i]->getIdEquipe() ;
				$idMatchT = $tabEquipesMatchT[$i]->getIdMatchT() ;

				UpdateScore($idEquipe, $idMatchT, $score);
				//UpdateNiveauEquipe($idEquipe, $score);

			}
		}
        header('Refresh:0; url=StatutTournoisEnCours_Championnat.php');
        unset($_POST);
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="../css/styleTournoiEnCours.css" />
	<title> Statut </title>
</head>
<body style="background-color:white">
	<div class="bandeau-haut">
		<?php 
			echo'<h1>'.$tournoi->getNom().'(Championnat)</h1>';
		?>
	</div>
	<hr>
	<hr>
	<div class="container-main1">
		<?php
			echo '<form action="StatutTournoisEnCours_Championnat.php" method="post">
			<div id="tab">
			<table>
			<tr>
			<th>Equipes A</th>
			<th>Equipes B</th>
			</tr>';
			for($i=0;$i<sizeof($tabEquipesMatchT);$i+=2)
			{
				$matchtTemp1 = $tabEquipesMatchT[$i] ;
				$nomEquipe1 = getEquipe($matchtTemp1->getIdEquipe())->getNomEquipe();

				$matchtTemp2 = $tabEquipesMatchT[$i+1] ;
				$nomEquipe2 = getEquipe($matchtTemp2->getIdEquipe())->getNomEquipe();

				if($matchtTemp1->getScore()==-1)
				{
					echo'<tr><td>'.$nomEquipe1.' (Score : <input type="number" name="'.$i.'"> )</td>';

				}
				else
				{
					echo'<tr><td>'.$nomEquipe1.' (Score : '.$tabEquipesMatchT[$i]->getScore().')</td>';
				}


				if($matchtTemp2->getScore()==-1)
				{
					echo'<td>'.$nomEquipe2.' (Score : <input type="number" name="'.($i+1).'"> )</td></tr>';
				}
				else
				{
					echo'<td>'.$nomEquipe2.' (Score : '.$tabEquipesMatchT[$i+1]->getScore().')</td></tr>';
				}		
				
			}

			echo '
			<tr>
			<td colspan=2><button type"submit" id="btn1" name="setScore" value="">Saisir score</button></td>
			</tr>
			<tr>
            <td colspan=2><button type"submit" id="btn2" name="setScoreRandom" value="">Saisir score random</button></td>
            </tr>
			</table>
			</div>
			</form>';

			echo'<form action="AffichageChampionnat.php" method="post">
			<button type"submit" id="btn1" name="" value="">Classement</button>
			</form>';

			echo'<form action="Tournois.php" method="post">
			<button type"submit" id="btn1" name="" value="">Liste Tournois</button>
			</form>';
			$bool = true ; 
			for($i=0;$i<sizeof($tabEquipesMatchT);++$i)
			{
				if($tabEquipesMatchT[$i]->getScore()==-1)
					$bool = false ;
			}
		?>
	</div>
</body>
</html>