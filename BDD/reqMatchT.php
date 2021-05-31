<?php
	include_once(realpath(dirname(__FILE__)).'/../BDD/reqTournoi.php');
	include_once(realpath(dirname(__FILE__)).'/../module/MatchT.php');

	function insertMatchT(int $idTournoi,string $date, string $horaire)
    {
        include('DataBaseLogin.inc.php');

        if(!estTournoi($idTournoi))
			trigger_error("ERREUR : Identifiant de tournoi invalide.");
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}

        $idMatchT = chooseIntegerIdSequential("MatchT", "idMatchT");

        $requete = "INSERT INTO MatchT VALUES($idMatchT,$idTournoi,'$date','$horaire');";

        $res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		

		return true;
		
		exit();

    }


    function IsAlreadyProgrammed(int $idT){
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		$requete = "SELECT * FROM MatchT WHERE idTournoi = \"$idT\";";

		$res = $connexion->query($requete);
		
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);

		
		if(mysqli_num_rows($res) >0){
			return true;
		}
		else{
			return false;
		}

		$connexion->close();
	}

	function estMatchTWithIdEquipes(string $idTournoi, int $idEquipe)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT EquipeMatchT.idMatchT FROM EquipeMatchT,MatchT WHERE EquipeMatchT.idMatchT = MatchT.idMatchT AND idTournoi = $idTournoi AND idEquipe = $idEquipe";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return false;
		}

		$nb = $res->num_rows;
		
		if($nb>0)
		{
			$objTemp = $res->fetch_object();
			$idMatchT = strval($objTemp->idMatchT);
		}
		
		$connexion->close();
		
		if(empty($idMatchT))
			return false;
		
		return true;
	}

	function melangerEquipes($tabEquipes)
	{
		$nbEquipes = sizeof($tabEquipes);
		$tabEquipesMelangees = array($nbEquipes);
		$random = rand(0,$nbEquipes-1);
		$debut = $random + 1;
		$fin = $random ;

		if($random == $nbEquipes-1)
		{
			--$debut;
			--$fin;
		}

		for($i=0;$i<$nbEquipes;$i=$i+2)
		{
			$tabEquipesMelangees[$i] = $tabEquipes[$debut]->getIdEquipe();
			$tabEquipesMelangees[$i+1] = $tabEquipes[$fin]->getIdEquipe();
						
			if($debut == ($nbEquipes - 1))
				$debut=0;
			else
				$debut=$debut+1;

			if($fin == 0)
				$fin = $nbEquipes - 1;
			else
				$fin = $fin - 1;
		}

		return $tabEquipesMelangees ;
	
	}





    function estMatchT(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idMatchT FROM MatchT WHERE idMatchT = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return false;
		}
		
		$objTemp = $res->fetch_object();
		$idMatchT = strval($objTemp->idMatchT);
		
		$connexion->close();
		
		if(empty($idMatchT))
			return false;
		
		return true;
	}
	
	function getMatchT(string $id)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM MatchT WHERE idMatchT = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$objTemp = $res->fetch_object();
		$idMatchT = strval($objTemp->idMatchT);
		$idTournoi = strval($objTemp->idTournoi);
		$date = strval($objTemp->date);
		$horaire = strval($objTemp->horaire);
		
		$connexion->close();
		
		if(empty($idMatchT))
			return NULL;
		
		return new MatchT($idMatchT, $idTournoi, $date, $horaire);
	}

	function getAllMatchT(int $idTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM MatchT WHERE idTournoi = \"$idTournoi\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbMatchs = $res->num_rows;
		
		$connexion->close();
		
		$tabMatchT = array();
		
		if($nbMatchs == 0)
			return $tabMatchT;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabMatchT, getMatchT($obj->idMatchT));
		}
		
		return $tabMatchT;
	}


	function getAllMatchTPhasesFinales(int $idTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}

		$id = getLastIdMatchPoule($idTournoi) ;

		$requete = "SELECT idMatchT FROM MatchT WHERE idTournoi = \"$idTournoi\" 
		AND idMatchT IN (SELECT idMatchT FROM MatchT WHERE idMatchT>$id);";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbMatchs = $res->num_rows;
		
		$connexion->close();
		
		$tabMatchT = array();
		
		if($nbMatchs == 0)
			return $tabMatchT;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabMatchT, getMatchT($obj->idMatchT));
		}
		
		return $tabMatchT;
	}


	function getAllMatchTWithNoEquipeMatchT(int $idTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM MatchT WHERE idTournoi=$idTournoi AND idMatchT NOT IN (SELECT idMatchT FROM EquipeMatchT)";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbMatchT = $res->num_rows;
		
		$connexion->close();
		
		$tabMatchT = array();
		
		if($nbMatchT == 0)
			return $tabMatchT;
		while($obj = $res->fetch_object())
		{
			array_push($tabMatchT, getMatchT($obj->idMatchT));
		}
		
		return $tabMatchT;
	}


	function getAllEquipesNoMatchT(int $idTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idEquipe FROM EquipeTournoi WHERE idTournoi=$idTournoi AND idEquipe NOT IN (SELECT idEquipe FROM EquipeMatchT, MatchT WHERE EquipeMatchT.idMatchT=MatchT.idMatchT AND MatchT.idTournoi=$idTournoi)";
		
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
			array_push($tabEquipes, getEquipe($obj->idEquipe));
		}
		
		return $tabEquipes;
	}

	function getAllEquipesWithMatchT(int $idTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idEquipe FROM EquipeMatchT,MatchT WHERE EquipeMatchT.idMatchT=MatchT.idMatchT AND idTournoi=$idTournoi";
		
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
			array_push($tabEquipes, getEquipe($obj->idEquipe));
		}
		
		return $tabEquipes;
	}


?>