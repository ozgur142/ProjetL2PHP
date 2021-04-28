<?php
	include_once('./BDD/reqUtilisateur.php');
	
	session_start();
	
	$ut = NULL;
	$estConnecte = false;
	$estAdministrateur = false;
	
	if(isset($_SESSION['login']))
	{
		if(verifLoginMdp(strval($_SESSION['login']), strval($_SESSION['motDePasse'])))
		{
			$ut = getUtilisateurWithEmail($_SESSION['login']);
			//$estConnecte = true;
			$estAdministrateur = ($ut->getRole() === "Administrateur");
		}
	}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<link rel="stylesheet" type="text/css" href="./css/syleIndex.css" />
		<!--<script type="text/javascript" src="PageDacc.js"></script>-->
		<title>Accueil</title>
	</head>
	<body>
		<div class="topnav">
			<div class="carre">
				<img src="img/logo.jpeg">
			</div>
			<a href="#">Informations</a>
			<a href="php/CreerTournoi.php">Créer Tournoi</a>
			<a href="php/Tournois.php">Liste des Tournois</a>
			<a href="php/Preinscription.php">Preinscription</a>
			<a href="php/CreerEquipe.php">Créer équipe</a>
			<a href="php/CreerGestionnaire.php">Créer gestionnaire</a>
			<a href="php/Inscription.php">Inscription</a>
			<a href="php/Tests.php">testArbre</a>
			
			<?php
				$propCreerGestionnaire = "<a href=\"php/CreerGestionnaire.php\">Créer un gestionnaire de tournoi</a>";
				
				if($estAdministrateur)
					echo $propCreerGestionnaire;
			?>
			
			<div class="topnav-right">
				<a href="php/Login.php">Connexion</a>
				<a href="php/Logout.php">Déconnexion</a>
			</div>
		</div>
		<div class="cadre">
			<h1>
				Bienvenue sur ce site de Tournois sportifs
			</h1>
			<p>
				- Pas de compte ? Inscrivez-vous dès maintenant -
			</p>
		</div>  
		<div class="inscription">
			<a href="php/Register.php">INSCRIPTION</a>   
		</div>
	</body>
</html>