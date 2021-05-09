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
	
	if(!estGestionnaire($ut->getIdUtilisateur()))
	{
		trigger_error("Vous n'êtes pas un gestionnaire de tournoi.");
		/*header('Location: index.php');
		exit();*/
	}
	
	$gestionnaire = getGestionnaire($ut->getIdUtilisateur());
	$br = "<br />";
	/*echo $gestionnaire->toString();
	echo $br;*/
	
	if(!isset($_SESSION["idTournoi"]))
	{
		trigger_error("ERREUR : Vous n'avez choisi aucun tournoi !");
		/*header('Location: index.php');
		exit();*/
	}
	
	$idTournoi = ((int)strval($_SESSION["idTournoi"]));
	
	if(!estTournoi($idTournoi))
	{
		trigger_error("ERREUR : Le tournoi sélectionné est invalide !");
		header('Location: ../index.php');
		exit();
	}
	
	$tournoi = getTournoi($idTournoi);
	//echo $tournoi->toString();
	
	if($tournoi->getIdGestionnaire() !== $gestionnaire->getIdGestionnaire())
	{
		trigger_error("ERREUR : Vous n'êtes pas le gestionnaire du tournoi que vous avez sélectionné.");
		header('Location: ../index.php');
		exit();
	}
	
	$tabEquipeTournoi = getEquipeTournoiWithIdTournoi($tournoi->getIdTournoi());
	
	if(sizeof($tabEquipeTournoi) == 0)
		trigger_error("ERREUR : Il n'y a aucune pré-inscription pour ce tournoi !");
	
	$tabEquipes = array();
	
	for($i=0;$i<sizeof($tabEquipeTournoi);++$i)
		array_push($tabEquipes, getEquipe($tabEquipeTournoi[$i]->getIdEquipe()));
	
	$nomTournoi = $tournoi->getNom();
	
	$enTeteTableau = "<table class=\"tableauClassique\">
	<thead>
		<tr>
			<th>Nom du tournoi</th>
			<th>Nom de l'équipe</th>
			<th>Inscrire</th>
			<th>Retirer</th>
			<th>Inscription validée ?</th>
		</tr>
	</thead>
	
	</tbody>";
	
	$corpsTableau = "";
	
	for($i=0;$i<sizeof($tabEquipeTournoi);++$i)
	{
		$nomEquipe = $tabEquipes[$i]->getNomEquipe();
		$idEquipe = $tabEquipes[$i]->getIdEquipe();
		$equipeEstInscrite = $tabEquipeTournoi[$i]->getEstInscrite();
		$insValTxt = (($equipeEstInscrite) ? "Oui" : "Non");
		
		/*echo $nomEquipe;
		echo $br;
		echo $idEquipe;*/
		
		$corpsTableau = $corpsTableau
						."<tr>
							<td>$nomTournoi</td>
							<td>$nomEquipe</td>
							<td>
								<input type=\"radio\" name=\"Ins$idEquipe\" id=\"Ins$idEquipe\" class=\"choixInscription\" value=\"Ins$idEquipe\" onclick=\"document.getElementById('Ret$idEquipe').checked = false\">
							</td>
							<td>
								<input type=\"radio\" name=\"Ins$idEquipe\" id=\"Ret$idEquipe\" class=\"choixRetirer\" value=\"Ret$idEquipe\" onclick=\"document.getElementById('Ins$idEquipe').checked = false\">
							</td>
							<td>$insValTxt</td>
						</tr>";
	}
	
	$finTableau = "</tbody>
	</table>";
	
	$tableau = $enTeteTableau.$corpsTableau.$finTableau;
	
	if(isset($_POST) && isset($_POST['envoiValeurs']))
	{
		$inscriptionsEffectuees = true;
		$retraitsEffectues = true;
		
		$tabTemp = array();
		
		for($i=0;$i<sizeof($tabEquipes);++$i)
		{
			$idEquipe = $tabEquipes[$i]->getIdEquipe();
			
			$tabTemp["Ins$idEquipe"] = $_POST["Ins$idEquipe"];
		}
		
		for($i=0;$i<sizeof($tabEquipes);++$i)
		{
			$idEquipe = $tabEquipes[$i]->getIdEquipe();
			
			if($tabTemp["Ins$idEquipe"] === "")
					trigger_error("ERREUR : Veuillez choisir une option valide !");
			else
			{
				$inscriptionValidee = ($tabTemp["Ins$idEquipe"] === "Ins$idEquipe");
				$inscriptionInvalidee = ($tabTemp["Ins$idEquipe"] === "Ret$idEquipe");
				
				if($inscriptionValidee)
					$inscriptionsEffectuees = (($inscriptionsEffectuees) && (modifierEquipeTournoi($idEquipe, $tournoi->getIdTournoi(), true)));
				else if($inscriptionInvalidee)
					$retraitsEffectues = (($retraitsEffectues) && (supprimerEquipeTournoi($idEquipe, $tournoi->getIdTournoi())));
			}
		}
		
		$verif = (($inscriptionsEffectuees) && ($retraitsEffectues));
		
		if(!$verif)
			trigger_error("ERREUR : La modification des inscriptions a subi des erreurs.");
		else
		{
			unset($_SESSION["idTournoi"]);
			
			header('Location: ../php/resInscription.php');
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
		<script type="text/javascript" src="../js/InscriptionJS.js"></script>
		<title>Inscription</title>
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
		
		<form action="Inscription.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Inscription</p>
			</h1>
			
			<p style="text-align: center;">Sélectionnez les équipes à inscrire ou à retirer du tournoi.</p>
			
			<hr>
			
			<label for="Tournoi"><b>Sélectionnez les équipes à inscrire ou à retirer du tournoi "
			<?php
				echo $tournoi->getNom();
			?>
			".
			</b></label>
			
			<?php
				echo $tableau;
			?>
			<hr>
			
			<button type="submit" class="registerbtn" name="envoiValeurs" value="Envoyer">Voilà</button>
			<button type="reset" name="effacerValeurs" value="Effacer">Voilà 2</button>
		</form>
	</body>
</html>