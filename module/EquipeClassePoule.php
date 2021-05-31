<?php
    include_once ('../BDD/reqEquipePoule.php');
    include_once ('../BDD/reqMatchPoule.php');
    
    class EquipeClassePoule
    {
        private $m_idEquipe;
        private $m_tabMatchPoule;
        
        public function __construct(int $idEquipe)
        {
            $this->m_idEquipe = $idEquipe;
            $this->m_tabMatchPoule = getAllMatchPouleWithIdEquipe($this->m_idEquipe);
        }
        
        public function getIdEquipe()
        {
            return $this->m_idEquipe;
        }
        
        public function getPointsEquipe()
        {
            if(sizeof($this->m_tabMatchPoule) == 0)
                return -1;
            
            $nb = 0;
            
            for($i=0;$i<sizeof($this->m_tabMatchPoule);++$i)
            {
                if($this->m_idEquipe == $this->m_tabMatchPoule[$i]->getIdEquipe1())
                {
                    if($this->m_tabMatchPoule[$i]->getScore1() < $this->m_tabMatchPoule[$i]->getScore2())
                        $nb += 1;
                    elseif($this->m_tabMatchPoule[$i]->getScore1() == $this->m_tabMatchPoule[$i]->getScore2())
                        $nb += 2;
                    elseif($this->m_tabMatchPoule[$i]->getScore1() > $this->m_tabMatchPoule[$i]->getScore2())
                        $nb += 4;
                }
                elseif($this->m_idEquipe == $this->m_tabMatchPoule[$i]->getIdEquipe2())
                {
                    if($this->m_tabMatchPoule[$i]->getScore2() < $this->m_tabMatchPoule[$i]->getScore1())
                        $nb += 1;
                    elseif($this->m_tabMatchPoule[$i]->getScore2() == $this->m_tabMatchPoule[$i]->getScore1())
                        $nb += 2;
                    elseif($this->m_tabMatchPoule[$i]->getScore2() > $this->m_tabMatchPoule[$i]->getScore1())
                        $nb += 4;
                }
            }
            
            return $nb;
        }
    }
?>