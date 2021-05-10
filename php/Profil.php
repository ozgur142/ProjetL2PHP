<?php
	include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqEquipe.php');
	include_once('../BDD/reqEquipeTournoi.php');
	
	session_start();
	
	if(!isset($_SESSION['login']))
	{
		trigger_error("Vous n'êtes pas authentifié.");
		header('Location: Login.php');
		exit();
	}
	
	if(!verifLoginMdp(strval($_SESSION['login']), strval($_SESSION['motDePasse'])))
	{
		trigger_error("Mauvais login/mot de passe.");
		header('Location: Login.php');
		exit();
	}
	
	$ut = getUtilisateurWithEmail($_SESSION['login']);
	$estAdministrateur = ($ut->getRole() === "Administrateur");
	$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
	$estJoueur = estJoueur($ut->getIdUtilisateur());
	if(!$estAdministrateur && !$estGestionnaire && !$estJoueur)
		$estUtilisateur = true ;
	$id = $ut->getIdUtilisateur();

	if(isset($_POST['envoiValeurs']) && strval($_POST['idT'])!=null)
	{   
		$_SESSION['idT'] = strval($_POST['idT']) ;
		
		
	}
	$_POST = array();

?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/styleProfil.css" />
		<script type="text/javascript" src="../js/RegisterJS.js"></script>
		<title>Mon profil</title>
	</head>	
	<body id="body">
		<div id="header">
			<a href="../index.php">Accueil</a>
			<a href="Logout.php">Se déconnecter</a>
			<a href="ChoixInscription.php">Gérer les inscriptions d'un tournoi</a>
		</div>

		<div id="container-main">

			<div id="section1">
				<img src="../img/gestionnaire2.png">
				<?php
				echo '<p style="text-align:center">'.$ut->getNom().' '.$ut->getPrenom().'</p><p style="text-align:center">'.$ut->getRole().' (ID : '.$ut->getIdUtilisateur().') ';
				?>
			</div>
			<div id="section2">
				<?php
				if($estGestionnaire)
					echo  '<p id="maj">Gestionnaire</p>';
				elseif($estJoueur)
				{
					$joueur = getJoueur($id);
					$equipe = getEquipe($joueur->getIdJoueur()) ;
					echo  '<p id="maj">Joueur ('.$equipe->getNomEquipe().')</p>';
					if($joueur->getCapitaine())
						echo  '<p style="text-align:center">- Capitaine -</p>';
				}
				else
					echo '<p id="maj">'.$ut->getRole().'</p>';
			
					echo 
					'<div id="section3">
						<table>
							<tr>
								<th style="text-align:left">Adresse électronique</th><th>'.$ut->getEmail().'</th>
							</tr>
							<tr>
								<th style="text-align:left">Mot de passe</th><th>************</th>
							</tr>
						</table>
					</div>';
				echo '
				</div>	
				</div>';
				if($estAdministrateur)
				{
					$tabTournois = getAllTournoiByDate();
					echo '<div id="tab">
					<table>
						<tr>
							</th></tr>
							<th>ID</th>
							<th>Nom</th>
							<th>Lieu</th>
							<th>Début</th>
							<th>Fin</th>
							<th>Durée</th>
							<th>Equipes</th>
							<th>Gestionnaire</th>
							<th>Staut</th>
						</tr>';

					for($i=0;$i<sizeof($tabTournois);++$i)
					{
						$idG = $tabTournois[$i]->getIdGestionnaire();
						$gest = getGestionnaire($idG) ;
						echo'
						<tr>
						<td>'.$tabTournois[$i]->getIdTournoi().'</td>	
						<td>'.$tabTournois[$i]->getNom().'</td>
						<td>'.$tabTournois[$i]->getLieu().'</td>
						<td>'.date("d/m/Y", strtotime($tabTournois[$i]->getDateDeb())).'</td>
						<td>'.date("d/m/Y", strtotime($tabTournois[$i]->getDateDeb(). '+'.$tabTournois[$i]->getDuree().' days')).'</td>
						<td>'.$tabTournois[$i]->getDuree().' jours</td>
						<td>'.$tabTournois[$i]->getNombreTotalEquipes().'</td>
						<td>'.$gest->getNom().' '.$gest->getPrenom().' (ID '.$idG.')</td>';
						if($tabTournois[$i]->termine())
							echo '<td>Terminé</td>';
						elseif($tabTournois[$i]->enCours())
							echo '<td>En Cours</td>';
						else
							echo '<td>A venir</td>';
						echo'</tr>';
					}
					echo'</table>';
				}
				if($estGestionnaire)
				{
					$gest = getGestionnaire($id);
					$tabTournois = getAllTournoiWithIdGestionnaireByDate($gest->getIdGestionnaire());
						
					if(sizeof($tabTournois)>0)
					{
						echo '<div id="tab2">';
						echo '<table>
						<tr>
						</th></tr>
						<th>ID</th>
						<th>Nom</th>
						<th>Lieu</th>
						<th>Début</th>
						<th>Fin</th>
						<th>Durée</th>
						<th>Equipes</th>
						<th>Statut</th>
						</tr>';
						
						for($i=0;$i<sizeof($tabTournois);++$i)
						{
							$idG = $tabTournois[$i]->getIdGestionnaire();
							$gest = getGestionnaire($idG) ;
							echo'<tr>';
							?>
							<?php
								echo '
								<td>'.$tabTournois[$i]->getIdTournoi().'</td>
								<td>'.$tabTournois[$i]->getNom().'</td>
								<td>'.$tabTournois[$i]->getLieu().'</td>
								<td>'.date("d/m/Y", strtotime($tabTournois[$i]->getDateDeb())).'</td>
								<td>'.date("d/m/Y", strtotime($tabTournois[$i]->getDateDeb(). '+'.$tabTournois[$i]->getDuree().' days')).'</td>
								<td>'.$tabTournois[$i]->getDuree().' jours</td>
								<td>'.$tabTournois[$i]->getNombreTotalEquipes().'</td>';
								if($tabTournois[$i]->termine())
									echo '<td>Terminé</td>';
								elseif($tabTournois[$i]->enCours())
									echo '<td>En Cours</td>';
								else
									echo '<td>A venir</td>';
								echo '</tr>';
						}
						echo'</table>';
						echo'</div>';
					}
					else
					{
						echo '<div id="tab">';
						echo "AUCUN TOURNOI";
						echo'</div>';
					}
				}
				
				?>
						
			</div>
	</body>
</html>