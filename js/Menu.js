// Fonctions utiles au menu.

function possedeClasse(element, className)
{
  return (((' ' + element.className + ' ').indexOf(' ' + className+ ' ')) > -1);
}

function changerIcone(x)
{
  var menuEntier = document.querySelector(".corpsMenu");
  console.log(menuEntier);
  x.classList.toggle("change");
  
  if(possedeClasse(x, "change"))
  {
    console.log("ayé");
    menuEntier.classList.add("afficherCorps");
  }
  else
    menuEntier.classList.remove("afficherCorps");
}