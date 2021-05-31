<?php
	class Poule
	{
		private $m_idPoule;
		private $m_idTournoi;
		private $m_nbEquipes;
		
		public function __construct(int $idP, int $idT, int $nbEq)
		{
			$this->m_idPoule = $idP;
			$this->m_idTournoi = $idT;
			$this->m_nbEquipes = $nbEq;
		}
		
		public function getIdPoule()
		{
			return $this->m_idPoule;
		}
		
		public function getTournoi()
		{
			return $this->m_idTournoi;
		}
		
		public function getNbEquipes()
		{
			return $this->m_nbEquipes;
		}
	}
?>