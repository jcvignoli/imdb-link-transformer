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
 #  Function : Popup people section    					     #
 #									     #
 #############################################################################

//---------------------------------------=[Vars]=----------------

require_once (dirname(__FILE__).'/../../../../wp-blog-header.php');
require_once ("functions.php"); 

if ($_GET["mid"]) {

## choix de imdb ou de pilot :
if ($imdb_admin_values['imdbsourceout']) 
	$engine = 'pilot';

switch($engine) {
	case "pilot":
	  require_once(dirname(__FILE__)."/../class/pilot_person.class.php");
	  $person = new pilot_person ($_GET["mid"]);
	  break;
	default:
	  require_once(dirname(__FILE__)."/../class/imdb_person.class.php");
	  $person = new imdb_person ($_GET["mid"]);
	  break;
}



  $pid = $_GET["mid"];
  $person->setid ($pid);

//--------------------------------------=[Layout]=---------------

		require_once ('popup-header.php'); ?>


                                                <!-- top page menu -->
<table class='tabletitrecolonne'>
    <tr>
        <td class='titrecolonne a:hover'>
            <a href="javascript:history.back(-10)"><font size='-2'><?php _e('Back', 'imdb'); ?></font></a>
        </td>
 		<td class='titrecolonne'>
			<a href='?mid=<?php echo $pid; ?>&film=<?php echo $_GET['film']; ?>&info=' title='<?php echo $person->name().": ".__('Filmography', 'imdb'); ?>'><?php _e('Filmography', 'imdb'); ?></a>
		</td>
		
		<td class='titrecolonne'>
			<a href='?mid=<?php echo $pid ; ?>&film=<?php echo $_GET['film']; ?>&info=bio' title='<?php echo $person->name().": ".__('Biography', 'imdb'); ?>'><?php _e('Biography', 'imdb'); ?></a>
		</td>
		
		<td class="titrecolonne">
			<a href='?mid=<?php echo $pid ; ?>&info=divers&film=<?php echo $_GET['film']; ?>' title='<?php echo $person->name().": ".__('Misc', 'imdb'); ?>'><?php _e('Misc', 'imdb'); ?>
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
			  echo "<strong>".__('Born on', 'imdb')."</strong> ".$birthday["day"]." ".$birthday["month"]." ".$birthday["year"];
			  }
			  if (!empty($birthday["place"])) { echo ", ".__('in', 'imdb')." ".$birthday["place"];} ?>
			  <?php // Dead
		      $death = $person->died();
			  if (!empty($death)) {
			  echo "<br /><strong>".__('Died on', 'imdb')."</strong> ".$death["day"]." ".$death["month"]." ".$death["year"];			
			  if (!empty($death["place"])) echo ", ".__('in', 'imdb')." ".$death["place"];
			  if (!empty($death["cause"])) echo ", ".__('cause', 'imdb')." ".$death["cause"];
			  }	?>
			</div>
			
            <?php flush (); ?>
        </td>
                                                <!-- displaying photo -->
        <td rowspan=110 class="colpicture">
             <?php if (($photo_url = $person->photo_localurl() ) != FALSE){ 
	            echo '<img class="imdbincluded-picture" src="'.$photo_url.'" alt="'.$person->name().'" '; 
              } else{ 
                echo '<img class="imdbincluded-picture" src="'.$imdb_admin_values[imdbplugindirectory].'pics/no_pics.gif" alt="'.__('no picture', 'imdb').'" '; 
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
			
				<td colspan="2" class="TitreSousRubriqueColDroite"><a href="javascript:toggleLayer('<?php echo $var;?>filmofield');" >[+] <?php _e('click to expand', 'imdb'); ?> [+]</a></td>
			</tr>
			<tr>
				<td></td>

				<td colspan="2" id="<?php echo $var;?>filmofield" class="TitreSousRubriqueColDroite">
			<?php
				$ii = "0";
				$tc = count($filmo);
			  foreach ($filmo as $film) {
			  	$nbfilms = $tc-$ii;
				echo "<li><strong>($nbfilms)</strong> <a href='popup-imdb_movie.php?mid=".$film["mid"]."'>".$film["name"]."</a>";

				if (!empty($film["year"])) {
					echo " (".$film["year"].")";
				} 

				// if (empty($film["chname"])) { 		//-> the result sent is not empty, but a breakline instead
				if ($film["chname"]=="
") {
					echo "";
				} else {
					if (empty($film["chid"])) { 
						echo " as ".$film["chname"];
					} else { 
						echo " as <a href='http://".$person->imdbsite."/character/ch".$film["chid"]."/'>".$film["chname"]."</a>"; }
				}

				echo "</li>\n\t\t";
				$ii++;

			  } //end for each filmo
			} // endif filmo ?>
		    	</td>
	    	    	</tr>			
		<?php } //endforeach
		flush(); // send to user data already run through ?>



                                       <!-- Filmography as soundtrack -->
		<?php 	$soundtrack=$person->movies_soundtrack() ;
 			if (!empty($soundtrack)) { ?>
				  <tr>
					<td class="TitreSousRubriqueColGauche">
						<div class="TitreSousRubrique"><?php _e('Soundtrack', 'imdb'); ?> filmo</div>
					</td>		
					<td colspan="2" class="TitreSousRubriqueColDroite"><a href="javascript:toggleLayer('soundtrackfield');" >[+] <?php _e('click to expand', 'imdb'); ?> [+]</a></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="2" id="soundtrackfield" class="TitreSousRubriqueColDroite">
						<?php
						for ($i=0;$i<count($soundtrack);++$i) {
							$ii = $i+"1";
							echo "<li><strong>($ii)</strong> ";
							echo "<a href='popup-imdb_movie.php?mid=".$soundtrack[$i]["mid"]."'>".$soundtrack[$i]["name"]."</a>";
							if (!empty($soundtrack[$i]["name"])) 
								echo " (".$soundtrack[$i]["year"].")";
							echo "</li>\n";
						} ?>
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
					<?php _e('Biographical movies', 'imdb') ?>
				</div>
 			</td>
			
			<td colspan="2" class="TitreSousRubriqueColDroite"><a href="javascript:toggleLayer('biographicalfield');" >[+] <?php _e('click to expand', 'imdb'); ?> [+]</a></td>
		</tr>
		<tr>
			<td></td>

			<td colspan="2" id="biographicalfield" class="TitreSousRubriqueColDroite">
			<?php
				for ($i=0;$i<count($pm);++$i) {
					$ii = $i+"1";
					echo "<li><strong>($ii)</strong> ";
					echo "<a href='popup-imdb_movie.php?mid=".$pm[$i]["imdb"]."'>".$pm[$i]["name"]."</a>";
					if (!empty($pm[$i]["year"])) 
						echo " (".$pm[$i]["year"].")";
					echo "</li>\n";
				} ?>
           		</td>
        	</tr>
		<?php	} 
		flush(); // send to user data already run through ?>

        <?php $bio = $person->bio(); ?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique"><?php _e('Biography', 'imdb'); ?>&nbsp;</div>
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
					<?php _e('Trivia', 'imdb'); ?>
				</div>
            </td>
			
			<td colspan="2" class="TitreSousRubriqueColDroite"><a href="javascript:toggleLayer('triviafield');" >[+] <?php _e('click to expand', 'imdb'); ?> [+]</a></td>
		</tr>
		<tr>
			<td></td>

			<td colspan="2" id="triviafield" class="TitreSousRubriqueColDroite">
			            
 			<?php 	for ($i=0;$i<$tc;++$i) {
					$ii = $i+"1";
					echo "<li><strong>($ii)</strong> ".$trivia[$i]."</li>\n";
				} ?>
		    </td>
        </tr>
		<?php } 
		flush(); // send to user data already run through ?>


                           <!-- Nicknames -->
		<?php $nicks = $person->nickname();
			  if (!empty($nicks)) {?>
        <tr>
            <td class="TitreSousRubriqueColGauche">
                <div class="TitreSousRubrique"><?php echo _e('Nicknames', 'imdb') ?></div>
            </td>
			
			<td colspan="2" class="TitreSousRubriqueColDroite"><a href="javascript:toggleLayer('nicknamesfield');" >[+] <?php _e('click to expand', 'imdb'); ?> [+]</a></td>			
		</tr>
		<tr>
			<td></td>

			<td colspan="2" id="nicknamesfield" class="TitreSousRubriqueColDroite">
			<?php 
			$txt = "";
			$i = "1";
   			foreach ($nicks as $nick) {
				$txt = "<br><li><strong>($i)</strong> ".$nick;
				echo substr($txt,4)."</li>\n";
				$i++;
  			} ?>
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
					<?php _e('Personal quotes', 'imdb') ?>
				</div>
 			</td>
			
			<td colspan="2" class="TitreSousRubriqueColDroite"><a href="javascript:toggleLayer('quotesfield');" >[+] <?php _e('click to expand', 'imdb'); ?> [+]</a></td>
		</tr>
		<tr>
			<td></td>

			<td colspan="2" id="quotesfield" class="TitreSousRubriqueColDroite">
			<?php 
				for ($i=0;$i<$tc;++$i) {
					$ii = $i+"1";
					echo "<li><strong>($ii)</strong> ".$quotes[$i]."</li>\n";
				} ?>
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
					<?php _e('Trademarks', 'imdb') ?>
				</div>
 			</td>
			
			<td colspan="2" class="TitreSousRubriqueColDroite"><a href="javascript:toggleLayer('tradmarksfield');" >[+] <?php _e('click to expand', 'imdb'); ?> [+]</a></td>
		</tr>
		<tr>
			<td></td>

			<td colspan="2" id="tradmarksfield" class="TitreSousRubriqueColDroite">
			<?php 
				for ($i=0;$i<count($tm);++$i) {
					$ii = $i+"1";
					echo "<li><strong>($ii)</strong> ".$tm[$i]."</li>\n";
				} ?>
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
			
				<td colspan="2" class="TitreSousRubriqueColDroite"><a href="javascript:toggleLayer('selffilmofield');" >[+] <?php _e('click to expand', 'imdb'); ?> [+]</a></td>
			</tr>
			<tr>
				<td></td>

				<td colspan="2" id="selffilmofield" class="TitreSousRubriqueColDroite">
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
            			</td>
    	    		</tr>			
			<?php }?>			


		  
     <?php } //------------------------------------------------------------------------------ end misc part ?>		  
		  
</table>
<br />


</body>
</html>

<?php
} else { // escape if no result found, otherwise imdblt fails
	imdblt_noresults_text();
}
?>
