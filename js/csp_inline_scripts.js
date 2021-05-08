/* Former inline function passed here to be Content Security Policy (CSP) Compliant
*  Needs jquery						
*/

/**** functions.php
*
*/

/* 
jQuery('.link-imdb').on('click', function(event){
document.getElementsByClassName("link-imdb").window.open(); 
})
*/

/**** imdb-movie.inc.php
*
*/

jQuery('a#highslide-pic').click(function(){
	return hs.expand(this, { useBox: false 
	});
});

/* FUNCTION: build highslide or classic popup according to the classes
*	This function on click on classes "link-imdblt-(.*)"
	1- extracts info from data-(.*) <a> attribute
	2- builds either a -highslide- or -classic- popup accordingly
*/ 

/* highslide popup, people */
(function ($) {
	$(document).on('click', 'a[data-highslidepeople]',function(e){
		Array.from(document.getElementsByClassName('link-imdblt-highslidepeople')).forEach((link) => {
			link.addEventListener('click', (e)=>{
				// vars from imdb-link-transformer.php
				var tmppopupLarg = csp_inline_scripts_vars.popupLarg;
				var tmppopupLong = csp_inline_scripts_vars.popupLong;
				// var mid from the class data-imdbltmid to build the link
				var misc_term = e.target.getAttribute('data-highslidepeople');
				var url_imdbperso = csp_inline_scripts_vars.imdb_path + 'inc/popup-imdb_person.php?mid=' + misc_term;
				// highslide popup
				return hs.htmlExpand(this, { 
					allowWidthReduction: true,
					objectType: 'iframe', 
					width: tmppopupLarg, 
					objectWidth: tmppopupLarg, 
					objectHeight: tmppopupLong, 
					headingEval: 'this.a.innerHTML', 
					wrapperClassName: 'titlebar', 
					src: url_imdbperso
				});
	  		});
		});
	});
})(jQuery);

/* classic popup, people */
(function ($) {
	$(document).on('click', 'a[data-classicpeople]',function(e){
		Array.from(document.getElementsByClassName('link-imdblt-classicpeople')).forEach((link) => {
			link.addEventListener('click', (e)=>{
				// vars from imdb-link-transformer.php
				var tmppopupLarg = csp_inline_scripts_vars.popupLarg;
				var tmppopupLong = csp_inline_scripts_vars.popupLong;

				// var mid from the class data-classicimdbltmid to build the link
				var misc_term = e.target.getAttribute('data-classicpeople');
				var url_imdbperso = csp_inline_scripts_vars.imdb_path + 'inc/popup-imdb_person.php?mid=' + misc_term;
				// classic popup
				window.open( url_imdbperso, 'popup', 'resizable=yes, toolbar=no, scrollbars=yes, location=no, width='+tmppopupLong+', height='+tmppopupLarg+', top=5, left=5')

	  		});
		});
	});
})(jQuery);

/* highslide popup, movie */
(function ($) {
	$(document).on('click', 'a[data-highslidefilm]',function(e){
		Array.from(document.getElementsByClassName('link-imdblt-highslidefilm')).forEach((link) => {
			link.addEventListener('click', (e)=>{
				// vars from imdb-link-transformer.php
				var tmppopupLarg = csp_inline_scripts_vars.popupLarg;
				var tmppopupLong = csp_inline_scripts_vars.popupLong;
				// var mid from the class data-imdbltmid to build the link
				var misc_term = e.target.getAttribute('data-highslidefilm');
				var url_imdbperso = csp_inline_scripts_vars.imdb_path + 'inc/popup-search.php?film=' + misc_term;
				// highslide popup
				return hs.htmlExpand(this, { 
					allowWidthReduction: true,
					objectType: 'iframe', 
					width: tmppopupLarg, 
					objectWidth: tmppopupLarg, 
					objectHeight: tmppopupLong, 
					headingEval: 'this.a.innerHTML', 
					wrapperClassName: 'titlebar', 
					src: url_imdbperso
				});
	  		});
		});
	});
})(jQuery);

/* classic popup, movie */
(function ($) {
	$(document).on('click', 'a[data-classicalfilm]',function(e){
		Array.from(document.getElementsByClassName('link-imdblt-classicalfilm')).forEach((link) => {
			link.addEventListener('click', (e)=>{
				// vars from imdb-link-transformer.php
				var tmppopupLarg = csp_inline_scripts_vars.popupLarg;
				var tmppopupLong = csp_inline_scripts_vars.popupLong;

				// var mid from the class data-classicimdbltmid to build the link
				var misc_term = e.target.getAttribute('data-classicalfilm');
				var url_imdbperso = csp_inline_scripts_vars.imdb_path + 'inc/popup-search.php?film=' + misc_term;
				// classic popup
				window.open( url_imdbperso, 'popup', 'resizable=yes, toolbar=no, scrollbars=yes, location=no, width='+tmppopupLong+', height='+tmppopupLarg+', top=5, left=5')

	  		});
		});
	});
})(jQuery);

/**** popup-imdb_person.php
*
*/
/* doesn't work */
jQuery('.historyback').click(function(event){
	 event.preventDefault();
	window.history.back();
});

