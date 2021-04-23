<?php
	include_once ('Utilisateur.php');
	
	class Gestionnaire extends Utilisateur
	{
		private $m_idGestionnaire;
		
		public function __construct(int $idU, string $nom, string $prenom, string $email, string $motDePasse, string $role, int $idG)
		{
			parent::__construct($idU, $nom, $prenom, $email, $motDePasse, $role);
			
			$this->m_idGestionnaire = $idG;
		}
		
		public function getIdGestionnaire()
		{
			return $this->m_idGestionnaire;
		}
	}
?>