var source = document.getElementById('ChoixVille');
var divAutoComplete = document.getElementById('auto');
var result = document.getElementById("result");

function estPresent(tableau, valeur)
{
	for(var i=0;i<tableau.length;++i)
	{
		if(tableau[i].nomVille === valeur.nomVille)
			return true;
	}
	
	return false;
}

function retirerDoublons(tableau)
{
	var tableauResultats = [];
	
	for(var i=0;i<tableau.length;++i)
	{
		if(!estPresent(tableauResultats, tableau[i]))
			tableauResultats.push(tableau[i]);
	}
	
	return tableauResultats;
}

fct = function(el) {
	var elem = el.srcElement;
	
	source.value = elem.innerHTML;
}

inputHandler = function(e) {
	var requete = "https://api-adresse.data.gouv.fr/search/?q=" + e.target.value + "&limit=15";
	
	if(e.target.value != "")
	{
		fetch(requete)
			.then(response => response.json())
			.then(
				(response) => {
					var tabTemp = response.features;
					var tabTemp2 = [];
					
					tabTemp.forEach(
						elem => {
							var donnees = {
								nomVille: elem.properties.city,
								posX: elem.geometry.coordinates[1],
								posY: elem.geometry.coordinates[0]
							};
							
							tabTemp2.push(donnees);
						}
					);
					
					tabTemp2 = retirerDoublons(tabTemp2);
					
					var tableauVilles = [];
					
					for(var i=0;i<tabTemp2.length;++i)
					{
						var nomVilleStr = String(tabTemp2[i].nomVille);
						var posXStr = String(tabTemp2[i].posX);
						var posYStr = String(tabTemp2[i].posY);
						
						tableauVilles.push(nomVilleStr + " (" + posXStr + ";" + posYStr + ")");
					}
					
					for(var i=0;i<tableauVilles.length;++i)
					{
						var b = document.getElementById(String(i) + "choix");
						
						if(b)
							divAutoComplete.removeChild(b);
						
						var a = document.createElement('div');
						a.setAttribute("id", String(i) + "choix");
						a.setAttribute("class", "choixAutoCompletion");
						a.addEventListener("click", fct.bind(String(i) + "choix"));
						a.innerHTML = tableauVilles[i];
						
						divAutoComplete.appendChild(a);
					}
				}
			)
			.catch(error => alert("Erreur : " + error));
	}
}

source.addEventListener('input', inputHandler);
source.addEventListener('propertychange', inputHandler);