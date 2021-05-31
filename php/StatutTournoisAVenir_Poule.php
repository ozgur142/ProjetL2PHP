<?php
	include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqJoueur.php');
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../BDD/reqEquipeMatchT.php');
	include_once('../BDD/reqEquipePoule.php');
	include_once('../BDD/reqPoule.php');
	include_once('../module/TasMax.php');
	
	//Si le nombre d'inscription n'atteint pas le bon nombre le gestionnaire pourra modifier le nbr d'équipes total dans la base de données
	
	//Tester cas pour les non puissance de 2
	
	//vériff date ?
	session_start();
	//$_SESSION['idT'] = $_GET['tournoi'];
	if(!isset($_SESSION['login']))
	{
		trigger_error("Vous ne pouvez pas accéder à cette page.");
		header('Location: Tournois.php');
		exit();
	}
	
	$ut = getUtilisateurWithEmail($_SESSION['login']);
	$estAdministrateur = ($ut->getRole() === "Administrateur");
	$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
	$idU = $ut->getIdUtilisateur();
	$id = $_SESSION['tournoi'] ;
	$tournoi = getTournoi($id);

	if(!$estGestionnaire)
	{
		if(!$estAdministrateur)
		{
			trigger_error("Vous n'avez pas les droits !");
			header('Location: Tournois.php');
			exit();
		}
	}
	
	$tabEquipesTournoi = getEquipeTournoiWithIdTournoi($tournoi->getIdTournoi());
	$nbEquipesInscrites = 0 ;
	$tabEquipes = array();
	for($i=0;$i<sizeof($tabEquipesTournoi);++$i)
	{
		if($tabEquipesTournoi[$i]->getEstInscrite())
			++$nbEquipesInscrites;
		array_push($tabEquipes,getEquipe($tabEquipesTournoi[$i]->getIdEquipe()));
	}
	
	$nbEquipesTotal = $tournoi->getNombreTotalEquipes();
	$tabEquipesDejaChoisies = array();
	$tabMatchs = getAllMatchT($tournoi->getIdTournoi());
	
	if(!$tabMatchs)//car l'insertion est trop lente.
		$tabMatchs = getAllMatchT($tournoi->getIdTournoi());
	
	$i = 0;
	
	while($i<sizeof($tabMatchs))
	{
		$idmatch = $tabMatchs[$i]->getIdMatchT();
		$equipematch = getEquipesMatchT($idmatch);
		//sizeof($equipematch);
		
		if(sizeof($equipematch)!=0)
		{
			array_push($tabEquipesDejaChoisies,$equipematch[0]->getIdEquipe());
			array_push($tabEquipesDejaChoisies,$equipematch[1]->getIdEquipe());
		}
		++$i;
	}
	
	$tabPoules = getAllPouleTournoi($tournoi->getIdTournoi());
	$nbEquipesDansPoules = 0;
	
	for($i=0;$i<sizeof($tabPoules);++$i)
	{
		//echo $tabPoules[$i]->getIdPoule();
		$tabEq = array();
		$tabEq = getAllEquipeOfPoule($tabPoules[$i]->getIdPoule());
		
		$nbEquipesDansPoules += sizeof($tabEq);
	}
	
	$nbMatchT = 0;
	$nbEqGagnantes = 0;
	
	for($i=0;$i<sizeof($tabPoules);++$i)
	{
		$nbEq = $tabPoules[$i]->getNbEquipes();
		
		$nbMatchT += ((($nbEq - 1) * $nbEq) / 2);
		
		$nbEqGagnantes += 2;
	}
	if($nbEqGagnantes>0)
	{
		while(!puissanceDe2($nbEqGagnantes) )
			++$nbEqGagnantes;
	}
	
	$nbMatchsGagnants = $nbEqGagnantes - 1;
	
	$nbMatchT += $nbMatchsGagnants;

	if(isset($_POST['inscriptions']))
	{
		$_SESSION["idTournoi"] = $id;
		header('Location: Inscription.php');
		exit();
	}
	elseif(isset($_POST['ModifPoule']))
	{
		$_SESSION["tournoi"] = $id;
		$_SESSION["pouleCreee"] = intval(strval($_POST['ModifPoule']));
		header('Location: AffecterEquipesPoule.php');
		exit();
	}

	if(isset($_POST['remplissage']))
	{
		$tabEquipesInscription = getAllEquipesByNiveauWithoutId() ;
		$z = 0 ;
		for($i=0;$i<$tournoi->getNombreTotalEquipes();++$i)
		{
			$equipe = $tabEquipesInscription[$i] ;
			insertEquipeTournoi($equipe->getIdEquipe(),$id,1) ;
		}
		unset($_POST);
		header('Refresh:0; url=StatutTournoisAVenir_Poule.php');	
	}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<link rel="stylesheet" type="text/css" href="../css/styleStatut.css" />
		<title> Statut </title>
	</head>
	
	<body>
		<div class="bandeau-haut">
			<?php 
				echo'<h1>'.$tournoi->getNom().' (Tournoi avec poules)</h1>';
			?>
		</div>
		
		<hr>
		<hr>

		<div class="container-main1">
			<?php
				echo '
				<div id="tab">
				';
				
				echo '<table>
				<tr>
				<th colspan="2">
				<h2 style="text-align:center; margin:5px"> 
				Récapitulatif des inscriptions
				</h2>
				</th>
				</tr>';
				
				for($i=0;$i<$nbEquipesTotal;++$i)
				{
					if(sizeof($tabEquipesTournoi)>0 && $tabEquipesTournoi[$i]!=null)
					{
						$equipe = getEquipe($tabEquipesTournoi[$i]->getIdEquipe());
						
						echo'
						<tr>
						<td>'.$equipe->getNomEquipe().'</td>';
						
						if($tabEquipesTournoi[$i]->getEstInscrite())
							echo'<td>Validée</td>';
						else
							echo'<td>En attentente de validation</td>';
						
						echo'</tr>';
					}
					else
					{
						echo '<tr>
						<td> - </td>
						<td> - </td>
						</tr>';
					}
				}
				
				echo '<tr><th colspan="2">';
				
				if($nbEquipesInscrites==$nbEquipesTotal)//date
				{
					echo '<p style="text-align:center">- Inscriptions terminées -</p>';
				}
				else
					echo '<p style="text-align:center">- Inscriptions non terminées -</p>';
				
				echo'</th></tr>';
				echo'</table>';
				echo '</div>';

				if(sizeof($tabPoules)>0)
				{
				
					echo "<div id=\"tabPoules\">
					<table>
						<tr>
							<th colspan=\"4\">
								<h2 style=\"text-align:center; margin:5px\"> 
									Récapitulatif des poules
								</h2>
							</th>
						</tr>
						
						<tr>
							<th>Poule</th>
							<th>Nombre maximal d'équipes</th>
							<th>Nombre d'équipes</th>
							<th>Ajouter des équipes</th>
						</tr>";

					for($i=0;$i<count($tabPoules);++$i)
					{
						$numCourant = ($i + 1);
						
						$tabEq = getAllEquipeOfPoule($tabPoules[$i]->getIdPoule());
						
						$nbMaxEq = $tabPoules[$i]->getNbEquipes();
						$nbEq = count($tabEq);
						$idPouleCourante = $tabPoules[$i]->getIdPoule();
						
						$formModifPoule = "<form action=\"StatutTournoisAVenir_Poule.php\" method=\"post\">
						<button type\"submit\" name=\"ModifPoule\" value=\"$idPouleCourante\" style=\"margin-bottom:1%\" class=\"btn\">Affecter</button>
						</form>";
						
						echo "<tr>
						<td>$numCourant</td>
						<td>$nbMaxEq</td>
						<td>$nbEq</td>";
						
						if($nbEq < $nbMaxEq)
							echo "<td>$formModifPoule</td>";
						else
							echo "<td>-</td>";
						
						echo "</tr>";
					}
				}
				
				echo "</table>
				</div>";
				
				echo '<div class="bouton">';

				if(sizeof($tabEquipesTournoi)==0)
				{
					echo'
					<form action="StatutTournoisAVenir_Poule.php" method="post">
					<button type"submit" id="btn1" name="remplissage" value="" style="margin-bottom:1%">Remplir équipes</button>
					</form>';
				}

				if($nbEquipesInscrites!=$nbEquipesTotal)
				{
					echo'
					<form action="StatutTournoisAVenir_Poule.php" method="post">
					<button type"submit" id="btn1" name="inscriptions" value="" style="margin-bottom:1%">Consulter/modifier inscriptions</button>
					</form>';
				}
				
				if($nbEquipesDansPoules < $nbEquipesTotal)
				{
					echo'
					<form action="CreerPoule.php" method="post">
					<button type"submit" id="btn1" name="setDate" value="" style="margin-bottom:1%">Créer des poules</button>
					</form>';
				}
				else 
				{
					if(sizeof($tabMatchs) != $nbMatchT)
					{
						echo'
						<form action="SaisieDatePoule.php" method="post">
						<button type"submit" id="btn1" name="setDate" value="" style="margin-bottom:1%">Saisir Dates</button>
						</form>';
					}
					else
					{
						echo'<form action="SaisieMatchsPoule.php" method="post">
						<button type="submit" id="btn2" name="setDate" value="">Consulter Matchs</button>
						</form>';
					}

				}
			?>
			
			<form action="Tournois.php" method="post">
				<button type="submit" id="btn2" value="" style="margin:auto">Retour</button>
			</form>
			</div>
		</div>
	</body>
</html>