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
	$id = $_SESSION['tournoiEnCours'] ;
	$tournoi = getTournoi($id);


	$tabEquipesTournoi = getAllEquipesWithMatchT($id);
	$tabEquipes = array();
	for($i=0;$i<sizeof($tabEquipesTournoi);++$i)
	{
		array_push($tabEquipes, getEquipe($tabEquipesTournoi[$i]->getIdEquipe() ));
	}

	$tasMax = new TasMax(sizeof($tabEquipes));
	$tasMax->insererAuxFeuilles($tabEquipes);
	$tabMatchs = getAllEquipeMatchT($id);
	$tasMax->UpdateTabMatchs($tabMatchs);

	$tabMatchBis = $tasMax->getTabMatchs();

	$z = sizeof($tabMatchBis)-1;

	while(($z != 0) && ($tabMatchBis[(($z / 2) - 1)] != null))
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
			echo '<form action="StatutTournoiEnCours.php" method="post">
			<div id="tab">
			<table>
			<tr>
			<th>Equipes A</th>
			<th>Equipes B</th>
			</tr>';
			for($i=$deb;$i>$fin;$i = $i - 2)
			{
				if($tabMatchBis[$i]->getScore()==-1)
				{
					echo'<tr><td>'.getEquipe($tabMatchBis[$i]->getIdEquipe())->getNomEquipe().' (score :<input type="number" name="'.$i.'")</td>';

				}
				else
				{
					echo'<tr><td>'.getEquipe($tabMatchBis[$i]->getIdEquipe())->getNomEquipe().' (score :'.$tabMatchBis[$i]->getScore().')</td>';
				}

				if($tabMatchBis[$i-1]->getScore()==-1)
				{
					echo'<td>'.getEquipe($tabMatchBis[$i-1]->getIdEquipe())->getNomEquipe().' (score :<input type="number" name="'.($i-1).'")</td></tr>';
				}
				else
				{
					echo'<td>'.getEquipe($tabMatchBis[$i-1]->getIdEquipe())->getNomEquipe().' (score :'.$tabMatchBis[$i-1]->getScore().')</td></tr>';
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




		?>
	</div>
</body>
</html>