<?php
	include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqJoueur.php');
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../BDD/reqEquipeMatchT.php');
	include_once('../module/TasMax.php');

	include_once('../module/MatchPoule.php');
	include_once('../module/EquipePoule.php');
	include_once('../module/Poule.php');
	include_once('../module/ClassementPoule.php');

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

	

	$tabPoules = getAllPouleTournoi($id);
	$nbMatchT = 0;
	$nbEqGagnantes = 0;
	
	for($i=0;$i<sizeof($tabPoules);++$i)
	{
		$nbEq = $tabPoules[$i]->getNbEquipes();
		
		$nbMatchT += ((($nbEq - 1) * $nbEq) / 2);
		
		$nbEqGagnantes += 2;
	}
	
	while(!puissanceDe2($nbEqGagnantes))
		++$nbEqGagnantes;
	
	$nbMatchsGagnants = $nbEqGagnantes - 1 ;
	
	$nbMatchT += $nbMatchsGagnants;

	$tabEquipesFinales = array();

	for($i=0;$i<sizeof($tabPoules);++$i)
	{
		array_push($tabEquipesFinales, getAllEquipePouleWithIdPoule($tabPoules[$i]->getIdPoule()));
	}

	$tabEquipesGagnantes = array() ;
	

	$max = $nbEqGagnantes ;

	$index = 0 ;
	for($i=0;$i<sizeof($tabEquipesFinales);++$i)
	{
		$tabTrie = new ClassementPoule($tabPoules[$i]->getIdPoule());
		$tab = $tabTrie->getTabEq() ;
		for($j=0;$j<sizeof($tabEquipesFinales[$i])/2;++$j)
		{
			$nbEqPoule = sizeof($tabEquipesFinales[$i]) ;
			$tabEquipesGagnantes[$index] = $tab[$nbEqPoule-$j-1] ;
			++$index ;
		}
	}

	$tabMatchTPhasesFinales = getAllMatchTPhasesFinales($id) ;


	$index1 = 0 ;
	$index2 = 1 ;
	

		for($i=0;$i<$nbEqGagnantes/2;++$i) //---> 0 -> 4
		{
			if(!estEquipeMatchT($tabMatchTPhasesFinales[$i]->getIdMatchT()))
			{
				$taillePoule = sizeof($tabEquipesFinales[$i]) ; // ---> 4
				if($i%2==0)
				{
					$equipe1 = $tabEquipesGagnantes[$index1]->getIdEquipe();
					$equipe2 = $tabEquipesGagnantes[$index1+$taillePoule/2+1]->getIdEquipe();
					insertEquipeMatchT($tabMatchTPhasesFinales[$i]->getIdMatchT(),$equipe1,$equipe2);
			
					$index1 += ($taillePoule/2) + 2 ;
				}
				else
				{
					$equipe1 = $tabEquipesGagnantes[$index2]->getIdEquipe();
					$equipe2 = $tabEquipesGagnantes[$index2+1]->getIdEquipe();
					insertEquipeMatchT($tabMatchTPhasesFinales[$i]->getIdMatchT(),$equipe1,$equipe2);
					$index2 += ($taillePoule/2) + 2 ;
				}
			}
		}
	


	$tabEquipes = array();
	
	for($i=0;$i<$nbEqGagnantes;++$i)
	{
		array_push($tabEquipes, getEquipe($tabEquipesGagnantes[$i]->getIdEquipe() ));
	}

	$tasMax = new TasMax(sizeof($tabEquipes));
	$tasMax->insererAuxFeuilles($tabEquipes);
	$tasMax->UpdatePhasesFinales($id);

	$tabMatchT = $tasMax->getTabMatchs();

	$tasMax->afficher() ;
	echo $tabMatchT[8]->getIdMatchT() ;

	$z = sizeof($tabMatchT)-1;

	while(($z != 0) && ($tabMatchT[(($z / 2) - 1)] != null))
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
				$matchtTemp = $tabMatchT[$i] ;
				$nomEquipe = getEquipe($tabMatchT[$i]->getIdEquipe())->getNomEquipe();
				$tasMax->setScoreTabMatchs($_POST[$i],$i) ;
				header('Refresh:0; url=StatutTournoiEnCours_PhasesFinales.php');
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
				header('Refresh:0; url=StatutTournoiEnCours_PhasesFinales.php');
			}
		}
	}

	if(isset($_POST['setScoreRandom'])){
        $tasMax->UpdatePhasesFinales($id);
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
   
        header('Refresh:0; url=StatutTournoiEnCours_PhasesFinales.php');
    }

    if(($idU == $tournoi->getIdGestionnaire()) || $estAdministrateur)
		{
			$retour = "<form action=\"StatutTournoiEnCours.php\" method=\"post\">
			<button type=\"submit\" id=\"btn1\" name=\"\" ></button>
			</form>";
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

	<div class="container-main1">
		<?php
			echo '<form action="StatutTournoiEnCours_PhasesFinales.php" method="post">
			<div id="tab">
			<table>
			<tr>
			<th>Equipes A</th>
			<th>Equipes B</th>
			</tr>';
			if($tabMatchT[1] && $tabMatchT[1]->getScore()!=-1)
			{
				$deb=0;
				$fin=0;
			}
			for($i=$deb;$i>$fin;$i = $i - 2)
			{
				$matchtTemp1 = $tabMatchT[$i] ;
				$nomEquipe1 = getEquipe($tabMatchT[$i]->getIdEquipe())->getNomEquipe();

				$matchtTemp2 = $tabMatchT[$i-1] ;
				$nomEquipe2 = getEquipe($tabMatchT[$i-1]->getIdEquipe())->getNomEquipe();

				
					if($tabMatchT[$i]->getScore()==-1)
					{
						echo'<tr><td>'.getEquipe($tabMatchT[$i]->getIdEquipe())->getNomEquipe().' (Score : <input type="number" name="'.$i.'"> )</td>';

					}
					else
					{
						echo'<tr><td>'.getEquipe($tabMatchT[$i]->getIdEquipe())->getNomEquipe().' (Score : '.$tabMatchT[$i]->getScore().')</td>';
					}

					if($tabMatchT[$i-1]->getScore()==-1)
					{
						echo'<td>'.getEquipe($tabMatchT[$i-1]->getIdEquipe())->getNomEquipe().' (Score : <input type="number" name="'.($i-1).'">) </td></tr>';
					}
					else
					{
						echo'<td>'.getEquipe($tabMatchT[$i-1]->getIdEquipe())->getNomEquipe().' (Score : '.$tabMatchT[$i-1]->getScore().')</td></tr>';
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
			echo'<form action="AfficherPhasesFinales.php" method="post">
			<button type"submit" id="btn1" name="VoirArbre" value="">Arbre Tournoi</button>
			</form>
			';

			echo'<form action="Tournois.php" method="post">
			<button type"submit" id="btn1" name="" value="">Liste Tournois</button>
			</form>';
					
			if($tasMax->tourPassable())
			{
				echo'<form action="StatutTournoiEnCours_PhasesFinales.php" method="post">
				<button type"submit" id="btn1" name="TourSuivant" value="">Tour Suivant</button>
				</form>
				';
			}

		?>
	</div>
</body>
</html>




































?>