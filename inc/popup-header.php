<?php

 #############################################################################
 # IMDb Link transformer                                                     #
 # written by Prometheus group                                               #
 # https://www.jcvignoli.com/blog                                            #
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

//if (empty ($_SERVER[HTTP_REFERER]) ) { // does not allow to call popup without a refer, an usual search engine behaviour
//	header( "HTTP/1.1 404 Not Found", false, 404); header("Status : 404 Not Found"); exit; }

//--------------------------------------=[Layout]=---------------

?><!DOCTYPE html>
<html>
<head>
<?php 	// Different pages, different titles 
if ($currentpage == "popup-imdb_person.php" ) {
?>	<title><?php bloginfo('name'); ?> <?php echo ' - '.$person->name(); 
			if (!empty($_GET['info'])) 
			 echo ' - '.$_GET['info']; ?>
		 </title>
	<?php } elseif ($currentpage == "popup-imdb_movie.php" ) { 
?>	<title><?php bloginfo('name'); ?> <?php echo ' - '.$movie->title().' '.$movie->year();
			if (!empty($_GET['info'])) 
			 echo ' - '.$_GET['info'];?>
		 </title>	
	<?php } else { 
?>	<title><?php bloginfo('name'); ?><?php echo " - ".__('Search', 'imdb')." \"".$_GET["film"]."\""; ?></title>
	<?php } ?>

	<meta name="keywords" lang="en" content="imdb, internet, movie, database, cinema, movie, wordpress, plugin, <?php echo $_GET["film"]; ?><?php if (isset($person)) echo ", ".$person->name(); ?>" />
	<meta name="description" lang="en" content="This page include the data related to <?php echo $_GET["film"]; ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" type="text/css" href="<?php echo IMDBLTURLPATH; ?>css/imdb.css">
	<link rel="shortcut icon" href="<?php echo IMDBLTURLPATH; ?>pics/favico.ico" />

	<script src="<?php echo IMDBLTURLPATH; ?>js/jquery.min.js"></script>
	<?php wp_enqueue_script('jquery'); // marche pas, faire marcher?>

	<style>
		.hidesection{display:none;}
		.activatehidesection{cursor:pointer;}
	</style>

</head>

<body class="imdbpopup">

