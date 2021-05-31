<?php
	include_once('reqGestionnaire.php');
	include_once(realpath(dirname(__FILE__)).'/../module/Tournoi.php');
	
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

	function estPuissanceDe2(int $idTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT nombreTotalEquipes FROM Tournoi WHERE idTournoi=$idTournoi";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}

		$objTemp = $res->fetch_object();
		$nb = strval($objTemp->nombreTotalEquipes);
		$connexion->close();

		while($nb%2==0)
			$nb=$nb/2;

		return $nb==1;
	}

	function UpdateNbEquipes(int $nbEquipes, int $idTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "UPDATE Tournoi SET nombreTotalEquipes=$nbEquipes WHERE idTournoi=$idTournoi";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		$connexion->close();
		return true;
	}



	//exactement la même fonction que intertTournoi();
	function creerTournoi(string $nom, string $dateDeb, int $duree, int $idGestionnaire, string $lieu, int $nombreTotalEquipes)
    {
        include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}

        $idT = chooseIntegerIdSequential("Tournoi", "idTournoi");

        $requete = "INSERT INTO Tournoi VALUES($idT, '$nom','$dateDeb', $duree, $idGestionnaire, '$lieu',$nombreTotalEquipes);";

        $res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		
		//unset($_POST);
		
		//header('Location: ../php/CreerTournoi.php');
		//exit();
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
		
		return new Tournoi($idTournoi, $nom, $dateDeb, $duree, $gest->getIdGestionnaire(), $lieu, $nombreTotalEquipes);
	}
	
	function getTournoiWithIdGestionnaire(string $id, int $indexTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Tournoi WHERE idGestionnaire = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbTournois = $res->num_rows;
		
		$connexion->close();
		
		$tabTournois = array();
		
		if($nbTournois == 0)
			return NULL;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabTournois, getTournoi($obj->idTournoi));
		}
		
		if(($indexTournoi < 0) || ($indexTournoi >= sizeof($tabTournois)))
			return NULL;
		
		return $tabTournois[$indexTournoi];
	}
	
	function getAllTournoiWithIdGestionnaire(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Tournoi WHERE idGestionnaire = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbTournois = $res->num_rows;
		
		$connexion->close();
		
		$tabTournois = array();
		
		if($nbTournois == 0)
			return $tabTournois;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabTournois, getTournoi($obj->idTournoi));
		}
		
		return $tabTournois;
	}

	function getAllTournoiWithIdGestionnaireByDate(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Tournoi WHERE idGestionnaire = \"$id\" ORDER BY dateDeb;";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbTournois = $res->num_rows;
		
		$connexion->close();
		
		$tabTournois = array();
		
		if($nbTournois == 0)
			return $tabTournois;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabTournois, getTournoi($obj->idTournoi));
		}
		
		return $tabTournois;
	}
	
	function getAllTournoi()
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Tournoi;";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbTournois = $res->num_rows;
		
		$connexion->close();
		
		$tabTournois = array();
		
		if($nbTournois == 0)
			return $tabTournois;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabTournois, getTournoi($obj->idTournoi));
		}
		
		return $tabTournois;
	}

	function getAllTournoibyDate()
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Tournoi ORDER BY dateDeb;";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbTournois = $res->num_rows;
		
		$connexion->close();
		
		$tabTournois = array();
		
		if($nbTournois == 0)
			return $tabTournois;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabTournois, getTournoi($obj->idTournoi));
		}
		
		return $tabTournois;
	}


	function getIdTournoiByName(string $nom)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idTournoi FROM Tournoi WHERE nom = \"$nom\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}

		$objTemp = $res->fetch_object();
		$id = strval($objTemp->idTournoi);
		
		$connexion->close();

		return $id ;
		
	
	}
?>