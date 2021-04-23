<?php
	include_once ('Utilisateur.php');
	
	class Joueur extends Utilisateur
	{
		private $m_idJoueur;
		private $m_estCapitaine;
		
		public function __construct(int $idU, string $nom, string $prenom, string $email, string $motDePasse, string $role, int $idJ, bool $estCapitaine)
		{
			parent::__construct($idU, $nom, $prenom, $email, $motDePasse, $role);
			
			$this->m_idJoueur = $idJ;
			$this->m_estCapitaine = ((bool)$estCapitaine);
		}
		
		public function getIdJoueur()
		{
			return $this->m_idJoueur;
		}
		
		public function getCapitaine()
		{
			return $this->m_estCapitaine;
		}
	}
?>