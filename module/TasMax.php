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
				echo "$this->m_tas[$i], ";
			
			for($i=sizeof($this->m_tas);$i<$this->m_nbCases;++$i)
				echo "null, ";
			
			echo "]";
			
			echo "<br />";
			
			echo strval(sizeof($this->m_tas));
			
			echo "<br />";
			
			echo strval($this->m_nbCases);
		}
	}
?>