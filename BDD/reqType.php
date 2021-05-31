<?php
	include_once(realpath(dirname(__FILE__)).'/../BDD/reqTournoi.php');
	
	function insertType(int $idTournoi,string $type)
    {
        include('DataBaseLogin.inc.php');

        if(!estTournoi($idTournoi))
			trigger_error("ERREUR : Identifiant de tournoi invalide.");
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}

		if($type!="")
		{
			$idType = chooseIntegerIdSequential("Type", "idType");

	        $requete = "INSERT INTO Type VALUES($idType,$idTournoi,'$type');";

	        $res = $connexion->query($requete);
			if(!$res)
				die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);

			$connexion->close();
		}

		unset($_POST);

		header('Location: ../php/CreerTournoi.php');
		exit();
    }

    function getTypeTournoi(string $idTournoi)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT typeTournoi FROM Type WHERE idTournoi = \"$idTournoi\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$objTemp = $res->fetch_object();
		$type = $objTemp->typeTournoi;
		
		$connexion->close();
		
		return $type ;
	}
?>