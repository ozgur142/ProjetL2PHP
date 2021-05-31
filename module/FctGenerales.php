<?php
	include_once('../BDD/reqPoule.php');
	include_once('../BDD/reqMatchPoule.php');
	include_once('../module/Poule.php');
	include_once('../module/MatchPoule.php');
	
	function bool2SQLStr(bool $valeurBooleenne)
	{
		return (($valeurBooleenne) ? "TRUE" : "FALSE");
	}

	function estPair(int $nb)
	{
		return (($nb % 2) == 0);
	}

	function puissanceDe2(int $nb)
	{
		while(estPair($nb))
			$nb = ($nb / 2);
		
		return ($nb == 1);
	}

	function nbEquipesPremierTour(int $x)
	{
		$val = $x ;
		if(!estPair($val))
			--$val ;
		while( (($val/2)+($x-$val))>0  && !puissanceDe2(($val/2)+($x-$val)) )
		{
			if(!estPair($val))
				--$val ;
			else
				$val-= 2;
		}
		return $val ;
	}
	
	function combinaisonDejaPresente($tab, int $id1, int $id2)
	{
		$estPresent = false;
		$i = 0;
		
		while(($i < sizeof($tab)) && (!$estPresent))
		{
			$tt = $tab[$i];
			
			$v1 = $tt[0];
			$v2 = $tt[1];
			
			++$i;
			$estPresent = ((($v1 == $id1) && ($v2 == $id2)) || (($v1 == $id2) && ($v2 == $id1)));
		}
		
		return $estPresent;
	}
	
	function pouleTerminee(int $idPoule)
	{
		$poule = getPoule($idPoule);
		$tabMatchPoule = getAllMatchPoulePoule($idPoule);
		
		if(!$poule)
			return false;
		
		$nbEq = $poule->getNbEquipes();
		$nbMP = ((($nbEq - 1) * $nbEq) / 2);
		
		$resultat = ((sizeof($tabMatchPoule) > 0) && ($nbMP == sizeof($tabMatchPoule)));
		
		if($resultat)
		{
			for($i=0;$i<sizeof($tabMatchPoule);++$i)
			{
				if(($tabMatchPoule[$i]->getScore1() == -1) || ($tabMatchPoule[$i]->getScore2() == -1))
					$resultat = false;
			}
		}
		
		return $resultat;
	}
?>