<?php
	include_once('Equipe.php');
	include_once('Tournoi.php');
	include_once('Entite.php');
	
	class EquipeTournoi extends Entite
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
		
		public function toString()
		{
			$res = strval($this->m_idEquipe)." "
				  .strval($this->m_idTournoi)." "
				  .strval($this->m_estInscrite);
			
			return $res;
		}
		
		public function toHTML()
		{
			$res = "<p>"
				  .strval($this->m_idEquipe)." <br />"
				  .strval($this->m_idTournoi)." <br />"
				  .strval($this->m_estInscrite)
				  ."</p>";
			
			return $res;
		}
	}
?>