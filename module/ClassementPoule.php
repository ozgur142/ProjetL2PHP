<?php
    include_once ('../BDD/reqEquipePoule.php');
    include_once('EquipeClassePoule.php');
    
    class ClassementPoule
    {
        private $m_idPoule;
        private $m_tabEq;
        
        public function __construct(int $idPoule)
        {
            $this->m_idPoule = $idPoule;
            $this->m_tabEq = array();
            
            $tabTemp = getAllEquipePouleWithIdPoule($this->m_idPoule);
            
            for($i=0;$i<sizeof($tabTemp);++$i)
                array_push($this->m_tabEq, new EquipeClassePoule($tabTemp[$i]->getIdEquipe()));
            $this->triABulle();
        }
        
        public function getIdPoule()
        {
            return $this->m_idPoule;
        }
        
        public function getTabEq()
        {
            return $this->m_tabEq;
        }
        
        private function triABulle()
        {
            $n = sizeof($this->m_tabEq);

            for($i=$n;$i>=1;--$i)
            {
                for($j=0;$j<($i-1);++$j)
                {         
                    if($this->m_tabEq[$j+1]->getPointsEquipe() < $this->m_tabEq[$j]->getPointsEquipe())
                    {
                        $t = $this->m_tabEq[$j];
                        $this->m_tabEq[$j] = $this->m_tabEq[$j+1];
                        $this->m_tabEq[$j+1] = $t;
                    }
                    //echo '<p style="color:white">'.getEquipe($arr[$j]->getIdEquipe())->getNomEquipe().'</p>';
                }
            }
        }

        public function afficheScore()
        {
            echo"[";
            for($i=0;$i<sizeof($this->m_tabEq);++$i)
                echo getEquipe($this->m_tabEq[$i]->getIdEquipe())->getNomEquipe()." : ".$this->m_tabEq[$i]->getPointsEquipe().",";
            echo "]";
        }
    }
?>