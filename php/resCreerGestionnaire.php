<?php
	include_once('../BDD/reqGestionnaire.php');
	
	session_start();
	
	if(!isset($_SESSION['login']))
	{
		trigger_error("Vous n'êtes pas authentifié.");
	}
	
	if(!verifLoginMdp(strval($_SESSION['login']), strval($_SESSION['motDePasse'])))
	{
		trigger_error("Mauvais login/mot de passe.");
		/*header('Location: Login.php');
		exit();*/
	}
	
	$ut = getUtilisateurWithEmail($_SESSION['login']);
	$estConnecte = true;
	$estAdministrateur = ($ut->getRole() === "Administrateur");
	
	if(!$estAdministrateur)
	{
		trigger_error("Vous n'êtes pas un administrateur du site.");
		header('Location: ../index.php');
		exit();
	}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/styleLogin.css" />
		<script type="text/javascript" src="../js/RegisterJS.js"></script>
		<title>Résultat création gestionnaire</title>
		<style>
			body .bandeau-haut img {
				width:70px;
				padding:5px 0 0 5px;
				margin:5px 0 0 5px;
				float:left;
			}

			.cadrebis{
				background-color:white;
				border:2px double black;
				box-shadow: 10px 10px grey;
				margin:auto;
				height:100%;
				width:500px;
				margin-top:3%;
			}

			h1,h2,p {
				font-family: tournois;
				text-align:center;
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

		<div class="cadrebis">
			<h1>Résultats création d'un gestionnaire</h1>
			<p>Tout est bon.</p>
		</div>
	</body>
</html>