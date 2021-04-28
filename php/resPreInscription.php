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
	
	if(!isset($_SESSION['Tournoi']))
		trigger_error("Aucun tournoi n'a été sélectionné pour la pré-inscription !");
	
	$idTournoi = ((int)strval($_SESSION['Tournoi']));
	
	if(!estTournoi($idTournoi))
		trigger_error("ERREUR : Le tournoi que vous avez sélectionné n'est pas valide !");
	
	$equipe = getEquipe($joueur->getIdEquipe());
	
	$equipe->addCapitaine($joueur);
	
	$tournoi = getTournoi($idTournoi);
	
	$nomEquipe = $equipe->getNomEquipe();
	$nomTournoi = $tournoi->getNom();
	
	$h2 = "<h2>Équipe : $nomEquipe</h2>";
	
	$res = "Pré-inscription de cette équipe au tournoi \"$nomTournoi\" bien effectuée.";
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/styleLogin.css" />
		<script type="text/javascript" src="../js/RegisterJS.js"></script>
		<title>Résultats pré-inscription</title>
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
		
		<h1>Résultat de la pré-inscription d'une équipe</h1>
		
		<?php
			echo $h2;
			
			echo $res;
		?>
	</body>
</html>