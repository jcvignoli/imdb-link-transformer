<?php

 #############################################################################
 # IMDb Link transformer                                                     #
 # written by Prometheus group                                               #
 # http://www.ikiru.ch/blog                                                  #
 # ------------------------------------------------------------------------- #
 # This program is free software; you can redistribute and/or modify it      #
 # under the terms of the GNU General Public License (see LICENSE)           #
 # ------------------------------------------------------------------------- #
 #									     #
 #  Function : popup's header                                                #
 #									     #
 #############################################################################

//---------------------------------------=[Vars]=----------------

$currentpage = parse_url($_SERVER['PHP_SELF']);
$currentpage = explode('/', $currentpage['path']);
$howmany = count($currentpage)-"1";
$currentpage = $currentpage[$howmany];

if (empty ($_SERVER[HTTP_REFERER]) ) { // does not allow to call popup without a refer, an usual search engine behaviour
	header( "HTTP/1.1 404 Not Found", false, 404); header("Status : 404 Not Found"); exit; }

//--------------------------------------=[Layout]=---------------

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/1">

	<?php // Different pages, different titles ?>
	<?php if ($currentpage == "popup-imdb_person.php" ) { ?>
		<title><?php bloginfo('name'); ?> <?php echo ' - '.$person->name(); 
			if (!empty($_GET['info'])) 
			 echo ' - '.$_GET['info']; ?>
		 </title>
	<?php } elseif ($currentpage == "popup-imdb_movie.php" ) { ?>
		<title><?php bloginfo('name'); ?> <?php echo ' - '.$movie->title().' '.$movie->year();
			if (!empty($_GET['info'])) 
			 echo ' - '.$_GET['info'];?>
		 </title>	
	<?php } else { ?>
		<title><?php bloginfo('name'); ?><?php echo " - ".__('Search', 'imdb')." \"".$_GET["film"]."\""; ?></title>
	<?php } ?>

	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<meta name="robots" content="INDEX,FOLLOW" />
	<meta name="author" content="" />
	<meta name="keywords" lang="en" content="imdb, internet, movie, database, cinema, movie, wordpress, plugin, <?php echo $_GET["film"]; ?><?php if (isset($person)) echo ", ".$person->name(); ?>" />
	<meta name="description" lang="en" content="This page include the data related to <?php echo $_GET["film"]; ?>" />
	<meta name="active" content="" />
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />	
	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="application/rss+xml" title="Comments RSS 2.0" href="<?php bloginfo('comments_rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS 0.92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />


	<link rel="stylesheet" type="text/css" href="<?php echo IMDBLTURLPATH; ?>css/imdb.css">
	<link rel="shortcut icon" href="<?php echo IMDBLTURLPATH; ?>pics/favico.ico" />

	<script src="<?php echo IMDBLTURLPATH; ?>js/hide-show.js"></script>	
	
	
</head>

<body onload="toggleLayer('commentform');" class="imdbpopup">

<?php
//----- 
//----- Please wait message
//----- 
// Layout
echo "<div id=\"loading\">".__('Please wait while data is retrieved... <br />Process can take some time.', 'imdb')."</div>";

// script
echo "<script>document.getElementById(\"loading\").style.display = 'none';</script>";
?> 
