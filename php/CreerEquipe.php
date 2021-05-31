<?php
	include_once('../BDD/reqUtilisateur.php');
	include_once('../BDD/reqEquipe.php');
	include_once('../BDD/reqGestionnaire.php');

	session_start();

	if(!isset($_SESSION['login']))
	{
		trigger_error("Vous ne pouvez pas accéder à cette page.");
		header('Location: ../index.php');
		exit();
	}

	$ut = getUtilisateurWithEmail($_SESSION['login']);
	$estAdministrateur = ($ut->getRole() === "Administrateur");

	if(!$estAdministrateur)
		{
			trigger_error("Vous n'avez pas les droits !");
			header('Location: ../index.php');
			exit();
		}
	
	if(isset($_POST) && isset($_POST['envoiValeurs']))
	{
		insertEquipe(strval($_POST['NomEquipe']), strval($_POST['Adresse']), strval($_POST['NumTel']));
	}
	
	$_POST = array();
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/styleLogin.css" />
		
		<style>
			body .bandeau-haut img {
				width:70px;
				padding:5px 0 0 5px;
				margin:5px 0 0 5px;
				float:left;
			}
		</style>
		
		<script type="text/javascript" src="../js/RegisterJS.js"></script>
		<title>Création d'une équipe</title>
	</head>
	
	<body>
		<div class="bandeau-haut">
			<a href="../index.php">
				<img src="../img/prev.png">
				<h3>RETOUR</h3>
			</a>
		</div>
		
		<form action="CreerEquipe.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Création d'une équipe</p>
			</h1>
			
			<p style="text-align: center;">Entrez vos information pour créer votre équipe</p>
			
			<hr>
			
			<label for="NomEquipe"><b>Nom de l'équipe</b></label>
			<input type="text" placeholder="Entrez le nom de votre équipe" name="NomEquipe" id="NomEquipe" required>        
			
			<label for="Adresse"><b>Adresse</b></label>
			<input type="text" placeholder="Entrez l'adresse de votre équipe" name="Adresse" id="Adresse" required>
			
			<label for="NumTel"><b>Numéro de téléphone</b></label>
			<br/>
			<input type="tel" placeholder="Motif 06-06-06-06-06" id="NumTel" name="NumTel" pattern="[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" required>
			<br/>			
			<button type="submit" class="registerbtn" name="envoiValeurs" value="Envoyer">Créer</button>
		</form>
		
	</body>
</html>