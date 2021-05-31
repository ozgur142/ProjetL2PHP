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
			p, h2, li, ul {
				color: white;
				text-align: justify;
			}
			a { color:  #fdfefe ; }
			a:hover {
				color:#0073e6;
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
				<p>fonctionnement</p>
			</h1>
			<hr>
			<h2>bienvenu sur notre sites de gestion de tournois sportif voici quelque information utile / importante pour comprendre comment correctement utilisé ce site.</h2>
			<p>dans un premier temps nous vous invitons a vous <a href="./Inscription.php" >créer un compte</a> adapté a ce que vous voulez faire :</p>
			<ul>
					<li> utilisateur si vous voulez voir l'avancement des tournois</li>
					<li>joueur si vous voulez rentrer dans une équipe et prendre par au tournois</li>
					<li>capitaine si vous voulez créer une équipe et choisir dans quel tournois jouer</li>
			</ul>
			<p>si vous voulez créer un tournois  il faudra contacter les administrateurs , les information nécessaire pour ce faire sont dans <a href="./contact.php" >contact</a> . </p>
			<p> vous devrez communiquer : </p>
			<ul>
					<li> le nom de votre compte</li>
					<li>le nom du tournois </li>
					<li>le type de tournois <a style="color: red;"> (entre coupe,tournois,championnat ) </a></li>
					<li>le lieu <a style="color: red;"> (en france) </a></li>
					<li>la date de début </li>
					<li>la durée</li>
					<li>le nombre d'équipes <a style="color: red;"> (une puissance de 2) </a></li>
			</ul>
			<p> votre tournois sera alors créé dans les plus bref délai et vous en serais le gestionnaire; ce sera donc a vous de validé les inscription , daté les matches et d'inscrire les scores </p>
			<h2>inscription au tournois :</h2>
			<p> seul le capitaine d'une équipe peux pré-inscrire cette dernière , le gestionnaire du tournois validera ou non la pré-inscription </p>
			<h1>
				<p>Les types de tournois</p>
			</h1>
			<hr>
			<h2>championnat  </h2>
			 	<p> toutes les équipes s'affronte des point sont attribué selon les victoires / nuls/ défaite , l'équipe qui a le plus de point quand tout les matches sont joué gagne</p> 
				<p>L’attribution des points s’organise de la manière suivante :</p>
				<ul>
					<li>victoire = 4 points</li>
					<li>match nul = 2 points</li>
					<li>défaite = 1 point</li>
				</ul>
			<h2>coupe </h2>
			<p>chaque tour les équipe s'affronte 2 a 2 la perdante est éliminé et la gagnante passe au tour suivant</p>
			<h2>tournois </h2>
			<p> séparé en deux partie : </p>
			<ul>
					<li>une phase de poule ou les équipe sont répartie en groupe de 4 et font un championnat entre elle puis les deux premiére passe a la phase final</li>
					<li>une phase final ou les équipes s'affronte comme lors d'une coupe (les premiéreq équipes de chaque poule affrontent les deuxiémes d'une autre poule) </li>
			</ul>
		</div>
	</body>
</html>