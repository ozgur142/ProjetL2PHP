<?php
	include_once('Entite.php');
	
	class MatchT extends Entite
	{
		protected $m_idMatchT;
		private $m_idTournoi ;
		private $m_date ;
		private $m_horaire ;
		
		//Constructeur
		public function __construct(int $idMatch, int $idTournoi, string $date, string $horaire)
		{
			$this->m_idMatchT = $idMatch;
			$this->m_idTournoi = $idTournoi ;
			$this->m_date = $date ;
			$this->m_horaire = $horaire ;
		}
		
		//ACESSEURS EN LECTURE
		public function afficher()
		{
			echo "Match n°".$this->m_idMatchT;
			echo "<br ./>";
		}
		
		public function getIdMatchT()
		{
			return $this->m_idMatchT;
		}
		
		public function getIdTournoi()
		{
			return $this->m_idTournoi;
		}

		public function getDate()
		{
			return $this->m_date;
		}

		public function getHoraire()
		{
			return $this->m_horaire;
		}






		public function setIdMatchT(int $id)
		{
			$this->m_idMatchT = $id;
		}
		
		public function setIdTournoi(int $id)
		{
			$this->m_idTournoi = $id;
		}

		public function setDate(string $date)
		{
			$this->m_date = $date;
		}

		public function setHoraire(string $horaire)
		{
			$this->m_horaire = $horaire;



		}
		
		public function toString()
		{
			return strval($this->m_idMatchT);
		}
		
		public function toHTML()
		{
			return "<p>".strval($this->m_idMatchT)."</p>";
		}
	}
?>