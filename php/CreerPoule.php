<?php
	include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqTournoi.php');
	include_once('../BDD/reqPoule.php');
	
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
	
	if(isset($_POST) && isset($_POST["EnvoyerValeurs"]))
	{
		echo "yo";
		$poule = insertPoule($_SESSION["tournoi"], $_POST["NbEquipes"]);
		
		$_SESSION["pouleCreee"] = $poule->getIdPoule();
		
		unset($_POST);
		
		header("Location: AffecterEquipesPoule.php");
		exit();
	}
	
	$_POST = array();
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/stylePoule.css" />
		<title>Créer une poule</title>
		<style>
			body .bandeau-haut img {
				width:70px;
				padding:5px 0 0 5px;
				margin:5px 0 0 5px;
				float:left;
			}

			#NbEquipes {
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
			#p {
				  text-align:center;
				  color:white;
				  font-family: tournois;
				  font-size:22px;
				  letter-spacing:1px
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
			<p style="text-align: center;">Créer une Poule</p>
		</h1>
		<hr>

		<p id="p" style="text-align: center;">Sélectionnez le nombre d'équipes à affecter à la poule</p>
		
		<form action="CreerPoule.php" method="post">
			<input type="number" name="NbEquipes" id="NbEquipes" placeholder="Nombre d'équipes" required>
			<hr>
			<button type="submit" class="registerbtn" name="EnvoyerValeurs" value="EnvoyerValeurs">Valider</button>
		</form>
	</body>
</html>