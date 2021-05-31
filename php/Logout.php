<?php
	include '../BDD/reqUtilisateur.php';
	
	session_start();
	
	if(isset($_POST) && isset($_POST['envoiValeurs']))
	{
		if(!isset($_SESSION['login']))
		{
			trigger_error("Vous n'êtes pas connecté !");
		}
		
		if(!verifLoginMdp(strval($_SESSION['login']), strval($_SESSION['motDePasse'])))
		{
			trigger_error("Vos identifiants sont incorrects !");
			
			header('Location: Login.php');
			exit();
		}
		
		session_destroy();
		
		header('Location: ../index.php');
		exit();
	}
	else if(isset($_POST) && isset($_POST['retour']))
	{
		header('Location: Prive.php');
		exit();
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
			
			.container {
				width:30%;
				margin:auto;
			}
		</style>
		
		<script type="text/javascript" src="../js/LoginJS.js"></script>
		<title>Déconnexion</title>
	</head>
	
	<body>
		<div class="bandeau-haut">
			<a href="../index.php">
				<img src="../img/prev.png">
				<h3>RETOUR</h3>
			</a>
		</div>
		
		<form action="Logout.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Déconnexion</p>
			</h1>
			
			<p style="text-align: center;">Voulez-vous vraiment vous déconnecter ?</p>
			
			<hr>
			
			<button type="submit" class="loginbtn" name="envoiValeurs" value="Envoyer">Oui</button>
			<button type="submit" class="loginbtn" name="retour" value="Envoyer">Non</button>
		</form>

	</body>
</html>