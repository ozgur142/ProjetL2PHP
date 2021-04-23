<?php
	include_once('Equipe.php');
	include_once('Tournoi.php');
	
	class EquipeTournoi
	{
		private $m_idEquipe;
		private $m_idTournoi;
		private $m_estInscrite;
		
		public function __construct(int $idE, int $idT, bool $estInscrite)
		{
			$this->m_idEquipe = $idE;
			$this->m_idTournoi = $idT;
			$this->m_estInscrite = $estInscrite;
		}
		
		public function getIdEquipe()
		{
			return $this->m_idEquipe;
		}
		
		public function getIdTournoi()
		{
			return $this->m_idTournoi;
		}
		
		public function getEstInscrite()
		{
			return $this->m_estInscrite;
		}
	}
?>