<?php
	include_once('reqEquipe.php');
	include_once('reqTournoi.php');
	include_once('reqEquipeTournoi.php');
	include_once('../module/EquipeTournoi.php');
	include_once('../module/FctGenerales.php');
	
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
		
		$estIns = bool2SQLStr($estInscrite);
		
		$requete = "INSERT INTO EquipeTournoi(`idEquipe`, `idTournoi`, `estInscrite`) VALUES($idE, $idT, $estIns);";
		
		$res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		
		unset($_POST);

		return true;
	}
	
	function modifierEquipeTournoi(int $idE, int $idT, bool $estInscrite)
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
		
		$estIns = bool2SQLStr($estInscrite);
		
		$requete = "UPDATE EquipeTournoi SET estInscrite = $estIns WHERE idEquipe = $idE AND idTournoi = $idT;";
		
		$res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		
		unset($_POST);
		
		return true;
	}
	
	function supprimerEquipeTournoi(int $idE, int $idT)
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
		
		$requete = "DELETE FROM EquipeTournoi WHERE idEquipe = $idE AND idTournoi = $idT;";
		
		$res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		
		unset($_POST);
		
		return true;
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
		
		$requete = "SELECT * FROM EquipeTournoi WHERE idEquipe = $idE AND idTournoi = \"$idT\";";
		
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
	
	function getEquipeTournoiWithIdTournoi(string $idT)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT E.idEquipe, ET.idTournoi
		FROM Equipe E
		INNER JOIN EquipeTournoi ET
		WHERE E.idEquipe = ET.idEquipe AND ET.idTournoi = $idT;";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$nbEquipeTournoi = $res->num_rows;
		
		$connexion->close();
		
		$tabEquipeTournoi = array();
		
		if($nbEquipeTournoi == 0)
			return $tabEquipeTournoi;
		
		while($obj = $res->fetch_object())
		{
			array_push($tabEquipeTournoi, getEquipeTournoi($obj->idEquipe, $idT));
		}
		
		return $tabEquipeTournoi;
	}
	
	function getNbEquipesTournoiWithId(string $idT)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}

		$requete = "SELECT * FROM EquipeTournoi WHERE idTournoi = \"$idT\";";
		
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}

		$nb = $res->num_rows; 

		$connexion->close();	
		
		return $nb;
	}

	function melanger(int $idTournoi)
	{

		$tabEquipesTournoi = getEquipeTournoiWithIdTournoi($idTournoi);
		if(sizeof($tabEquipesTournoi)>0)
		{
			$nbEquipes = getNbEquipesTournoiWithId($idTournoi);
			$tab = array($nbEquipes) ;
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
				$tab[$i] = $tabEquipesTournoi[$debut]->getIdEquipe();
				$tab[$i+1] = $tabEquipesTournoi[$fin]->getIdEquipe();
						
				if($debut == ($nbEquipes - 1))
					$debut=0;
				else
					$debut=$debut+1;

				if($fin == 0)
					$fin = $nbEquipes - 1;
				else
					$fin = $fin - 1;
			}

			return $tab ;
		}
		else
			return null;
	}



	function melangerParNiveaux(int $idTournoi)
	{

		$tabEquipesTournoi = getAllEquipesByNiveau($idTournoi);
		if(sizeof($tabEquipesTournoi)>0)
		{
			$nbEquipes = getNbEquipesTournoiWithId($idTournoi);
			$tab = array($nbEquipes) ;

			$debTab = 0 ;
			$finTab = $nbEquipes -1 ;

			$deb1 = 0 ;
			$fin1 = $nbEquipes -1 ;

			$nbPlaces = 0 ;
	
			while($debTab<$finTab)
			{
				$tab[$debTab] = $tabEquipesTournoi[$deb1]->getIdEquipe() ;
				$tab[$finTab] = $tabEquipesTournoi[$deb1+1]->getIdEquipe() ;

				++$debTab;
				--$finTab;
				$nbPlaces+=2;

				if($debTab<$finTab)
				{
					$tab[$debTab] = $tabEquipesTournoi[$fin1]->getIdEquipe() ;
					$tab[$finTab] = $tabEquipesTournoi[$fin1-1]->getIdEquipe() ;
					$nbPlaces+=2;

					++$debTab;
					--$finTab;

					$deb1+=2 ;
					$fin1-=2 ;
				}
			}

			if($debTab==$finTab && $nbPlaces<$nbEquipes)
				$tab[$debTab] = $tabEquipesTournoi[$deb1]->getIdEquipe() ;
			
			return $tab ;
		}
		else
			return null;
	}

	function insertNull(int $idT, int $nbEquipes)
	{
		include('DataBaseLogin.inc.php');
			
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}

		$requete1 = "INSERT INTO EquipeTournoi(`idEquipe`, `idTournoi`, `estInscrite`) VALUES(1, $idT, 1);";
		$requete2 = "INSERT INTO EquipeTournoi(`idEquipe`, `idTournoi`, `estInscrite`) VALUES(2, $idT, 1);";
		
		if($nbEquipes==1)
		{
			$res1 = $connexion->query($requete1);
			if(!$res1)
				die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		}

		if($nbEquipes>1)
		{
			$res1 = $connexion->query($requete1);
			if(!$res1)
				die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);

			$res2 = $connexion->query($requete2);
			if(!$res2)
				die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		}

		$connexion->close();

		//return true;
		
	}


?>