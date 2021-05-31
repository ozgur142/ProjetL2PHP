<?php
	include_once('./BDD/reqUtilisateur.php');
	include_once('./BDD/reqJoueur.php');
	include_once('./BDD/reqTournoi.php');
	
	session_start();
	
	$ut = NULL;
	$estConnecte = false;
	$estAdministrateur = false;
	$estJoueur = false ;
	$estGestionnaire = false ;
	
	if(isset($_SESSION['login']))
	{
		if(verifLoginMdp(strval($_SESSION['login']), strval($_SESSION['motDePasse'])))
		{
			$ut = getUtilisateurWithEmail($_SESSION['login']);
			$estConnecte = true;
			$estAdministrateur = ($ut->getRole() === "Administrateur");
			$estJoueur = estJoueur($ut->getIdUtilisateur());
			$estGestionnaire = estGestionnaire($ut->getIdUtilisateur());
		}
	}
	
	$tabTournois = getAllTournoi();
	$possedeTournois = (count($tabTournois) > 0);
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<link rel="stylesheet" type="text/css" href="./css/syleIndex.css" />
		<script type="text/javascript" src="./js/Menu.js"></script>
		<title>Accueil</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
	        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
	        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
	        crossorigin="anonymous"></script>
	    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
	        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
	        crossorigin="anonymous"></script>
	    <!--<link rel="stylesheet" href="style.css">!-->
		
		<link rel="stylesheet" type="text/css" href="./css/styleCarte.css" />
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
		<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
	</head>
	<body>
		<div class="bandeau-haut">	
			<h3 style="font-size:50px;margin:auto;">TOURNOIS SPORTIFS</h3>
			<div class="topnav-right">
				<?php
					$register = "<a href=\"php/InscriptionChoixRole.php\" style=\"text-decoration: none\">Inscription</a>";
					$co = "<a href=\"php/Login.php\" style=\"text-decoration: none\">Connexion</a>";
					$deco = "<a href=\"php/Logout.php\" style=\"text-decoration: none\">Déconnexion</a>";

					$total1 = "<a href=\"php/InscriptionChoixRole.php\" style=\"text-decoration: none\">Inscription</a>
					<a href=\"php/Login.php\" style=\"text-decoration: none\">Connexion</a>";

					$total2 = "<a href=\"php/Profil.php\" style=\"text-decoration: none\">Mon Compte</a>
					<a href=\"php/Logout.php\" style=\"text-decoration: none\">Déconnexion</a>";	
					echo (($estConnecte) ? $total2 : $total1);
				?>
			</div>
			
			<div class="unBeauMenu">
				<div class="iconeMenu" onclick="changerIcone(this)">
					<div class="barre1"></div>
					</br>
					<div class="barre2"></div>
					</br>
					<div class="barre3"></div>
				</div>

				<div class="corpsMenu">
					<ul class="listeItemsMenus">

						<li class="itemMenu"><a class="lien" href="php/Tournois.php">Liste des Tournois</a></li>
						<?php
						if($estAdministrateur)
						{
							echo'
							<li class="itemMenu"><a class="lien" href="php/CreerTournoi.php">Création de tournois</a></li>
							<li class="itemMenu"><a class="lien" href="php/CreerEquipe.php">Créer Equipe</a></li>
							<li class="itemMenu"><a class="lien" href="php/CreerGestionnaire.php">Créer gestionnaire</a></li>
							<li class="itemMenu"><a class="lien" href="php/ChoixInscription.php">Inscription</a></li>
							';
						}

						if($estGestionnaire)
						{
							echo'<li class="itemMenu"><a class="lien" href="php/ChoixInscription.php">Inscription</a></li>';
						}

						if($estJoueur)
						{
							echo'<li class="itemMenu"><a class="lien" href="php/Preinscription.php">Préinscription</a></li>';
						}
						?>
						<li class="itemMenu"><a class="lien" href="php/APropos.php">À Propos</a></li>
						<li class="itemMenu"><a class="lien" href="php/Contact.php">Contact</a></li>
						
					</ul>
				</div>
			</div>
		</div>
		<div class="container-main">

		<div id="carouselExemple" class="carousel slide" data-ride="carousel" data-interval="5000">

	        <ol class="carousel-indicators">
	            <li data-target="#carouselExemple" data-slide-to="0" class="active"></li>
	            <li data-target="#carouselExemple" data-slide-to="1"></li>
	            <li data-target="#carouselExemple" data-slide-to="2"></li>
	            <li data-target="#carouselExemple" data-slide-to="3"></li>
	            <li data-target="#carouselExemple" data-slide-to="4"></li>
	        </ol>


	        <div class="carousel-inner">

	            <div class="carousel-item active">
	                <img src="img/back3.jpg">
	            </div>

	            <div class="carousel-item">
	                <img src="img/basket.jpg">
	            </div>

	            <div class="carousel-item">
	                <img src="img/volley.jpg">
	            </div>

	            <div class="carousel-item">
	                <img src="img/tennis.jpg">
	            </div>

	            <div class="carousel-item">
	                <img src="img/foot.jpg">
	            </div>

	        </div>

	        <a href="#carouselExemple" class="carousel-control-prev" role="button" data-slide="prev">
	            <span class="carousel-control-prev-icon" aria-hidden="ture"></span>
	            <span class="sr-only">Previous</span>
	        </a>
	        <a href="#carouselExemple" class="carousel-control-next" role="button" data-slide="next">
	            <span class="carousel-control-next-icon" aria-hidden="true"></span>
	            <span class="sr-only">Next</span>
	        </a>

    	</div>


	    <script>
	        $('.carousel').carousel({pause: "null"})
	    </script>


    <div class="cadre">
		<h1>Bienvenue</h1>
	</div>
	
	<div id="mapid"></div>
		<?php
			$propIns = "
			<p>
				- Pas de compte ? Inscrivez-vous dès maintenant -
			</p>";
				
			if(!$estConnecte)
				echo $propIns;
			
			if($possedeTournois)
			{
				
				$declarationDonneesJSON = "var donnees = [";
				
				for($i=0;$i<count($tabTournois);++$i)
				{
					$strTemp = strval($tabTournois[$i]->getLieu());
					$tabTemp = explode("(", $strTemp);
					
					$nomVilleTemp = strval($tabTemp[0]);
					$posTemp = strval($tabTemp[1]);
					$posTemp2 = strval(explode(")", $posTemp)[0]);
					$tabPosTemp = explode(";", $posTemp2);
					
					$nomVille = strval(explode(" ", $nomVilleTemp)[0]);
					
					$posXTemp = strval($tabPosTemp[0]);
					$posYTemp = strval($tabPosTemp[1]);
					
					if(!$posXTemp)
						trigger_error("ERREUR : Localisation non définie.");
					
					if(!$posYTemp)
						trigger_error("ERREUR : Localisation non définie.");
					
					$posX = floatval($posXTemp);
					$posY = floatval($posYTemp);
					
					$nomTournoi = $tabTournois[$i]->getNom();
					$dateDeb = $tabTournois[$i]->getDateDeb();
					$duree = $tabTournois[$i]->getDuree();
					$nbEquipes = $tabTournois[$i]->getNombreTotalEquipes();
					
					$declarationDonneesJSON = $declarationDonneesJSON."{ nomVille: '$nomVille', posX: $posX, posY: $posY, nomTournoi : '$nomTournoi', dateDeb: '$dateDeb', duree: '$duree', nbEquipes: $nbEquipes }";
					
					if($i < (count($tabTournois) - 1))
						$declarationDonneesJSON = $declarationDonneesJSON.",";
				}
				
				$declarationDonneesJSON = $declarationDonneesJSON."];";
				
				$p1 = "<script>
				
				var mymap = L.map('mapid').setView([51.505, -0.09], 13);
				
				L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
					attribution: 'Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors, Imagery © <a href=\"https://www.mapbox.com/\">Mapbox</a>',
					maxZoom: 18,
					id: 'mapbox/streets-v11',
					tileSize: 512,
					zoomOffset: -1,
					accessToken: 'pk.eyJ1Ijoiam9mZnJleXB1amFkZSIsImEiOiJja3A1bWhzbDkwbmtkMnZzMWw3NDMxdDdnIn0.PmGtjPOaJsBREWnhD0QDSw'
				}).addTo(mymap);";
				
				$p1 = $p1.$declarationDonneesJSON;
				
				$p2 = "</script>";
				
				$carteStr = $p1;
				
				$carteStr = $carteStr."
				var tabPopUp = [];
				
				for(var i=0;i<donnees.length;++i)
				{
					var tournoiHTML = \"<b>\" + String(donnees[i].nomTournoi) + \"</b> <br />\";
					
					tournoiHTML = tournoiHTML + \"Date de début :\" + String(donnees[i].dateDeb) + \"<br />\";
					tournoiHTML = tournoiHTML + \"Durée :\" + String(donnees[i].duree) + \" jours<br />\";
					tournoiHTML = tournoiHTML + \"Ville :\" + String(donnees[i].nomVille) + \"<br />\";
					tournoiHTML = tournoiHTML + \"Nombre d'équipes :\" + String(donnees[i].nbEquipes) + \"<br />\";
					
					var popUp = L.marker([donnees[i].posX, donnees[i].posY]).addTo(mymap).bindPopup(tournoiHTML);
					tabPopUp.push(popUp);
				}
				
				tabPopUp[0].openPopup();
				";
				
				$carteStr = $carteStr.$p2;
				
				//echo "<div id=\"mapid\"></div>";
				
				echo $carteStr;
			}
		?>
	</div>
	</body>
</html>