<?php
	include_once ('Joueur.php');
	
	class Equipe
	{
		private $m_idEquipe;
		private $m_nomEquipe;
		private $m_niveau;
		private $m_adresse;
		private $m_numTel;
		private $m_tabJoueurs;
		private $m_placeTournoi;
		
		public function __construct(int $idE, string $nomEquipe, int $niveau, string $adresse, string $numTel, $tabJoueurs)
		{
			if(!is_array($tabJoueurs))
				trigger_error("ERREUR : Un tableau de joueurs est demandé.");
			
			$this->m_idEquipe = $idE;
			$this->m_nomEquipe = $nomEquipe;
			$this->m_niveau = $niveau;
			$this->m_adresse = $adresse;
			$this->m_numTel = $numTel;
			
			$this->m_tabJoueurs = array();
			
			for($i=0;$i<count($tabJoueurs);++$i)
				array_push($this->m_tabJoueurs, $tabJoueurs[$i]);
		}
		
		public function getIdEquipe()
		{
			return $this->m_idEquipe;
		}
		
		public function getNomEquipe()
		{
			return $this->m_nomEquipe;
		}
		
		public function getNiveau()
		{
			return $this->m_niveau;
		}
		
		public function getAdresse()
		{
			return $this->m_adresse;
		}
		
		public function getNumTel()
		{
			return $this->m_numTel;
		}
		
		public function getTabJoueurs()
		{
			return $this->m_tabJoueurs;
		}
		
		public function getNbJoueurs()
		{
			return sizeof($this->m_tabJoueurs);
		}
		
		public function getCapitaine()
		{
			for($i=0;$i<sizeof($this->m_tabJoueurs);++$i)
			{
				if($this->m_tabJoueurs[$i]->getCapitaine() === true)
				{
					return $this->m_tabJoueurs[$i];
				}
			}
			
			return null;
		}
	}
?>