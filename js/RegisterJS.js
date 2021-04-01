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

function vider()
{
	document.getElementByName("Prenom").value = "";
	document.getElementByName("Nom").value = "";
	document.getElementByName("Mail").value = "";
	document.getElementByName("psw").value = "";
	document.getElementByName("psw_repeat").value = "";
	return false;
}