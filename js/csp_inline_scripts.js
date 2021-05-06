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

/* FUNCTION: build highslide popup for link-imdb2 classes
*	This function on click on classes "link-imdb2"
	1- extracts info from data-imdbltmid="(.*)"> 
	2- builds a highslide popup accordingly 
*/ 
(function ($) {
	$(document).on('click', 'a[data-imdbltmid]',function(e){
		Array.from(document.getElementsByClassName('link-imdb2')).forEach((link) => {
			link.addEventListener('click', (e)=>{
				// vars from imdb-link-transformer.php
				var tmppopupLarg = csp_inline_scripts_vars.popupLarg;
				var tmppopupLong = csp_inline_scripts_vars.popupLong;
				// var mid from the class data-imdbltmid to build the link
				var misc_term = e.target.getAttribute('data-imdbltmid');
				var url_imdbperso = csp_inline_scripts_vars.imdb_path + 'inc/popup-imdb_person.php?mid=' + misc_term;
				// highslide popup
				return hs.htmlExpand(this, { 
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

/* workin in progress */
jQuery('a#link-imdb2').click(function(){
	window.open('url_imdbperso', 
			'popup', 
			'resizable=yes, toolbar=no, scrollbars=yes, location=no, width=tmppopupLarg, height=tmppopupLong, 				top=5, left=5');
});
/**** popup-imdb_person.php
*
*/
/* doesn't work */
jQuery('.historyback').click(function(event){
	 event.preventDefault();
	window.history.back();
});

