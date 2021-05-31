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
	
	$id = $_SESSION['tournoi'] ;
	$tournoi = getTournoi($id);
	$tabEquipesTournoi = getEquipeTournoiWithIdTournoi($tournoi->getIdTournoi());
	
	$tabPoules = getAllPouleTournoi($tournoi->getIdTournoi());
	
	$estBon = true;
	$nbMatchPoule = 0;
	
	$tabMatchPoulesVerif = getAllMatchPouleTournoi($tournoi->getIdTournoi());
	
	for($i=0;$i<sizeof($tabPoules);++$i)
	{
		$nbEq = $tabPoules[$i]->getNbEquipes();
		
		$nbMP = ((($nbEq - 1) * $nbEq) / 2);
		
		$nbMatchPoule += $nbMP;
	}
	
	if($nbMatchPoule > sizeof($tabMatchPoulesVerif))
	{
		$indice = 0 ;
		for($i=0;$i<sizeof($tabPoules);++$i)
		{
	
			$tabEqPoule = getAllEquipePouleWithIdPoule($tabPoules[$i]->getIdPoule());
			$poule = $tabPoules[$i];
					
			if(sizeof($tabEqPoule) == $poule->getNbEquipes())
			{
				$tabTemp = array();
				
				for($j=0;$j<sizeof($tabEqPoule);++$j)
				{
					for($k=0;$k<sizeof($tabEqPoule);++$k)
					{
						if($k != $j)
						{
							if(!combinaisonDejaPresente($tabTemp, $tabEqPoule[$j]->getIdEquipe(), $tabEqPoule[$k]->getIdEquipe()))
							{
								$tt = array();
								
								array_push($tt, $tabEqPoule[$j]->getIdEquipe());
								array_push($tt, $tabEqPoule[$k]->getIdEquipe());
								
								array_push($tabTemp, $tt);
							}
						}
					}
				}
				
				$tabMatchT = getAllMatchT($tournoi->getIdTournoi());
				
				for($j=0;$j<sizeof($tabTemp);++$j)
				{
					$tt = $tabTemp[$j];
					
					$idE1 = $tt[0];
					$idE2 = $tt[1];
					
					$temp = insertMatchPoule($idE1, $idE2, $tabMatchT[$indice]->getIdMatchT(), -1, -1);
					++$indice ;
				}
			}
		}
	}
	
	$tabMatchPoules = getAllMatchPouleTournoi($tournoi->getIdTournoi());
	$tabEquipes = getAllEquipeOfTournoi($tournoi->getIdTournoi());
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
							<th>Date/Horaire</th>
							<th>Statut match</th>
						</tr>";
					
					for($j=0;$j<sizeof($tabMatchPoules);++$j)
					{
						$matchCourant = ($j + 1);
						
						$eq1 = getEquipe($tabMatchPoules[$j]->getIdEquipe1());
						$eq2 = getEquipe($tabMatchPoules[$j]->getIdEquipe2());
						
						$nomEquipeA = $eq1->getNomEquipe();
						$nomEquipeB = $eq2->getNomEquipe();
						
						$matchT = getMatchT($tabMatchPoules[$j]->getIdMatchT());
						
						$dateMatchT = $matchT->getDate();
						$horaireMatchT = $matchT->getHoraire();
						
						//$pouleCourante = getPouleWithMatchPoule($tabMatchPoules[$j]);
						$pouleCourante = getPouleWithEquipeAndTournoi($tabMatchPoules[$j]->getIdEquipe1(), $tournoi->getIdTournoi());
						
						if($pouleCourante->getIdPoule() == $tabPoules[$i]->getIdPoule())
						{
							echo "<tr><td>$matchCourant</td>
							<td>$nomEquipeA</td>
							<td>$nomEquipeB</td>
							<td>$dateMatchT $horaireMatchT</td>
							<td>Validé</td></tr>";
						}
					}
					
					echo '</table>
					</div>';
				}
			?>
			
			<form action="StatutTournoisAVenir_Poule.php" method="post">
				<button type="submit" id="btn2" value="">Retour</button>
			</form>
		</div>
	</body>
</h1>