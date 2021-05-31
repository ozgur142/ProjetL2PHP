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
	
	if(!isset($_SESSION['tournoiPasse']))
	{
		trigger_error("ERREUR : Aucun tournoi n'a été sélectionné.");
	}
	
	$tournoi = getTournoi($_SESSION['tournoiPasse']);
	$id = $tournoi->getIdTournoi();

	$retour = "";
	if(isset($_SESSION['login']))
	{
		$ut = getUtilisateurWithEmail($_SESSION['login']);
		$estAdministrateur = ($ut->getRole() === "Administrateur");
		$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
		$idU = $ut->getIdUtilisateur();
	}


	$tabIdPoules = getAllPouleTournoi($id) ; //4 cases

	$tabMatchsPoule = array() ; 


	for($i=0;$i<sizeof($tabIdPoules);++$i)
	{
		$tab = getAllMatchPoulePoule($tabIdPoules[$i]->getIdPoule());//6
		array_push($tabMatchsPoule, $tab);//[4][6]

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

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="../css/styleTournoiEnCours.css" />
	<title> Statut </title>
</head>
<body>
	<div class="bandeau-haut">
		<?php 
			echo'<h1>'.$tournoi->getNom().'</h1>';
		?>
	</div>
	<hr>
	<hr>

	<?php
	echo'<div class="container-main1">';	


	$color = ["bleu1" => "#ccccff",
		   "bleu2" => "#6666ff",
		   "jaune1" => "#ffff99",
		   "jaune2" => "#ffff33",
		   "rouge1" => "#ff8566",
		   "rouge2" => "#ff8566",
		   "vert1" => "#99ff99",
		   "vert2" => "#009900",
		];


		$COLOR = ["1" => "#6666ff",
		   "2" => "#ccccff",
		   "3" => "#ffff33",
		   "4" => "#ffff99",
		   "5" => "#ff471a",
		   "6" => "#ff8566",
		   "7" => "#009900",
		   "8" => "#99ff99",
		];

	$key1 = 1 ;
	$key2 = 1 ;

	echo '<div class="tabGeneral">';

	for($i=0;$i<sizeof($tabIdPoules);++$i)
	{
		$tabEquipesPoule = getAllEquipePouleWithIdPoule($tabIdPoules[$i]->getIdPoule());
		$nbEquipesPoule = getPoule($tabIdPoules[$i]->getIdPoule())->getNbEquipes() ;

		$tabTrie = new ClassementPoule($tabIdPoules[$i]->getIdPoule());
		$tabEquipesTries = $tabTrie->getTabEq() ;


		echo '
		<div id="tabPoule" style="background-color:'.$COLOR[$key1].'">
		<table>
		<tr><th colspan=2 style="height:50px">POULE '.($i+1).'</th><th>Class.</th></tr>';

		for($j=sizeof($tabTrie->getTabEq())-1;$j>=0;--$j)
		{

			$pointsEquipe = new EquipeClassePoule($tabEquipesTries[$j]->getIdEquipe());
			$points = $pointsEquipe->getPointsEquipe();
			
			$equipe = getEquipe($tabEquipesTries[$j]->getIdEquipe());

			echo'
			<tr>
			<td style="background-color:'.$COLOR[$key2].'">'.$equipe->getNomEquipe().'</td><td style="background-color:'.$COLOR[$key2].'">'.$points.'</td><td style="background-color:'.$COLOR[$key2].'">'.(sizeof($tabTrie->getTabEq())-$j).'</td>
			</tr>';
			if($j%2==0)
				++$key2;
		}
		$key1+=2;
		$key2 = $key1 ;

		echo'</table>
		</div>
		';
	}
	echo '</div>';



	$key3 = 1 ;
	$key4 = 1 ;

	echo '<div class="tabGeneral2">';

	for($i=0;$i<sizeof($tabIdPoules);++$i)
	{
		$tabEquipesPoule = getAllEquipePouleWithIdPoule($tabIdPoules[$i]->getIdPoule());
		$nbEquipesPoule = getPoule($tabIdPoules[$i]->getIdPoule())->getNbEquipes() ;


		echo '
		<div id="tabPoule2" style="background-color:'.$COLOR[$key3].'">
		<table>
		<tr><th colspan=4 style="height:50px">MATCHS POULE '.($i+1).'</th></tr>';

		for($j=0;$j<$nbEquipesPoule*($nbEquipesPoule-1)/2;++$j)
		{
			$equipe1 = getEquipe($tabMatchsPoule[$i][$j]->getIdEquipe1());
			$equipe2 = getEquipe($tabMatchsPoule[$i][$j]->getIdEquipe2());

			$score1 = $tabMatchsPoule[$i][$j]->getScore1();
			$score2 = $tabMatchsPoule[$i][$j]->getScore2();

			if($score1==-1 && $score2==-1)
				echo '<tr><td>'.$equipe1->getNomEquipe().'<td style="width:5%">-</td><td style="width:5%">-</td></td><td>'.$equipe2->getNomEquipe().'</td></tr>';
			else
				echo '<tr><td>'.$equipe1->getNomEquipe().'<td style="width:5%">'.$score1.'</td><td style="width:5%">'.$score2.'</td></td><td>'.$equipe2->getNomEquipe().'</td></tr>';
		}
		echo'</table>
		</div>
		';
		$key3+=2;
	}
	echo '</div>';

	?>
		

</body>
<form action="statutTournoiPasse_Poule_Arbre.php" method="post">
	<button type="submit" id="btn1" name="" value="">Arbre Phases Finales</button>
</form>
<form action="Tournois.php" method="post">
	<button type="submit" id="btn1" name="" value="">Liste Tournois</button>
</form>
</html>