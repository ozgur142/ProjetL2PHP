<?php
	include_once('./BDD/reqUtilisateur.php');
	
	session_start();
	
	$ut = NULL;
	$estConnecte = false;
	$estAdministrateur = false;
	
	if(isset($_SESSION['login']))
	{
		if(verifLoginMdp(strval($_SESSION['login']), strval($_SESSION['motDePasse'])))
		{
			$ut = getUtilisateurWithEmail($_SESSION['login']);
			//$estConnecte = true;
			$estAdministrateur = ($ut->getRole() === "Administrateur");
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./css/syleIndex.css" />
		<!--<script type="text/javascript" src="PageDacc.js"></script>-->
	</head>

    <body>
        <div class="topnav">
            <a href="#">Information</a>
            <a href="#">Tournois en cours</a>
            <a href="Tournois.php">Liste des Tournois</a>
            <a href="php/Preinscription.php">Preinscription</a>
            <div class="topnav-right">
              <a href="php/Login.php">Login</a>
			  <a href="php/Logout.php">Se déconnecter</a>
            </div>
			
			<?php
				$propCreerGestionnaire = "<a href=\"php/CreerGestionnaire.php\">Créer un gestionnaire de tournoi</a>";
				
				if($estAdministrateur)
					echo $propCreerGestionnaire;
			?>
        </div>
    </body>
</html>
