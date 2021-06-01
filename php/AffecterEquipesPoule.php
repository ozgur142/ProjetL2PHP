<?php
	include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqPoule.php');
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../BDD/reqEquipePoule.php');
	include_once('../BDD/reqMatchT.php');
	include_once('../BDD/reqMatchPoule.php');
	include_once('../module/FctGenerales.php');
	
	session_start();
	
	if(!isset($_SESSION['login']))
	{
		trigger_error("Vous n'êtes pas connecté.e !");
		header('Location: Tournois.php');
	}

	$estAdministrateur = false ;
	$estGestionnaireDuTournoi = false ;
	$id = $_SESSION['tournoi'] ;
	$tournoi = getTournoi($id);
	
	$ut = getUtilisateurWithEmail($_SESSION['login']);
	$idU = $ut->getIdUtilisateur();
	$estAdministrateur = ($ut->getRole() === "Administrateur");
	$estGestionnaireDuTournoi = $tournoi->getIdGestionnaire() == $idU ;	

	if(! ($estGestionnaireDuTournoi || $estAdministrateur))
	{
		trigger_error("Vous n'avez pas les droits !");
		header('Location: Tournois.php');
	}

	
	

	if(!($idU === $tournoi->getIdGestionnaire()) && !$estAdministrateur)
	{
		trigger_error("ERREUR : Vous n'êtes pas le gestionnaire de ce tournoi ni un administrateur du site !");
	}
	
	if(!isset($_SESSION["pouleCreee"]))
	{
		trigger_error("ERREUR : Vous n'avez sélectionné aucune poule de tournoi !");
	}
	
	$idPoule = $_SESSION["pouleCreee"];
	
	//echo "$idPoule";
	
	if(!estPoule($idPoule))
	{
		trigger_error("ERREUR : La poule que vous avez sélectionné est invalide !");
	}
	
	$poule = getPoule($idPoule);
	$tabEquipe = getAllEquipeOfTournoi($tournoi->getIdTournoi());
	$tabEquipePoule = getAllEquipePouleWithIdPoule($idPoule);
	$tabEquipeTournoi = getAllEquipeOfTournoiNotInAnyPoule($tournoi->getIdTournoi());
	
	if(count($tabEquipe) == 0)
	{
		trigger_error("ERREUR : Il n'y a aucune équipe d'inscrite à ce tournoi !");
	}
	
	if(count($tabEquipePoule) >= $poule->getNbEquipes())
	{
		trigger_error("ERREUR : Le nombre d'équipes maximal de cette poule a déjà été atteint !");
	}
	
	$nbEquipesAAffecter = ($poule->getNbEquipes() - count($tabEquipePoule));




	$dejaChoisi = array() ;	
	$message_error = "";
	$message_error2 = "";

	if(isset($_POST) && isset($_POST['EnvoyerValeurs']))
	{
		$verif = true;
		
		for($i=0;$i<$nbEquipesAAffecter;++$i)
		{
			$clefCourantePost = "eq".strval($i);
			
			if($_POST[$clefCourantePost] == "")
			{
				$verif = false;
				
				trigger_error("ERREUR : Veuillez saisir une équipe valide !");
				$message_error2 = "<p style=\" font-family:Helvetica Neue,Helvetica,Arial,sans-serif;color:red;text-align:center\">
				ATTENTION ! Il faut entrer toutes les équipes de la poule</p>";
			}
			else
			{
				array_push($dejaChoisi, $_POST[$clefCourantePost]) ;
			}
		}
		
		if($verif)
		{
			$bool = true ;
			for($i=0;$i<$nbEquipesAAffecter;++$i)
			{
				for($j=$i+1;$j<$nbEquipesAAffecter;++$j)
				{
					if($dejaChoisi[$i]==$dejaChoisi[$j])
						$bool = false ;
				}	
			}

			if(!$bool)
			{
				$message_error = "<p style=\" font-family:Helvetica Neue,Helvetica,Arial,sans-serif;color:red;text-align:center\">
				ATTENTION ! Il faut entrer des équipes différentes</p>";
			}
			else
			{
				$idP = $poule->getIdPoule();
				
				for($i=0;$i<$nbEquipesAAffecter;++$i)
				{
					$clefCourantePost = "eq".strval($i);
					
					$temp = insertEquipePoule($_POST[$clefCourantePost], $idP);
				}
				
				header('Location: StatutTournoisAVenir_Poule.php');
				exit();
			}
		}
	}
	
	$_POST = array();
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<link rel="stylesheet" type="text/css" href="../css/stylePoule.css" />
		<title>Affectation d'équipes</title>
		<style>
			body .bandeau-haut img {
				width:70px;
				padding:5px 0 0 5px;
				margin:5px 0 0 5px;
				float:left;
			}

			#drapeau {
				background-color:white;
				color:#333333;
				font-family:Helvetica Neue,Helvetica,Arial,sans-serif;
				width:30%;
				height:40px;
				text-align: center;
				margin:auto;
				font-size:18px;
				border-radius:5px;
				display:block;
				margin-bottom:2%;
			}
			label {
				color:white;
				font-family: tournois;
				margin:auto;
				text-align: center;
				display:block;
				margin-bottom: 10px;

			}
			
		</style>
	</head>
	
	<body>
		<div class="bandeau-haut">
			<a href="StatutTournoisAVenir_Poule.php">
				<img src="../img/prev.png">
				<h3>RETOUR</h3>
			</a>
		</div>
		<h1>
			<p>Affectation d'équipes à une poule</p>
		</h1>
		<hr>
		<form action="AffecterEquipesPoule.php" method="post">
			<?php
				echo $message_error;
				echo $message_error2;
				for($i=0;$i<$nbEquipesAAffecter;++$i)
				{
					$drapeau = "eq".strval($i);
					
					$suffixe = ((($i + 1) > 1) ? "ème" : "ère");
					
					$label = "<label for=\"$drapeau\">".strval(($i + 1))."<sup>$suffixe</sup> équipe</label>";
					
					$champChoixEquipe = "<div>
						<select id=\"drapeau\" name=\"$drapeau\">
							<option value=\"\">Choisir une équipe</option>";
						
						for($j=0;$j<count($tabEquipeTournoi);++$j)
						{
							$idEquipeTemp = strval($tabEquipeTournoi[$j]->getIdEquipe());
							$nomEquipeTemp = strval($tabEquipeTournoi[$j]->getNomEquipe());
							
							$champChoixEquipe = $champChoixEquipe."<option value=\"$idEquipeTemp\">$nomEquipeTemp</option>";
						}
						
						$champChoixEquipe = $champChoixEquipe."</select>
					</div>";
					
					echo $label;
					echo $champChoixEquipe;
				}
			?>
			<hr>
			<input type="submit" class="registerbtn" name="EnvoyerValeurs" value="EnvoyerValeurs">
		</form>
	</body>
</html>