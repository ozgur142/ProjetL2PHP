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
	
	$estGest = estGestionnaire($ut->getIdUtilisateur());
	$estAdmin = ($ut->getRole() == "Administrateur");
	
	if(!$estGest && !$estAdmin)
	{
		trigger_error("Vous n'êtes pas un gestionnaire de tournoi ni un administrateur du site.");
	}
	
	$gestionnaire = null;
	
	if($estGest)
		$gestionnaire = getGestionnaire($ut->getIdUtilisateur());
	
	if(!isset($_SESSION["idTournoi"]))
	{
		trigger_error("ERREUR : Vous n'avez choisi aucun tournoi !");
	}
	
	$idTournoi = ((int)strval($_SESSION["idTournoi"]));
	
	if(!estTournoi($idTournoi))
	{
		trigger_error("ERREUR : Le tournoi sélectionné est invalide !");
		header('Location: ../index.php');
		exit();
	}
	
	$tournoi = getTournoi($idTournoi);
	
	if(!$estAdmin)
	{
		if($gestionnaire !== null)
		{
			if($tournoi->getIdGestionnaire() !== $gestionnaire->getIdGestionnaire())
			{
				trigger_error("ERREUR : Vous n'êtes pas le gestionnaire du tournoi que vous avez sélectionné.");
				header('Location: ../index.php');
				exit();
			}
		}
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
		
		
		$corpsTableau = $corpsTableau
						."<tr>
							<td>$nomTournoi</td>
							<td>$nomEquipe</td>
							<td>
								<input type=\"radio\" name=\"Ins$idEquipe\" checked id=\"Ins$idEquipe\" class=\"choixInscription\" value=\"Ins$idEquipe\" onclick=\"document.getElementById('Ret$idEquipe').checked = false\" checked>
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
		<style>
			body .bandeau-haut img {
				width:70px;
				padding:5px 0 0 5px;
				margin:5px 0 0 5px;
				float:left;
			}

			.tableauClassique td,.tableauClassique th {
				width: 15%;
				text-align:center;
			}

		</style>
	</head>
	
	<body>
		<div class="bandeau-haut">
			<a href="Tournois.php">
				<img src="../img/prev.png">
				<h3>RETOUR</h3>
			</a>
		</div>

		
		<form action="Inscription.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Inscription</p>
			</h1>
			
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
			<br/>
			<hr>
			
			<button type="submit" class="registerbtn" name="envoiValeurs" value="Envoyer">Valider</button>
			<button type="reset" name="effacerValeurs" value="Effacer">Effacer les champs</button>
		</form>
	</body>
</html>
