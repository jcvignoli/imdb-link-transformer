// Function to activate/unactivate forms checkboxes (dependending of the choice made)
// First function comes from http://www.editeurjavascript.com/scripts/scripts_formulaires_3_593.php
// other are "homebrew"

function GereControle(Controleur, Controle, Masquer) {
var objControleur = document.getElementById(Controleur);
var objControle = document.getElementById(Controle);
	if (Masquer=='1')
		objControle.style.visibility=(objControleur.checked==true)?'visible':'hidden';
	else
		objControle.disabled=(objControleur.checked==true)?false:true;
	return true;
}


function GereControleInverse(Controleur, Controle, Masquer) {
var objControleur = document.getElementById(Controleur);
var objControle = document.getElementById(Controle);
	if (Masquer=='1')
		objControle.style.visibility=(objControleur.checked==false)?'visible':'hidden';
	else
		objControle.disabled=(objControleur.checked==false)?false:true;
	return true;
}




function checkAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = true ;
}


function uncheckAll(field)
{
for (i = 0; i < field.length; i++)
	field[i].checked = false ;
}


function imdb_pilot_imdbfill_GereControle(valeurSelect, Controleur, Controle, Masquer) {
var objControleur = document.getElementById(Controleur);
var objControle = document.getElementById(Controle);
	if (valeurSelect == 'FULL_ACCESS') {
		if (Masquer=='1')
			objControle.style.visibility=(objControleur.checked==true)?'visible':'hidden';
		else
			objControle.disabled=(objControleur.checked==true)?false:true;
		return true;
	} 
}
