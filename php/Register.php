<?php
	include('../BDD/reqEquipe.php');
	session_start();
	
	if(isset($_POST) && isset($_POST['envoiValeurs']))
	{
		$_POST['psw'] = strval(hash("sha256", strval($_POST['psw'])));
		$_POST['psw_repeat'] = strval(hash("sha256", strval($_POST['psw_repeat'])));
		
		if(!$_SESSION['estJoueur'])
		{
			insertUser(strval($_POST['Nom']), strval($_POST['Prenom']), strval($_POST['Mail']), strval($_POST['psw']), strval($_POST['psw_repeat']), strval($_POST['role']));
		}
		else
		{
			if($_POST["Equipe"] === "")
				trigger_error("ERREUR : Veuillez choisir une équipe valide !");
			
			$equipeChoisie = getEquipe($_POST["Equipe"]);
			/*$estCapitaine = ((isset($_POST["EstCapitaine"])) && (!(empty($_POST["EstCapitaine"])))
						  && ($equipeChoisie->getCapitaine() === null));*/
			
			insertJoueur(strval($_POST['Nom']), strval($_POST['Prenom']), strval($_POST['Mail']), strval($_POST['psw']), strval($_POST['psw_repeat']), strval("Utilisateur"), strval($equipeChoisie->getIdEquipe()), 0);
		}
	}
	
	$tabEquipes = getAllEquipe();
	
	$msgErr = "<div class=\"erreur\">
							<h1>ERREUR !</h1>
							
							<p>
								Aucune équipe n'a été créée ! Vous ne pouvez pas vous inscrire !
							</p>
						</div>";
	
	/*$champCapitaine = "<div>
						<label for=\"ChoixEstCapitaine\">Êtes-vous le capitaine de l'équipe ?</label>
						<div id=\"ChoixEstCapitaine\">
							<label for=\"EstCapitaine\">Oui</label>
							<input type=\"radio\" name=\"EstCapitaine\" id=\"EstCapitaine\" value=\"EstCapitaine\" onclick=\"document.getElementById('NEstPasCapitaine').checked = false\">
							
							<label for=\"NEstPasCapitaine\">Non</label>
							<input type=\"radio\" name=\"NEstPasCapitaine[]\" id=\"NEstPasCapitaine\" value=\"NEstPasCapitaine\" onclick=\"document.getElementById('EstCapitaine').checked = false\">
						</div>
					</div>";*/
	
	$champChoixEquipe = "<div>
	<select id=\"Equipe\" name=\"Equipe\">
		<option value=\"\">---Choisissez votre équipe---</option>";
	
	for($i=0;$i<count($tabEquipes);++$i)
	{
		$idEquipeTemp = strval($tabEquipes[$i]->getIdEquipe());
		$nomEquipeTemp = strval($tabEquipes[$i]->getNomEquipe());
		
		$champChoixEquipe = $champChoixEquipe."<option value=\"$idEquipeTemp\">$nomEquipeTemp</option>";
	}
	
	$champChoixEquipe = $champChoixEquipe."</select>
</div>";
	
	$champsJoueur = "<div id=\"OptJoueur\">"./*$champCapitaine.*/$champChoixEquipe."</div>";
	
	$_POST = array();
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/styleLogin.css" />
		<style>
			body .bandeau-haut img {
				width:70px;
				padding:5px 0 0 5px;
				margin:5px 0 0 5px;
				float:left;
			}
		</style>
		
		<script type="text/javascript" src="../js/RegisterJS.js"></script>
		<title>Inscription</title>
	</head>
	
	<body>
		<div class="bandeau-haut">
			<a href="../php/InscriptionChoixRole.php">
				<img src="../img/prev.png">
				<h3>RETOUR</h3>
			</a>
		</div>
		
		<form action="Register.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Inscription</p>
			</h1>
			
			<p style="text-align: center;">Entrez vos informations pour vous inscrire</p>
			
			<hr>
			
			<label for="Prenom"><b>Prénom</b></label>
			<input type="text" placeholder="Entrez votre prénom" name="Prenom" id="Prenom" required>        
			
			<label for="Nom"><b>Nom</b></label>
			<input type="text" placeholder="Entrez votre nom" name="Nom" id="Nom" required>
			
			<label for="Mail"><b>Mail</b></label>
			<input type="email" placeholder="Entrez votre mail" name="Mail" id="Mail" required>
			
			<label for="psw"><b>Mot de passe</b></label>
			<input type="password" placeholder="Entrez votre mot de passe" name="psw" id="psw" required>
			
			<label for="psw-repeat"><b>Confirmation</b></label>
			<input type="password" placeholder="Répétez votre mot de passe" name="psw_repeat" id="psw_repeat" required>
			
			<br>
			
			
			</br>
			
			<div class ="container_role">
			<?php
			if($_SESSION['estJoueur']){
				echo "<b>Choix D'équipe de Joueur</b>";
				echo $champChoixEquipe;
			}
			else{
				echo '<label for="Utilisateur">Utilisateur</label>';
				echo '<input type="hidden" name="role" id="Utilisateur" value="Utilisateur" onclick="gestionOptionsJoueur()">';
			}

			?>
				
				
			</div>
			
			<hr>
			
			<button type="submit" class="registerbtn" name="envoiValeurs" value="Envoyer">S'inscrire</button>
		</form>
		
		<div class="container-signin">
			<p>Vous avez un compte? <a href="Login.php">Connectez-vous</a></p>
		</div>
	</body>
</html>