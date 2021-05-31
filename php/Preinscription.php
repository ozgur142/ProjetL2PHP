<?php
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../BDD/reqJoueur.php');
	include_once('../BDD/reqUtilisateur.php');
	
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
	//$estGestionnaire = false ;
	//$estAdministrateur = false ;
	//$estAdministrateur = ($ut->getRole() === "Administrateur");
	//$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
	$estJoueur = estJoueur($ut->getIdUtilisateur()) ;


	if(!$estJoueur)
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
	<select id=\"Tournoi\" name=\"Tournoi\" >
		<option value=\"\">Choisir tournoi</option>";
	
	for($i=0;$i<count($tabEquipes);++$i)
	{
		$idTournoiTemp = strval($tabEquipes[$i]->getIdTournoi());
		$nomTournoiTemp = strval($tabEquipes[$i]->getNom());
		
		$champChoixTournoi = $champChoixTournoi."<option value=\"$idTournoiTemp\">$nomTournoiTemp</option>";
	}
	
	$champChoixTournoi = $champChoixTournoi."</select>
</div>";
	
	if(isset($_POST) && isset($_POST['envoiValeurs']) && isset($_POST['Tournoi']))
	{	
		if($_POST['Tournoi']!="")
		{
			$_SESSION['Tournoi'] = $_POST['Tournoi'];
			insertEquipeTournoi(strval($equipe->getIdEquipe()), strval($_POST['Tournoi']), false);
			header('Location: ../php/resPreInscription.php');
			exit();
		
		}
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
		
		<form action="Preinscription.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Pré-inscripition</p>
			</h1>
			
			<p style="text-align: center; font-size:22px;">
				Votre équipe : 
				<?php
					echo $equipe->getNomEquipe();
				?>
			</p>
			<hr>
			<label for="Tournoi"><b>Sélectionnez un tournoi</b></label>
			<?php
				echo $champChoixTournoi
			?>
			<hr>
			
			<button type="submit" class="registerbtn" name="envoiValeurs" value="Envoyer">Se Pré-inscrire</button>
		</form>
	</body>
</html>