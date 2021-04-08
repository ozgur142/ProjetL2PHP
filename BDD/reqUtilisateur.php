<?php
	function lineCount(string $table)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$res = $connexion->query("SELECT * FROM $table;");
		
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$row = $res->fetch_assoc();
		
		$connexion->close();
		
		return $res->num_rows;
	}
	
	function insertUser(string $nom, string $prenom, string $email, string $mdp, string $confirmation, string $role)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$idU = lineCount("Utilisateur") + 1;
		
		if(strcmp($mdp, $confirmation) != 0)
			trigger_error("Le mot de passe et la confirmation ne correspondent pas.");
		
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			trigger_error("$email n'est pas une adresse mail.");
		
		if((strcmp($role, "Utilisateur") != 0) && (strcmp($role, "Administrateur") != 0))
			trigger_error("Le rôle de l'utilisateur est invalide.");
		
		$requete = "INSERT INTO Utilisateur VALUES($idU, '$nom', '$prenom', '$email', '$mdp', '$role');";
		
		$res = $connexion->query($requete);
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$connexion->close();
		
		unset($_POST);
		
		header('Location: ../php/Login.php');
		exit();
	}
	
	function verifLogin(string $login)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idUtilisateur FROM Utilisateur WHERE email = \"$login\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return false;
		}
		
		$res->data_seek(0);
		$verif = $res->fetch_assoc()["idUtilisateur"];
		
		$connexion->close();
		
		if(empty($verif))
			return false;
		
		return true;
	}
	
	function verifLoginMdp(string $login, string $mdp)
	{
		if(!verifLogin($login))
			return false;
		
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT idUtilisateur FROM Utilisateur WHERE email = \"$login\" AND motDePasse = \"$mdp\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return false;
		}
		
		$res->data_seek(0);
		$verif = $res->fetch_assoc()["idUtilisateur"];
		
		$connexion->close();
		
		if(empty($verif))
			return false;
		
		return true;
	}
	
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
	
	function getUtilisateur(string $id)
	{
		include('DataBaseLogin.inc.php');
		include('../module/Utilisateur.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Utilisateur WHERE idUtilisateur = \"$id\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$res->data_seek(0);
		$idUtilisateur = $res->fetch_assoc()["idUtilisateur"];
		$nom = $res->fetch_assoc()["nom"];
		$prenom = $res->fetch_assoc()["prenom"];
		$email = $res->fetch_assoc()["email"];
		$motDePasse = $res->fetch_assoc()["motDePasse"];
		$role = $res->fetch_assoc()["role"];
		
		$connexion->close();
		
		if(empty($idUtilisateur))
			return NULL;
		
		return new Utilisateur($idUtilisateur, $nom, $prenom, $email, $motDePasse, $role);
	}
	
	function getUtilisateurWithEmail(string $login)
	{
		include('DataBaseLogin.inc.php');
		include('../module/Utilisateur.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$requete = "SELECT * FROM Utilisateur WHERE email = \"$login\";";
		
		$res = $connexion->query($requete);
		if(!$res)
		{
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
			$connexion->close();
			
			return NULL;
		}
		
		$res->data_seek(0);
		$idUtilisateur = $res->fetch_assoc()["idUtilisateur"];
		$nom = $res->fetch_assoc()["nom"];
		$prenom = $res->fetch_assoc()["prenom"];
		$email = $res->fetch_assoc()["email"];
		$motDePasse = $res->fetch_assoc()["motDePasse"];
		$role = $res->fetch_assoc()["role"];
		
		$connexion->close();
		
		if(empty($idUtilisateur))
			return NULL;
		
		return new Utilisateur($idUtilisateur, $nom, $prenom, $email, $motDePasse, $role);
	}
	
	function getJoueur(string $id)
	{
		include('DataBaseLogin.inc.php');
		
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