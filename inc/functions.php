<?php

/**
 * Recursively delete a directory
 *
 * @param string $dir Directory name
 * credits to http://ch.php.net/manual/en/function.unlink.php#87045
 */
function imdblt_unlinkRecursive($dir){
    if(!$dh = @opendir($dir)){
        return;
    }
    while (false !== ($obj = readdir($dh))) {
        if($obj == '.' || $obj == '..') {
            continue;
        }

        if (!@unlink($dir . '/' . $obj)){
            unlinkRecursive($dir.'/'.$obj, true);
        }
    }

    closedir($dh);

    return;
} 

/**
 * Recursively scan a directory
 *
 * @param string $dir Directory name
 * @param string $filesbydefault it's the count of files contained in folder and not taken into account for the count
 * credits to http://ch2.php.net/manual/en/function.is-dir.php#85961 & myself
 */

function imdblt_isEmptyDir($dir, $filesbydefault= "3"){	
	return (($files = @scandir($dir)) && count($files) <= $filesbydefault);
} 

/**
 * Remove an html link
 * @param string $toremove Data wherefrom remove every html link
 */

function imdblt_remove_link ($toremove) {
	$toremove = preg_replace("/<a(.*?)>/", "", $toremove);
	return $toremove;
}

/**
 * Convert an imdb link to an internal popup link
 * @param string $convert Link to convert into popup highslide link
 */

function imdblt_convert_into_popup ($convert) {
	global $imdb_admin_values;

	$result = "<a class=\"link-imdb2 highslide\" onclick=\"return hs.htmlExpand(this, { objectType: 'iframe', width: " . $imdb_admin_values[popupLarg]. ", objectWidth: ". $imdb_admin_values[popupLarg].", objectHeight: ". $imdb_admin_values[popupLong]. ", headingEval: 'this.a.innerHTML', wrapperClassName: 'titlebar', src: '" . $imdb_admin_values[imdbplugindirectory] . "inc/popup-imdb_person.php?mid=" . "\${6}\${8}" . "' } )\" title=\"". esc_html__('open a new window with IMDb informations', 'imdb'). '" href="#" >';

	$convert = preg_replace("~(<a )((href=)(.*?))(nm)([[:alnum:]])((.*?)/\">)~", "$result", $convert);

	return $convert;
}


/**
 * Personal signature
 *
 */

function imdblt_admin_signature(){ ?>
	<div class="soustitre">
	<table class="options">
		
		<tr>
			<td><div class="explain"><?php _e('<strong>Licensing Info:</strong> Under the GPL licence, "IMDb link transformer" is based on <a href="http://www.izzysoft.de">imdbphp project</a> classes. However, a huge customization work has been required to implement it to wordpress; check support page for', 'imdb'); ?> <a href="?page=imdblt_options&subsection=help&helpsub=support"><?php _e('more', 'imdb') ?></a>.</div>
			</td>
		</tr>
		<tr>
			<td><div class="explain"> &copy; 2005-<?php print date("Y")?> <a href="<?php echo IMDBHOMEPAGE; ?>">Prometheus Group</a></div>
			</td>
		</tr>
	</table>
	</div>
<?php
} 



/**
 * Text displayed when no result is found
 *
 */

function imdblt_noresults_text(){ 
	echo "<br />";
	echo "<div class='noresult'>Sorry, no result found for this reference</div>";
	echo "<br />";
} 


/**
 * Recursively test an multi-dimensionnal array
 *
 * @param string $multiarray Array name
 * credits to http://in2.php.net/manual/fr/function.empty.php#94786
 */

function is_multiArrayEmpty($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $value) {
            if (!is_multiArrayEmpty($value)) {
                return false;
            }
        }
    }
    elseif (!empty($mixed)) {
        return false;
    }
    return true;
} 

/**
 * IMDb source link display
 *
 */

function imdblt_source_imdb($midPremierResultat){ 
	global $imdb_admin_values;
	echo '&nbsp;&nbsp;';
	echo '<a href="http://'.$imdb_admin_values[imdbwebsite].'/title/tt'.$midPremierResultat.'" >';
	echo "<img class='imdbelementSOURCE-picture' src=\"".$imdb_admin_values[imdbplugindirectory].'pics/imdb-link.png" />';
	echo '&nbsp;&nbsp;IMDb\'s page for this movie</a>';
} 


/**
 * Moviepilot source link
 *
 */

function imdblt_source_moviepilot($midPremierResultat){ 
	global $imdb_admin_values;
	echo '<a href="http://'.$imdb_admin_values[pilotsite].'/movies/imdb-id-'.(int)$midPremierResultat.'" >';
	echo "<img class='imdbelementSOURCE-picture' src=\"".$imdb_admin_values[imdbplugindirectory].'pics/moviepilot.png" />';
	echo '&nbsp;&nbsp;moviepilot\'s page for this movie</a>';
} 


/**
 * Activate taxomony from wordpress
 *
 */

add_action( 'init', 'create_imdblt_taxonomies', 0 );

function create_imdblt_taxonomies() {
	global $imdb_admin_values,$imdb_widget_values;

	if ($imdb_widget_values['imdbtaxonomytitle'] ==  true) {
		register_taxonomy('title', array('page','post'), array( 'hierarchical' => false, 'label' => esc_html__("Movie's titles", 'imdb'), 'query_var' => 'title', 'rewrite' => array( 'slug' => 'title' ) )  ) ; }

	if ($imdb_widget_values['imdbtaxonomygenre'] ==  true) {
		register_taxonomy('genre', array('page','post'), array( 'hierarchical' => false, 'label' => esc_html__("Movie's genres", 'imdb'), 'query_var' => 'genre', 'rewrite' => array( 'slug' => 'genre' ) )  ) ; }

	if ($imdb_widget_values['imdbtaxonomykeywords'] ==  true) {
		register_taxonomy('keywords', array('page','post'), array( 'hierarchical' => false, 'label' => esc_html__("Movie's keywords", 'imdb'), 'query_var' => 'keywords', 'rewrite' => array( 'slug' => 'keywords' ) )  ) ; }

	if ($imdb_widget_values['imdbtaxonomycountry'] == true) {
		register_taxonomy('country', array('page','post'), array( 'hierarchical' => false, 'label' => esc_html__("Movie's countries", 'imdb'), 'query_var' => 'country', 'rewrite' => array( 'slug' => 'country' ) )  ) ; }

	if ($imdb_widget_values['imdbtaxonomylanguage'] == true) {
		register_taxonomy('language', array('page','post'), array( 'hierarchical' => false, 'label' => esc_html__("Movie's languages", 'imdb'), 'query_var' => 'language', 'rewrite' => array( 'slug' => 'language' ) )  ) ; }

	if ($imdb_widget_values['imdbtaxonomycomposer'] == true) {
		register_taxonomy('composer', array('page','post'), array( 'hierarchical' => false, 'label' => esc_html__("Movie's composers", 'imdb'), 'query_var' => 'composer', 'rewrite' => array( 'slug' => 'composer' ) )  ) ; }

	if ($imdb_widget_values['imdbtaxonomycolor'] == true) {
		register_taxonomy('color', array('page','post'), array( 'hierarchical' => false, 'label' => esc_html__("Movie's colors", 'imdb'), 'query_var' => 'color', 'rewrite' => array( 'slug' => 'color' ) )  ) ; }

	if ($imdb_widget_values['imdbtaxonomydirector'] == true) {
		register_taxonomy('director', array('page','post'), array( 'hierarchical' => false, 'label' => esc_html__("Movie's directors", 'imdb'), 'query_var' => 'director', 'rewrite' => array( 'slug' => 'director' ) )  ) ; }

	if ($imdb_widget_values['imdbtaxonomycreator'] == true) {
		register_taxonomy('creator', array('page','post'), array( 'hierarchical' => false, 'label' => esc_html__("Movie's creators", 'imdb'), 'query_var' => 'creator', 'rewrite' => array( 'slug' => 'creator' ) )  ) ; }

	if ($imdb_widget_values['imdbtaxonomyproducer'] == true) {
		register_taxonomy('producer', array('page','post'), array( 'hierarchical' => false, 'label' => esc_html__("Movie's producers", 'imdb'), 'query_var' => 'producer', 'rewrite' => array( 'slug' => 'producer' ) )  ) ; }

	if ($imdb_widget_values['imdbtaxonomyactor'] == true) {
		register_taxonomy('actor', array('page','post'), array( 'hierarchical' => false, 'label' => esc_html__("Movie's actors", 'imdb'), 'query_var' => 'actor', 'rewrite' => array( 'slug' => 'actor' ) )  ) ; }
		
	if ($imdb_widget_values['imdbtaxonomywriter'] == true) {
		register_taxonomy('writer', array('page','post'), array( 'hierarchical' => false, 'label' => esc_html__("Movie's writers", 'imdb'), 'query_var' => 'writer', 'rewrite' => array( 'slug' => 'writer' ) )  ) ; }
}

/**
 * Count me function
 * allows movie total count (how many time a movie is called by plugin
 *
 */

function count_me($thema, &$count_me_siffer) {
	global $count_me_siffer, $test;
	$count_me_siffer++;
	$test[$count_me_siffer] = $thema;
	$ici=array_count_values($test);

	if ($ici[$thema] < 2) 
		return "nomore";
}



/**
 * Highslide popup function
 * constructs a HTML link to open a popup (using highslide library)
 * (called from imdb-link-transformer.php
 */

function imdb_popup_highslide_link ($link_parsed, $popuplarg="", $popuplong="" ) {
	global $imdb_admin_values;
		
	if (! $popuplarg )
		$popuplarg=$imdb_admin_values["popupLarg"];

	if (! $popuplong )
		$popuplong=$imdb_admin_values["popupLong"];

	$parsed_result =	"<span class=\"link-imdb\"><a class=\"highslide\" onclick=\"return hs.htmlExpand(this, { objectType: 'iframe', width: ".
					$popuplarg . 
					", objectWidth: " . 
					$popuplarg . 
					", objectHeight: " . 
					$popuplong .
					", headingEval: 'this.a.innerHTML', headingText: '" .
					ucwords(imdb_htmlize($link_parsed[1])) .
					"', wrapperClassName: 'titlebar', src: '" .
					$imdb_admin_values[imdbplugindirectory] .
					"inc/popup-search.php?film=" .
					imdb_htmlize($link_parsed[1]) .
					"' } );\" href=\"#\" title=\"" .esc_html__('open a new window with IMDb informations', 'imdb')."\">" .
					$link_parsed[1] .
					"</a></span>";
	
	return $parsed_result;
}


/**
 * Classical popup function
 * constructs a HTML link to open a popup
 * (called from imdb-link-transformer.php
 */

function imdb_popup_link ($link_parsed, $popuplarg="", $popuplong="" ) {
	global $imdb_admin_values;
		
	if (! $popuplarg )
		$popuplarg=$imdb_admin_values["popupLarg"];

	if (! $popuplong )
		$popuplong=$imdb_admin_values["popupLong"];

	$parsed_result =	"<a class=\"link-imdb\" onclick=\"window.open('" .
					$imdb_admin_values[imdbplugindirectory] .
					"inc/popup-search.php?film=" .
					imdb_htmlize($link_parsed[1]) .
					"', 'popup', 'resizable=yes, toolbar=0, scrollbars=yes, status=no, location=no, width=" .
					$popuplarg . 
					", height=" .
					$popuplong .
					", top=5, left=5')\" title=\"".esc_html__('open a new window with IMDb informations', 'imdb')."\">" .
					$link_parsed[1] .
					"</a>"; 
	
	return $parsed_result;
}


/**
 * HTMLizing function
 * transforms movie's name in a way to be able to be searchable (ie "ô" becomes "&ocirc;") 
 * 
 */

function imdb_htmlize ($link) {
    // a. quotes escape
    $lienhtmlize = addslashes($link);      
    // b.converts db to html -> no more needed
    //$lienhtmlize = htmlentities($lienhtmlize,ENT_NOQUOTES,"UTF-8");
    // c. regular expression to convert all accents; weird function...
    $lienhtmlize = preg_replace('/&(?!#[0-9]+;)/s', '&amp;', $lienhtmlize);
    // d. turns spaces to "+", which allows titles including several words
    $lienhtmlize = str_replace(array(' '), array('+'), $lienhtmlize);
    
    return $lienhtmlize; 
}


?>
