<?php
	include_once('../BDD/reqTournoi.php');
	include_once('../BDD/reqGestionnaire.php');
	
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
	
	$ut = getUtilisateurWithEmail($_SESSION['login']);
	/*
	if(!estGestionnaire($ut->getIdUtilisateur()))
	{
		trigger_error("Vous n'êtes pas un gestionnaire.");
		header('Location: ../index.php');
		exit();
	}
	*/

	$estAdministrateur = ($ut->getRole() === "Administrateur");
	
	if(!$estAdministrateur)
	{
		trigger_error("Vous n'êtes pas un administrateur du site.");
		header('Location: ../index.php');
		exit();
	}




	$tabGestionnaires = getAllGestionnaire();
	
	$champChoixGestionnaire = "<div>
	<select id=\"Gestionnaire\" name=\"Gestionnaire\">
		<option value=\"\">---Affectation de gestionnaire---</option>";
	
	for($i=0;$i<count($tabGestionnaires);++$i)
	{
		$idGestionnaireTemp = strval($tabGestionnaires[$i]->getIdGestionnaire());
		$nomGestionnaireTemp = strval($tabGestionnaires[$i]->getNom());
		$prenomGestionnaireTemp = strval($tabGestionnaires[$i]->getPrenom());
		
		$champChoixGestionnaire = $champChoixGestionnaire."<option value=\"$idGestionnaireTemp\">$idGestionnaireTemp - $nomGestionnaireTemp $prenomGestionnaireTemp</option>";
	}
	
	$champChoixGestionnaire = $champChoixGestionnaire."</select>
	</div>";






	
	if(isset($_POST) && isset($_POST['envoiValeurs']))
	{   
		$nbEquipes = $_POST['nombreTotalEquipes'] ;
		
		creerTournoi(strval($_POST['nom']),$_POST['dateDeb'], $_POST['duree'],$_POST['Gestionnaire'], strval($_POST['lieu']),$_POST['nombreTotalEquipes']);
	}
	
	$_POST = array();
	
	//Après 7 ou 8 tournois crées il y a des erreurs dans la requête si on souhaite créer un autre tournoi
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../css/syleIndex.css" />
		<style>
			.btn:hover {
				opacity:1;
				color:red;
			}
			
			.btn {
				background-color: #0d0d0d;
				color: white;
				padding: 16px 20px;
				margin: 8px 0;
				border: none;
				cursor: pointer;
				width: 100%;
				opacity: 0.9;
				border-radius:5px;
			}
		</style>
	</head>
	<body>
		<div>
			<a href="../index.php">
				<img src="../img/home.png">
			</a>
		</div>
		
		<h2 style="text-align:center">Création d'un tournoi</h2> 
		
		<div id="container">
			<form action="CreerTournoi.php" method="post">
				<p>
					<label for="nom"><b>Nom</b></label>
					<input type="text" name="nom" id="nom" placeholder="(max 30 caractères)" required /><br />
					
					<label for="lieu"><b>Lieu</b></label>
					<input type="text" name="lieu" id="lieu" placeholder="(max 30 caractères)" required/><br />
					
					<label for="duree"><b>Durée</b></label>
					<input type="number" name="duree" id="duree" required/><br />
					
					<label for="nombreTotalEquipes"><b>Nombre d'équipes</b></label>
					<input type="number" name="nombreTotalEquipes" id="nombreTotalEquipes" required/><br />
					
					<label for="dateDeb"><b>Date de début</b></label>
					<input type="date" name="dateDeb" id="dateDeb" required/><br />

					<?php
					echo $champChoixGestionnaire;
					?>
					
					<label for="descriptif">Description</label>
					<input type="text" name="descriptif" id="descriptif" placeholder="Bref descriptif du tournoi"/><br />
					
					<button type="submit" class="btn"  name="envoiValeurs" value="Envoyer">Créer</button> 
				</p>
			</form>
		</div>
	</body>
</html>