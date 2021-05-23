<?php
	include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqJoueur.php');
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../BDD/reqEquipeMatchT.php');
	include_once('../module/TasMax.php');


	session_start();
	
	if(!isset($_SESSION['login']))
		trigger_error("Vous n'êtes pas connecté.e !");
	
	$ut = getUtilisateurWithEmail($_SESSION['login']);
	$estAdministrateur = ($ut->getRole() === "Administrateur");
	$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
	$idU = $ut->getIdUtilisateur();


	$id = $_SESSION['tournoiEnCours'] ;
	$tournoi = getTournoi($id);


	
	if(!$estGestionnaire || !($idU == $tournoi->getIdGestionnaire()))
	{
		if(!$estAdministrateur)
		{
			trigger_error("Vous n'avez pas les droits !");
			header('Location: Tournois.php');
			exit();
		}
	}


	$tabEquipesTournoi = getEquipeTournoiWithIdTournoi($id);
	$tabEquipesMatchsTemp = getAllEquipeMatchT($id);

	$tabEquipesBonSens = array();

	for($i=0;$i<sizeof($tabEquipesTournoi);++$i)
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

	$tasMax->Update($id);

	$tabMatchs = $tasMax->getTabMatchs();

	$z = sizeof($tabMatchs)-1;

	while(($z != 0) && ($tabMatchs[(($z / 2) - 1)] != null))
	{
		$z = $z - 2;
	}
	$deb = $z;
	$fin = $z / 2;

	if(isset($_POST['setScore']))
	{
		for($i=$deb;$i>=$fin;--$i)
		{
			if(isset($_POST[$i]) && $_POST[$i]!="")
			{
				$tasMax->setScoreTabMatchs($_POST[$i],$i);
			}
		}
		unset($_POST);
	}
	
	if($estAdministrateur || $estGestionnaire)
	{
		if(isset($_POST) && isset($_POST['TourSuivant']))
		{
			if(!$tasMax->tourPassable())
				trigger_error("Il y a un problème avec le tas max.");
			
			$tasMax->prochainTour($id);
		}
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
			echo'<h1>'.$tournoi->getNom().'</h1>';
		?>
	</div>
	<hr>
	<hr>
	<?php
	?>
	<div class="container-main1">
		<?php
		//$tasMax->afficher();
			echo '<form action="StatutTournoiEnCours.php" method="post">
			<div id="tab">
			<table>
			<tr>
			<th>Equipes A</th>
			<th>Equipes B</th>
			</tr>';
			if($tabMatchs[1] && $tabMatchs[1]->getScore()!=-1)
			{
				$deb=0;
				$fin=0;
			}
			for($i=$deb;$i>$fin;$i = $i - 2)
			{
				if($tabMatchs[$i]->getScore()==-1)
				{
					echo'<tr><td>'.getEquipe($tabMatchs[$i]->getIdEquipe())->getNomEquipe().' (Score : <input type="number" name="'.$i.'")</td>';

				}
				else
				{
					echo'<tr><td>'.getEquipe($tabMatchs[$i]->getIdEquipe())->getNomEquipe().' (Score : '.$tabMatchs[$i]->getScore().')</td>';
				}

				if($tabMatchs[$i-1]->getScore()==-1)
				{
					echo'<td>'.getEquipe($tabMatchs[$i-1]->getIdEquipe())->getNomEquipe().' (Score : <input type="number" name="'.($i-1).'")</td></tr>';
				}
				else
				{
					echo'<td>'.getEquipe($tabMatchs[$i-1]->getIdEquipe())->getNomEquipe().' (Score : '.$tabMatchs[$i-1]->getScore().')</td></tr>';
				}
			}
			echo '
			<tr>
			<td colspan=2><button type"submit" id="btn1" name="setScore" value="">Saisir score</button></td>
			</tr>
			</table>
			</div>
			</form>';

			echo'<form action="AffichageTournoi.php" method="post">
			<button type"submit" id="btn1" name="VoirArbre" value="">Arbre Tournoi</button>
			</form>
			';

			echo'<form action="Tournois.php" method="post">
			<button type"submit" id="btn1" name="" value="">Liste Tournois</button>
			</form>';
			
			echo "<br />";
			echo "<br />";
			echo "<br />";
			
			if($tasMax->tourPassable())
			{
				echo'<form action="StatutTournoiEnCours.php" method="post">
				<button type"submit" id="btn1" name="TourSuivant" value="">Tour Suivant</button>
				</form>
				';
			}
		?>
	</div>
</body>
</html>