<?php
	include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqJoueur.php');
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../BDD/reqEquipeMatchT.php');
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
	$id = $_SESSION['tournoi'];
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
	
	while($i < sizeof($tabMatchs))
	{
		$idmatch = $tabMatchs[$i]->getIdMatchT();
		$equipematch = getEquipesMatchT($idmatch);
		
		sizeof($equipematch);
		
		if(sizeof($equipematch) != 0)
		{
			array_push($tabEquipesDejaChoisies,$equipematch[0]->getIdEquipe());
			array_push($tabEquipesDejaChoisies,$equipematch[1]->getIdEquipe());
		}
		
		++$i;
	}
	
	if(isset($_POST['inscriptions']))
	{
		$_SESSION["idTournoi"] = $id;
		
		header('Location: Inscription.php');
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
		header('Refresh:0; url=StatutTournoisAVenir_Championnat.php');	
	}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<link rel="stylesheet" type="text/css" href="../css/styleStatut.css" />
		<title>Statut</title>
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
					if((sizeof($tabEquipesTournoi) > 0) && ($tabEquipesTournoi[$i] != null))
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
				
				if(($nbEquipesInscrites == $nbEquipesTotal))//date
				{
					echo '<p style="text-align:center">- Inscriptions terminées -</p>';
				}
				else
					echo '<p style="text-align:center">- Inscriptions non terminées -</p>';
				
				echo'</th></tr>';
				echo'</table>';
				echo '</div>
				<div class="bouton">';

				if(sizeof($tabEquipesTournoi)==0)
				{
					echo'
					<form action="StatutTournoisAVenir_Championnat.php" method="post">
					<button type"submit" id="btn1" name="remplissage" value="" style="margin-bottom:1%">Remplir équipes</button>
					</form>';
				}
				
				if(($nbEquipesInscrites != $nbEquipesTotal))
				{
					echo'
					<form action="StatutTournoisAVenir_Championnat.php" method="post">
					<button type"submit" id="btn1" name="inscriptions" value="" style="margin-bottom:1%">Consulter/modifier inscriptions</button>
					</form>';
				}
				else
				{
					if(sizeof($tabMatchs) != ($nbEquipesTotal) * ($nbEquipesTotal-1) / 2)
					{					
						echo'
						<form action="SaisieDateChampionnat.php" method="post">
						<button type"submit" id="btn1" name="setDate" value="" style="margin-bottom:1%">Saisir Dates</button>
						</form>';
					}
					else
					{
						echo'<form action="SaisieMatchsChampionnat.php" method="post">
						<button type="submit" id="btn2" name="setDate" value="">Saisir / Consulter Matchs</button>
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