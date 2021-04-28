<?php
	include_once('../BDD/reqEquipeTournoi.php');
	
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
	
	$ut = getUtilisateurWithEmail($_SESSION['login']);
	$estConnecte = true;
	$estAdministrateur = ($ut->getRole() === "Administrateur");
	
	if(!estGestionnaire($ut->getIdUtilisateur()))
	{
		trigger_error("Vous n'êtes pas un gestionnaire de tournoi.");
		/*header('Location: index.php');
		exit();*/
	}
	
	$gestionnaire = getGestionnaire($ut->getIdUtilisateur());
	$br = "<br />";
	echo $gestionnaire->toString();
	echo $br;
	
	$tabTournoi = getAllTournoiWithIdGestionnaire($gestionnaire->getIdUtilisateur());
	
	$champChoixTournoi = "<div>
	<select id=\"Tournoi\" name=\"Tournoi\">
		<option value=\"\">---Choisissez votre tournoi---</option>";
	
	for($i=0;$i<count($tabTournoi);++$i)
	{
		$idTournoiTemp = strval($tabTournoi[$i]->getIdTournoi());
		$nomTournoiTemp = strval($tabTournoi[$i]->getNom());
		
		$champChoixTournoi = $champChoixTournoi."<option value=\"$idTournoiTemp\">$nomTournoiTemp</option>";
	}
	
	$champChoixTournoi = $champChoixTournoi."</select>
</div>";
	
	if(isset($_POST) && isset($_POST['envoiValeurs']))
	{
		if($_POST["Tournoi"] === "")
			trigger_error("ERREUR : Veuillez choisir un tournoi valide !");
		else
		{
			$idTournoi = ((int)strval($_POST["Tournoi"]));
			
			unset($_POST);
			
			if(!estTournoi($idTournoi))
				trigger_error("ERREUR : Le tournoi sélectionné est invalide !");
			else
			{
				$tournoi = getTournoi($idTournoi);
				
				if($tournoi->getIdGestionnaire() !== $gestionnaire->getIdGestionnaire())
					trigger_error("ERREUR : Vous n'êtes pas le gestionnaire du tournoi que vous avez sélectionné.");
				else
				{
					$_SESSION["idTournoi"] = $idTournoi;
					
					header('Location: ../php/Inscription.php');
					exit();
				}
			}
		}
	}
	
	$_POST = array();
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/styleLogin.css" />
		<script type="text/javascript" src="../js/InscriptionJS.js"></script>
		<title>Choix inscription</title>
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
		
		<form action="ChoixInscription.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Choix d'inscription</p>
			</h1>
			
			<p style="text-align: center;">Sélectionnez le tournoi dont il faut gérer les inscriptions.</p>
			
			<hr>
			
			<label for="Tournoi"><b>Sélectionnez le tournoi dont il faut gérer les inscriptions.</b></label>
			
			<?php
				echo $champChoixTournoi;
			?>
			<hr>
			
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