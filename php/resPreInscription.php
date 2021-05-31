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
	
	$res = "<h2>Tournoi : \"$nomTournoi\"</h2>";
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/styleLogin.css" />
		<script type="text/javascript" src="../js/RegisterJS.js"></script>
		<title>Résultats pré-inscription</title>
		<style>
			body .bandeau-haut img {
				width:70px;
				padding:5px 0 0 5px;
				margin:5px 0 0 5px;
				float:left;
			}

			.cadrebis{
				background-color:white;
				border:2px double black;
				box-shadow: 10px 10px grey;
				margin:auto;
				height:100%;
				width:500px;
				margin-top:3%;
			}
			h1,h2,p {
				font-family: tournois;
				text-align:center;
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

		<div class="cadrebis">
			<h1>Pré-inscription réussie</h1>

			<?php
				echo $h2;
				
				echo $res;
			?>
		</div>
	</body>
</html>