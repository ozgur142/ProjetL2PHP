<?php
	include_once('Entite.php');
	
	class Utilisateur extends Entite
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
		
		public function toString()
		{
			$res = strval($this->m_idUtilisateur)." "
				  .strval($this->m_nom)." "
				  .strval($this->m_prenom)." "
				  .strval($this->m_email)." "
				  .strval($this->m_motDePasse)." "
				  .strval($this->m_role);
			
			return $res;
		}
		
		public function toHTML()
		{
			$res = "<p>"
				  .strval($this->m_idUtilisateur)." <br />"
				  .strval($this->m_nom)." <br />"
				  .strval($this->m_prenom)." <br />"
				  .strval($this->m_email)." <br />"
				  .strval($this->m_motDePasse)." <br />"
				  .strval($this->m_role)
				  ."</p>";
			
			return $res;
		}
	}
?>