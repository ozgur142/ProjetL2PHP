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
			body div img {
				width:50px;
				border:5px groove white;
				padding:5px;
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
		<div>
			<a href="../index.php">
			<img src="../img/home.png">
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
			<button type="reset" name="effacerValeurs" value="Effacer">Effacer les champs</button>
		</form>
		
		<div class="container-logout">
			<p>Pas encore de compte ? <a href="Register.php">Créer un compte</a>.</p>
		</div>
	</body>
</html>