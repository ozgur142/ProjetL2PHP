<?php
	include_once('reqUtilisateur.php');
	include_once('../module/Utilisateur.php');
	include_once('../module/Gestionnaire.php');
	
	function insertGestionnaire(string $nom, string $prenom, string $email, string $mdp, string $confirmation, string $role)
	{
		include('DataBaseLogin.inc.php');
		
		$resInsertionUtilisateur = insertUtilisateur($nom, $prenom, $email, $mdp, $confirmation, $role);
		
		if(!$resInsertionUtilisateur)
			trigger_error("Erreur insertion utilisateur (gestionnaire).");
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$ut = getUtilisateurWithEmail($email);
		$idG = $ut->getIdUtilisateur();
		
		$requete = "INSERT INTO Gestionnaire VALUES($idG);";
		
		$res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		
		unset($_POST);
		
		header('Location: ../php/Login.php');
		exit();
	}
	
	function estGestionnaire(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idGestionnaire FROM Gestionnaire WHERE idGestionnaire = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return false;
		}
		
		$objTemp = $res->fetch_object();
		$idGestionnaire = strval($objTemp->idGestionnaire);
		
		$connexion->close();
		
		if(empty($idGestionnaire))
			return false;
		
		return true;
	}
	
	function getGestionnaire(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Gestionnaire WHERE idGestionnaire = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$objTemp = $res->fetch_object();
		$idGestionnaire = strval($objTemp->idGestionnaire);
		
		$connexion->close();
		
		if(empty($idGestionnaire))
			return NULL;
		
		$ut = getUtilisateur($id);
		
		return new Gestionnaire($ut->getIdUtilisateur(), $ut->getNom(), $ut->getPrenom(), $ut->getEmail(), $ut->getMdp(), $ut->getRole(), $idGestionnaire);
	}
?>