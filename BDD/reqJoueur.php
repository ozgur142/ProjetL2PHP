<?php
	include('reqGeneralBDD.php');
	
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
		
		$res->data_seek(0);
		$verif = $res->fetch_assoc()["idJoueur"];
		
		$connexion->close();
		
		if(empty($verif))
			return false;
		
		return true;
	}
	
	function getJoueur(string $id)
	{
		include('DataBaseLogin.inc.php');
		include('reqUtilisateur.php');
		
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
		
		$res->data_seek(0);
		$idJoueur = $res->fetch_assoc()["idJoueur"];
		$estCapitaine = $res->fetch_assoc()["estCapitaine"];
		
		$connexion->close();
		
		if(empty($idJoueur))
			return NULL;
		
		$ut = getUtilisateur($id);
		
		return new Joueur($ut->getIdJoueur(), $ut->getNom(), $ut->getPrenom(), $ut->getEmail(), $ut->getMdp(), $ut->getRole(), $idJoueur, $estCapitaine);
	}
?>