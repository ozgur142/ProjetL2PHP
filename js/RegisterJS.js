function gestionCode()
{
	var radio = document.getElementById("gestionnaire");
	var code_entre = document.getElementById("code");
	
	if (radio.checked == true)
	{
		code_entre.style.display = "block";
	} 
	else
	{
		code_entre.style.display = "none";
	}
}

function gestionOptionsJoueur()
{
	var radioJoueur = document.getElementById("Joueur");
	var optionsJoueur = document.getElementById("OptJoueur");
	//var choixEstCapitaine = document.getElementById("EstCapitaine");
	var choixEquipe = document.getElementById("Equipe");
	
	if(radioJoueur.checked == true)
	{
		optionsJoueur.style.display = "block";
		
		//choixEstCapitaine.required = true;
		choixEquipe.required = true;
	} 
	else
	{
		optionsJoueur.style.display = "none";
		
		//choixEstCapitaine.required = false;
		choixEquipe.required = false;
	}
}

function vider()
{
	document.getElementByName("Prenom").value = "";
	document.getElementByName("Nom").value = "";
	document.getElementByName("Mail").value = "";
	document.getElementByName("psw").value = "";
	document.getElementByName("psw_repeat").value = "";
	return false;
}