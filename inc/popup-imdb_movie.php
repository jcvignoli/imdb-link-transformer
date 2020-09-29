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
 #  Function : Popup movie section    					     #
 #									     #
 #############################################################################

//---------------------------------------=[Vars]=----------------

require_once (dirname(__FILE__).'/../../../../wp-blog-header.php');
require_once ("functions.php"); 

## toute la partie, avec le choix de imdb ou de pilot :
if ($imdb_admin_values['imdbsourceout']) 
	$engine = 'pilot';

switch($engine) {
	case "pilot":
	  require_once(dirname(__FILE__)."/../class/pilotsearch.class.php");
	  require_once(dirname(__FILE__)."/../class/pilot.class.php");
	  $search = new pilotsearch();
	  break;
	default:
	  require_once(dirname(__FILE__)."/../class/imdbsearch.class.php");
	  require_once(dirname(__FILE__)."/../class/imdb.class.php");
	  $search = new imdbsearch();
	  break;
}

if ($engine=="pilot") $movie = new pilot ($_GET["mid"]);
	else $movie = new imdb ($_GET["mid"]);

if (isset ($_GET["mid"])) {
    $movieid = $_GET["mid"];
    $movie->setid ($movieid);
    // $imdblt = new imdblt; // class from imdb-link-transformer.php -> to activate when class will be used
//--------------------------------------=[Layout]=---------------

		require_once ('popup-header.php'); ?>
                                                <!-- top page menu -->
<table class='tabletitrecolonne'>
    <tr>
        <td class='titrecolonne a:hover'>
            <a href='popup.php?film=<?php echo imdb_htmlize($movie->title()); ?>&norecursive=yes' title="<?php _e('Search for movies with the same name', 'imdb'); ?>"><font size='-2'><?php _e('Search AKAs', 'imdb'); ?></font></a>
        </td>
        <td class='titrecolonne'>
			<a href='popup-imdb_movie.php?mid=<?php echo $movieid; ?>&film=<?php echo $_GET['film']; ?>&info=' title='<?php echo $movie->title().": ".__('Movie', 'imdb'); ?>'><?php _e('Movie', 'imdb'); ?></a>
		</td>
        <td class='titrecolonne'>
			<a href='popup-imdb_movie.php?mid=<?php echo $movieid; ?>&film=<?php echo $_GET['film']; ?>&info=actors' title='<?php echo $movie->title().": ".__('Actors', 'imdb'); ?>'><?php _e('Actors', 'imdb'); ?></a>
		</td>
        <td class='titrecolonne'>
			<a href='popup-imdb_movie.php?mid=<?php echo $movieid; ?>&film=<?php echo $_GET['film']; ?>&info=crew' title='<?php echo $movie->title().": ".__('Crew', 'imdb'); ?>'><?php _e('Crew', 'imdb'); ?></a>
		</td>
        <td class='titrecolonne'>
			<a href='popup-imdb_movie.php?mid=<?php echo $movieid; ?>&film=<?php echo $_GET['film']; ?>&info=resume' title='<?php echo $movie->title().": ".__('Plot', 'imdb'); ?>'><?php _e('Plot', 'imdb'); ?></a>
		</td>
        <td class='titrecolonne'>
			<a href='popup-imdb_movie.php?mid=<?php echo $movieid; ?>&film=<?php echo $_GET['film']; ?>&info=divers' title='<?php echo $movie->title().": ".__('Misc', 'imdb'); ?>'><?php _e('Misc', 'imdb'); ?></a>
		</td>
    </tr>
</table>

<table class="TableauPresentation" width="100%">
    <tr width="100%">
        <td colspan="2">
            <div class="titrefilm"><?php echo $movie->title(); ?> &nbsp;&nbsp;(<?php echo $movie->year (); ?>)</div>
            <div class="soustitrefilm"><?php echo $movie->tagline(); ?> </div>
            <?php flush (); ?>
        </td>
                                                <!-- Movie's picture display -->
        <td class="colpicture">
	 <?php 	## The picture is either taken from the movie itself or if it doesn't exist, from a standard "no exist" picture.
		## The width value is taken from plugin settings, and added if the "thumbnail" option is unactivated
echo '<img class="imdbincluded-picture" src="';

	if (($photo_url = $movie->photo_localurl() ) != FALSE){ 
		echo $photo_url.'" alt="'.$movie->title().'" '; 
	} else { 
		echo $imdb_admin_values[imdbplugindirectory].'pics/no_pics.gif" alt="'.__('no picture', 'imdb').'" '; 
	}

	// add width only if "Display only thumbnail" is on "no"
	if ($imdb_admin_values[imdbcoversize] == FALSE){
		echo 'width="'.$imdb_admin_values[imdbcoversizewidth].'px" ';
	}

echo '/ >'; ?>

         </td>
    </tr>
</table>
  
 
<table class="TableauSousRubrique">

<?php if (empty($_GET['info'])){      // display something when nothing has been selected in the menu
         //---------------------------------------------------------------------------introduction part start ?>
     
                                                <!-- Title akas -->         
     <tr> 
         <td class="TitreSousRubriqueColGauche">
            <div class="TitreSousRubrique"><?php _e('AKA', 'imdb'); ?>&nbsp;</div>
         </td>
         <td colspan="2" class="TitreSousRubriqueColDroite">
		 	<li>
		 <?php    	$aka = $movie->alsoknow();
				  	$cc  = count($aka);
				  	if (!empty($aka)) {
				    	foreach ( $aka as $ak){
      					echo $ak["title"];
      						if (!empty($ak["year"])) {
								echo " ".$ak["year"];
							};
      						if (!empty($ak["country"])) {
      							echo  " (".$ak["country"].")";
							}
      							/*if (empty($ak["lang"])) { 
									if (!empty($ak["comment"])) {
									echo ", ".$ak["comment"]; }
      							} else {
        							if (!empty($ak["comment"])) {
									echo ", ".$ak["comment"];}
        						echo " [".$ak["lang"]."]";
					  			}*/
					  		echo "<br />";
						}
					flush();
				  	}  ?>
			</li>
         </td>
     </tr>
                                                <!-- Year -->
     <tr>
        <td class="TitreSousRubriqueColGauche">
            <div class="TitreSousRubrique"><?php _e('Year', 'imdb'); ?>&nbsp;</div>
        </td>
        <td colspan="2" class="TitreSousRubriqueColDroite">
             <li><?php echo $movie->year(); ?></li>
        </td>
     </tr>
                                                <!-- Runtime -->
     <tr>
        <td class="TitreSousRubriqueColGauche">
            <div class="TitreSousRubrique"><?php _e('Runtime', 'imdb'); ?>&nbsp;</div>
         </td>

        
        <td colspan="2" class="TitreSousRubriqueColDroite">
			<?php $runtime = $movie->runtime();
			if (!empty($runtime)) { ?>
            <li><?php echo $runtime." ".__('minutes', 'imdb'); ?></li>
		    <?php }; 
			flush(); // send to user data already run through ?>
        </td>
     </tr>
     
     <?php if ($movie->votes()) { ?>              <!-- Rating and votes -->
     <tr>
        <td class="TitreSousRubriqueColGauche">
           <div class="TitreSousRubrique"><?php _e('Rating', 'imdb'); ?>&nbsp;</div>
        </td>
        
        <td colspan="2" class="TitreSousRubriqueColDroite">
            <li><?php echo $movie->votes(); ?> <?php _e('Vote average', 'imdb'); ?> <?php echo $movie->rating(); ?></li>
        </td>
     </tr>
     <?php }; ?>
     
                                                <!-- Language -->
	<?php   $languages = $movie->languages();
	if (!empty($languages)) { ?>
     <tr>
        <td class="TitreSousRubriqueColGauche">
            <div class="TitreSousRubrique"><?php echo(sprintf(_n('Language', 'Languages', count($languages), 'imdb'))); ?>&nbsp;</div>
        </td>
        
        <td colspan="2" class="TitreSousRubriqueColDroite">
            <li>
			<?php for ($i = 0; $i + 1 < count($languages); $i++) {
			      echo $languages[$i].', ';
				    }
			    echo $languages[$i]; ?>
			</li>
        </td>
     </tr>
     <?php }; 
	flush(); // send to user data already run through ?>
             
			                                    <!-- Country -->
	<?php $country = $movie->country();
	if (!empty($country)) { ?>
     <tr>
        <td class="TitreSousRubriqueColGauche">
            <div class="TitreSousRubrique"><?php echo(sprintf(_n('Country', 'Countries', count($country), 'imdb'))); ?>&nbsp;</div>
        </td>
        
        <td colspan="2" class="TitreSousRubriqueColDroite">
            <li><?php
                    for ($i = 0; $i + 1 < count ($country); $i++) {
	                echo $country[$i];
	                echo ", ";
                    }
                    echo $country[$i]; 
            ?></li>
        </td>
     </tr>
     <?php }; ?>

                                                <!-- All Genres -->
     <tr>
        <td class="TitreSousRubriqueColGauche">
            <div class="TitreSousRubrique"><?php _e('Genre', 'imdb'); ?>&nbsp;</div>
        </td>
        
        <td colspan="2" class="TitreSousRubriqueColDroite">
            <li><?php $test = $movie->genre ();  
			if (! empty($test)) {
			$gen = $movie->genres ();
			
                        for ($i = 0; $i + 1 < count ($gen); $i++) {
	                    echo $gen[$i];
	                    echo ", ";
                        }
            echo $gen[$i];
			}
			flush(); // send to user data already run through  ?>
			</li>
        </td>
     </tr>
                                                <!-- Colors -->
     <tr>
        <td class="TitreSousRubriqueColGauche">
            <div class="TitreSousRubrique"><?php _e('Color', 'imdb'); ?>&nbsp;</div>
        </td>
        
        <td colspan="2" class="TitreSousRubriqueColDroite">
            <li><?php   $col = $movie->colors ();
                      for ($i = 0; $i + 1 < count ($col); $i++) {
	                  echo $col[$i];
	                  echo ", ";
                      }
                       echo $col[$i];
            ?></li>
        </td>
     </tr>
                                                <!-- Sound -->
     <tr>
        <td class="TitreSousRubriqueColGauche">
            <div class="TitreSousRubrique"><?php _e('Sound', 'imdb'); ?>&nbsp;</div>
        </td>
        
        <td colspan="2" class="TitreSousRubriqueColDroite">
            <li><?php   $sound = $movie->sound ();
                        for ($i = 0; $i + 1 < count ($sound); $i++) {
	                    echo $sound[$i];
	                    echo ", ";
                        }
            echo $sound[$i];
            ?></li>
        </td>
     </tr>

<?php } //------------------------------------------------------------------------------ introduction part end ?>


<?php  if ($_GET['info'] == 'actors'){ 
            // ------------------------------------------------------------------------------ casting part start ?>

                                                <!-- casting --> 
        <?php $cast = $movie->cast(); 
			if (!empty($cast)) { ?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique"><?php _e('Casting', 'imdb'); ?>&nbsp;</div>
            </td>
            
            <td colspan="2" class="TitreSousRubriqueColDroite">
                <?php for ($i = 0; $i < count ($cast); $i++) { ?>
					<li>
						<div align="center" class="imdbdiv-liees">
							<div style="float:left">
								<?php echo $cast[$i]["role"]; ?>
							</div>
							<div align="right">
								<a href="popup-imdb_person.php?mid=<?php echo $cast[$i]["imdb"]; ?>" title='<?php _e('link to imdb', 'imdb'); ?>'>
								<?php echo $cast[$i]["name"]; ?></a>
							</div>
						</div>
					</li>
                <?php }; // endfor ?>
            </td>
        </tr>
        <?php }; ?>		
		
<?php } // ------------------------------------------------------------------------------ casting part end ?>

<?php if ($_GET['info'] == 'crew'){ 
            // ------------------------------------------------------------------------------ crew part start ?>

                                                <!-- director -->
        <?php $director = $movie->director(); 
		  if (!empty($director)) {?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique"><?php echo(sprintf(_n('Director', 'Directors', count($director), 'imdb'))); ?>&nbsp;</div>
            </td>
            
            <td colspan="2" class="TitreSousRubriqueColDroite">
                <?php for ($i = 0; $i < count ($director); $i++) { ?>
					<li>
						<div align="center">
							<div style="float:left">
								<?php if ( $i > 0 ) echo ', '; ?>
								<a href="popup-imdb_person.php?mid=<?php echo $director[$i]["imdb"] ?>" title='<?php _e('link to imdb', 'imdb'); ?>'>
								<?php echo $director[$i]["name"]; ?></a>
							</div>
							<div align="right">
								<?php echo $director[$i]["role"]; ?>
							</div>
						</div>
					</li>
                <?php }; // endfor ?>
			<br /><br />
            </td>
        </tr>
        <?php }; 
		flush(); // send to user data already run through ?>	
                                                <!-- Writer -->
        <?php $write = $movie->writing(); 
		  if (!empty($write)) {?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique"><?php echo(sprintf(_n('Writer', 'Writers', count($write), 'imdb'))); ?>&nbsp;</div>
            </td>
            
            <td colspan="2" class="TitreSousRubriqueColDroite">
                <?php  for ($i = 0; $i < count ($write); $i++) {  ?>
					<li>
						<div align="center" class="imdbdiv-liees">
							<div style="float:left">
        	                    <a href="popup-imdb_person.php?mid=<?php echo $write[$i]["imdb"] ?>" title='<?php _e('link to imdb', 'imdb'); ?>'>
								<?php echo $write[$i]["name"]; ?></a>
							</div>
							<div align="right">
	                            <?php echo $write[$i]["role"] ?>
							</div>
						</div>
					</li>
                <?php }; // endfor ?>
			<br />
            </td>
        </tr>
        <?php }; 
		flush(); // send to user data already run through ?>	
		
                                                <!-- producer -->
        <?php $produce = $movie->producer(); 
		if (!empty($produce)) { ?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique"><?php echo(sprintf(_n('Producer', 'Producers', count($produce), 'imdb'))); ?>&nbsp;</div>
            </td>
            
            <td colspan="2" class="TitreSousRubriqueColDroite">
                <?php  for ($i = 0; $i < count ($produce); $i++) {  ?>
					<li>
						<div align="center" class="imdbdiv-liees">
							<div style="float:left">
                            	<a href="popup-imdb_person.php?mid=<?php echo $produce[$i]["imdb"] ?>" title='<?php _e('link to imdb', 'imdb'); ?>'>
                            	<?php echo $produce[$i]["name"]; ?></a>
							</div>
							<div align="right">
								<?php echo $produce[$i]["role"] ?>
							</div>
						</div>
					</li>
                <?php }; // endfor ?>
            </td>
        </tr>
		<?php }; ?>
		
		
<?php } //----------------------------------------------------------------------------- crew part end ?>

     
<?php  if ($_GET['info'] == 'resume'){ 
            // ------------------------------------------------------------------------------ resume part start ?>

                                                <!-- resume short --> 
        <?php $plotoutline = $movie->plotoutline();
				if (!empty($plotoutline)) { ?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique"><?php echo(sprintf(_n('Plot outline', 'Plots outline', count($plotoutline), 'imdb'))); ?>&nbsp;</div>
            </td>
            
            <td colspan="2" class="TitreSousRubriqueColDroite">
				<li><?php echo $plotoutline; ?><br /><br /></li>
            </td>
        </tr>
    	 <?php 	} ?>

                                                <!-- resume long --> 
        <?php $plot = $movie->plot (); 
			if (!empty($plot)) { ?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique"><?php echo(sprintf(_n('Plot', 'Plots', count($plot), 'imdb'))); ?>&nbsp;&nbsp;</div>
            </td>
            
            <td colspan="2" class="TitreSousRubriqueColDroite">
				<li>
				<?php for ($i = 1; $i < count ($plot); $i++) {
                            echo "<strong>($i)</strong>".$plot[$i]."<br /><br />"; 
				};?>
				</li>
            </td>
        </tr>
    	 <?php 	} ?>
	 
<?php	 } // ------------------------------------------------------------------------------ resume part end ?>


<?php 	if ($_GET['info'] == 'divers'){ 
            // ------------------------------------------------------------------------------ misc part start ?>

                                                <!-- Trivia --> 
		 <?php $trivia = $movie->trivia();
		  $gc = count($trivia);
		  if ($gc > 0) { ?>
	        <tr>
				<td class="TitreSousRubriqueColGauche">
					<div class="TitreSousRubrique"><?php echo(sprintf(_n('Trivia', 'Trivias', count($trivia), 'imdb'))); ?>&nbsp;</div>
				</td>
				<td colspan="2" class="TitreSousRubriqueColDroite">
					<a href="javascript:toggleLayer('triviafieldmovie')" >[+] <?php _e('click to expand', 'imdb'); ?> [+]</a>
				</td>
			</tr>
			<tr>
				<td></td>

				<td colspan="2" id="triviafieldmovie" class="TitreSousRubriqueColDroite">
			<?php		
			for ($i=0;$i<$gc;++$i) {
     			 if (empty($trivia[$i])) break;
				 $ii = $i+"1";
				 echo "<li><strong>($ii)</strong>".preg_replace("/http\:\/\/".str_replace(".","\.",$movie->imdbsite)."\/name\/nm(\d{7})\//","popup-imdb_person.php?mid=\\1",$trivia[$i])."</li>\n<br />";
		    }; ?>
			<br />
            	</td>
    	    </tr>	
    	<?php } ?>


                                                <!-- Soundtrack -->

		<?php $soundtracks = $movie->soundtrack();
			  $gc = count($soundtracks);
			  if ($gc > 0) { ?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique">
					<?php echo(sprintf(_n('Soundtrack', 'Soundtracks', count($soundtracks), 'imdb'))); ?> 
				</div>
            </td>

				<td colspan="2" class="TitreSousRubriqueColDroite">
					<a href="javascript:toggleLayer('soundtrackfield')">[+] <?php _e('click to expand', 'imdb'); ?> [+]</a>
				</td>
			</tr>
			<tr>
				<td></td>

				<td colspan="2" id="soundtrackfield" class="TitreSousRubriqueColDroite">			            
	 			<?php for ($i=0;$i<$gc;++$i) {
						$ii = $i+"1";
							if (empty($soundtracks[$i])) break;
						$credit1 = preg_replace("/http\:\/\/".str_replace(".","\.",$movie->imdbsite)."\/name\/nm(\d{7})\//","popup-imdb_person.php?mid=\\1",$soundtracks[$i]["credits"][0]);
						$credit2 = preg_replace("/http\:\/\/".str_replace(".","\.",$movie->imdbsite)."\/name\/nm(\d{7})\//","popup-imdb_person.php?mid=\\1",$soundtracks[$i]["credits"][1]);
						echo "<li><strong>($ii)</strong> ".$soundtracks[$i]["soundtrack"]." ".$credit1." ".$credit2."</li><br />";
    				} 
					flush(); // send to user data already run through ?>
		    </td>
        </tr>
		<?php } ?>

                                                <!-- Goofs --> 
		 <?php $goofs = $movie->goofs();
		  $gc    = count($goofs);
		  if ($gc > 0) { ?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique"><?php echo(sprintf(_n('Goof', 'Goofs', count($goofs), 'imdb'))); ?>&nbsp;</div>
            </td>
            	<td colspan="2" class="TitreSousRubriqueColDroite">
					<a href="javascript:toggleLayer('goofsfield')">[+] <?php _e('click to expand', 'imdb'); ?> [+]</a>
				</td>
			</tr>
			<tr>
				<td></td>

				<td colspan="2" id="goofsfield" class="TitreSousRubriqueColDroite">			            			  
				<?php		
				for ($i=0;$i<$gc;++$i) {
					 if (empty($goofs[$i])) break;
					 $ii = $i+"1";
				echo "<li><strong>($ii) ".$goofs[$i]["type"]."</strong> ".$goofs[$i]["content"]."</li><br />";
				}; ?>
            </td>
        </tr>
    	<?php } ?>

<?php	 } // ------------------------------------------------------------------------------ misc part end ?>
     


</table>
<br />
<?php }; ?>

</body>
</html>
