<?php
	include_once('reqEquipe.php');
	include_once('reqTournoi.php');
	include_once('../module/EquipeTournoi.php');
	
	function insertEquipeTournoi(int $idE, int $idT, bool $estInscrite)
	{
		include('DataBaseLogin.inc.php');
		
		if(!estEquipe($idE))
			trigger_error("ERREUR : Identifiant d'équipe invalide.");
		
		if(!estTournoi($idT))
			trigger_error("ERREUR : Identifiant de tournoi invalide.");
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "INSERT INTO EquipeTournoi VALUES($idE, $idT, $estInscrite);";
		
		$res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		
		unset($_POST);
		
		header('Location: ../php/Login.php');
		exit();
	}
	
	function estEquipeTournoi(string $idE, string $idT)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idEquipe, idTournoi FROM EquipeTournoi WHERE idEquipe = $idE AND idTournoi = \"$idT\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return false;
		}
		
		$objTemp = $res->fetch_object();
		$idEquipe = strval($objTemp->idEquipe);
		$idTournoi = strval($objTemp->idTournoi);
		
		$connexion->close();
		
		if(empty($idEquipe))
			return false;
		
		if(empty($idTournoi))
			return false;
		
		return true;
	}
	
	function getEquipeTournoi(string $idE, string $idT)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM EquipeTournoi WHERE idEquipe = $idE AND idTournoi = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$objTemp = $res->fetch_object();
		$idEquipe = strval($objTemp->idEquipe);
		$idTournoi = strval($objTemp->idTournoi);
		$estInscrite = strval($objTemp->estInscrite);
		
		$connexion->close();
		
		if(empty($idEquipe))
			return NULL;
		
		if(empty($idTournoi))
			return NULL;
		
		return new EquipeTournoi($idEquipe, $idTournoi, $estInscrite);
	}
?>