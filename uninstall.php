<?php

 #############################################################################
 # IMDb Link transformer                                                     #
 # written by Prometheus group                                               #
 # http://www.ikiru.ch/blog                                                  #
 # ------------------------------------------------------------------------- #
 # This program is free software; you can redistribute and/or modify it      #
 # under the terms of the GNU General Public License (see LICENSE)           #
 # ------------------------------------------------------------------------- #
 #       			                                             #
 #  Function : Uninstall completely IMDb LT when deleting the plugin	     #
 #       	  			                                     #
 #############################################################################


if(!defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) 
	exit();

	delete_option( 'imdbAdminOptions' ); 
	delete_option( 'imdbWidgetOptions' );
	delete_option( 'imdbCacheOptions' );
	
	$terms = get_terms( 'genre'); ##delete all terms added for genre
	foreach ( $terms as $term ) {
		wp_delete_term( $term->term_id, 'genre' ); 
	}

	echo "IMDbLT options deleted.";

?>
