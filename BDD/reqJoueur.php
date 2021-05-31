<?php
	include_once('reqUtilisateur.php');
	include_once(realpath(dirname(__FILE__)).'/../module/Utilisateur.php');
    include_once(realpath(dirname(__FILE__)).'/../module/Joueur.php');
	
	function insertJoueur(string $nom, string $prenom, string $email, string $mdp, string $confirmation, string $role, string $idEquipe, bool $estCapitaine)
	{
		include('DataBaseLogin.inc.php');
		
		$resInsertionUtilisateur = insertUtilisateur($nom, $prenom, $email, $mdp, $confirmation, $role);
		
		if(!$resInsertionUtilisateur)
			trigger_error("Erreur insertion utilisateur (joueur).");
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$ut = getUtilisateurWithEmail($email);
		$idJ = $ut->getIdUtilisateur();
		
		$estCap = $estCapitaine ? "TRUE" : "FALSE";
		
		$requete = "INSERT INTO Joueur VALUES($idJ, '$idEquipe', $estCap);";
		
		$res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		
		unset($_POST);
		
		header('Location: ../php/Login.php');
		exit();
	}
	
	function estJoueur(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idJoueur FROM Joueur WHERE idJoueur = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return false;
		}
		
		$objTemp = $res->fetch_object();
		if(empty($objTemp))
		{
			$connexion->close();
			return false;
		}
		else
		{
			$idJoueur = strval($objTemp->idJoueur);
			$connexion->close();
			return true;
		}
	}
	
	function getJoueur(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Joueur WHERE idJoueur = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$objTemp = $res->fetch_object();
		$idJoueur = strval($objTemp->idJoueur);
		$idEquipe = strval($objTemp->idEquipe);
		$estCapitaine = strval($objTemp->estCapitaine);
		
		$connexion->close();
		
		if(empty($idJoueur))
			return NULL;
		
		$ut = getUtilisateur($id);
		
		return new Joueur($ut->getIdUtilisateur(), $ut->getNom(), $ut->getPrenom(), $ut->getEmail(), $ut->getMdp(), $ut->getRole(), $idJoueur, $idEquipe, $estCapitaine);
	}
?>