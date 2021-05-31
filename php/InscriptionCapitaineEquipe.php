<?php
    include_once('../BDD/reqJoueur.php');
	include_once('../BDD/reqEquipe.php');
    if(isset($_POST) && isset($_POST['envoiValeurs']))
	{
        $_POST['psw'] = strval(hash("sha256", strval($_POST['psw'])));
        $_POST['psw_repeat'] = strval(hash("sha256", strval($_POST['psw_repeat'])));
		$idE = insertEquipe(strval($_POST['NomEquipe']), strval($_POST['Adresse']), strval($_POST['NumTel']));
        insertJoueur(strval($_POST['Nom']), strval($_POST['Prenom']), strval($_POST['Mail']), strval($_POST['psw']), strval($_POST['psw_repeat']), strval("Utilisateur"),strval($idE) , 1);
	} 
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
			<a href="../php/InscriptionChoixRole.php">
				<img src="../img/prev.png">
				<h3>RETOUR</h3>
			</a>
		</div>
		
		<form action="InscriptionCapitaineEquipe.php" method="POST" onreset="return vider();" class="container">
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

            <p style="text-align: center;">Entrez vos informations pour vous inscrire</p>
			
			<hr>
			
			<label for="Prenom"><b>Prénom</b></label>
			<input type="text" placeholder="Entrez votre prénom" name="Prenom" id="Prenom" required>        
			
			<label for="Nom"><b>Nom</b></label>
			<input type="text" placeholder="Entrez votre nom" name="Nom" id="Nom" required>
			
			<label for="Mail"><b>Mail</b></label>
			<input type="email" placeholder="Entrez votre mail" name="Mail" id="Mail" required>
			
			<label for="psw"><b>Mot de passe</b></label>
			<input type="password" placeholder="Entrez votre mot de passe" name="psw" id="psw" required>
			
			<label for="psw-repeat"><b>Confirmation</b></label>
			<input type="password" placeholder="Répétez votre mot de passe" name="psw_repeat" id="psw_repeat" required>

            <button type="submit" class="registerbtn" name="envoiValeurs" value="Envoyer">Créer votre compte et vos equipe</button>

		</form>
		
	</body>
</html>
