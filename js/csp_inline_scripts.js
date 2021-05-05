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

jQuery('a#highslide-director').click(function(){
var tmppopupLarg = csp_inline_scripts_vars.popupLarg;
var tmppopupLong = csp_inline_scripts_vars.popupLong;
var director_tag = document.getElementById('highslide-director');
var director_term = director_tag.getAttribute("data-search");
var url_imdbperso = csp_inline_scripts_vars.imdb_path + 'inc/popup-imdb_person.php?mid=' + director_term;

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

jQuery('a#highslide-director-local').click(function(){
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

/**** options-cache.php
*
*/

/* Confirmation popup for individual refresh and delete of movies and persons */
/* confirm dialog if attribute "data-confirm" in "a" tag */
(function ($) {
  $(document).on('click', 'a[data-confirm]',function(e){
	if(!confirm($(this).data('confirm'))){
	  e.stopImmediatePropagation();
	  e.preventDefault();
	}
  });
})(jQuery);

/* check all inputs */
/* movies */
(function ($) {
  $(document).on('click', 'input[data-check]',function(e){
	checkAll(document.getElementsByName('imdb_cachedeletefor[]'));
  });
})(jQuery);
/* people */
(function ($) {
  $(document).on('click', 'input[data-check-people]',function(e){
	checkAll(document.getElementsByName('imdb_cachedeletefor_people[]'));
  });
})(jQuery);

/* uncheck all inputs */
/* movies */
(function ($) {
  $(document).on('click', 'input[data-uncheck]',function(e){
	uncheckAll(document.getElementsByName('imdb_cachedeletefor[]'));
  });
})(jQuery);
/* people */
(function ($) {
  $(document).on('click', 'input[data-uncheck-people]',function(e){
	uncheckAll(document.getElementsByName('imdb_cachedeletefor_people[]'));
  });
})(jQuery);

