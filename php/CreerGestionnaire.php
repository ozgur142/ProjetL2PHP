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
		<option value=\"\">Choisir un gestionnaire</option>";
	
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
		<style>
			body .bandeau-haut img {
				width:70px;
				padding:5px 0 0 5px;
				margin:5px 0 0 5px;
				float:left;
			}

			#Gestionnaire {
				background-color:white;
				color:#333333;
				font-family:Helvetica Neue,Helvetica,Arial,sans-serif;
				width:70%;
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

		
		<form action="CreerGestionnaire.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Créer un gestionnaire</p>
			</h1>
			
			<p style="text-align: center;">Sélectionner le gestionnaire de tournoi que vous voulez créer.</p>
			
			<hr>
			
			<?php
				echo $champChoixGestionnaire;
			?>
			
			<br />
			
			<button type="submit" class="registerbtn" name="envoiValeurs" value="Envoyer">Créer</button>
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