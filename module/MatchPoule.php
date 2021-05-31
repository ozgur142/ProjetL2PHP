<?php
	class MatchPoule
	{
		private $m_idEquipe1;
		private $m_idEquipe2;
		private $m_idMatchT;
		private $m_score1;
		private $m_score2;
		
		public function __construct(int $idEquipe1, int $idEquipe2, int $idMatchT, int $score1, int $score2)
		{
			$this->m_idEquipe1 = $idEquipe1;
			$this->m_idEquipe2 = $idEquipe2;
			$this->m_idMatchT = $idMatchT;
			$this->m_score1 = $score1;
			$this->m_score2 = $score2;
		}
		
		public function getIdEquipe1()
		{
			return $this->m_idEquipe1;
		}
		
		public function getIdEquipe2()
		{
			return $this->m_idEquipe2;
		}
		
		public function getIdMatchT()
		{
			return $this->m_idMatchT;
		}
		
		public function getScore1()
		{
			return $this->m_score1;
		}
		
		public function getScore2()
		{
			return $this->m_score2;
		}
	}
?>