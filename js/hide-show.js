// Fonction permettant d'afficher/cacher un élément en cliquant sur un texte

function toggleLayer( whichLayer ) {
var elem, vis;
if( document.getElementById ) // this is the way the standards work<br>
	elem = document.getElementById( whichLayer );
else if( document.all ) // this is the way old msie versions work<br>
	elem = document.all[whichLayer];
else if( document.layers ) // this is the way nn4 works<br>
	elem = document.layers[whichLayer];
	vis = elem.style;
// if the style.display value is blank we try to figure it out here<br>
if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)
	vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';
	vis.display = (vis.display==''||vis.display=='block')?'none':'block';
}
