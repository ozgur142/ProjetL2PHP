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
	
	function getLastIntegerId(string $table, string $columnId)
	{
		include('DataBaseLogin.inc.php');
		
		$connexion = new mysqli($server, $user, $passwd, $db);
	
		if($connexion->connect_error)
		{
			echo('Erreur de connexion('.$connexion->connect_errno.') '.$connexion->connect_error);
		}
		
		$res = $connexion->query("SELECT $columnId FROM $table;");
		
		if(!$res)
			die('Echec lors de l\'exécution de la requête: ('.$connexion->errno.') '.$connexion->error);
		
		$objTemp = $res->fetch_object();
		$id = -1;
		
		while($obj = $res->fetch_object())
		{
			$id = ((int)$obj->$columnId);
		}
		
		$connexion->close();
		
		return $id;
	}
	
	function chooseIntegerIdSequential(string $table, string $columnId)
	{
		$lc = lineCount($table);
		$lid = getLastIntegerId($table, $columnId);
		
		++$lc;
		
		while($lc <= $lid)
			++$lc;
		
		return $lc;
	}
?>