<?php
	include('reqJoueur.php');
	
	function insertEquipe(string $nomEquipe, string $adresse, string $numTel)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$idE = lineCount("Equipe") + 1;
		
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
		
		$res->data_seek(0);
		$verif = $res->fetch_assoc()["idEquipe"];
		
		$connexion->close();
		
		if(empty($verif))
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
		
		$res->data_seek(0);
		$idEquipe = $res->fetch_assoc()["idEquipe"];
		$nomEquipe = $res->fetch_assoc()["nomEquipe"];
		$niveau = $res->fetch_assoc()["niveau"];
		$adresse = $res->fetch_assoc()["adresse"];
		$numTel = $res->fetch_assoc()["numTel"];
		
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
		
		$res->fetch_assoc();
		$nbJoueursEquipe = $res->num_rows;
		
		if($nbJoueursEquipe > 0)
		{
			while($obj = $res->fetch_object())
			{
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
		
		$res->fetch_assoc();
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