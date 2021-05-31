<?php
	include_once('../module/FctGenerales.php');
	include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqJoueur.php');
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../BDD/reqEquipeMatchT.php');
	include_once('../BDD/reqMatchPoule.php');
	include_once('../BDD/reqMatchT.php');
	include_once('../BDD/reqPoule.php');
	include_once('../BDD/reqEquipePoule.php');
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
	
	$id = $_SESSION['tournoiEnCours'] ;
	$tournoi = getTournoi($id);
	$tabEquipesTournoi = getEquipeTournoiWithIdTournoi($tournoi->getIdTournoi());
	
	$tabMatchPoules = getAllMatchPouleTournoi($tournoi->getIdTournoi());
	$tabPoules = getAllPouleTournoi($tournoi->getIdTournoi());
	$tabEquipes = getAllEquipeOfTournoi($tournoi->getIdTournoi());
	
	if(isset($_POST) && isset($_POST["EnvoyerValeurs"]))
	{
		for($i=0;$i<sizeof($tabMatchPoules);++$i)
		{
			$score1 = rand(1, 15);
			$score2 = rand(1, 15);
			
			while($score2 === $score1)
				$score2 = rand(1, 15);
			
			updateScoreMatchPoule($tabMatchPoules[$i]->getIdEquipe1(), $tabMatchPoules[$i]->getIdEquipe2(), $tabMatchPoules[$i]->getIdMatchT(), $score1, $score2);
			header('Refresh:0; url=StatutTournoisEnCours_Poule.php');
		}
	}
	
	$toutesPoulesTerminees = true;
	
	for($i=0;$i<sizeof($tabPoules);++$i)
		$toutesPoulesTerminees = (($toutesPoulesTerminees) && (pouleTerminee($tabPoules[$i]->getIdPoule())));
	
	
	$_POST = array();
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<link rel="stylesheet" type="text/css" href="../css/styleStatut.css" />
		<title>Saisie matchs coupe</title>
	</head>
	
	<body>
		<div class="bandeau-haut">
			<?php 
				echo'<h1>'.$tournoi->getNom().'</h1>';
			?>
		</div>
		
		<br />
		<br />
		
		<div class="container-main2">
			<h1 style="font-size:35px"></h1>
			
			<?php
				for($i=0;$i<sizeof($tabPoules);++$i)
				{
					$numPouleCourante = ($i + 1);
					
					echo '<div class="tabSpec">';
					echo"
					<table>
						<tr>
							<th colspan=\"5\">
								<h2 style=\"text-align:center; margin:5px\"> 
									Poule N°$numPouleCourante
								</h2>
							</th>
						</tr>
						
						<tr>
							<th rowspan=\"1\">Matchs</th>
							<th>Equipe A</th>
							<th>Equipe B</th>
							<th>Score 1</th>
							<th>Score 2</th>
						</tr>";
					
					for($j=0;$j<sizeof($tabMatchPoules);++$j)
					{
						$matchCourant = ($j + 1);
						
						$eq1 = getEquipe($tabMatchPoules[$j]->getIdEquipe1());
						$eq2 = getEquipe($tabMatchPoules[$j]->getIdEquipe2());
						
						$nomEquipeA = $eq1->getNomEquipe();
						$nomEquipeB = $eq2->getNomEquipe();
						
						$matchT = getMatchT($tabMatchPoules[$j]->getIdMatchT());
						
						$scoreMatchPoules1 = $tabMatchPoules[$j]->getScore1();
						$scoreMatchPoules2 = $tabMatchPoules[$j]->getScore2();
						
						$pouleCourante = getPouleWithEquipeAndTournoi($tabMatchPoules[$j]->getIdEquipe1(), $tournoi->getIdTournoi());
						
						if($pouleCourante->getIdPoule() == $tabPoules[$i]->getIdPoule())
						{
							echo "<tr><td>$matchCourant</td>
							<td>$nomEquipeA</td>
							<td>$nomEquipeB</td>";
							if($scoreMatchPoules1==-1 && $scoreMatchPoules2==-1)
								echo"<td>0</td><td>0</td>";
							else
								echo"<td>$scoreMatchPoules1</td><td>$scoreMatchPoules2</td>";
						}
					}
					
					echo '</table>
					</div>';
				}
			?>
			<form action="AffichagePoule.php" method="post">
				<button type="submit" id="btn1" name="" value="">Classement</button>
			</form>
			<form action="StatutTournoisEnCours_Poule.php" method="post">
				<button type="submit" id="btn2" name="EnvoyerValeurs" value="Saisir scores aléatoires" >Saisir scores aléatoires</button>
				<button type="submit" id="btn2" value="" >Retour</button>
			</form>
		</div>
	</body>
</h1>