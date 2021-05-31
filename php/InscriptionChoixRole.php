<?php
    session_start();
    if(isset($_POST) && isset($_POST['envoiValeurs']))
	{
        if(isset($_POST["choixRole"]) && $_POST["choixRole"] === "Capitane") {
            header('Location: ../php/InscriptionCapitaineEquipe.php');
            unset($_POST);
            exit();
        }
        elseif(isset($_POST["choixRole"]) && $_POST["choixRole"]!=""){
            if ($_POST["choixRole"]=="Joueur")
                $_SESSION["estJoueur"] = 1;
            else 
                $_SESSION["estJoueur"] = 0;
            header('Location: ../php/Register.php');
            unset($_POST);
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/styleLogin.css" />
		<script type="text/javascript" src="../js/InscriptionJS.js"></script>
		<title>Choix Role</title>
		<style>
			body .bandeau-haut img {
				width:70px;
				padding:5px 0 0 5px;
				margin:5px 0 0 5px;
				float:left;
			}

			#Tournoi {
				background-color:white;
				color:#333333;
				font-family:Helvetica Neue,Helvetica,Arial,sans-serif;
				width:40%;
				height:40px;
				text-align: center;
				font-size:18px;
				border-radius:5px;
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
		
		<form action="InscriptionChoixRole.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Choix de role</p>
			</h1>
			
			<p style="text-align: center;">Selectionnez votre role.</p>
			
			<hr>
			
			<label for="Role"><b>Selectionnez votre role.</b></label>
	        <select id= "choixRole" name="choixRole"> 
                <option value=>Choisir votre role</option>
                <option value="Joueur">Joueur</option>
                <option value="Capitane">Capitaine</option>
                <option value="Utilisateur">Utilisateur</option>
            </select>
            
			<hr>
			
			<button type="submit" class="registerbtn" name="envoiValeurs" value="Envoyer">Valider</button>
		</form>
	</body>
</html>