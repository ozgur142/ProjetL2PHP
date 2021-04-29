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
	
	$tabUtilisateurs = getAllSimpleUtilisateur();
	
	$champChoixGestionnaire = "<div>
	<select id=\"Gestionnaire\" name=\"Gestionnaire\">
		<option value=\"\">---Choisissez le gestionnaire à créer---</option>";
	
	for($i=0;$i<count($tabUtilisateurs);++$i)
	{
		$idUtilisateurTemp = strval($tabUtilisateurs[$i]->getIdUtilisateur());
		$nomUtilisateurTemp = strval($tabUtilisateurs[$i]->getNom());
		$prenomUtilisateurTemp = strval($tabUtilisateurs[$i]->getPrenom());
		
		$champChoixGestionnaire = $champChoixGestionnaire."<option value=\"$idUtilisateurTemp\">$idUtilisateurTemp - $nomUtilisateurTemp $prenomUtilisateurTemp</option>";
	}
	
	$champChoixGestionnaire = $champChoixGestionnaire."</select>
</div>";
	
	if(isset($_POST) && isset($_POST['envoiValeurs']))
	{
		if($_POST['Gestionnaire'] === "")
			trigger_error("ERREUR : Veuillez saisir un gestionnaire valide.");
		else
			insertGestionnaireForExistingUtilisateur(((int)strval($_POST['Gestionnaire'])));
	}
	
	$_POST = array();
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/styleLogin.css" />
		<script type="text/javascript" src="../js/RegisterJS.js"></script>
		<title>Création d'un gestionnaire de tournoi</title>
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
		
		<form action="CreerGestionnaire.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Création d'un gestionnaire de tournoi</p>
			</h1>
			
			<p style="text-align: center;">Sélectionner le gestionnaire de tournoi que vous voulez créer.</p>
			
			<hr>
			
			<?php
				echo $champChoixGestionnaire;
			?>
			
			<br />
			
			<button type="submit" class="registerbtn" name="envoiValeurs" value="Envoyer">Voilà</button>
			<button type="reset" name="effacerValeurs" value="Effacer">Voilà 2</button>
		</form>
		
		<?php
			$propCreerCompte = "<div class=\"container signin\">
				<p>Vous avez un compte? <a href=\"Login.php\">Sign in</a>.</p>
			</div>";
			
			if(!$estConnecte)
				echo $propCreerCompte;
		?>
	</body>
</html>