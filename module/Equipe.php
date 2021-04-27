<?php
	include_once('Joueur.php');
	
	class Equipe extends Entite
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
		
		public function addCapitaine($cap)
		{
			if($this->getCapitaine())
				return false;
			
			if(!($cap instanceof Joueur))
				return false;
			
			if($cap->getIdEquipe() !== $this->m_idEquipe)
				return false;
			
			if(!($cap->getCapitaine()))
				return false;
			
			$tabTemp = array();
			
			array_push($tabTemp, $cap);
			
			for($i=0;$i<sizeof($this->m_tabJoueurs);++$i)
				array_push($tabTemp, $this->m_tabJoueurs[$i]);
			
			$this->m_tabJoueurs = array();
			
			for($i=0;$i<sizeof($tabTemp);++$i)
				array_push($this->m_tabJoueurs, $tabTemp[$i]);
			
			return true;
		}
		
		public function toString()
		{
			$res = strval($this->m_idEquipe)." "
				  .strval($this->m_nomEquipe)." "
				  .strval($this->m_niveau)." "
				  .strval($this->m_adresse)." "
				  .strval($this->m_numTel)." "
				  ."\n";
			
			for($i=0;$i<count($this->m_tabJoueurs);++$i)
				$res = $res.strval($this->m_tabJoueurs[$i].toString())."\n";
			
			$res = $res.strval($this->m_placeTournoi);
			
			return $res;
		}
		
		public function toHTML()
		{
			$res = "<p>"
				  .strval($this->m_idEquipe)." <br />"
				  .strval($this->m_nomEquipe)." <br />"
				  .strval($this->m_niveau)." <br />"
				  .strval($this->m_adresse)." <br />"
				  .strval($this->m_numTel)." <br />"
				  ."<br />";
			
			for($i=0;$i<count($this->m_tabJoueurs);++$i)
				$res = $res.strval($this->m_tabJoueurs[$i].toHTML())."<br />";
			
			$res = $res
				  .strval($this->m_placeTournoi)
				  ."</p>";
			
			return $res;
		}
	}
?>