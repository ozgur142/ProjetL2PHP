<?php
	include_once('../BDD/reqEquipeTournoi.php');
	include_once('../BDD/reqType.php');
	$tabTournois= getAllTournoi();
	session_start();
	
	$ut = null;
	$estAdministrateur = null;
	$estGestionnaire = null;

	if(isset($_SESSION['login']))
	{
		$ut = getUtilisateurWithEmail($_SESSION['login']);
		$estAdministrateur = ($ut->getRole() === "Administrateur");
		$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
	}

	if($estGestionnaire || $estAdministrateur)
	{
		if(isset($_POST['tournoi']))
		{
			if(getTypeTournoi(strval($_POST['tournoi']))=="Championnat")
			{
				$_SESSION['tournoi'] = strval($_POST['tournoi']) ;
				header('Location: StatutTournoisAVenir_Championnat.php');
			}
			elseif(getTypeTournoi(strval($_POST['tournoi']))=="Tournoi")
			{
				$_SESSION['tournoi'] = strval($_POST['tournoi']) ;
				header('Location: StatutTournoisAVenir_Poule.php');
			}
			else
			{
				$_SESSION['tournoi'] = strval($_POST['tournoi']) ;
				header('Location: StatutTournoisAVenir.php');
			}
		}

		if(isset($_POST['tournoiEnCours']))
		{
			if(getTypeTournoi(strval($_POST['tournoiEnCours']))=="Championnat")
			{
				$_SESSION['tournoiEnCours'] = strval($_POST['tournoiEnCours']) ;
				header('Location: StatutTournoisEnCours_Championnat.php');
			}
			elseif(getTypeTournoi(strval($_POST['tournoiEnCours']))=="Tournoi")
			{
				$_SESSION['tournoiEnCours'] = strval($_POST['tournoiEnCours']) ;
				header('Location: StatutTournoisEnCours_Poule.php');
			}
			else
			{
				$_SESSION['tournoiEnCours'] = strval($_POST['tournoiEnCours']) ;
				header('Location: StatutTournoiEnCours.php');
			}
		}

		if(isset($_POST['tournoiPasse']))
		{
			if(getTypeTournoi(strval($_POST['tournoiPasse']))=="Championnat")
			{
				$_SESSION['tournoiPasse'] = strval($_POST['tournoiPasse']) ;
				header('Location: statutTournoiPasses_Championnat.php');
			}
			elseif (getTypeTournoi(strval($_POST['tournoiPasse']))=="Tournoi") 
			{
				$_SESSION['tournoiPasse'] = strval($_POST['tournoiPasse']) ;
				header('Location: statutTournoiPasse_Poule.php');
			}
			else
			{
				$_SESSION['tournoiPasse'] = strval($_POST['tournoiPasse']) ;
				header('Location: statutTournoiPasses.php');
			}
		}
	}
	else
	{
		if($_POST && isset($_POST['tournoiEnCours']))
		{
			if(getTypeTournoi(strval($_POST['tournoiEnCours']))=="Championnat")
			{
				$_SESSION['tournoiEnCours'] = strval($_POST['tournoiEnCours']) ;
				header('Location: StatutTournoisEnCours_Championnat.php');
			}
			elseif(getTypeTournoi(strval($_POST['tournoiEnCours']))=="Tournoi")
			{
				$_SESSION['tournoiEnCours'] = strval($_POST['tournoiEnCours']) ;
				header('Location: StatutTournoisEnCours_Poule.php');
			}
			else
			{
				$_SESSION['tournoiEnCours'] = strval($_POST['tournoiEnCours']) ;
				header('Location: StatutTournoiEnCours.php');
			}
		}

		if($_POST && isset($_POST['tournoiPasse']) && strval($_POST['tournoiPasse'])!=null)
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
		<style>
			body .bandeau-haut img {
				width:70px;
				padding:5px 0 0 5px;
				margin:5px 0 0 5px;
				float:left;
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

		<div class="cadre">   
			<h1>
				<p style="text-align: center;">Tournois passés</p>
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
					$ville = explode("(",$tabTournois[$i]->getLieu())[0];
					$tabMatchT = getAllMatchT($tabTournois[$i]->getIdTournoi());
					$tournoiFinis = true;
					if(sizeof($tabMatchT)==0)
						$tournoiFinis = false;
					else{
						$MatchTaVerifier = $tabMatchT[(count($tabMatchT)-1)];
						$tabEquipeMatchT = getEquipesMatchT($MatchTaVerifier->getIdMatchT());
						if(sizeof($tabEquipeMatchT)==0)
							$tournoiFinis = false;
						else{
							for($j = 0;$j<count($tabEquipeMatchT);$j++){
								$score = $tabEquipeMatchT[$j]->getScore();
								if($score == -1){
									$tournoiFinis = false;
									break;
								}
							}
						}
					}
					echo'<tr>';
					if($tournoiFinis)
					{
						echo '<td><button type=submit name="tournoiPasse" value="'.$tabTournois[$i]->getIdTournoi().'" class="btn">'.$tabTournois[$i]->getNom().'</button></td>';
						echo '<td>'.$ville.'</td>';
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
					$tabMatchT = getAllMatchT($tabTournois[$i]->getIdTournoi());
					$tournoiFinis = true;
					if(sizeof($tabMatchT)==0)
						$tournoiFinis = false;
					else{
						$MatchTaVerifier = $tabMatchT[(count($tabMatchT)-1)];
						$tabEquipeMatchT = getEquipesMatchT($MatchTaVerifier->getIdMatchT());
						if(sizeof($tabEquipeMatchT)==0)
							$tournoiFinis = false;
						else{
							for($j = 0;$j<count($tabEquipeMatchT);$j++){
								$score = $tabEquipeMatchT[$j]->getScore();
								if($score == -1){
									$tournoiFinis = false;
									break;
								}
							}
						}
					}
					echo'<tr>';
					if($tabTournois[$i]->enCours() && ($tabTournois[$i]->tournoiPres()) && !$tournoiFinis )
					{
						$ville = explode("(",$tabTournois[$i]->getLieu())[0];
						echo '<td><button type=submit name="tournoiEnCours" value="'.$tabTournois[$i]->getIdTournoi().'" class="btn">'.$tabTournois[$i]->getNom().'</button></td>';
						echo '<td>'.$ville.'</td>';
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
					if($tabTournois[$i]->aVenir() || !($tabTournois[$i]->tournoiPres()))
					{
						$ville = explode("(",$tabTournois[$i]->getLieu())[0];
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
						echo '<td>'.$ville.'</td>';
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