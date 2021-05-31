<?php
	include_once(realpath(dirname(__FILE__)).'/../module/Equipe.php');
	include_once(realpath(dirname(__FILE__)).'/../module/EquipeMatchT.php');

	class TasMax
	{
		private $m_tas;
		private $m_nbCases;
		private $m_tabMatchs;

		public function __construct(int $nbEquipes)
		{
			$this->m_tas = array();
			$this->m_tabMatchs = array();
			
			$this->m_nbCases = 2 * $nbEquipes- 1 ;
			for($i=0;$i<$this->m_nbCases;++$i)
			{
				array_push($this->m_tas, null);
				array_push($this->m_tabMatchs, null);
			}
		}
		
		public function insererAuxFeuilles(/*Equipe*/ $eq) //prend en param un tableau d'équipes
		{
			$indice = 0;
		
			for($i=($this->m_nbCases - 1);$i>($this->m_nbCases - 1 - sizeof($eq));--$i)
			{
				$this->m_tas[$i] = $eq[$indice];
				
				++$indice;
			}
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
		}

		public function getEquipesTour(){
			$i = $this->m_nbCases - 1;
			
			while(($i > 0) && ($this->m_tas[(($i / 2) - 1)] != null))
			{
				$i = $i - 2;
			}
			$deb = $i ;
			$fin = $deb/2 - 1;
			$nbe = $deb-$fin ;
			$tabEquipes = array();
			for($k=0;$k<($nbe);++$k)
			{
				array_push($tabEquipes,$this->m_tas[$nbe-$k+2]);
				

			}

			return $tabEquipes ;

		}

		//Créer fonction rentrer 2e tour

		//Faire en 3 phases.
		//1) Génère match
		//2) jouer match
		//3) Avancer équipe

		//tester si le score est différent de 0

		public function genereMatchs()
		{
			$i = $this->m_nbCases - 1;
			
			while(($i != 0) && ($this->m_tas[(($i / 2) - 1)] != null))
			{
				$i = $i - 2;
			}
			
			$deb = $i;
			$fin = $i / 2;
			
			//if deb - fin
			
			if(($fin % 2) == 0)
				$fin= $fin + 1;

			for($i=$deb;$i>$fin;$i=$i-2)
			{
				$this->m_tabMatchs[$i] = new EquipeMatchT($this->m_tas[$i]->getIdEquipe(),$i);
				$this->m_tabMatchs[($i - 1)] = new EquipeMatchT($this->m_tas[($i - 1)]->getIdEquipe(),$i);
				
				if($this->m_tas[$i]->getIdEquipe() == -1)
				{
					$this->m_tabMatchs[$i]->setScoreVal(0);
					$this->m_tabMatchs[($i - 1)]->setScoreVal(0);
				}
				elseif($this->m_tas[($i - 1)]->getIdEquipe() != -1)
				{
					$this->m_tabMatchs[$i]->setScore();
					$this->m_tabMatchs[($i - 1)]->setScore();
				}
				else
				{
					$this->m_tabMatchs[$i]->setScoreVal(0);
					$this->m_tabMatchs[($i - 1)]->setScoreVal(0);
				}
			}
		}

		public function prochainTour(int $idTournoi)
		{
			$tabMatchT = getAllMatchTWithNoEquipeMatchT($idTournoi) ;
			$res = pow(2,$this->nbTours()-$this->tourCourant()) ;

			$i = $this->m_nbCases - 1;
			
			while(($i != 0) && ($this->m_tas[(($i / 2) - 1)] != null))
				$i = $i - 2;

			$deb = $i;
			$fin = $i / 2;

			for($i=$deb;$i>$fin;--$i)
			{
				if($this->m_tas[$i]->getIdEquipe() == -1)
					$this->m_tas[(($i / 2) - 1)] = $this->m_tas[($i - 1)];
				else
				{
					if($this->m_tabMatchs[$i]->getScore() >= $this->m_tabMatchs[($i - 1)]->getScore())
					{
						$this->m_tas[(($i / 2) - 1)] = $this->m_tas[$i];
					}
					else
					{
						$this->m_tas[(($i / 2) - 1)] = $this->m_tas[($i - 1)];
					}
				}
			}
			$i = $fin - 1 ;

			while(($i>0) && ($this->m_tas[(($i / 2) - 1)] != null))
			{
				$i = $i - 2;
			}
			$deb = $i;
			$fin = $i / 2;

			$index = 0 ;
			for($i=$deb;$i>$fin;$i=$i-2)
			{
				insertEquipeMatchT($tabMatchT[$index]->getIdMatchT(),$this->m_tas[$i]->getIdEquipe(),$this->m_tas[$i-1]->getIdEquipe());
				++$index ;
			}
		}

		public function Update(int $idTournoi)
		{
			$tabEquipesMatchT = getAllEquipeMatchT($idTournoi) ;
			$size = sizeof($tabEquipesMatchT);
	 		$indice = $this->m_nbCases-1 ;

			for($i=$indice;$i>$indice-$size;--$i)
			{
				$this->m_tabMatchs[$i] = $tabEquipesMatchT[$indice-$i] ;
				$this->m_tas[$i] = getEquipe(($tabEquipesMatchT[$indice-$i]->getIdEquipe())) ;
			}
			if($this->m_tabMatchs[1] && $this->m_tabMatchs[1]->getScore()!=-1)
			{
				$id = getIdEquipeGagnante($this->m_tabMatchs[1]->getIdMatchT()) ;
				//echo "ID_MATCH:".$this->m_tabMatchs[1]->getIdMatchT() ;
				//echo '<br ./>';
				$eq = getEquipe($id) ;
				//echo "ID_EQUIPE:".$eq->getIdEquipe();
				//echo '<br ./>';
				//echo "NOM_EQUIPE:".$eq->getNomEquipe();
				$this->m_tas[0] = $eq;
			}
		}


		public function UpdatePhasesFinales(int $idTournoi)
		{
			$tabEquipesMatchT = getAllEquipeMatchTSupA($idTournoi) ;
			$size = sizeof($tabEquipesMatchT);
	 		$indice = $this->m_nbCases-1 ;

			for($i=$indice;$i>$indice-$size;--$i)
			{
				$this->m_tabMatchs[$i] = $tabEquipesMatchT[$indice-$i] ;
				$this->m_tas[$i] = getEquipe(($tabEquipesMatchT[$indice-$i]->getIdEquipe())) ;
			}
			if($this->m_tabMatchs[1] && $this->m_tabMatchs[1]->getScore()!=-1)
			{
				$id = getIdEquipeGagnante($this->m_tabMatchs[1]->getIdMatchT()) ;
				//echo "ID_MATCH:".$this->m_tabMatchs[1]->getIdMatchT() ;
				//echo '<br ./>';
				$eq = getEquipe($id) ;
				//echo "ID_EQUIPE:".$eq->getIdEquipe();
				//echo '<br ./>';
				//echo "NOM_EQUIPE:".$eq->getNomEquipe();
				$this->m_tas[0] = $eq;
			}
		}

		public function setScoresEquipesVide(int $indice){
			$this->m_tabMatchs[$indice]->setScoreVal(-1);
		}


		public function setScoreTabMatchs(int $score,int $indice)
		{
			$this->m_tabMatchs[$indice]->setScoreVal($score);
		}

		public function tourPassable(){
			$i = $this->m_nbCases - 1;
			while(($i != 0) && ($this->m_tas[(($i / 2) - 1)] != null))
			{
				$i = $i - 2;
			}
			$deb = $i;
			$fin = $i / 2;
			$test = true ;
			if($fin==0)
				++$fin;

			for($i=$deb;$i>=$fin;--$i)
			{
					if($this->m_tabMatchs[$i] && $this->m_tabMatchs[$i]->getScore()==-1)
						$test=false;
			}
			return $test;
		}


		public function getTabMatchs()
		{
			return $this->m_tabMatchs;
		}
		
		public function getMatch($i,$j)
		{
			return $this->m_tabMatchs[$i][$j];
		}
		
		public function getTas()
		{
			return $this->m_tas;
		}
		
		public function nbTours()
		{
			$nbEquipes = (($this->m_nbCases + 1) / 2);
			$i = 0;
			
			while(pow(2, $i) < $nbEquipes)
			{
				$i = $i + 1;
			}
			
			return $i;
		}

		//8 equipes
		//nbTours = 3
		//TourCourant = 1
		// 2 = 2^($nbTours-TourCourant+1)
		//1 =


		
		public function tourCourant()
		{
			$nbEquipes = (($this->m_nbCases + 1) / 2);
			$tour = 1 ;
			$fin = (($this->m_nbCases - 1) / 2);
			
			while(($fin>0) && $this->m_tas[($fin - 1)] != null)
			{
				$tour = $tour + 1;
				$fin = $fin/2 - 1;
			}
			return $tour;
		}
		
		//ne pas mélanger les équipes vides
		
		public function melangerEquipes()
		{
			$nbEquipes = (($this->m_nbCases + 1) / 2); 
			$limite = $nbEquipes - 1;
			
			if($this->m_tas[($limite - 1)] == null)
			{
				$tab = array($nbEquipes);
				$random = rand($limite,$this->m_nbCases-1);
				$debut = $random + 1;
				$fin = $random;
				
				if($random == ($this->m_nbCases - 1))
				{
					--$debut;
					--$fin;
				}
				
				for($i=0;$i<($nbEquipes);$i=$i+2)
				{
					$tab[$i] = $this->m_tas[$debut];
					$tab[$i+1] = $this->m_tas[$fin];
					
					if($debut == ($this->m_nbCases - 1))
						$debut=$limite;
					else
						$debut=$debut+1;

					if($fin == $limite)
						$fin = $this->m_nbCases - 1;
					else
						$fin = $fin - 1;
				}

				for($i=$limite;$i<$this->m_nbCases;++$i)
					$this->m_tas[$i] = $tab[$i-$limite];
			}
		}

		public function placerEquipes() {}

		public function afficherArbre()
		{
			$deb = $this->m_nbCases-1 ;
			$fin = ($this->m_nbCases-1)/2 ;
			$x = 0 ;
			$y = 30 ; 
			$l1marge = 41 ; 
			$l2marge = 96 ; 
			$espace = 112 ;
			$pui = 0 ;
			
			while(pow(2, $pui) < (($deb / 2) + 1))
				$pui = $pui + 1;
			
			echo '<div id="container">';
			
			for($i=1;$i<=($this->nbTours());$i++)
			{
				echo'<section id=s'.$i.'>';
				
				for($j=$deb;$j>($deb - pow(2, $pui));--$j)
				{
					if($this->m_tas[$j] != null)
					{
						$nom = $this->m_tas[$j]->getNomEquipe();
						
						if($this->m_tabMatchs[$j] != null)
						{
							if ($this->m_tabMatchs[$j]->getScore() != -1)
							{
								if(($j % 2) == 0)
								{
									if($this->m_tabMatchs[$j]->getScore() <= $this->m_tabMatchs[($j - 1)]->getScore())
									{
										echo '<div style="color:#e60000">'.$nom.'<p>'.$this->m_tabMatchs[$j]->getScore().'</p></div>';
									}
									else
									{
										echo '<div style="color:#33ccff">'.$nom.'<p>'.$this->m_tabMatchs[$j]->getScore().'</p></div>';
									}
								}
								else
								{
									if($this->m_tabMatchs[$j]->getScore() < $this->m_tabMatchs[($j + 1)]->getScore())
									{
										echo '<div style="color:#e60000">'.$nom.'<p>'.$this->m_tabMatchs[$j]->getScore().'</p></div>';
									}
									else
									{
										echo '<div style="color:#33ccff">'.$nom.'<p>'.$this->m_tabMatchs[$j]->getScore().'</p></div>';
									}
								}
							}
							else 
							{
							if(($j % 2) == 0)
							{
								if($this->m_tabMatchs[$j]->getScore() <= $this->m_tabMatchs[($j - 1)]->getScore())
								{
									echo '<div>'.$nom.'</div>';
								}
								else
								{
									echo '<div>'.$nom.'</div>';
								}
							}
							else
							{
								if($this->m_tabMatchs[$j]->getScore() < $this->m_tabMatchs[($j + 1)]->getScore())
								{
									echo '<div>'.$nom.'</div>';
								}
								else
								{
									echo '<div>'.$nom.'</div>';
								}
							}
						}
						}

						else
						{
							echo '<div>'.$nom.'<p>0</p></div>';
						}
					}
					else
					{
						echo '<div></div>';
					}
				}
				
				echo '</section>';
				
				if($i<$this->nbTours())
				{
					echo '<div id=ligne1'.$i.'>';
					
					for($a=0;$a<((($this->m_nbCases + 1) / 2)/pow(2, ($i + 1)));++$a)
					{
						echo '<div></div>';
					}
					
					echo'
					<style>
					#ligne1'.$i.' div:first-child {
					margin-top:'.$l1marge.'px;}
					#ligne1'.$i.' {
					width: 70px; 
					height:95%; 
					float: left;
					}
					#ligne1'.$i.' div {
					border: 1px solid white; 
					border-left: none; 
					width:100%;
					height:'.$espace.'px;
					margin-bottom:'.$espace.'px;
					}
					</style>';
					
					echo'</div>';
					
					echo '<div id=ligne2'.$i.'>';
					
					for($a=0;$a<((($this->m_nbCases + 1) / 2) / pow(2, ($i + 1)));++$a)
					{
						echo '<div></div>';
					}
					
					echo '
					<style>
					#ligne2'.$i.' div:first-child {
					margin-top:'.$l2marge.'px;}
					#ligne2'.$i.' {
					width: 70px; 
					height:95%; 
					float: left;
					}
					#ligne2'.$i.' div {
					border-top: 1px solid white; 
					width:100%;
					height:'.$espace.'px;
					margin-bottom:'.$espace.'px;
					}
					</style>';
					
					echo '</div>';
				}
				
				echo'
				<style>
				#s'.$i.' div:first-child {
				margin-top:'.$x.'px;
				}
				
				#s'.$i.' > div:nth-child(2n-1) {
				border:1px solid white;
				}
				
				#s'.$i.' > div:nth-child(2n) {
				margin-bottom:'.$y.'px;
				border-top:none;
				border-left:1px solid white;
				border-right:1px solid white;
				border-bottom:1px solid white;
				}
				</style>
				';
				
				if($i != $this->nbTours())
				{
					$espace = $espace*2; 

					$l1marge = $l1marge + pow(2,$i-1)*55;
					$l2marge = $l2marge + pow(2,$i-1)*110 ;

				

					$x = $x + pow(2,$i-1)*55 ; 
					$y = $y + pow(2,$i-1)*112;

					$deb = $fin - 1 ; 
					$fin = $deb / 2 ; 

					$pui = $pui - 1 ;
				}
			}
			
			$win = $this->nbTours() + 1;
			$x = $x + 20;
			
			echo'<section id=s'.$win.'>';
			
			if($this->m_tas[0] != null)
			{
				$nom = $this->m_tas[0]->getNomEquipe();
				
				echo '<div style="color:#ffff4d">'.$nom.'</p></div>';
			}
			
			else
				echo '<div></div>';
			
			echo'</section>';
			
			echo'
				<style>
				#s'.$win.' div {
				margin-top:'.$x.'px;
				margin-left:140px;
				border:1px solid white;
				text-align:center;
				padding:10px 0 0 0;
				float:left;
				}
				</style>
				';

			echo'</div>';
		}
	}
?>