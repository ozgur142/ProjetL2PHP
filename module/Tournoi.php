<?php
	include('TasMax.php');
	include('Gestionnaire.php');
	
	class Tournoi
	{
		private $m_tasMax;
		
		private $m_idTournoi;
		private $m_nom;
		private $m_dateDeb;
		private $m_duree;
		private $m_gestionnaire;
		private $m_lieu;
		private $m_nombreTotalEquipes;
		
		private $m_tableauEquipes;
		
		public function __construct(int $idT, string $nom, string $dateDeb, string $duree, $gestionnaire, string $lieu, string $nombreTotalEquipes)
		{
			$m_idTournoi = $idT;
			$m_nom = $idT;
			$m_dateDeb = $idT;
			$m_duree = $idT;
			$m_gestionnaire = $idT;
			$m_lieu = $idT;
			$m_nombreTotalEquipes = $idT;
		}
		
		public function getIdTournoi()
		{
			return $this->m_idTournoi;
		}
		
		public function getNom()
		{
			return $this->m_nom;
		}
		
		public function getDateDeb()
		{
			return $this->m_dateDeb;
		}
		
		public function getDuree()
		{
			return $this->m_duree;
		}
		
		public function getGestionnaire()
		{
			return $this->m_gestionnaire;
		}
		
		public function getLieu()
		{
			return $this->m_lieu;
		}
		
		public function getNombreTotalEquipes()
		{
			return $this->m_nombreTotalEquipes;
		}
	}
?>