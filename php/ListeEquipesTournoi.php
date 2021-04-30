<?php
	include_once('../BDD/reqGestionnaire.php');
	include_once('../BDD/reqJoueur.php');
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../module/TasMax.php');
	
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
	$id = $ut->getIdUtilisateur();

	if(!$estGestionnaire)
	{
		if(!$estAdministrateur)
		{
			trigger_error("Vous n'avez pas les droits !");
			header('Location: Profil.php');
			exit();
		}
	}

	$_POST = array();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="../css/styleGestion.css" />
	<script type="text/javascript" src="../js/RegisterJS.js"></script>
	<title>Equipes tournoi</title>
</head>	
<body id="body">
	<div id="header">
		<a href="../index.php">Accueil</a>
		<a href="Logout.php">Se déconnecter</a>
		<a href="ChoixInscription.php">Gérer les inscriptions d'un tournoi</a>
		<a href="Profil.php">Profil</a>
	</div>
	<div id="container-main">
		<?php
			$id = $_SESSION['idT'] ;
			$tournoi = getTournoi($id);
			$tabEquipes = getEquipeTournoiWithIdTournoi($id);
			$nbe = $tournoi->getNombreTotalEquipes();
			$nbPreinscrits = getNbEquipesTournoiWithId($id) ;
			$nbValide = 0 ;
			$tabEq =array();


			echo'
			<div id="en-tete">
			<h1>'.$tournoi->getNom().'</h1>
			</div>

			';

	
				echo '
				<div id="tab3">
				';
				echo '<table>
				<tr>
				<th>Equipes</th>
				<th>Inscription</th>
				</tr>';
				for($i=0;$i<$nbe;++$i)
				{				
					if($tabEquipes[$i]!=null)
					{
						$equipe = getEquipe($tabEquipes[$i]->getIdEquipe()) ;
						array_push($tabEq,$equipe);
						echo'
						<tr>
						<td>'.$equipe->getNomEquipe().'</td>';
						if($tabEquipes[$i]->getEstInscrite())
						{
							echo'<td>Validée</td>';
							++$nbValide;
						}
						else
							echo'<td>En attentente de validation</td>';
						echo'</tr>';
					}
					else
					{
						echo '<tr>
							<td> - </td>
							<td> - </td>
							</tr>';
					}
				}
				echo'</table>';
				echo '</div>';

				echo '
				<h2 style="text-align:center; background-color:#333333; width:57%; margin:auto"> 
				Début du tournoi '.date("d/m/Y",strtotime($tournoi->getDateDeb())).'
				</h2>';


				if($nbValide==$nbe)
					echo '<p style="text-align:center">Inscriptions terminées</p>';
				else
					echo '<p style="text-align:center">Inscriptions non terminées</p>';
				

				echo '<div id="contenu">';
		?>	
	</div>
</body>
</html>