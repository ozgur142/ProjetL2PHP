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
		trigger_error("Vous n'êtes pas connecté.e !");
	
	$ut = getUtilisateurWithEmail($_SESSION['login']);
	$estAdministrateur = ($ut->getRole() === "Administrateur");
	$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
	$idU = $ut->getIdUtilisateur();
	
	$id = $_SESSION['tournoiEnCours'] ;
	$tournoi = getTournoi($id);

	if(!($idU === $tournoi->getIdGestionnaire()) && !$estAdministrateur)
	{
		header('Location: AffichageTournoi.php');
		exit();
	}


	$tabEquipesTournoi = getEquipeTournoiWithIdTournoi($id);
	$tabEquipesMatchsTemp = getAllEquipeMatchT($id);

	$tabEquipesBonSens = array();

	$bool = estPuissanceDe2($id);
	$taille  = sizeof($tabEquipesTournoi) ;
	$k = 0 ;
	$surplus = 0 ;
	if(!$bool)
	{
		while(pow(2,$k)<$taille)
			++$k ;
		--$k;
		$surplus = $tournoi->getNombreTotalEquipes() - pow(2,$k) ;
	}

	for($i=0;$i<(sizeof($tabEquipesTournoi));++$i)
	{

		$ide = $tabEquipesMatchsTemp[$i]->getIdEquipe();
		$tabEquipesBonSens[$i] = getEquipe($ide);
	}

	$tabEquipes = array();
	for($i=sizeof($tabEquipesBonSens)-1;$i>=0;--$i)
	{
		array_push($tabEquipes, getEquipe($tabEquipesBonSens[$i]->getIdEquipe() ));
	}
	//echo sizeof($tabEquipes);
	$tasMax = new TasMax(sizeof($tabEquipes));
	$tasMax->insererAuxFeuilles($tabEquipes);


	$tasMax->Update($id);

	$tabMatchs = $tasMax->getTabMatchs();
	$tas = $tasMax->getTas();


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
				$matchtTemp = $tabMatchs[$i] ;
				$nomEquipe = getEquipe($tabMatchs[$i]->getIdEquipe())->getNomEquipe();


				if($matchtTemp=="NULLA")
				{
					$tasMax->setScoreTabMatchs(-2,$i) ;
		
				}
				else
				{
					$tasMax->setScoreTabMatchs($_POST[$i],$i) ;
				}
				header('Refresh:0; url=StatutTournoiEnCours.php');
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
			else
			{

				$tasMax->prochainTour($id);
				header('Refresh:0; url=StatutTournoiEnCours.php');
			}
		}
	}

	if(isset($_POST['setScoreRandom'])){
        $tasMax->Update($id);
        $TabMatchRandom = $tasMax->getTabMatchs();
        $t = sizeof($TabMatchRandom)-1;
        while(($t != 0) && ($TabMatchRandom[(($t / 2) - 1)] != null))
        {
            $t = $t - 2;
        }
        $debb = $t;
        $finn = $t / 2;
        for($i=$debb;$i>=$finn;$i--){
            $randomN = rand(0,10);
            $tasMax->setScoreTabMatchs($randomN,$i);
        }
        if($tasMax->tourPassable())
            $tasMax->prochainTour($id);
        header('Refresh:0; url=StatutTournoiEnCours.php');
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
			if($tabMatchs[1] && $tabMatchs[1]->getScore()!=-1)
			{
				$deb=0;
				$fin=0;
			}
			for($i=$deb;$i>$fin;$i = $i - 2)
			{
				$matchtTemp1 = $tabMatchs[$i] ;
				$nomEquipe1 = getEquipe($tabMatchs[$i]->getIdEquipe())->getNomEquipe();

				$matchtTemp2 = $tabMatchs[$i-1] ;
				$nomEquipe2 = getEquipe($tabMatchs[$i-1]->getIdEquipe())->getNomEquipe();

				if($nomEquipe1!="NULLA" && $nomEquipe2!="NULLA")
				{
					if($tabMatchs[$i]->getScore()==-1)
					{
						echo'<tr><td>'.getEquipe($tabMatchs[$i]->getIdEquipe())->getNomEquipe().' (Score : <input type="number" name="'.$i.'"> )</td>';

					}
					else
					{
						echo'<tr><td>'.getEquipe($tabMatchs[$i]->getIdEquipe())->getNomEquipe().' (Score : '.$tabMatchs[$i]->getScore().')</td>';
					}

					if($tabMatchs[$i-1]->getScore()==-1)
					{
						echo'<td>'.getEquipe($tabMatchs[$i-1]->getIdEquipe())->getNomEquipe().' (Score : <input type="number" name="'.($i-1).'">) </td></tr>';
					}
					else
					{
						echo'<td>'.getEquipe($tabMatchs[$i-1]->getIdEquipe())->getNomEquipe().' (Score : '.$tabMatchs[$i-1]->getScore().')</td></tr>';
					}
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
			echo'<form action="AffichageTournoi.php" method="post">
			<button type"submit" id="btn1" name="VoirArbre" value="">Arbre Tournoi</button>
			</form>
			';

			echo'<form action="Tournois.php" method="post">
			<button type"submit" id="btn1" name="" value="">Liste Tournois</button>
			</form>';
					
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