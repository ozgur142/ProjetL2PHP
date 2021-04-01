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
		}
		
		public function afficher()
		{
			echo "[";
			
			for($i=0;$i<sizeof($this->m_tas);++$i)
				echo "$this->m_tas[$i], ".((($i < (sizeof($this->m_tas) - 1)) && (sizeof($this->m_tas) < $this->m_nbCases)) ? ", " : "" );
			
			for($i=sizeof($this->m_tas);$i<$this->m_nbCases;++$i)
				echo "null".(($i < ($this->m_nbCases - 1)) ? ", " : "");
			
			echo "]";
			
			echo "<br />";
			
			echo strval(sizeof($this->m_tas));
			
			echo "<br />";
			
			echo strval($this->m_nbCases);
		}
	}
?>