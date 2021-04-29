<?php
// IMDb link transformer wordpress plugin
//
// (c) 2005-21 Prometheus group
// https://www.jcvignoli.com/blog
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// *****************************************************************

/*
Plugin Name: IMDb link transformer
Plugin URI: https://www.jcvignoli.com/blog/imdb-link-transformer-wordpress-plugin
Description: Add to every movie title tagged with &lt;!--imdb--&gt; (...) &lt;!--/imdb--&gt; a link to an <a href="http://www.imdb.com"><acronym title="internet movie database">imdb</acronym></a> popup. Can also display data related to movies either in a <a href="widgets.php">widget</a> or inside a post. Perfect for your movie reviews. Cache handling. Have a look at the <a href="admin.php?page=imdblt_options">options page</a>.
Version: 3
Author: jcv
Author URI: https://www.jcvignoli.com/blog
*/ 

if (!defined('WP_DEBUG'))
	define('WP_DEBUG', false);
if (!defined('SCRIPT_DEBUG'))
	define('SCRIPT_DEBUG', false);

// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) 
	die('You are not allowed to call this page directly.');

# Requires
require_once ('config.php');
require_once ('inc/functions.php');
require_once ('inc/widget.php');

### IMDbLT Table Name
/*
global $wpdb;
$wpdb->imdblt = $wpdb->prefix.'imdblt';
*/

if (class_exists("imdb_settings_conf")) {
	$imdb_ft = new imdb_settings_conf();
	$imdb_admin_values = $imdb_ft->get_imdb_admin_option();
	$imdb_widget_values = $imdb_ft->get_imdb_widget_option();
	$imdb_cache_values = $imdb_ft->get_imdb_cache_option();
}

if (class_exists("imdblt")) {
	global $imdb_ft, $imdb_admin_values, $imdb_widget_values, $imdb_cache_values;
	$start = new imdblt;
	$start->imdblt_init();
}

// *********************
// ********************* Functions
// *********************
class imdblt {

/*constructor*/
function imdblt_init () {
	$this->imdblt_start ();
}

/**
1.- Replace <!--imdb--> tags inside the posts
**/

##### a) Looks for what is inside tags  <!--imdb--> ...  <!--/imdb--> and constructs a link to "popup.php"
function parse_imdb_tags($correspondances){
	global $imdb_admin_values;
    
	$correspondances = $correspondances[0];
	preg_match("/<!--imdb-->(.*?)<!--\/imdb-->/i", $correspondances, $link_parsed);

	// link construction

	if ($imdb_admin_values['imdbpopup_highslide'] == 1) { // highslide popup
		$link_parsed = imdb_popup_highslide_link ($link_parsed) ;
	} else {						// classic popup
	    	$link_parsed = imdb_popup_link ($link_parsed) ;
	}

	return $link_parsed;
}

##### b) Replace  <!--imdb--> tags with links to "popup.php"
function imdb_linking($text) {
	$pattern = '/<!--imdb-->(.*?)<!--\/imdb-->/i';
	$text = preg_replace_callback($pattern,array(&$this, 'parse_imdb_tags'),$text);
	return $text;
}

/**
2.- Replace [imdblt]movieID[/imdblt] tags inside posts (as an automation of imdb_external_call function)
**/

##### a) Looks for what is inside tags [imdblt] .... [/imdblt] and include the movies data

function parse_imdb_tag_transform ($text) {
	global $imdb_admin_values, $wp_query;
	$imdballmeta[] = $text[1];
	return $this->imdb_external_call($imdballmeta);
}

##### b) Replace [imdblt] .... [/imdblt] tags with movies data
function imdb_tags_transform ($text) {
	$pattern = "'\[imdblt\](.*?)\[/imdblt\]'si";
	return preg_replace_callback($pattern, array(&$this, 'parse_imdb_tag_transform'),$text);
}

/**
3.- Replace [imdbltid]movieID[/imdbltid] tags inside posts (with imdb_external_call function)
**/

##### a) Looks for what is inside tags [imdbltid] .... [/imdbltid] and include the movies data

function parse_imdb_tag_transform_id ($text) {
	global $imdb_admin_values, $wp_query;
	$imdballmeta = $text[1];
	return $this->imdb_external_call('','',$imdballmeta);
}

##### b) Replace [imdblt] .... [/imdblt] tags with movies data
function imdb_tags_transform_id ($text) {
	$pattern = "'\[imdbltid\](.*?)\[/imdbltid\]'si";
	return preg_replace_callback($pattern, array(&$this, 'parse_imdb_tag_transform_id'),$text);
}

/**
4.-  Add tags button <!--imdb--> <!--/imdb--> to writing admin page
**/

##### a) HTML part
function imdb_add_quicktag() {
    if (wp_script_is('quicktags')){
	?>
	<script type="text/javascript">
		QTags.addButton( 'imdb_handler', 'IMDBlt', '<!--imdb-->', '<!--/imdb-->', '', 'IMDBlt', 1 );
	</script>
	<?php
    }
}


##### b) tinymce part (wysiwyg display)

function imdb_addbuttons() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", array(&$this, "add_imdb_tinymce_plugin"));
     add_filter('mce_buttons', array(&$this, 'register_imdb_button'));
   }
}
function register_imdb_button($buttons) {
   array_push($buttons, "separator", "imdb");
   return $buttons;
}
// Load the TinyMCE plugin 
function add_imdb_tinymce_plugin($plugin_array) {
   $plugin_array['imdb'] = WP_PLUGIN_URL .'/imdb-link-transformer/js/tinymce_editor_imdblt_plugin.js';
   return $plugin_array;
}

/**
5.- Add the stylesheet & javascript to pages head
**/ 

##### a) outside admin part
function imdb_add_head_blog () {
	global $imdb_admin_values; ?>

<!-- Added by "Imdb link transformer" plugin -->
<?php if (file_exists (TEMPLATEPATH . "/imdb.css") ) { // an imdb.css exists inside theme folder, take it! ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/imdb.css" type="text/css" media="screen" />
<?php } else { // no imdb.css exists in theme, add default one?>
<link rel="stylesheet" href="<?php echo $imdb_admin_values[imdbplugindirectory] ?>css/imdb.css" type="text/css" media="screen" />
<?php } 

// Highslide popup
if ($imdb_admin_values['imdbpopup_highslide'] == 1) { 
// wp_enqueue_script('imdblt-highslide-js', $imdb_admin_values[imdbplugindirectory] . "js/highslide/highslide-with-html.js");
// wp_enqueue_style('imdblt-highslide-css', $imdb_admin_values[imdbplugindirectory] . "css/highslide.css", '', '', 'screen'); ?>
<script type="text/javascript" src="<?php echo $imdb_admin_values[imdbplugindirectory] ?>js/highslide/highslide-with-html.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $imdb_admin_values[imdbplugindirectory] ?>css/highslide.css" media="screen" />
<script type="text/javascript">
hs.allowWidthReduction = true
hs.graphicsDir = '<?php echo $imdb_admin_values[imdbplugindirectory] ?>js/highslide/graphics/';
hs.showCredits = false;
hs.outlineType = 'custom';
hs.easing = 'linearTween';
hs.align = 'center';
hs.useBox = true;
hs.registerOverlay(
	{ html: '<div class=\"closebutton\" onclick=\"return hs.close(this)\" title=\"Close\"></div>',
	position: 'top right',
	useOnHtml: true, fade: 2 }
);
</script>
<!--[if lt IE 7]>'
	<link rel="stylesheet" type="text/css" href="<?php echo $imdb_admin_values[imdbplugindirectory] ?>css/highslide-ie6.css" type="text/css" media="screen" />
<![endif]--><?php
}?>

<!-- /Added by "Imdb link transformer" plugin -->

<?php
}

##### b) admin part
function imdb_add_head_admin () {
	$this->imdb_add_css_admin ();
	$this->imdb_add_js_admin ();
}
function imdb_add_css_admin() {
	global $imdb_admin_values;
	wp_enqueue_style('imdblt_css_admin', IMDBLTURLPATH . "css/imdb-admin.css");
	wp_enqueue_style("imdblt_highslide", IMDBLTURLPATH ."css/highslide.css");
}
function imdb_add_js_admin () {
	global $imdb_admin_values;
	wp_enqueue_script('common'); // script needed for meta_boxes (ie, help.php)
	wp_enqueue_script('wp-lists'); // script needed for meta_boxes (ie, help.php)
	wp_enqueue_script('postbox'); // script needed for meta_boxes (ie, help.php)
	wp_enqueue_script('jquery'); // script needed by highslide and maybe others
	wp_enqueue_script("imdblt_highslide", IMDBLTURLPATH ."js/highslide/highslide-with-html.min.js", array(), "5.0");
	wp_enqueue_script('imdblt_un-active-boxes', IMDBLTURLPATH . "js/un-active-boxes.js");
	wp_enqueue_script('imdblt_movevalues-formeselectboxes', IMDBLTURLPATH . "js/movevalues-formselectboxes.js");
} 

/**
7.- Add the admin menu
**/

function imdb_admin_panel() {
	global $imdb_ft, $imdb_admin_values;
	if (!isset($imdb_ft)) {
		return;
	}
	
	if (function_exists('add_options_page') && ($imdb_admin_values['imdbwordpress_bigmenu'] == 0 ) ) {
		add_options_page('IMDb link transformer Options', 'IMDb LT', 'administrator', 'imdblt_options', array(&$imdb_ft, 'printAdminPage'));

		// third party plugin
		add_filter('ozh_adminmenu_icon_imdblt_options', array(&$this, 'ozh_imdblt_icon') );
	}
	if (function_exists('add_submenu_page') && ($imdb_admin_values['imdbwordpress_bigmenu'] == 1 ) ) {
		// big menu for many pages for admin sidebar
		add_menu_page( 'IMDb LT Options', 'IMDb LT' , 8, 'imdblt_options', array(&$imdb_ft, 'printAdminPage'), $imdb_admin_values[imdbplugindirectory].'pics/imdb.gif');
		add_submenu_page( 'imdblt_options' , __('IMDb link transformer options page', 'imdb'), __('General options', 'imdb'), 8, 'imdblt_options');
		add_submenu_page( 'imdblt_options' , __('Widget & In post options page', 'imdb'), __('Widget/In post', 'imdb'), 8, 'imdblt_options&subsection=widgetoption', array(&$imdb_ft, 'printAdminPage'));
		add_submenu_page( 'imdblt_options',  __('Cache management options page', 'imdb'), __('Cache management', 'imdb'), 8, 'imdblt_options&subsection=cache', array(&$imdb_ft, 'printAdminPage'));
		add_submenu_page( 'imdblt_options' , __('Help page', 'imdb'), __('Help', 'imdb'), 8, 'imdblt_options&subsection=help', array(&$imdb_ft, 'printAdminPage'));
		//
	}

	if (function_exists('add_action') ) {
		// scripts & css
		add_action('admin_enqueue_scripts', array(&$this, 'imdb_add_head_admin') );
		// buttons
		add_action('admin_print_footer_scripts', array(&$this, 'imdb_add_quicktag') );
		
		// add imdblt menu in toolbar menu (top wordpress menu)
		if ($imdb_admin_values['imdbwordpress_tooladminmenu'] == 1 )
			add_action('admin_bar_menu', array(&$this, 'add_admin_toolbar_menu'),70 );
	}
}

/**
8.- Function external call (ie, inside a post) 
    can come from [imdblt] and [imdbltid]
**/

function imdb_external_call ($moviename="", $external="", $filmid="") {
global $imdb_admin_values, $imdb_widget_values, $wp_query;

	if (empty($moviename) && empty($filmid)) {					// old way (no parameter) - old plugin compatibility purpose
	
	$filmid = $wp_query->post->ID;
	$imdballmeta = get_post_meta($filmid, 'imdb-movie-widget', false);
	echo "<div class='imdbincluded'>";
	include ( "inc/imdb-movie.inc.php" );
	echo "</div>";
	} 

	if (!empty($moviename) && ($external == "external")) {				// call function from external (using parameter "external") 
	$imdballmeta[0] = $moviename;						       // especially made to be integrated (ie, inside a php code)
											// can't accept caching through ob_start
						
	echo "<div class='imdbincluded'>";
	include ( "inc/imdb-movie.inc.php" );
	echo "</div>";
	} 
	
	if (($external == "external") && ($filmid))  {					// call function from external (using parameter "external" ) 
											// especially made to be integrated (ie, inside a php code)
											// can't accept caching through ob_start
	$imdballmeta = 'imdb-movie-widget-noname';
	$moviespecificid = $filmid;
	echo "<div class='imdbincluded'>";
	include ( "inc/imdb-movie.inc.php" );
	echo "</div>";
	}

	ob_start(); // ob_start (cache) system to display data precisely where there're wished) -> start record

	if (!empty($moviename) && (empty($external))) {					// new way (using a parameter - imdb movie name)
	$imdballmeta = $moviename;

	echo "<div class='imdbincluded'>";
	include ( "inc/imdb-movie.inc.php" );
	echo "</div>";
	$out1 = ob_get_contents(); //put the record into value
	} 

	if (($filmid) && (empty($external)))  {						// new way (using a parameter - imdb movie id)
	$imdballmeta = 'imdb-movie-widget-noname';
	$moviespecificid = $filmid;
	echo "<div class='imdbincluded'>";
	include ( "inc/imdb-movie.inc.php" );
	echo "</div>";
	$out2 = ob_get_contents(); //put the record into value
	}

	ob_end_clean(); // end record
	return $out1.$out2;

}

/**
8.- Add icon for Admin Drop Down Icons
* http://planetozh.com/blog/my-projects/wordpress-admin-menu-drop-down-css/
**/

function ozh_imdblt_icon() {
	global $imdb_admin_values;
	return $imdb_admin_values[imdbplugindirectory]. 'pics/imdb.gif';
}

/**
9.- Add admin menu to the toolbar
**/

function add_admin_toolbar_menu($admin_bar) {
	global $imdb_admin_values;

	$admin_bar->add_menu( array('id'=>'imdblt-menu','title' => "<img src='$imdb_admin_values[imdbplugindirectory]pics/imdb.gif' width='16px' />&nbsp;&nbsp;".__('IMDB LT'),'href'  => 'admin.php?page=imdblt_options', 'meta'  => array('title' => __('IMDBLT Menu'), ),) );

	$admin_bar->add_menu( array('parent' => 'imdblt-menu','id' => 'imdblt-menu-options','title' => "<img src='$imdb_admin_values[imdbplugindirectory]pics/admin-general.png' width='16px' />&nbsp;&nbsp;".__('General options'),'href'  =>'admin.php?page=imdblt_options','meta'  => array('title' => __('General options'),),) );

	$admin_bar->add_menu( array('parent' => 'imdblt-menu','id' => 'imdblt-menu-widget-options','title' => "<img src='$imdb_admin_values[imdbplugindirectory]pics/admin-widget-inside.png' width='16px' />&nbsp;&nbsp;".__('Widget options'),'href'  =>'admin.php?page=imdblt_options&subsection=widgetoption','meta'  => array('title' => __('Widget options'),),) );
	
	$admin_bar->add_menu( array('parent' => 'imdblt-menu','id' => 'imdblt-menu-cache-options','title' => "<img src='$imdb_admin_values[imdbplugindirectory]pics/admin-cache.png' width='16px' />&nbsp;&nbsp;".__('Cache options'),'href'  =>'admin.php?page=imdblt_options&subsection=cache','meta' => array('title' => __('Cache options'),),) );

	$admin_bar->add_menu( array('parent' => 'imdblt-menu','id' => 'imdblt-menu-help','title' => "<img src='$imdb_admin_values[imdbplugindirectory]pics/admin-help.png' width='16px' />&nbsp;&nbsp;".__('Help'),'href' =>'admin.php?page=imdblt_options&subsection=help','meta'  => array('title' => __('Help'),),) );

}

// *********************
// ********************* Automatisms & executions
// *********************

function imdblt_start () {
	global $imdb_configs_values, $imdb_ft;

	// Be sure WP is running
	if (function_exists('add_action')) {
	    	// css for main blog
		add_action('wp_head', array(&$this, 'imdb_add_head_blog'),3 );

		// add links to popup
		add_filter('the_content', array(&$this, 'imdb_linking'), 11);
		add_filter('the_excerpt', array(&$this, 'imdb_linking'), 11);
		
	    	// delete next line if you don't want to run IMDB link transformer through comments
		add_filter('comment_text', array(&$this, 'imdb_linking'), 11);

		// add data inside a post
		add_action('the_content', array(&$this, 'imdb_tags_transform'), 11);
		add_action('the_content', array(&$this, 'imdb_tags_transform_id'), 11);

		// add admin menu
		if (isset($imdb_ft)) {
			add_action('admin_menu', array(&$this, 'imdb_admin_panel') );
			add_action('init', array(&$this, 'imdb_addbuttons') );
		}
	
		// register widget
		add_action('plugins_loaded', 'register_imdbwidget');
	}
}

} // end class



### Function: Create Preferences Tables
/*
add_action('activate_imdb-link-transformer/imdb-link-transformer.php', 'create_imdblt_table');
function create_imdblt_table() {
	global $wpdb;
	if(@is_file(ABSPATH.'/wp-admin/upgrade-functions.php')) {
		include_once(ABSPATH.'/wp-admin/upgrade-functions.php');
	} elseif(@is_file(ABSPATH.'/wp-admin/includes/upgrade.php')) {
		include_once(ABSPATH.'/wp-admin/includes/upgrade.php');
	} else {
		die('We have problem finding your \'/wp-admin/upgrade-functions.php\' and \'/wp-admin/includes/upgrade.php\'');
	}
	// Create IMDbLT Table
	$charset_collate = '';
	if($wpdb->supports_collation()) {
		if(!empty($wpdb->charset)) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if(!empty($wpdb->collate)) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}
	}
	$create_table = array();
	$create_table['imdblt'] = "CREATE TABLE $wpdb->imdblt (".
									"id int(10) NOT NULL auto_increment,".
									"category varchar(20) character set utf8 NOT NULL default '',".
									"option varchar(100) character set utf8 NOT NULL default '',".
									"value varchar(200) character set utf8 NOT NULL default '',".
									"PRIMARY KEY (imdblt_id)) $charset_collate;";
	maybe_create_table($wpdb->prepare($wpdb->imdblt), $create_table['imdblt']);
}*/

?>
