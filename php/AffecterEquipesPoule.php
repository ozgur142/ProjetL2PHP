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
		trigger_error("Vous n'êtes pas connecté.e !");
	
	$ut = getUtilisateurWithEmail($_SESSION['login']);
	$estAdministrateur = ($ut->getRole() === "Administrateur");
	$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
	$idU = $ut->getIdUtilisateur();
	
	$id = $_SESSION['tournoi'] ;
	$tournoi = getTournoi($id);

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
			}
		}
		
		if($verif)
		{
			$idP = $poule->getIdPoule();
			
			for($i=0;$i<$nbEquipesAAffecter;++$i)
			{
				$clefCourantePost = "eq".strval($i);
				
				$temp = insertEquipePoule($_POST[$clefCourantePost], $idP);
			}
			
			header('Location: Tournois.php');
			exit();
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
			<a href="../index.php">
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