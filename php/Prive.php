<?php
	include_once  '../BDD/reqUtilisateur.php';
	
	session_start();
	
	if(!isset($_SESSION['login']))
	{
		trigger_error("WTF");
	}
	
	if(!verifLoginMdp(strval($_SESSION['login']), strval($_SESSION['motDePasse'])))
	{
		trigger_error("WTF2");
		header('Location: Login.php');
		exit();
	}
	
	$br = "<br>"
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
		<title> Titre </title>
	</head>
	
	<body>
		<h1>Ceci est un titre important</h1>
		<p>Ceci est un paragraphe important contenant des informations importantes.</p>
		<?php
			echo $_SESSION['login'];
			
			echo $br;
			
			echo $_SESSION['motDePasse'];
			
			echo $br;
			header('Location: ../index.php');
		?>
	</body>
</html>