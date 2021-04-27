<?php
	include_once('Entite.php');
	
	class MatchT extends Entite
	{
		protected $m_idMatchT;
		
		//Constructeur
		public function __construct(int $id)
		{
			$this->m_idMatchT = $id;
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
		
		//ACCESSEURS EN ECRITURE
		public function setId(int $id)
		{
			$this->m_idMatchT = $id;
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