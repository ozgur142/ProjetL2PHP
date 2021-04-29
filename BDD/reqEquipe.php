<?php
	include_once('reqJoueur.php');
	include_once('../module/Equipe.php');
	
	function insertEquipe(string $nomEquipe, string $adresse, string $numTel)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$idE = chooseIntegerIdSequential("Equipe", "idEquipe");
		
		$requete = "INSERT INTO Equipe VALUES($idE, '$nomEquipe', 0, '$adresse', '$numTel');";
		
		$res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		
		unset($_POST);
		
		header('Location: ../php/Login.php');
		exit();
	}
	
	function estEquipe(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idEquipe FROM Equipe WHERE idEquipe = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return false;
		}
		
		$objTemp = $res->fetch_object();
		$idEquipe = strval($objTemp->idEquipe);
		
		$connexion->close();
		
		if(empty($idEquipe))
			return false;
		
		return true;
	}
	
	function getEquipe(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Equipe WHERE idEquipe = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$objTemp = $res->fetch_object();
		$idEquipe = strval($objTemp->idEquipe);
		$nomEquipe = strval($objTemp->nomEquipe);
		$niveau = ((int)$objTemp->niveau);
		$adresse = strval($objTemp->adresse);
		$numTel = strval($objTemp->numTel);
		
		$connexion->close();
		
		if(empty($idEquipe))
			return NULL;
		
		$tabJoueursEquipe = array();
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Joueur WHERE idEquipe = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbJoueursEquipe = $res->num_rows;
		
		if($nbJoueursEquipe > 0)
		{
			while($obj = $res->fetch_object())
			{
				//echo $obj->idJoueur;
				array_push($tabJoueursEquipe, getJoueur($obj->idJoueur));
			}
		}
		
		$connexion->close();
		
		return new Equipe($idEquipe, $nomEquipe, $niveau, $adresse, $numTel, $tabJoueursEquipe);
	}
	
	function getAllEquipe()
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
		
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Equipe;";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbEquipes = $res->num_rows;
		
		$connexion->close();
		
		$tabEquipes = array();
		
		if($nbEquipes == 0)
			return $tabEquipes;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabEquipes, getEquipe(strval($obj->idEquipe)));
		}
		
		return $tabEquipes;
	}
?>