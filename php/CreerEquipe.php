<?php
	include_once('../BDD/reqEquipe.php');
	
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
			body div img {
				width:50px;
				border:5px groove white;
				padding:5px;
				float:left;
			}
		</style>
		
		<script type="text/javascript" src="../js/RegisterJS.js"></script>
		<title>Création d'une équipe</title>
	</head>
	
	<body>
		<div>
			<a href="../index.php">
				<img src="../img/home.png">
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
			<input type="tel" id="NumTel" name="NumTel" pattern="[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" required>
			<small>Motif du numéro de téléphone : 06-06-06-06-06</small>
			
			<br />
			
			<button type="submit" class="registerbtn" name="envoiValeurs" value="Envoyer">Voilà</button>
			<button type="reset" name="effacerValeurs" value="Effacer">Voilà 2</button>
		</form>
		
		<div class="container signin">
			<p>Vous avez un compte? <a href="Login.php">Sign in</a>.</p>
		</div>
	</body>
</html>