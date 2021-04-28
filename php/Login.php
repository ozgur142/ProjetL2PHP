<?php
	if(isset($_POST) && isset($_POST['envoiValeurs']))// && isset($_POST['mail']) && isset($_POST['psw']))
	{
		$_POST['psw'] = strval(hash("sha256", strval($_POST['psw'])));
		
		include('../BDD/reqUtilisateur.php');
		
		if(!verifLoginMdp(strval($_POST['mail']), strval($_POST['psw'])))
			trigger_error("Login et/ou mot de passe invalide(s).");
		
		echo verifLoginMdp(strval($_POST['mail']), strval($_POST['psw']));
		
		session_start();
		
		$_SESSION['login'] = strval($_POST['mail']);
		$_SESSION['motDePasse'] = strval($_POST['psw']);
		
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
			}
		</style>
		
		<script type="text/javascript" src="../js/LoginJS.js"></script>
		<title>Authentification</title>
	</head>
	
	<body>
		<div>
			<a href="../index.php">
			<img src="../img/home.png">
			</a>
		</div>
		
		<div class="cadre">
			<form action="Login.php" method="POST" onreset="return vider();" class="container">
				<h1>
					<p style="text-align: center;">Authentification</p>
				</h1>
				
				<p style="text-align: center;">Entrez vos information pour acceder à votre compte</p>
				
				<hr>

				<label for="mail"><b>Mail</b></label>
				<input type="email" placeholder="Entrez votre mail" name="mail" id="mail" required>

				<label for="psw"><b>Mot de passe</b></label>
				<input type="password" placeholder="Entrez votre mot de passe" name="psw" id="psw" required>

				<hr>
				
				<button type="submit" class="loginbtn" name="envoiValeurs" value="Envoyer">S'authentifier</button>
				<button type="reset" name="effacerValeurs" value="Effacer">gg</button>
			</form>
			
			<div class="container">
				<p>Pas encore de compte ? <a href="Register.php">Créer un compte</a>.</p>
			</div>
		</div>
	</body>
</html>