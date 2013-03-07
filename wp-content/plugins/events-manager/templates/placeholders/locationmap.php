<?php 
/*
 * This file contains the HTML generated for a single location Google map. You can copy this file to yourthemefolder/plugins/events/templates and modify it in an upgrade-safe manner.
 * 
 * There is one argument passed to you, which is the $args variable. This contains the arguments you could pass into shortcodes, template tags or functions like EM_Events::get().
 * 
 * In this template, we encode the $args array into JSON for javascript to easily parse and request the locations from the server via AJAX.
 */
	/* @var $EM_Location EM_Location */
	global $EM_Event;
	//echo '<pre>';	
	//print_r($EM_Event->categories->categories); exit;
	
	if ( get_option('dbem_gmap_is_active') && ( is_object($EM_Location)  && $EM_Location->location_latitude != 0 && $EM_Location->location_longitude != 0 ) ) {
		$width = (isset($args['width'])) ? $args['width']:'400';
		$height = (isset($args['height'])) ? $args['height']:'300';
		$rand = substr(md5(rand().rand()),0,5);
		?>
   		<div class='em-location-map' id='em-location-map-<?php echo $rand ?>' style='background: #CDCDCD; width: <?php echo $width ?>px; height: <?php echo $height ?>px'><?php _e('Loading Map....', 'dbem'); ?></div>
   		<div class='em-location-map-info' id='em-location-map-info-<?php echo $rand ?>' style="display:none; visibility:hidden;">
   			<div class="em-map-balloon" style="font-size:12px;">
   				<div class="em-map-balloon-content" >
					<?php 
						echo "Name:".$EM_Event->event_name."<br/>"; 
						echo "Sports:";					
						foreach($EM_Event->categories as $sport){
							echo $sport->name.", ";						
						}						

						echo "<br/>Date:".$EM_Event->event_start_date." To ".$EM_Event->event_end_date."<br/>"; 
					/*for premium users : by user2*/
						echo "Time:".$EM_Event->event_start_time." To ".$EM_Event->event_end_time."<br/>"; 
						echo "Content:".$EM_Event->post_content."<br/>"; 
					?>
						Website:<a href=#><?php echo $EM_Event->website_link; ?><br/></a> 
					<?php
						echo "Owner Name:".$EM_Event->event_owner_name."<br/>"; 
					?>
						<input type="button" value=" contact us" /><br/>
						<a href=#>Read more..</a>
					<?php // echo $EM_Location->output(get_option('dbem_location_baloon_format')); ?>
				</div>
   			</div>
   		</div>
		<div class='em-location-map-coords' id='em-location-map-coords-<?php echo $rand ?>' style="display:none; visibility:hidden;">
			<span class="lat"><?php echo $EM_Location->location_latitude; ?></span>
			<span class="lng"><?php echo $EM_Location->location_longitude; ?></span>
		</div>
		<?php
	}elseif( is_object($EM_Location) && $EM_Location->location_latitude == 0 && $EM_Location->location_longitude == 0 ){
		echo '<i>'. __('Map Unavailable', 'dbem') .'</i>';
	}
