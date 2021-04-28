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
		<title>Résultat création d'un gestionnaire de tournoi</title>
	</head>
	
	<body>
		<div>
			<a href="Login.php">Se connecter</a>
			<a href="Logout.php">Se déconnecter</a>
			<a href="Register.php">Créer un compte</a>
			<a href="CreerEquipe.php">Créer une équipe</a>
			<a href="Preinscription.php">Pré-inscrire une équipe</a>
			<a href="ChoixInscription.php">Gérer les inscriptions d'un tournoi</a>
			<?php
				$propCreerGestionnaire = "<a href=\"CreerGestionnaire.php\">Créer un gestionnaire de tournoi</a>";
				
				if($estAdministrateur)
					echo $propCreerGestionnaire;
			?>
		</div>
		
		<h1>Résultat création d'un gestionnaire de tournoi</h1>
		
		<p style="">Le gestionnaire de tournoi a bel et bien été créé.</p>
		
		<?php
			$propCreerCompte = "<div class=\"container signin\">
				<p>Vous avez un compte? <a href=\"Login.php\">Sign in</a>.</p>
			</div>";
			
			if(!$estConnecte)
				echo $propCreerCompte;
		?>
	</body>
</html>