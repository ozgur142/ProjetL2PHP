<?php
	include_once('Utilisateur.php');
	
	class Joueur extends Utilisateur
	{
		private $m_idJoueur;
		private $m_idEquipe;
		private $m_estCapitaine;
		
		public function __construct(int $idU, string $nom, string $prenom, string $email, string $motDePasse, string $role, int $idJ, int $idE, bool $estCapitaine)
		{
			parent::__construct($idU, $nom, $prenom, $email, $motDePasse, $role);
			
			$this->m_idJoueur = $idJ;
			$this->m_idEquipe = $idE;
			$this->m_estCapitaine = ((bool)$estCapitaine);
		}
		
		public function getIdJoueur()
		{
			return $this->m_idJoueur;
		}
		
		public function getIdEquipe()
		{
			return $this->m_idEquipe;
		}
		
		public function getCapitaine()
		{
			return $this->m_estCapitaine;
		}
		
		public function toString()
		{
			$res = parent::toString();
			
			$res = $res
				  ." "
				  .strval($this->m_idJoueur)." "
				  .strval($this->m_idEquipe)." "
				  .strval($this->m_estCapitaine);
			
			return $res;
		}
		
		public function toHTML()
		{
			$res = parent::toHTML();
			
			$res = $res
				  ."<p>"
				  .strval($this->m_idJoueur)." <br />"
				  .strval($this->m_idEquipe)." <br />"
				  .strval($this->m_estCapitaine)
				  ."</p>";
			
			return $res;
		}
	}
	
	function fusionIdJoueur($T1, $T2)
	{
		$i1 = 0;
		$i2 = 0;
		
		$n1 = sizeof($T1);
		$n2 = sizeof($T2);
		
		$T = array();
		
		for($iT=0;$iT<($n1+$n2);++$iT)
		{
			if(($i2 >= $n2) || (($i1 < $n1) && ($T1[$i1]->getIdJoueur() <= $T2[$i2]->getIdJoueur())))
			{
				$T[$iT] = $T1[$i1];
				++$i1;
			}
			else
			{
				$T[$iT] = $T2[$i2];
				++$i2;
			}
		}
		
		return $T;
	}
	
	function triFusionIdJoueur($T)
	{
		$n = sizeof($T);
		
		if($n > 1)
		{
			$n1 = ($n / 2);
			$n2 = ($n - $n1);
			$T1 = array();
			$T2 = array();
			
			for($i=0;$i<$n1;++$i)
				$T1[$i] = $T[$i];
			
			for($i=$n1;$i<$n;++$i)
				$T2[($i - $n1)] = $T[$i];
			
			$T1 = triFusionIdJoueur($T1);
			$T2 = triFusionIdJoueur($T2);
			
			$T = fusionIdJoueur($T1, $T2);
		}
		
		return $T;
	}
?>