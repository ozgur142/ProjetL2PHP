<?php
	include_once('../BDD/reqEquipeTournoi.php');
	$tabTournois= getAllTournoi();
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
				Rajouter un lien cliquable vers le tournoi relantant des informations.
				Rajouter le vainqueur pour les tournois passés ?
				Rajouter Lien vers description de équipes du tournoi, puis composition de chaque équipe ?
				Rajouter lien vers l'arbre associé des tournois en cours et terminés
				Creer une page avec un menu déroulant ?
				Dans un premier temps on pourra soit cliquer sur les tournois en cours passés et à venir.
				Puis un tableau déroulant avec les bons tournois va s'afficher.
				Ou alors créer une barre de recherche qui va rechercher les tournois par nom/date/nbEquipes ?
				Attention si le gestionnaire est supprimé les tournois associés le sont aussi.
				Une fois un tournoi terminé créer une gestionnaire reservoir ?
				*/
				echo '<table>
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
						echo '<td>'.$tabTournois[$i]->getNom().'</td>';
						echo '<td>'.$tabTournois[$i]->getLieu().'</td>';
						echo '<td>'.date("jS F, Y", strtotime($tabTournois[$i]->getDateDeb())).'</td>';
						echo '<td>'.date("jS F, Y", strtotime($tabTournois[$i]->getDateDeb(). '+'.$tabTournois[$i]->getDuree().' days')).'</td>';
						echo '<td>'.$tabTournois[$i]->getDuree().' jours</td>';
						echo '<td>'.$tabTournois[$i]->getNombreTotalEquipes().'</td>';
					}
					echo'</tr>';
				}
				echo'</table>';
			?>
		</div>
		<div class="cadre">   
			<h1>
				<p style="text-align: center;">Tournois en cours</p>
			</h1>
			<?php
				echo '<table>
				<tr>
				<th>Nom</th>
				<th>Lieu</th>
				<th>Début</th>
				<th>Equipes</th>
				</tr>';
				for($i=0;$i<sizeof($tabTournois);++$i)
				{
					echo'<tr>';
					if($tabTournois[$i]->enCours())
					{
						echo '<td>'.$tabTournois[$i]->getNom().'</td>';
						echo '<td>'.$tabTournois[$i]->getLieu().'</td>';
						echo '<td>'.date("jS F, Y", strtotime($tabTournois[$i]->getDateDeb())).'</td>';
						echo '<td>'.$tabTournois[$i]->getNombreTotalEquipes().'</td>';
					}
					echo'</tr>';
				}
				echo'</table>';
			?>
		</div>

		<div class="cadre">   
			<h1>
				<p style="text-align: center;">Tournois à venir</p>
			</h1>
			<?php
				echo '<table>
				<tr>
				<th>Nom</th>
				<th>Lieu</th>
				<th>Début</th>
				<th>Equipes restantes</th>
				</tr>';
				for($i=0;$i<sizeof($tabTournois);++$i)
				{
					echo'<tr>';
					if($tabTournois[$i]->aVenir())
					{
						$nbPlaces = $tabTournois[$i]->getNombreTotalEquipes();
						$nbInscrits = getNbEquipesTournoiWithId($tabTournois[$i]->getIdTournoi()) ;
						echo '<td>'.$tabTournois[$i]->getNom().'</td>';
						echo '<td>'.$tabTournois[$i]->getLieu().'</td>';
						echo '<td>'.date("jS F, Y", strtotime($tabTournois[$i]->getDateDeb())).'</td>';
						echo '<td>'.($nbPlaces-$nbInscrits).'/'.$nbPlaces.'</td>';
					}
					echo'</tr>';
				}
				echo'</table>';
			?>
		</div>
	</body>
</html>