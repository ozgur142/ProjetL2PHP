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
	$id = $ut->getIdUtilisateur();

	$estAdministrateur = ($ut->getRole() === "Administrateur");
	$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
	$estJoueur = estJoueur($ut->getIdUtilisateur());
	$estUtilisateur = false ;

	$tabTournoisAdmin = array();
	$tabTournois = array();
	$gest = 0 ;

	if($estAdministrateur)
		$tabTournoisAdmin = getAllTournoibyDate();
	elseif($estGestionnaire)
	{
		$gest = getGestionnaire($id);
		$tabTournois = getAllTournoiWithIdGestionnaireByDate($gest->getIdGestionnaire());
	}
	elseif(!$estJoueur) {
		$estUtilisateur = true ;
	}

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

		<style>
			body .bandeau-haut img {
				width:70px;
				padding:5px 0 0 5px;
				margin:5px 0 0 5px;
				float:left;
			}
		</style>
	</head>	
	<body id="body">
		<div class="bandeau-haut">
			<a href="../index.php">
				<img src="../img/prev.png">
				<h3>RETOUR</h3>
			</a>
		</div>

		<div id="container-main">
			<?php
			$nom = $ut->getNom() ;
			$prenom = $ut->getPrenom() ;
			$role = $ut->getRole() ;

			echo '<p style="text-align:center;font-size:25px">'.$nom.' '.$prenom;
			echo'<hr>';
			
			echo'
			<table style="margin:auto">
				<tr>
				<th style="text-align:center">Adresse électronique</th><th>'.$ut->getEmail().'</th>
				</tr>
				<tr>
				<th style="text-align:center">Mot de passe</th><th>************</th>
				</tr>
				<tr>
				<th style="text-align:center">IDENTIFIANT</th><th>'.$id.'</th>
				</tr>';

			if($estGestionnaire)
				echo  '<tr><th style="text-align:center">Role</th><th>Gestionnaire</th></tr>';
			elseif($estAdministrateur)
				echo  '<tr><th style="text-align:center">Role</th><th>Administrateur</th></tr>';
			elseif($estJoueur)
			{
				$joueur = getJoueur($id);
				$equipe = getEquipe($joueur->getIdEquipe()) ;
				echo  '<tr><th style="text-align:center>('.$equipe->getNomEquipe().')</th></tr>';
				if($joueur->getCapitaine())
					echo  '<tr><th style="text-align:center>Role</th><th>Capitaine ('.$equipe->getNomEquipe().')</th></tr>';
				else
					echo  '<tr><th style="text-align:center>Equipe</th><th>'.$equipe->getNomEquipe().'</th></tr>';
			}
			else
			{
				echo'<tr><th style="text-align:center>Role</th><th>'.$ut->getRole().'</th></tr>';
			}
			echo'</table>';
			?>
		</div>

			<?php

				if($estGestionnaire || $estAdministrateur)
				{
					$monTab = array() ;
					if($estAdministrateur)
						$monTab = $tabTournoisAdmin ;
					else
						$monTab = $tabTournois ;
					
						
					if(sizeof($monTab)>0)
					{
						echo '<div id="tab2">';
						echo '<table>
						<tr>
						</th></tr>
						<th>ID</th>';
						if($estAdministrateur)
							echo'<th>Gestionnaire</th>';
						echo'<th>Nom</th>
						<th>Lieu</th>
						<th>Début</th>
						<th>Fin</th>
						<th>Durée</th>
						<th>Equipes</th>
						<th>Statut</th>
						</tr>';
						
						for($i=0;$i<sizeof($monTab);++$i)
						{
							$idG = $monTab[$i]->getIdGestionnaire();
							$gest = getGestionnaire($idG) ;
							$ville = explode("(",$monTab[$i]->getLieu())[0];
							echo'<tr>';
							?>
							<?php
								echo '
								<td>'.$monTab[$i]->getIdTournoi().'</td>';
								if($estAdministrateur)
									echo '<td>'.$gest->getNom().' '.$gest->getPrenom().' (ID '.$idG.')</td>';
								echo'<td>'.$monTab[$i]->getNom().'</td>

								<td>'.$ville.'</td>
								<td>'.date("d/m/Y", strtotime($monTab[$i]->getDateDeb())).'</td>
								<td>'.date("d/m/Y", strtotime($monTab[$i]->getDateDeb(). '+'.$monTab[$i]->getDuree().' days')).'</td>
								<td>'.$monTab[$i]->getDuree().' jours</td>
								<td>'.$monTab[$i]->getNombreTotalEquipes().'</td>';
								if($monTab[$i]->termine())
									echo '<td>Terminé</td>';
								elseif($monTab[$i]->enCours())
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