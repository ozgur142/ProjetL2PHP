<?php
	include_once('reqGestionnaire.php');
	include_once('../module/Tournoi.php');
	
	function insertTournoi(string $nom, string $dateDeb, string $duree, int $idGestionnaire, string $lieu, string $nombreTotalEquipes)
	{
		include('DataBaseLogin.inc.php');
		
		if(!estGestionnaire($idGestionnaire))
			trigger_error("ERREUR : Identifiant de gestionnaire invalide.");
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$idT = chooseIntegerIdSequential("Tournoi", "idTournoi");
		
		$requete = "INSERT INTO Tournoi VALUES($idT, $nom, $dateDeb, $duree, $idGestionnaire, $lieu, $nombreTotalEquipes);";
		
		$res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		
		unset($_POST);
		
		header('Location: ../php/Login.php');
		exit();
	}
	
	function estTournoi(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idTournoi FROM Tournoi WHERE idTournoi = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return false;
		}
		
		$objTemp = $res->fetch_object();
		$idTournoi = strval($objTemp->idTournoi);
		
		$connexion->close();
		
		if(empty($idTournoi))
			return false;
		
		return true;
	}
	
	function getTournoi(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Tournoi WHERE idTournoi = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$objTemp = $res->fetch_object();
		$idTournoi = strval($objTemp->idTournoi);
		$nom = strval($objTemp->nom);
		$dateDeb = strval($objTemp->dateDeb);
		$duree = strval($objTemp->duree);
		$idGestionnaire = strval($objTemp->idGestionnaire);
		$lieu = strval($objTemp->lieu);
		$nombreTotalEquipes = strval($objTemp->nombreTotalEquipes);
		
		$connexion->close();
		
		if(empty($idTournoi))
			return NULL;
		
		$gest = getGestionnaire($idGestionnaire);
		
		return new Tournoi($idTournoi, $nom, $dateDeb, $duree, $gest, $lieu, $nombreTotalEquipes);
	}
?>