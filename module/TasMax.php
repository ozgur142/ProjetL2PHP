<?php
	include('../module/Equipe.php');
	include('../module/EquipeMatchT.php');
	
	class TasMax
	{
		private $m_tas;
		private $m_nbCases;
		private $m_tabMatchs ;
		
		public function __construct(int $nbEquipes)
		{
			$this->m_tas = array();
			$this->m_nbCases = (2 * $nbEquipes) - 1;
			$this->m_tabMatchs = array((2*$nbEquipes)-1);
			
			for($i=0;$i<$this->m_nbCases;++$i)
				array_push($this->m_tas, null);
		}
		
		public function afficher()
		{
			echo "[";
			
			for($i=0;$i<count($this->m_tas);++$i)
			{
				if($this->m_tas[$i] === null)
				{
					echo "null, ";
				}
				else
				{
					$nom = $this->m_tas[$i]->getNomEquipe();
					echo "$nom, ";//.((($i < (count($this->m_tas) - 1)) && (sizeof($this->m_tas) < $this->m_nbCases)) ? ", " : "" );
				}
			}
			
			for($i=count($this->m_tas);$i<$this->m_nbCases;++$i)
				echo "null".(($i < ($this->m_nbCases - 1)) ? ", " : "");
			
			echo "]";
			
			echo "<br />";
			/*
			echo strval(count($this->m_tas));
			
			echo "<br />";
			
			echo strval($this->m_nbCases);
			*/
		}
		
		public function insererAuxFeuilles(/*Equipe*/ $eq) //prend en param un tableau d'équipes
		{
			$feuille = $this->m_nbCases / 2 ;
			
			for($i=0;$i<sizeof($eq);++$i)
			{
				$this->m_tas[$feuille] = $eq[$i];
				++$feuille;
			}
		}

		public function genereMatchs(){
			$i = $this->m_nbCases - 1 ;
			while($this->m_tas[$i/2-1] != null){
				$i = $i - 2 ;
			}
			$deb = $i ;
			$fin = $i/2 ;

			for($i=$deb;$i>$fin;$i=$i-2){
				$this->m_tabMatchs[$i] = new EquipeMatchT($this->m_tas[$i]->getIdEquipe(),$i);
				$this->m_tabMatchs[$i]->setScore();
				$this->m_tabMatchs[$i]->afficher();
				echo "<br ./>";

				$this->m_tabMatchs[$i-1] = new EquipeMatchT($this->m_tas[$i-1]->getIdEquipe(),$i);
				$this->m_tabMatchs[$i-1]->setScore();
				$this->m_tabMatchs[$i-1]->afficher();
				echo "<br ./>";
				//++$idMatchs;
			}
		}

		/*
		public function equipeGagnante(Equipe $equipe1, Equipe $equipe2){
			$score1=-1 ;  
			$score2=-1 ;
			$id1 = $equipe1->getIdEquipe();
			$id2 = $equipe2->getIdEquipe();
			$i = 0 ;
			while($score1==-1 || $score2==-1){
				if($id1 == $tabMatchs[$i]->getIdEquipe()){
					$score1 = $tabMatchs[$i]->getScore();
				}
				if($id2 == $tabMatchs[$i]->getIdEquipe()){
					$score2 = $tabMatchs[$i]->getScore();
				}
				$i = ++$i;
			}
			if($score1 > $score2) {return $equipe1;}
			else {return $equipe2;}
		}
		*/
		//Réécrire la fonction et la mettre dans la classe MatchT ?

		public function prochainTour(){
			$i = $this->m_nbCases-1 ;
			while($this->m_tas[$i/2-1] != null){
				$i = $i - 2 ;
			}
			$deb = $i;
			$fin = $i/2;
			for($i=$deb;$i>$fin;--$i){
				if($this->m_tabMatchs[$i]->getScore()>$this->m_tabMatchs[$i-1]->getScore()){
					$this->m_tas[$i/2-1] = $this->m_tas[$i];
				}
				else{
					$this->m_tas[$i/2-1] = $this->m_tas[$i-1];
				}
				
			}
		}

		public function getTabMatchs(){
			return $this->m_tabMatchs;
		}

		public function getMatch($i,$j){
			return $this->m_tabMatchs[$i][$j];
		}


	}
?>