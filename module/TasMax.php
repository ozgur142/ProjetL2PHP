<?php
	include('../module/Equipe.php');
	
	class TasMax
	{
		private $m_tas;
		private $m_nbCases;
		
		public function __construct(int $nbEquipes)
		{
			$this->m_tas = array();
			$this->m_nbCases = (2 * $nbEquipes) - 1;
			
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
			
			echo strval(count($this->m_tas));
			
			echo "<br />";
			
			echo strval($this->m_nbCases);
		}
		
		public function insererAuxFeuilles(/*Equipe*/ $eq)
		{
			$feuille = $this->m_nbCases / 2;
			
			for($i=0;$i<sizeof($eq);++$i)
			{
				$this->m_tas[$feuille] = $eq[$i];
				++$feuille;
			}
		}
	}
?>