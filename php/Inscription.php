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
		header('Location: index.php');
		exit();
	}
	
	$gestionnaire = getGestionnaire($ut->getIdUtilisateur());
	$br = "<br />";
	echo $gestionnaire->toString();
	echo $br;
	
	$tournoi = getTournoiWithIdGestionnaire($gestionnaire->getIdUtilisateur());
	echo $tournoi->toString();
	$tabEquipeTournoi = getEquipeTournoiWithIdTournoi($tournoi->getIdTournoi());
	
	if(sizeof($tabEquipeTournoi) == 0)
		trigger_error("ERREUR : Il n'y a aucune pré-inscription pour ce tournoi !");
	
	$tabEquipes = array();
	
	for($i=0;$i<sizeof($tabEquipeTournoi);++$i)
		array_push($tabEquipes, getEquipe($tabEquipeTournoi[$i]->getIdEquipe()));
	
	$nomTournoi = $tournoi->getNom();
	
	
	$enTeteTableau = "<table>
	<thead>
		<tr>
			<th>Nom du tournoi</th>
			<th>Nom de l'équipe</th>
			<th>Inscrire</th>
			<th>Retirer</th>
		</tr>
	</thead>
	
	</tbody>";
	
	$corpsTableau = "";
	
	for($i=0;$i<sizeof($tabEquipeTournoi);++$i)
	{
		$nomEquipe = $tabEquipes[$i]->getNomEquipe();
		$idEquipe = $tabEquipes[$i]->getIdEquipe();
		
		echo $nomEquipe;
		echo $br;
		echo $idEquipe;
		
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
						</tr>";
	}
	
	$finTableau = "</tbody>
	</table>";
	
	$tableau = $enTeteTableau.$corpsTableau.$finTableau;
	
	if(isset($_POST) && isset($_POST['envoiValeurs']))
	{
		$inscriptionsEffectuees = true;
		$retraitsEffectues = true;
		
		for($i=0;$i<sizeof($tabEquipes);++$i)
		{
			$idEquipe = $tabEquipes[$i]->getIdEquipe();
			
			if($_POST["Ins$idEquipe"] === "")
					trigger_error("ERREUR : Veuillez choisir une option valide !");
			
			$inscriptionValidee = ($_POST["Ins$idEquipe"] === "Ins$idEquipe");
			
			if($inscriptionValidee)
				$inscriptionsEffectuees = (($inscriptionsEffectuees) && (modifierEquipeTournoi($idEquipe, $tournoi->getIdTournoi(), true)));
			else
				$retraitsEffectues = (($retraitsEffectues) && (supprimerEquipeTournoi($idEquipe, $tournoi->getIdTournoi())));
		}
		
		$verif = (($inscriptionsEffectuees) && ($retraitsEffectues));
		
		if(!$verif)
			trigger_error("ERREUR : La modification des inscriptions a subi des erreurs.");
		else
		{
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
		<form action="Inscription.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Inscripition</p>
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