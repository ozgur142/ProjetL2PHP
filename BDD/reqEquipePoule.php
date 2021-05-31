<?php
	include_once(realpath(dirname(__FILE__)).'/../BDD/reqGeneralBDD.php');
	include_once(realpath(dirname(__FILE__)).'/../BDD/reqEquipe.php');
	include_once(realpath(dirname(__FILE__)).'/../module/EquipePoule.php');
	
	function insertEquipePoule(int $idEquipe, int $idPoule)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "INSERT INTO EquipePoule VALUES($idEquipe, $idPoule);";
		
		$res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		
		return new EquipePoule($idEquipe, $idPoule);
	}
	
	function estEquipePoule(string $idE, string $idP)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idEquipe, idPoule FROM EquipePoule WHERE idEquipe = \"$idE\" AND idPoule = \"$idP\";";
		
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
		
		$idEquipe = strval($objTemp->idEquipe);
		$idPoule = strval($objTemp->idPoule);
		
		$connexion->close();
		
		if(empty($idEquipe))
			return false;
		
		if(empty($idPoule))
			return false;
		
		return true;
	}
	
	function getEquipePoule(string $idE, string $idP)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM EquipePoule WHERE idEquipe = \"$idE\" AND idPoule = \"$idP\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$objTemp = $res->fetch_object();
		$idEquipe = intval(strval($objTemp->idEquipe));
		$idPoule = intval(strval($objTemp->idPoule));
		
		$connexion->close();
		
		if(empty(strval($idEquipe)))
			return NULL;
		
		if(empty(strval($idPoule)))
			return NULL;
		
		return new EquipePoule($idEquipe, $idPoule);
	}

	function getAllEquipePoule()
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
		
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM EquipePoule;";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbEquipePoules = $res->num_rows;
		
		$connexion->close();
		
		$tabEquipePoules = array();
		
		if($nbEquipePoules == 0)
			return $tabEquipePoules;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabEquipePoules, getPoule($obj->idEquipe, $obj->idPoule));
		}
		
		return $tabEquipePoules;
	}
	
	function getAllEquipePouleWithIdPoule(string $idPoule)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
		
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM EquipePoule WHERE idPoule = \"$idPoule\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbEquipePoules = $res->num_rows;
		
		$connexion->close();
		
		$tabEquipePoules = array();
		
		if($nbEquipePoules == 0)
			return $tabEquipePoules;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabEquipePoules, getEquipePoule($obj->idEquipe, $obj->idPoule));
		}
		
		return $tabEquipePoules;
	}
	
	function getAllEquipeOfPoule(int $idPoule)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
		
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM EquipePoule WHERE idPoule = $idPoule;";
		
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