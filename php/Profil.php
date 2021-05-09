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
				if(!isset($_POST['modifier']))
				{
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
						<form action="Profil.php" method="POST">
							<button type="submit" class="envoi" name="modifier" value="Envoyer" style="margin-top:45px">Modifier informations</button>
						</form>	
					</div>';
				}
				else
				{
					echo 
					'<div id="section3">
						<form action="Profil.php" method="POST">

							<label for="mail"><b>Adresse électronique</b></label>
							
							<input type="email" placeholder="@" name="mail" required>
							</br>

							<label for="psw"><b>Mot de passe</b></label>
							
							<input type="password" placeholder="********** ANcien mdp" name="psw" style="margin-left:77px" required>

							</br>

							<label for="psw_repeat"><b>Confirmation</b></label>
							
							<input type="password" placeholder="**********" name="psw_repeat" style="margin-left:77px" required>
							</br>
						
							<button type="submit" class="envoi" name="envoiValeurs" value="Envoyer">Valider informations</button>
							';
							if(!isset($_POST['modifier']))
								echo'<button type="submit" class="envoi" name="voir" value="Envoyer" style="margin-top:15px">Mes Tournois</button>

						</form>
					</div>';
				}
				echo '
				</div>	
				</div>';
				if($estAdministrateur)
				{
					$tabTournois = getAllTournoiByDate();
					echo '<div id="tab">
					<table>
						<tr>
							<form action="Profil.php" method="post">
								<tr><th colspan="9" style="text-align:left">
								<input type="number" name="idT" id="idT" placeholder="Entrer un ID pour consulter un tournoi"><button type="submit" class="btn" name="envoiValeurs" value="Envoyer">Rechercher</button> 
							</form>
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
						<form action="Profil.php" method="post">
							<tr><th colspan="8" style="text-align:left">
							<input type="number" name="idT" id="idT" placeholder="Entrer ID pour consulter un tournoi"><button type="submit" class="btn" name="envoiValeurs" value="Envoyer">Rechercher</button> 
						</form>
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
				/*if($estJoueur)
				{
					$joueur = getJoueur($id);
					$equipe = getEquipe($joueur->getIdEquipe()) ;
					$listeTournois = getAllTournoi();
					$indiceTournoi = -1 ;
					for($i=0;$i<sizeof($listeTournois);++$i)
					{
						if(estEquipeTournoi($equipe->getIdEquipe(),$listeTournois[$i]->getIdTournoi()))
						{
			
							$indiceTournoi = $i ;
						}
					}
		
					echo '
					<div id="tab2">
					<table>
					<tr>
					<th>Nom</th>
					<th>Lieu</th>
					<th>Début</th>
					<th>Fin</th>
					<th>Durée</th>
					<th>Statut</th>
					</tr>
					<tr>';
					if($indiceTournoi!=-1)
					{
						echo '
						<td>'.$listeTournois[$indiceTournoi]->getNom().'</td>
						<td>'.$listeTournois[$indiceTournoi]->getLieu().'</td>
						<td>'.date("d/m/Y", strtotime($listeTournois[$indiceTournoi]->getDateDeb())).'</td>
						<td>'.date("d/m/Y", strtotime($listeTournois[$indiceTournoi]->getDateDeb(). '+'.$listeTournois[$indiceTournoi]->getDuree().' days')).'</td>
						<td>'.$listeTournois[$indiceTournoi]->getDuree().'</td>';
						if(getEquipeTournoi($equipe->getIdEquipe(),$listeTournois[$indiceTournoi]->getIdTournoi())->getEstInscrite())
							echo'<td>Inscrite</td>';
						else
							echo'<td>Pré-inscrite</td>';
					}
					else
						echo '<td colspan="6">Aucun Tournoi</td>';
					echo'</tr></div>';
				
					


				}*/
				
				?>
						
			</div>
	</body>
</html>