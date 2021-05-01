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
 #  Function : Popup people section    					     #
 #									     #
 #############################################################################

//---------------------------------------=[Vars]=----------------

//require_once (dirname(__FILE__).'/../../../../wp-load.php');
require_once (plugin_dir_path( __FILE__ ).'/../bootstrap.php');
require_once (plugin_dir_path( __FILE__ )."/functions.php");  

use \Imdb\Config;

//---------------------------------------=[Vars]=----------------
global $imdb_admin_values, $imdb_widget_values, $imdb_cache_values;

# Initialization of IMDBphp
$config = new Config();
$config->cachedir = $imdb_cache_values['imdbcachedir'] ?? NULL;
$config->photodir = $imdb_cache_values['imdbphotodir'] ?? NULL;
$config->imdb_img_url = $imdb_cache_values['imdbimgdir'] ?? NULL;
$config->photoroot = $imdb_cache_values['imdbphotoroot'] ?? NULL;

if (isset ($_GET["mid"])) {
$pid = filter_var( $_GET["mid"], FILTER_SANITIZE_NUMBER_INT);
$person = new Imdb\Person($pid, $config);


//--------------------------------------=[Layout]=---------------

	//require_once ('popup-header.php'); 
	get_header();  ?>


                                                <!-- top page menu -->
<table class='tabletitrecolonne'>
    <tr>
        <td class='titrecolonne a:hover'>
            <a href="javascript:history.back(-10)"><font size='-2'><?php esc_html_e('Back', 'imdb'); ?></font></a>
        </td>
 		<td class='titrecolonne'>
			<a href='<?php echo IMDBLTURLPATH."inc/"; ?>popup-imdb_person.php?mid=<?php echo $pid; ?>&film=<?php echo $_GET['film']; ?>&info=' title='<?php echo $person->name().": ".esc_html__('Filmography', 'imdb'); ?>'><?php esc_html_e('Filmography', 'imdb'); ?></a>
		</td>
		
		<td class='titrecolonne'>
			<a href='<?php echo IMDBLTURLPATH."inc/"; ?>popup-imdb_person.php?mid=<?php echo $pid ; ?>&film=<?php echo $_GET['film']; ?>&info=bio' title='<?php echo $person->name().": ".esc_html__('Biography', 'imdb'); ?>'><?php esc_html_e('Biography', 'imdb'); ?></a>
		</td>
		
		<td class="titrecolonne">
			<a href='<?php echo IMDBLTURLPATH."inc/"; ?>popup-imdb_person.php?mid=<?php echo $pid ; ?>&film=<?php echo $_GET['film']; ?>&info=divers' title='<?php echo $person->name().": ".esc_html__('Misc', 'imdb'); ?>'><?php esc_html_e('Misc', 'imdb'); ?>
		</td>
		
		<td class='titrecolonne'></td>
   </tr>
</table>

                                                <!-- Photo & identity -->

<table class="TableauPresentation">
    <tr>
        <td colspan="2">
            <div class="identity"><?php echo $person->name(); ?> &nbsp;&nbsp;</div>
            <div class="soustitreidentity">
			<?php  // Born
			  $birthday = $person->born(); 
			  if (!empty($birthday)) {
			  echo "<strong>".esc_html__('Born on', 'imdb')."</strong> ".$birthday["day"]." ".$birthday["month"]." ".$birthday["year"];
			  }
			  if (!empty($birthday["place"])) { echo ", ".esc_html__('in', 'imdb')." ".$birthday["place"];} ?>
			  <?php // Dead
		      $death = $person->died();
			  if (!empty($death)) {
			  echo "<br /><strong>".esc_html__('Died on', 'imdb')."</strong> ".$death["day"]." ".$death["month"]." ".$death["year"];			
			  if (!empty($death["place"])) echo ", ".esc_html__('in', 'imdb')." ".$death["place"];
			  if (!empty($death["cause"])) echo ", ".esc_html__('cause', 'imdb')." ".$death["cause"];
			  }	?>
			</div>
			
            <?php flush (); ?>
        </td>
                                                <!-- displaying photo -->
        <td rowspan=110 class="colpicture">
             <?php if (($photo_url = $person->photo_localurl() ) != FALSE){ 
	            echo '<img class="imdbincluded-picture" src="'.$photo_url.'" alt="'.$person->name().'" '; 
              } else{ 
                echo '<img class="imdbincluded-picture" src="'.$imdb_admin_values[imdbplugindirectory].'pics/no_pics.gif" alt="'.esc_html__('no picture', 'imdb').'" '; 
             } 
	// add width only if "Display only thumbnail" is on "no"
	if ($imdb_admin_values[imdbcoversize] == FALSE){
		echo 'width="'.$imdb_admin_values[imdbcoversizewidth].'px" ';
	}

echo '/ >'; ?>

         </td>
    </tr>
</table>

                                                <!-- under section  -->

<table class="TableauSousRubrique">
	<?php if (empty($_GET['info'])){      // display only when nothing is selected from the menu
	//---------------------------------------------------------------------------start filmography part ?>

                                       <!-- Filmography -->
		<?php $ff = array("director","actor","actress","producer");
		  foreach ($ff as $var) {
			$fdt = "movies_$var";
			$filmo = $person->$fdt();
			$flname = ucfirst($var);
			if (!empty($filmo)) { ?>
			  <tr>
				<td class="TitreSousRubriqueColGauche">
					<div class="TitreSousRubrique"><?php echo $flname;?> filmo</div>
				</td>
			
				<td colspan="2" class="TitreSousRubriqueColDroite">
					<div class="activatehidesection">[+] <?php esc_html_e('click to expand', 'imdb'); ?> [+]</div>
					<div class="hidesection">
			<?php
				$ii = "0";
				$tc = count($filmo);
			  foreach ($filmo as $film) {
			  	$nbfilms = $tc-$ii;
				echo "<li><strong>($nbfilms)</strong> <a href='". IMDBLTURLPATH."inc/"."popup-imdb_movie.php?mid=".$film["mid"]."'>".$film["name"]."</a>";

				if (!empty($film["year"])) {
					echo " (".$film["year"].")";
				} 

				// if (empty($film["chname"])) { 		//-> the result sent is not empty, but a breakline instead
				if ($film["chname"]=="
") {
					echo "";
				} else {
					if (empty($film["chid"])) { 
						if (!empty($film["chname"]))
							echo " as ".$film["chname"];
					} else { 
						echo " as <a href='https://".$person->imdbsite."/character/ch".$film["chid"]."/'>".$film["chname"]."</a>"; }
				}

				echo "</li>\n\t\t";
				$ii++;

			  } //end for each filmo
			} // endif filmo ?>
		    			</div>
		    		</td>
	    	    	</tr>			
		<?php } //endforeach
		flush(); // send to user data already run through ?>



                                       <!-- Filmography as soundtrack -->
		<?php 	$soundtrack=$person->movies_soundtrack() ;
 			if (!empty($soundtrack)) { ?>
				  <tr>
					<td class="TitreSousRubriqueColGauche">
						<div class="TitreSousRubrique"><?php esc_html_e('Soundtrack', 'imdb'); ?> filmo</div>
					</td>		
				<td colspan="2" class="TitreSousRubriqueColDroite">
					<div class="activatehidesection">[+] <?php esc_html_e('click to expand', 'imdb'); ?> [+]</div>
					<div class="hidesection">
						<?php
						for ($i=0;$i<count($soundtrack);++$i) {
							$ii = $i+"1";
							echo "<li><strong>($ii)</strong> ";
							echo "<a href='popup-imdb_movie.php?mid=".$soundtrack[$i]["mid"]."'>".$soundtrack[$i]["name"]."</a>";
							if (!empty($soundtrack[$i]["name"])) 
								echo " (".$soundtrack[$i]["year"].")";
							echo "</li>\n";
						} ?>
		    			</div>
		    		</td>
	    	    	</tr>

	<?php		}



		} //------------------------------------------------------------------------------ end filmo part ?>

     <?php if ($_GET['info'] == 'bio'){ 
            	// ------------------------------------------------------------------------------ partie bio ?>
                                       <!-- Biographie -->

                        				<!-- Biographical movies -->
		<?php $pm = $person->pubmovies();
			  if (!empty($pm)) { ?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique">
					<?php esc_html_e('Biographical movies', 'imdb') ?>
				</div>
 			</td>
			
				<td colspan="2" class="TitreSousRubriqueColDroite">
					<div class="activatehidesection">[+] <?php esc_html_e('click to expand', 'imdb'); ?> [+]</div>
					<div class="hidesection">
			<?php
				for ($i=0;$i<count($pm);++$i) {
					$ii = $i+"1";
					echo "<li><strong>($ii)</strong> ";
					echo "<a href='popup-imdb_movie.php?mid=".$pm[$i]["imdb"]."'>".$pm[$i]["name"]."</a>";
					if (!empty($pm[$i]["year"])) 
						echo " (".$pm[$i]["year"].")";
					echo "</li>\n";
				} ?>
				</div>
           		</td>
        	</tr>
		<?php	} 
		flush(); // send to user data already run through ?>

        <?php $bio = $person->bio(); ?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique"><?php esc_html_e('Biography', 'imdb'); ?>&nbsp;</div>
            </td>
            
            <td colspan="2" class="TitreSousRubriqueColDroite">
		<li>

		<?php // echo preg_replace('/http\:\/\/'.str_replace(".","\.",$person->imdbsite).'\/name\/nm(\d{7})\//','?mid=\\1&engine='.$_GET['engine'],$bio[$idx]["desc"]);		
    if (count($bio)<2) $idx = 0; else $idx = 1;
		echo $bio[$idx]["desc"]; // above's doesn't work, made this one 
 ?>
		</li>
            </td>
        </tr>

</table>
<br />
     <?php } //------------------------------------------------------------------------------ end bio's part ?>

     <?php if ($_GET['info'] == 'divers'){ 
            // ------------------------------------------------------------------------------ misc part ?>
                                       <!-- Misc -->

                           <!-- Trivia -->
		<?php $trivia = $person->trivia();
		if (!empty($trivia)) {
		$tc = count($trivia); ?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique">
					<?php esc_html_e('Trivia', 'imdb'); ?>
				</div>
            </td>
			
				<td colspan="2" class="TitreSousRubriqueColDroite">
					<div class="activatehidesection">[+] <?php esc_html_e('click to expand', 'imdb'); ?> [+]</div>
					<div class="hidesection">
			            
 			<?php 	for ($i=0;$i<$tc;++$i) {
					$ii = $i+"1";
					echo "<li><strong>($ii)</strong> ".$trivia[$i]."</li>\n";
				} ?>
					</div>
				</td>
       			</tr>
		<?php } 
		flush(); // send to user data already run through ?>


                           <!-- Nicknames -->
		<?php $nicks = $person->nickname();
			  if (!empty($nicks)) {?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique"><?php echo esc_html_e('Nicknames', 'imdb') ?></div>
            </td>
			
		<td colspan="2" class="TitreSousRubriqueColDroite">
			<div class="activatehidesection">[+] <?php esc_html_e('click to expand', 'imdb'); ?> [+]</div>
			<div class="hidesection">
			<?php 
			$txt = "";
			$i = "1";
   			foreach ($nicks as $nick) {
				$txt = "<br><li><strong>($i)</strong> ".$nick;
				echo substr($txt,4)."</li>\n";
				$i++;
  			} ?>
			</div>
            	</td>
        </tr>
		<?php } ?>
		
                           <!-- Personal Quotes -->
		<?php $quotes = $person->quotes();
			  if (!empty($quotes)) { 
	  			$tc = count($quotes); ?>
        <tr>
            	<td class="TitreSousRubriqueColGauche">
               		<div class="TitreSousRubrique">
				<?php esc_html_e('Personal quotes', 'imdb') ?>
			</div>
 		</td>
			
		<td colspan="2" class="TitreSousRubriqueColDroite">
			<div class="activatehidesection">[+] <?php esc_html_e('click to expand', 'imdb'); ?> [+]</div>
			<div class="hidesection">
				<?php 
				for ($i=0;$i<$tc;++$i) {
					$ii = $i+"1";
					echo "<li><strong>($ii)</strong> ".$quotes[$i]."</li>\n";
				} ?>
			</div>
           	 </td>
        </tr>
		<?php } 
		flush(); // send to user data already run through ?>


                           <!-- Trademarks -->
		<?php $tm = $person->trademark();
			  if (!empty($tm)) { ?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique">
					<?php esc_html_e('Trademarks', 'imdb') ?>
				</div>
 			</td>
			
		<td colspan="2" class="TitreSousRubriqueColDroite">
			<div class="activatehidesection">[+] <?php esc_html_e('click to expand', 'imdb'); ?> [+]</div>
			<div class="hidesection">
			<?php 
				for ($i=0;$i<count($tm);++$i) {
					$ii = $i+"1";
					echo "<li><strong>($ii)</strong> ".$tm[$i]."</li>\n";
				} ?>
		</div>
            </td>
        </tr>
		<?php } 
		flush(); // send to user data already run through ?>



                           <!-- selffilmo -->
		<?php $ff = array("self");
		  foreach ($ff as $var) {
			$fdt = "movies_$var";
			$filmo = $person->$fdt();
			$flname = ucfirst($var);
			if (!empty($filmo)) { ?>
			  <tr>
				<td class="TitreSousRubriqueColGauche">
					<div class="TitreSousRubrique"><?php echo $flname;?> filmo</div>
				</td>
			
		<td colspan="2" class="TitreSousRubriqueColDroite">
			<div class="activatehidesection">[+] <?php esc_html_e('click to expand', 'imdb'); ?> [+]</div>
			<div class="hidesection">
			<?php
				$ii = "0";
				$tc = count($filmo);
			  foreach ($filmo as $film) {
			  	$nbfilms = $tc-$ii;
				echo "<li><strong>($nbfilms)</strong> <a href='popup-imdb_movie.php?mid=".$film["mid"]."'>".$film["name"]."</a>";
				if (!empty($film["year"])) {
				echo " (".$film["year"].")";
				} 
				if (empty($film["chname"])) echo "";
				else {
				  if (empty($film["chid"])) echo " as ".$film["chname"];
				  else echo " as <a href='http://".$person->imdbsite."/character/ch".$film["chid"]."/'>".$film["chname"]."</a>";
				}
				echo "</li>\n\t\t";
				$ii++;
			  }
			}?>
					</div>
            			</td>
    	    		</tr>			
			<?php }?>			


		  
     <?php } //------------------------------------------------------------------------------ end misc part ?>		   
</table>
<br />
<?php 	// call wordpress footer functions;
	wp_meta();
	//get_footer(); // this one gets too much uneeded information
	wp_footer(); 
?>
</body>
</html>
<?php 	exit(); // quit the call of the page, to avoid double loading process ?>

<?php
	} else { // escape if no result found, otherwise imdblt fails
		imdblt_noresults_text();
}
?>
