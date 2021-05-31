<?php
	include_once(realpath(dirname(__FILE__)).'/../BDD/reqGeneralBDD.php');
	include_once(realpath(dirname(__FILE__)).'/../module/Poule.php');
	
	function insertPoule(int $idTournoi, int $nbEquipes)
	{
		include('DataBaseLogin.inc.php');
		
		$idP = intval(chooseIntegerIdSequential("Poule", "idPoule"));
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "INSERT INTO Poule VALUES($idP, $idTournoi, $nbEquipes);";
		
		$res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		
		return new Poule($idP, $idTournoi, $nbEquipes);
	}
	
	function estPoule(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idPoule FROM Poule WHERE idPoule = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return false;
		}
		
		$objTemp = $res->fetch_object();
		
		if(!$objTemp)
			return false;
		
		$idPoule = strval($objTemp->idPoule);
		
		$connexion->close();
		
		if(empty($idPoule))
			return false;
		
		return true;
	}
	
	function getPoule(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Poule WHERE idPoule = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$objTemp = $res->fetch_object();
		$idPoule = intval(strval($objTemp->idPoule));
		$idTournoi = intval(strval($objTemp->idTournoi));
		$nbEquipes = intval(strval($objTemp->nbEquipes));
		
		$connexion->close();
		
		if(empty(strval($idPoule)))
			return NULL;
		
		return new Poule($idPoule, $idTournoi, $nbEquipes);
	}

	function getAllPoule()
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
		
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Poule ;";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbPoules = $res->num_rows;
		
		$connexion->close();
		
		$tabPoules = array();
		
		if($nbPoules == 0)
			return $tabPoules;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabPoules, getPoule($obj->idPoule));
		}
		
		return $tabPoules;
	}
	
	function compterPoulesTournoi(int $idTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
		
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Poule WHERE idTournoi = $idTournoi;";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbPoules = $res->num_rows;
		
		$connexion->close();
		
		return $nbPoules;
	}
	
	function getAllPouleTournoi(int $idTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
		
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Poule WHERE idTournoi = $idTournoi;";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbPoules = $res->num_rows;
		
		$connexion->close();
		
		$tabPoules = array();
		
		if($nbPoules == 0)
			return $tabPoules;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabPoules, getPoule($obj->idPoule));
		}
		
		return $tabPoules;
	}
	
	function getPouleWithEquipeAndTournoi(int $idEquipe, int $idTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
		
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "
		SELECT *
		FROM Poule
		WHERE idTournoi = $idTournoi AND idPoule IN (
													  SELECT idPoule
													  FROM EquipePoule
													  WHERE idEquipe = $idEquipe);";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$connexion->close();
		
		$obj = $res->fetch_object();
		return getPoule($obj->idPoule);
	}
?>