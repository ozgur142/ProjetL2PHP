<?php
	include('../BDD/reqEquipe.php');
	
	if(isset($_POST) && isset($_POST['envoiValeurs']))
	{
		$_POST['psw'] = strval(hash("sha256", strval($_POST['psw'])));
		$_POST['psw_repeat'] = strval(hash("sha256", strval($_POST['psw_repeat'])));
		
		if($_POST["role"] == "Utilisateur")
		{
			insertUser(strval($_POST['Nom']), strval($_POST['Prenom']), strval($_POST['Mail']), strval($_POST['psw']), strval($_POST['psw_repeat']), strval($_POST['role']));
		}
		else if($_POST["role"] == "Joueur")
		{
			if($_POST["Equipe"] === "")
				trigger_error("ERREUR : Veuillez choisir une équipe valide !");
			
			$equipeChoisie = getEquipe($_POST["Equipe"]);
			$estCapitaine = ((isset($_POST["EstCapitaine"])) && (!(empty($_POST["EstCapitaine"])))
						  && ($equipeChoisie->getCapitaine() === null));
			
			insertJoueur(strval($_POST['Nom']), strval($_POST['Prenom']), strval($_POST['Mail']), strval($_POST['psw']), strval($_POST['psw_repeat']), strval("Utilisateur"), strval($equipeChoisie->getIdEquipe()), $estCapitaine);
		}
	}
	
	$tabEquipes = getAllEquipe();
	
	$msgErr = "<div class=\"erreur\">
							<h1>ERREUR !</h1>
							
							<p>
								Aucune équipe n'a été créée ! Vous ne pouvez pas vous inscrire !
							</p>
						</div>";
	
	$champCapitaine = "<div>
						<label for=\"ChoixEstCapitaine\">Êtes-vous le capitaine de l'équipe ?</label>
						<div id=\"ChoixEstCapitaine\">
							<label for=\"EstCapitaine\">Oui</label>
							<input type=\"radio\" name=\"EstCapitaine\" id=\"EstCapitaine\" value=\"EstCapitaine\" onclick=\"document.getElementById('NEstPasCapitaine').checked = false\">
							
							<label for=\"NEstPasCapitaine\">Non</label>
							<input type=\"radio\" name=\"NEstPasCapitaine[]\" id=\"NEstPasCapitaine\" value=\"NEstPasCapitaine\" onclick=\"document.getElementById('EstCapitaine').checked = false\">
						</div>
					</div>";
	
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
	
	$champsJoueur = "<div id=\"OptJoueur\">".$champCapitaine.$champChoixEquipe."</div>";
	
	$_POST = array();
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../css/styleLogin.css" />
		
		<style>
			body div img {
				width:50px;
				border:5px groove white;
				padding:5px;
				float:left;
			}
		</style>
		
		<script type="text/javascript" src="../js/RegisterJS.js"></script>
		<title>Inscription</title>
	</head>
	
	<body>
		<div>
			<a href="../index.php">
			<img src="../img/home.png">
			</a>
		</div>
		
		<form action="Register.php" method="POST" onreset="return vider();" class="container">
			<h1>
				<p style="text-align: center;">Inscription</p>
			</h1>
			
			<p style="text-align: center;">Entrez vos information pour créer votre compte</p>
			
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
			
			<b>Sélectionnez votre rôle dans le tournoi</b>
			
			</br>
			
			<div class ="container_role">
				<label for="Joueur">Joueur</label>
				<input type="radio" name="role" id="Joueur" value="Joueur" onclick="gestionOptionsJoueur()">
				
				<br>
				
				<?php
					if(count($tabEquipes) == 0)
					{
						echo $msgErr;
					}
					else
					{
						echo $champsJoueur;
					}
				?>
				
				<label for="Utilisateur">Utilisateur</label>
				<input type="radio" name="role" id="Utilisateur" value="Utilisateur" onclick="gestionOptionsJoueur()">
			</div>
			
			<hr>
			
			<button type="submit" class="registerbtn" name="envoiValeurs" value="Envoyer">Voilà</button>
			<button type="reset" name="effacerValeurs" value="Effacer">Voilà 2</button>
		</form>
		
		<div class="container-signin">
			<p>Vous avez un compte? <a href="Login.php">Connectez-vous</a></p>
		</div>
	</body>
</html>