<?php
include_once('../BDD/reqEquipeTournoi.php');
include_once('../BDD/reqEquipeMatchT.php');
include_once('../BDD/reqMatchT.php');
include_once('../module/MatchT.php');
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Tests Matchs</title>
	</head>
	<body>
		<h1>Ceci est un test</h1>
		<?php

		$date = strtotime("2021-12-25");
		echo "DATE = "; echo date("Y-m-d",$date) ;
		echo '<br ./>';

		$time = mktime(10,45,30);
		echo "TIME = "; echo date("H:i:s",$time);
		echo '<br ./>';

		//mélange les équipes 
		$tabMelange = melanger(1);
		//affiche la séquence des ID mélangés
		for($i=0;$i<sizeof($tabMelange);++$i)
			echo $tabMelange[$i];
		echo '<br ./>';






		
		if(insertMatchT(1,"2021-05-10","10:30"))
		{
			if(insertEquipeMatchT(1,1,2))
				echo "X";
		}
		
		if(insertMatchT(1,"2021-05-10","10:30"))
		{
			if(insertEquipeMatchT(2,3,4))
				echo "X";
		}

		?>	
	</body>
</html>



