<?php
	class Utilisateur
	{
		private $m_idUtilisateur;
		private $m_nom;
		private $m_prenom;
		private $m_email;
		private $m_motDePasse;
		private $m_role;
		
		public function __construct(int $idU, string $nom, string $prenom, string $email, string $motDePasse, string $role)
		{
			$this->m_idUtilisateur = $idU;
			$this->m_nom = $nom;
			$this->m_prenom = $prenom;
			$this->m_email = $email;
			$this->m_motDePasse = $motDePasse;
			$this->m_role = $role;
		}
		
		public function getIdUtilisateur()
		{
			return $this->m_idUtilisateur;
		}
		
		public function getNom()
		{
			return $this->m_nom;
		}
		
		public function getPrenom()
		{
			return $this->m_prenom;
		}
		
		public function getEmail()
		{
			return $this->m_email;
		}
		
		public function getMdp()
		{
			return $this->m_motDePasse;
		}
		
		public function getRole()
		{
			return $this->m_role;
		}
	}
?>