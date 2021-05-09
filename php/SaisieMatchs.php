<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(E_ALL);

	include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqJoueur.php');
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../BDD/reqEquipeMatchT.php');
	include_once('../module/TasMax.php');

	//Si le nombre d'inscription n'atteint pas le bon nombre le gestionnaire pourra modifier le nbr d'équipes total dans la base de données

	//Tester cas pour les non puissance de 2

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
	if(!$tabMatchs)//car l'insertion est trop lente.
		$tabMatchs = getAllMatchT($tournoi->getIdTournoi()) ;
	$tabEquipesDejaChoisies = getAllEquipesWithMatchT($id);
	$tabEquipesPasChoisies = getAllEquipesNoMatchT($id);
	
	if(isset($_POST['melanger']) && sizeof($tabEquipesPasChoisies)!=0)
	{
		/*
		$tabRandom = melangerEquipes($tabEquipesPasChoisies);
		$size = sizeof($tabRandom)-1 ;

		$i = 0 ;
		while(estEquipeMatchT($tabMatchs[$i]->getIdMatchT()))
		{
			echo $tabMatchs[$i]->getIdMatchT() ;
			echo $i;
			++$i;
		}
		insertEquipeMatchT($tabMatchs[$i]->getIdMatchT(),$tabRandom[0],$tabRandom[$size]);
		unset($_POST);
		*/
		$tabMelange = melanger($id);
		for($i=0;$i<$nbEquipesTotal/2;++$i)
		{
			insertEquipeMatchT($tabMatchs[$i]->getIdMatchT(),$tabMelange[2*$i],$tabMelange[2*$i+1]);
		}
		

	}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="../css/styleStatut.css" />
	<title> Saisie Matchs </title>
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
		echo '<div id="tab1">
		<form action="SaisieMatchs.php" method="post">
		<table>
		<tr>
		<th rowspan="1"></th>
		<th>Equipe A</th>
		<th>Equipe B</th>
		<th>Date/Horaire</th>
		<th>Statut match</th>
		</tr>
		';
		$indexMatch = 1 ;
		$z = 0 ;
		for($i=0;$i<sizeof($tabEquipesDejaChoisies);$i = $i + 2)
		{
			//$nom1 = $tabEquipesDejaChoisies[$i]->getNomEquipe();
			//$nom2 = $tabEquipesDejaChoisies[$i+1]->getNomEquipe();
			$doubleTable = getEquipesMatchT($tabMatchs[$indexMatch-1]->getIdMatchT()) ;

			$e1 = getEquipe($doubleTable[0]->getIdEquipe());
			$e2 = getEquipe($doubleTable[1]->getIdEquipe());
			$nom1=$e1->getNomEquipe();
			$nom2=$e2->getNomEquipe();

			echo '<tr><td style="font-weight: bold">Match n°'.$indexMatch.'</td><td>'.$nom1.'</td><td>'.$nom2.'</td><td>'.date("d/m/Y",strtotime($tabMatchs[$indexMatch-1]->getDate())).' '.$tabMatchs[$indexMatch-1]->getHoraire().'</td><td>Validé<td></tr>';
				++$indexMatch ;
		}

		if($indexMatch>1)
			$z = $indexMatch - 1 ;
		//echo $z ;

		if($z<sizeof($tabMatchs)-1)
		{
			$matchTemp = $tabMatchs[$z] ;
			echo'<tr><td style="font-weight: bold">Match n°'.($indexMatch).'</td>';
			if(sizeof($tabEquipesDejaChoisies)<$nbEquipesTotal)
			{	
				echo '
				<td>
				<select id="Equipe" name="Equipe1">
				<option value="none">Choisir équipe</option>';
				for($i=0;$i<sizeof($tabEquipesPasChoisies);++$i)
					echo '<option value="'.$tabEquipesPasChoisies[$i]->getIdEquipe().'">'.$tabEquipesPasChoisies[$i]->getNomEquipe().'</option>';
				echo'</select>
				</td>
				<td>
				<select id="Equipe" name="Equipe2">
				<option value="none">Choisir équipe</option>';
				for($i=0;$i<sizeof($tabEquipesPasChoisies);++$i)
					echo '<option value="'.$tabEquipesPasChoisies[$i]->getIdEquipe().'">'.$tabEquipesPasChoisies[$i]->getNomEquipe().'</option>';

				echo'</select>
				</td>
				<td>'.date("d/m/Y",strtotime($matchTemp->getDate())).' '.$matchTemp->getHoraire().'</td>';
				echo'<td><button type=submit name="valider" value="valider" style="padding:5px">Valider</button>
				</td>';
			}
			else
			{
				echo'<td>A venir</td> <td>A venir</td> <td>'.date("d/m/Y",strtotime($matchTemp->getDate())).' '.$matchTemp->getHoraire().'</td> <td>A venir</td></tr>';
			}

				++$indexMatch;
		}
		++$z ;

		for($i=$z;$i<$nbEquipesTotal-1;++$i)
		{
			$matchTemp = $tabMatchs[$i] ;
			if($i>=$nbEquipesTotal/2)
				echo'<tr><td style="font-weight: bold">Match n°'.$indexMatch.'</td><td> A venir </td><td> A venir </td><td>'.date("d/m/Y",strtotime($matchTemp->getDate())).' '.$matchTemp->getHoraire().'</td><td>A venir</td></tr>';
			else
				echo'<tr><td style="font-weight: bold">Match n°'.$indexMatch.'</td><td> - </td><td> - </td><td>'.date("d/m/Y",strtotime($matchTemp->getDate())).' '.$matchTemp->getHoraire().'</td></tr>';
			++$indexMatch;
		}	

		echo '</table>
		</form>
		</div>';


		if(isset($_POST['valider']) && isset($_POST['Equipe1']) && isset($_POST['Equipe2']))
		{
			if($_POST['Equipe1']==$_POST['Equipe2'] || ($_POST['Equipe1']=="none" || $_POST['Equipe2']=="none"))
			{
					echo '<p style=" font-family:Helvetica Neue,Helvetica,Arial,sans-serif;color:red;text-align:center">
				ATTENTION : Il faut entrer des équipes différentes</p>';
			}
			else
			{
				$i = 0 ;
				while(estEquipeMatchT($tabMatchs[$i]->getIdMatchT()))
					++$i;
				if($i!=$nbEquipesTotal/2 && !$bool = estMatchTWithIdEquipes($_SESSION['tournoi'],$_POST['Equipe1']))
				{
					insertEquipeMatchT($tabMatchs[$i]->getIdMatchT(),$_POST["Equipe1"],$_POST["Equipe2"]);
					unset($_POST);
				}		
			}
		}
		if(sizeof($tabEquipesDejaChoisies)!=$nbEquipesTotal)
		{
			echo '
			<form action="SaisieMatchs.php" method="post">
				<button type="submit" id="btn2" name="melanger" value="" style="margin:auto">Melanger</button>
			</form>';
		}
		?>
		<form action="StatutTournoisAVenir.php" method="post">
			<button type="submit" id="btn2" value="">Retour</button>
		</form>
	</div>

</body>
</html>