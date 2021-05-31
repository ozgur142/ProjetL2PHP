<?php
	include_once(realpath(dirname(__FILE__)).'/../module/MatchT.php');
	include_once(realpath(dirname(__FILE__)).'/../BDD/reqEquipeMatchT.php');
	
	class EquipeMatchT extends Entite
	{
		private $m_idEquipe;
		private $m_score = -1;
		private $m_idMatchT ;
		
		public function __construct(int $idEquipe,int $idMatch,int $score)
		{	
			$this->m_idMatchT = $idMatch ;
			$this->m_idEquipe = $idEquipe;
			$this->m_score = $score;
		}
		
		//ACCESSEURS EN LECTURE
		public function afficher()
		{
			echo"ID équipe : ".$this->m_idEquipe."<br ./>";
			echo"ID Match : ".$this->m_idMatchT."<br ./>";
			echo"Score : ".$this->m_score."<br ./>";
		}

		public function getIdMatchT()
		{
			return $this->m_idMatchT;
		}
		
		public function getIdEquipe()
		{
			return $this->m_idEquipe;
		}
		
		public function getScore()
		{
			return $this->m_score ;
		}
		
		//ACCESSEURS EN ECRITURE
		public function setScore()
		{
			//setScoreAleatoire
			$this->m_score = rand(0,10);
		}
		
		public function setScoreVal($score)
		{ 
			$this->m_score = $score ;
			UpdateScore($this->m_idEquipe, $this->m_idMatchT, $this->m_score);
		}
		
		public function toString()
		{
			$res = parent::toString();
			
			$res = $res
				  ." "
				  .strval($this->m_idEquipe);
			
			return $res;
		}
		
		public function toHTML()
		{
			$res = parent::toHTML();
			
			$res = $res
				  ."<p>"
				  .strval($this->m_idEquipe)
				  ."</p>";
			
			return $res;
		}
	}
?>