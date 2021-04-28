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
	
	if(!estJoueur($ut->getIdUtilisateur()))
	{
		trigger_error("Vous n'êtes pas un joueur d'équipe.");
		header('Location: ../index.php');
		exit();
	}
	
	$joueur = getJoueur($ut->getIdUtilisateur());
	
	if(!$joueur->getCapitaine())
	{
		trigger_error("Vous n'êtes pas un capitaine d'équipe.");
		header('Location: ../index.php');
		exit();
	}
	
	$equipe = getEquipe($joueur->getIdEquipe());
	
	$equipe->addCapitaine($joueur);
	
	$tabEquipes = getAllTournoi();
	
	$br = "<br />";
	
	for($i=0;$i<$equipe->getNbJoueurs();++$i)
	{
		$j = $equipe->getTabJoueurs()[$i];
	}
	
	if(!is_array($tabEquipes))
		trigger_error("ERREUR : requête tournoi");
	
	if(count($tabEquipes) == 0)
		trigger_error("ERREUR : résultat requête tournoi vide.");
	
	$champChoixTournoi = "<div>
	<select id=\"Tournoi\" name=\"Tournoi\">
		<option value=\"\">---Choisissez votre tournoi---</option>";
	
	for($i=0;$i<count($tabEquipes);++$i)
	{
		$idTournoiTemp = strval($tabEquipes[$i]->getIdTournoi());
		$nomTournoiTemp = strval($tabEquipes[$i]->getNom());
		
		$champChoixTournoi = $champChoixTournoi."<option value=\"$idTournoiTemp\">$nomTournoiTemp</option>";
	}
	
	$champChoixTournoi = $champChoixTournoi."</select>
</div>";
	
	if(isset($_POST) && isset($_POST['envoiValeurs']))
	{
		$_SESSION['Tournoi'] = $_POST['Tournoi'];
		
		insertEquipeTournoi(strval($equipe->getIdEquipe()), strval($_POST['Tournoi']), false);
	}
	
	$_POST = array();
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/styleLogin.css" />
		<script type="text/javascript" src="../js/RegisterJS.js"></script>
		<title>Pré-inscription</title>
	</head>
	
	<body>
		<div>
			<a href="Login.php">Se connecter</a>
			<a href="Logout.php">Se déconnecter</a>
			<a href="Register.php">Créer un compte</a>
			<a href="CreerEquipe.php">Créer une équipe</a>
			<a href="Preinscription.php">Pré-inscrire une équipe</a>
			<a href="ChoixInscription.php">Gérer les inscriptions d'un tournoi</a>
		</div>
		
		<form action="Preinscription.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Pré-inscripition</p>
			</h1>
			
			<p style="text-align: center;">
				Le nom de votre équipe est : 
				<?php
					echo $equipe->getNomEquipe();
				?>
			</p>
			
			<p style="text-align: center;">Sélectionnez le tournoi auquel vous voulez vous pré-inscrire.</p>
			
			<hr>
			
			<label for="Tournoi"><b>Sélectionnez le tournoi auquel vous voulez vous pré-inscrire parmis les choix suivants.</b></label>
			<?php
				echo $champChoixTournoi
			?>
			<hr>
			
			<button type="submit" class="registerbtn" name="envoiValeurs" value="Envoyer">Voilà</button>
			<button type="reset" name="effacerValeurs" value="Effacer">Voilà 2</button>
		</form>
	</body>
</html>