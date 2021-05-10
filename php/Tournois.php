<?php
	include_once('../BDD/reqEquipeTournoi.php');
	$tabTournois= getAllTournoi();
	session_start();

	if(isset($_SESSION['login']))
	{
		$ut = getUtilisateurWithEmail($_SESSION['login']);
		$estAdministrateur = ($ut->getRole() === "Administrateur");
		$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
	}

	if($estGestionnaire || $estAdministrateur)
	{
		if($_POST && strval($_POST['tournoi'])!=null)
		{
			$_SESSION['tournoi'] = strval($_POST['tournoi']) ;
			header('Location: StatutTournoisAVenir.php');
		}

		if($_POST && strval($_POST['tournoiEnCours'])!=null)
		{
			$_SESSION['tournoiEnCours'] = strval($_POST['tournoiEnCours']) ;
			header('Location: StatutTournoiEnCours.php');
		}

		if($_POST && strval($_POST['tournoiPasse'])!=null)
		{
			$_SESSION['tournoiPasse'] = strval($_POST['tournoiPasse']);
			header('Location: statutTournoiPasses.php');
		}
	}
	else
	{
		if($_POST && strval($_POST['tournoiEnCours'])!=null)
		{
			$_SESSION['tournoiEnCours'] = strval($_POST['tournoiEnCours']);
			header('Location: AffichageTournoi.php');
		}
	
		if($_POST && strval($_POST['tournoiPasse'])!=null)
		{
			$_SESSION['tournoiPasse'] = strval($_POST['tournoiPasse']);
			header('Location: statutTournoiPasses.php');
		}
	}
	
	$_POST = array();

?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<link rel="stylesheet" type="text/css" href=".././css/styleTournois.css" />
		<title> Liste des Tournois </title>
	</head>
	<body>
		<div>
			<a href="../index.php">
				<img src="../img/home.png">
			</a>
		</div>
		<style>
			body div img {
				width:50px;
				border:5px groove white;
				padding:5px;
			}
		</style>
		
		<div class="cadre">   
			<h1>
				<p style="text-align: center;">Tournois passés</p>
			</h1>
			<?php
				/*
				Sur la page Html
				Rajouter Lien vers description de équipes du tournoi, puis composition de chaque équipe ?
				Rajouter lien vers l'arbre associé des tournois en cours et terminés
				Creer une page avec un menu déroulant ?
				Dans un premier temps on pourra soit cliquer sur les tournois en cours passés et à venir.
				Puis un tableau déroulant avec les bons tournois va s'afficher.
				Ou alors créer une barre de recherche qui va rechercher les tournois par nom/date/nbEquipes ?
				Attention si le gestionnaire est supprimé les tournois associés le sont aussi.
				Une fois un tournoi terminé créer une gestionnaire reservoir ?
				*/
				echo '<form action="Tournois.php" method="post">
				<table>
				<tr>
				<th>Nom</th>
				<th>Lieu</th>
				<th>Début</th>
				<th>Fin</th>
				<th>Durée</th>
				<th>Equipes</th>
				</tr>';
				for($i=0;$i<sizeof($tabTournois);++$i)
				{
					echo'<tr>';
					if($tabTournois[$i]->termine())
					{
						echo '<td><button type=submit name="tournoiPasse" value="'.$tabTournois[$i]->getIdTournoi().'" class="btn">'.$tabTournois[$i]->getNom().'</button></td>';
						echo '<td>'.$tabTournois[$i]->getLieu().'</td>';
						echo '<td>'.date("jS F, Y", strtotime($tabTournois[$i]->getDateDeb())).'</td>';
						echo '<td>'.date("jS F, Y", strtotime($tabTournois[$i]->getDateDeb(). '+'.$tabTournois[$i]->getDuree().' days')).'</td>';
						echo '<td>'.$tabTournois[$i]->getDuree().' jours</td>';
						echo '<td>'.$tabTournois[$i]->getNombreTotalEquipes().'</td>';
					}
					echo'</tr>';
				}
				echo'</table>
				</form>';
			?>
		</div>
		<div class="cadre">   
			<h1>
				<p style="text-align: center;">Tournois en cours</p>
			</h1>
			<?php
				echo '<form action="Tournois.php" method="post">
				<table>
				<tr>
				<th>Nom</th>
				<th>Lieu</th>
				<th>Début</th>
				<th>Fin</th>
				<th>Durée</th>
				<th>Equipes</th>
				</tr>';
				for($i=0;$i<sizeof($tabTournois);++$i)
				{
					echo'<tr>';
					if($tabTournois[$i]->enCours())
					{
						echo '<td><button type=submit name="tournoiEnCours" value="'.$tabTournois[$i]->getIdTournoi().'" class="btn">'.$tabTournois[$i]->getNom().'</button></td>';
						echo '<td>'.$tabTournois[$i]->getLieu().'</td>';
						echo '<td>'.date("jS F, Y", strtotime($tabTournois[$i]->getDateDeb())).'</td>';
						echo '<td>'.date("jS F, Y", strtotime($tabTournois[$i]->getDateDeb(). '+'.$tabTournois[$i]->getDuree().' days')).'</td>';
						echo '<td>'.$tabTournois[$i]->getDuree().' jours</td>';
						echo '<td>'.$tabTournois[$i]->getNombreTotalEquipes().'</td>';
					}
					echo'</tr>';
				}
				echo'</table>
				</form>';
			?>
		</div>

		<div class="cadre">   
			<h1>
				<p style="text-align: center;">Tournois à venir</p>
			</h1>
			<?php
				echo '<form action="Tournois.php" method="post">
				<table>
				<tr>
				<th>Nom</th>
				<th>Lieu</th>
				<th>Début</th>
				<th>Fin</th>
				<th>Durée</th>
				<th>Equipes restantes</th>
				</tr>';
				for($i=0;$i<sizeof($tabTournois);++$i)
				{
					echo'<tr>';
					if($tabTournois[$i]->aVenir())
					{
						$k=0;
						$nbe = $tabTournois[$i]->getNombreTotalEquipes();
						$id = $tabTournois[$i]->getIdTournoi();
						$tabEquipes = getEquipeTournoiWithIdTournoi($id);
						if(sizeof($tabEquipes)>0)
						{
							for($j=0;$j<sizeof($tabEquipes);++$j)
								if($tabEquipes[$j]->getEstInscrite())
									++$k;	
						}
						$nbPlaces = $tabTournois[$i]->getNombreTotalEquipes();
						echo '<td><button type=submit name="tournoi" value="'.$tabTournois[$i]->getIdTournoi().'" class="btn">'.$tabTournois[$i]->getNom().'</button></td>';
						echo '<td>'.$tabTournois[$i]->getLieu().'</td>';
						echo '<td>'.date("jS F, Y", strtotime($tabTournois[$i]->getDateDeb())).'</td>';
						echo '<td>'.date("jS F, Y", strtotime($tabTournois[$i]->getDateDeb(). '+'.$tabTournois[$i]->getDuree().' days')).'</td>';
						echo '<td>'.$tabTournois[$i]->getDuree().' jours</td>';
						echo '<td>'.($nbPlaces-$k).'/'.$nbPlaces.'</td>';
					}
					echo'</tr>';
				}
				echo'</table>
				</form>';
			?>
		</div>
	</body>
</html>