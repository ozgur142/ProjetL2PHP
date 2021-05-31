<?php

function estPair(int $nb)
{
	return $nb%2==0;
}

function estPuissanceDe2(int $nb)
{
	while(estPair($nb))
			$nb=$nb/2;
		return $nb==1;
}

function test(int $x)
{
	$val = $x ;
	if(!estPair($val))
		--$val ;
	while( (($val/2)+($x-$val))>0  && !estPuissanceDe2(($val/2)+($x-$val)) )
	{
		if(!estPair($val))
			--$val ;
		else
			$val-= 2;
	}
	return estPuissanceDe2(($val/2)+($x-$val));
}


for($i=1;$i<100;++$i)
{
	//echo $i ;
	$a = $i ;
	if(test($a)==true)
		echo 'Marche pour'.$i.' équipes';
	echo '<br ./>';
}


?>