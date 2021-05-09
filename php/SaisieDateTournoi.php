<?php
	include_once('../BDD/reqEquipeTournoi.php');
    include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../BDD/reqEquipe.php');
	include_once('../module/TasMax.php');
	include_once('../BDD/reqMatchT.php');
	include_once('../module/MatchT.php');
	
	session_start();
	
    if(!isset($_SESSION['login']))
	{
		trigger_error("Vous n'êtes pas authentifié.");
	}
	
    if(!verifLoginMdp(strval($_SESSION['login']), strval($_SESSION['motDePasse'])))
	{
		trigger_error("Mauvais login/mot de passe.");
		header('Location: Login.php');
		exit();
	}
	
	if(!isset($_SESSION['tournoi']))
	{
		trigger_error("Aucun tournoi sélectionné !");
	}

    $ut = getUtilisateurWithEmail($_SESSION['login']);
    
	$TournoiEnGestion = getTournoi($_SESSION['tournoi']);

	$tabMatchs = getAllMatchT($TournoiEnGestion->getIdTournoi()) ;
	$nbe = $TournoiEnGestion->getNombreTotalEquipes() ;
	
	if(sizeof($tabMatchs)==$nbe-1)
	{
		trigger_error("Les dates des matchs de ce tournoi sont déjà définies !");
		header ('Location: StatutTournoisAVenir.php');
	}


	
	if($TournoiEnGestion == NULL){
		trigger_error("QQCH VA MAL");
	}
	
	$tabEquipe = getEquipeTournoiWithIdTournoi($_SESSION['tournoi']);
	
	if(count($tabEquipe)<2)
	{
		echo "Attention!!! pas assez d'equipe est inscrit pour programmer ce tournoi";
	}
	
	if(isset($_POST) && isset($_POST['envoiValeurs']) && !(IsAlreadyProgrammed($_SESSION['tournoi'])))
	{
		$machDansCeTour = $_POST['nbEquipe'] - 1;
		
		for($i=1;$i < $_POST['nbTour'];++$i)
		{
			for($j=0;$j <= $machDansCeTour;++$j)
			{
				echo "i = ".strval($i)." j = ".strval($j);
				$time = explode(' ', $_POST["datetimepicker$i$j"]);
				$date = $time[0];
				$horaire = $time[1];
				insertMatchT($_SESSION['tournoi'],$date,$horaire);
				$machDansCeTour = $machDansCeTour - 1;
			}
		}
		header ('Location: StatutTournoisAVenir.php');
	}
	
	$_POST = array();
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<title> Gestion de Tournoi</title>
		<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script> 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.full.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.css " /> 
		<link rel="stylesheet" type="text/css" href="../css/styleStatut.css" />
	</head>
	<body>
	<div class="bandeau-haut">
		<?php 
			echo'<h1>'.$TournoiEnGestion->getNom().'</h1>';
		?>
	</div>
	<hr>
	<hr>
	<div class="container-main1">
	<?php
		if($TournoiEnGestion->aVenir()){
			$tasTournoi = new TasMax(count($tabEquipe));
			$tasTournoi->insererAuxFeuilles($tabEquipe);
			$nombreDetabe = 0;
			$dureeDechaquetour = ($TournoiEnGestion->getDuree()) / ($tasTournoi->nbTours() + 1);
			$dureeDechaquetour = (int)$dureeDechaquetour;
			$dateDeb = strtotime($TournoiEnGestion->getDateDeb());
			echo '<form method="post" action="SaisieDateTournoi.php" >';
			echo '
			<div id="tabDates">
			<table>
					<tr>
					<th>Tour</th>
					<th>Debut</th>
					<th>Fin</th>
					<th colspan=2>Dates et Horaires</th>
					</tr>';
			$machDansCeTour = 0;
			$nombrequipe = count($tabEquipe);
			for($i=0;$i < ($tasTournoi->nbTours() + 1);++$i)
			{
				$dateFin = strtotime("+$dureeDechaquetour days",$dateDeb);
				echo '<tr>';
				echo '<td style="width:70px;font-weight: bold">Tour '.$nombreDetabe.'</td>';
				echo '<td style="width:150px">'.date("d/m/Y", $dateDeb).'</td>';
				echo '<td style="width:150px">'.date("d/m/Y", $dateFin).'</td>';
				
				if(estGestionnaire($ut->getIdUtilisateur()) && !IsAlreadyProgrammed($_SESSION['tournoi']));
				{
					for($j=0;$j<$machDansCeTour;$j++)
					{
						echo '<td>(Match'.($j+1).')<input id="datetimepicker'.$nombreDetabe.$j.'" name="datetimepicker'.$nombreDetabe.$j.'" type="text" ></td> ';
						echo "<script>
						jQuery('#datetimepicker".$nombreDetabe.$j."').datetimepicker({
							format:'Y-m-d H:i', 
							minDate: '".date('Y-m-d', $dateDeb)."',
							maxDate: '". date('Y-m-d', $dateFin)."',
							allowTimes:
							[
							'08:00' ,'10:00' ,'12:00', '14:00', '16:00', 
							'18:00','20:00'
							]
						}); 
						</script>";
					}
				}
				if($i==0)
					echo'<td colspan=2 style="font-style:italic">- Tour de préparation -</td>';
				
				$nombreDetabe++;
				$machDansCeTour = $nombrequipe / 2;
				$nombrequipe = $nombrequipe / 2;
				$dateDeb = strtotime("+1 days", $dateFin);
				$dureeDechaquetour--;
				echo '</tr>';
			}
			
			if(!IsAlreadyProgrammed($_SESSION['tournoi']))
			{
				$idT = $_SESSION['tournoi'];
				echo '</table>
				</div>';
				echo '<input id="nbTour" name="nbTour" value="'.($tasTournoi->nbTours() + 1).'" type="hidden" >';
				echo '<input id="idT" name="idT" value="'.$idT.'" type="hidden" >';
				echo '<input id="nbEquipes" name="nbEquipe" value="'.count($tabEquipe).'" type="hidden" >';
				echo'<div class="bouton">';
					echo '<button type="submit" id="btn1"  name="envoiValeurs" value="Envoyer">Saisir Dates</button> ';
					echo '</form>
				</div>';
			}
		}
		?>
	</div>
	</body>
</html>