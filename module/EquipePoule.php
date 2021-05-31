<?php
	class EquipePoule
	{
		private $m_idEquipe;
		private $m_idPoule;
		
		public function __construct(int $idEquipe, int $idPoule)
		{
			$this->m_idEquipe = $idEquipe;
			$this->m_idPoule = $idPoule;
		}
		
		public function getIdEquipe()
		{
			return $this->m_idEquipe;
		}
		
		public function getIdPoule()
		{
			return $this->m_idPoule;
		}
	}
?>