<?php
	include_once(realpath(dirname(__FILE__)).'/../BDD/reqTournoi.php');
	include_once(realpath(dirname(__FILE__)).'/../BDD/reqMatchT.php');
	include_once(realpath(dirname(__FILE__)).'/../module/EquipeMatchT.php');


	//Idée : regrouper les tables EquipeMatchT et MatchT?
	//IdMatchT, idTournoi, date, horaire, idEquipe1, iEquipe2, score1, score2
	//Quand le match est créé on initialise les score à -1
	//Puis quand le match est joué on le change.


	//insertion des matchs par 2 ?
	function insertEquipeMatchT(int $idMatchT,int $idEquipe1, int $idEquipe2)
    {
        include('DataBaseLogin.inc.php');

        if(!estMatchT($idMatchT))
			trigger_error("ERREUR : Identifiant de match invalide.");

		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}

        $requete = "INSERT INTO EquipeMatchT VALUES($idEquipe1,$idMatchT,-1),
        ($idEquipe2,$idMatchT,-1)
        ;";

        $res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();

		return true ;
		

    }


    function estEquipeMatchT(string $idMatchT)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idMatchT FROM EquipeMatchT WHERE idMatchT = \"$idMatchT\";";
		
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
			$id = strval($objTemp->idMatchT);
		}
		
		$connexion->close();
		
		if(empty($id))
			return false;
		
		return true;
	}

	function getAllEquipeMatchT(int $idTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT EquipeMatchT.idMatchT,idEquipe FROM EquipeMatchT,MatchT WHERE EquipeMatchT.idMatchT=MatchT.idMatchT AND idTournoi=$idTournoi";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbEquipesMatchT = $res->num_rows;
		
		$connexion->close();
		
		$tabEquipesMatchT = array();
		
		if($nbEquipesMatchT == 0)
			return $tabEquipesMatchT;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabEquipesMatchT, getSingleEquipeMatchT($obj->idEquipe,$obj->idMatchT));
		}
		
		return $tabEquipesMatchT;
	}


	function getAllEquipeMatchTSupA(int $idTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}

		$id = getLastIdMatchPoule($idTournoi) ;

		$requete = "SELECT EquipeMatchT.idMatchT,idEquipe FROM EquipeMatchT,MatchT WHERE EquipeMatchT.idMatchT=MatchT.idMatchT AND idTournoi=$idTournoi AND EquipeMatchT.idMatchT IN (SELECT idMatchT FROM MatchT WHERE idMatchT>$id);";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbEquipesMatchT = $res->num_rows;
		
		$connexion->close();
		
		$tabEquipesMatchT = array();
		
		if($nbEquipesMatchT == 0)
			return $tabEquipesMatchT;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabEquipesMatchT, getSingleEquipeMatchT($obj->idEquipe,$obj->idMatchT));
		}
		
		return $tabEquipesMatchT;
	}



	function UpdateScore(int $idEquipe, int $idMatchT, int $score)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "UPDATE EquipeMatchT SET score=$score WHERE idMatchT=$idMatchT AND idEquipe=$idEquipe";
		
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


	
	function getSingleEquipeMatchT(string $idEquipe, string $idMatchT)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM EquipeMatchT WHERE idEquipe = \"$idEquipe\" AND idMatchT = \"$idMatchT\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$objTemp = $res->fetch_object();
		$idEquipe = strval($objTemp->idEquipe);
		$idMatchT = strval($objTemp->idMatchT);
		$score = strval($objTemp->score);

		$connexion->close();
		
		if(empty($idEquipe && $idMatchT))
			return NULL;
		
		return new EquipeMatchT($idEquipe, $idMatchT, $score);
	}

	function getEquipesMatchT(string $idMatchT)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM EquipeMatchT WHERE idMatchT = \"$idMatchT\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}

		$tabEquipesMatchT = array();

		if($res->num_rows==0)
			return $tabEquipesMatchT;

		while($obj = $res->fetch_object())
		{
			$idEquipe = strval($obj->idEquipe);
			$idMatchT = strval($obj->idMatchT);
			$score = strval($obj->score);
			array_push($tabEquipesMatchT, new EquipeMatchT($idEquipe, $idMatchT, $score));
		}
		$connexion->close();

		return $tabEquipesMatchT ;

	}



	function modifierEquipeMatchT(int $idEquipe, int $idMatchT, int $score)
	{
		include('DataBaseLogin.inc.php');
		
		if(!estEquipe($idEquipe))
			trigger_error("ERREUR : Identifiant d'équipe invalide.");
		
		if(!estMatchT($idMatchT))
			trigger_error("ERREUR : Identifiant de MatchT invalide.");

		if($score<0)
			trigger_error("ERREUR : Score invalide.");
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}	
		
		$requete = "UPDATE EquipeMatchT SET score = $score WHERE idEquipe = $idEquipe AND idMatchT = $idMatchT;";
		
		$res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		
		//unset($_POST);
		
		return true;
	}

	function getIdEquipeGagnante(int $idMatchT){

		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM EquipeMatchT WHERE idMatchT = \"$idMatchT\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}

		$objTemp = $res->fetch_object();

		$id1 = strval($objTemp->idEquipe);
		$score1 = strval($objTemp->score);

		$objTemp = $res->fetch_object();

		$id2 = strval($objTemp->idEquipe);
		$score2 = strval($objTemp->score);

		if($score1>$score2)
			return $id1 ;
		else
			return $id2 ;
	}


	function getIdEquipePerdante(int $idMatchT){

		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM EquipeMatchT WHERE idMatchT = \"$idMatchT\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}

		$objTemp = $res->fetch_object();

		$id1 = strval($objTemp->idEquipe);
		$score1 = strval($objTemp->score);

		$objTemp = $res->fetch_object();

		$id2 = strval($objTemp->idEquipe);
		$score2 = strval($objTemp->score);

		if($score1<$score2)
			return $id1 ;
		else
			return $id2 ;
	}

	function estMatchNull(int $idMatchT){

		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM EquipeMatchT WHERE idMatchT = \"$idMatchT\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}

		$objTemp = $res->fetch_object();

		$id1 = strval($objTemp->idEquipe);
		$score1 = strval($objTemp->score);

		$objTemp = $res->fetch_object();

		$id2 = strval($objTemp->idEquipe);
		$score2 = strval($objTemp->score);

		return $score1 == $score2 ;

	}

?>
