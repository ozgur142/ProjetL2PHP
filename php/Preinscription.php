<?php
	include '../BDD/reqUtilisateur.php';
	
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
	
	$ut = getUtilisateurWithEmail($_SESSION['login'];
	
	if(!estJoueur($ut->getIdUtilisateur()))
	{
		trigger_error("Vous n'êtes pas un joueur d'équipe.");
		header('Location: index.php');
		exit();
	}
	
	$joueur = getJoueur($ut->getIdUtilisateur());
	
	if(!$joueur->getCapitaine())
	{
		trigger_error("Vous n'êtes pas un capitaine d'équipe.");
		header('Location: index.php');
		exit();
	}
	
    $nom_equipe = $_POST("NomEquipe");
    $nom_tournoi = $_POST("NomDeTournoi");
    if($nom_equipe == NULL && $nom_equipe == ""){
        $req = "SELECT idUtilisateur FROM Utilisateur, Joueur WHERE idUtilisateur = ".$_SESSION['idUtilisateur']." and idUtilisateur = idJoueur and estCapitane = 1";
        if(!isset($req)){
            return false;
        }
        // Est ce qu'on doit creer une autre table dans notre base de donne pour stocker les requeste d'inscription?
    }
    
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/styleRegLog.css" />
		<script type="text/javascript" src="../js/RegisterJS.js"></script>
		<title>Inscription</title>
	</head>
	
	<body>
    <form action="Preinscription.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Preinscripition</p>
			</h1>
			
			<p style="text-align: center;">Entrez vos information pour créer votre compte</p>
			
			<hr>
			
			<label for="NomEquipe"><b>Votre nom d'equipe</b></label>
			<input type="text" placeholder="Entrez votre nom d'equipe" name="NomEquipe" id="NomEquipe" required>       

            <label for="NomDeTournoi"><b>Entrez le nom de la tournois que vous voulez entrez</b></label>
			<input type="text" placeholder="Entrez nom de Tournoi" name="NomDeTournoi" id="NomDeTournoi" required> 

			<hr>
			
			<button type="submit" class="registerbtn" name="envoiValeurs" value="Envoyer">Voilà</button>
			<button type="reset" name="effacerValeurs" value="Effacer">Voilà 2</button>
		</form>
	</body>
</html>