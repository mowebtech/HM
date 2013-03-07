<?php 
/*
 * This file contains the HTML generated for maps. You can copy this file to yourthemefolder/plugins/events/templates and modify it in an upgrade-safe manner.
 * 
 * There is one argument passed to you, which is the $args variable. This contains the arguments you could pass into shortcodes, template tags or functions like EM_Events::get().
 * 
 * In this template, we encode the $args array into JSON for javascript to easily parse and request the locations from the server via AJAX.
 */


	$args['em_ajax'] = true; 
	$rand = substr(md5(rand().rand()),0,5);
//print_r($atts); exit;
	?>



	<div class='em-locations-map' id='em-locations-map-<?php echo $rand; ?>' style='width:400px; height:400px'><em><?php _e('Loading Map....', 'dbem'); ?></em></div>
	<div class='em-locations-map-coords' id='em-locations-map-coords-<?php echo $rand; ?>' style="display:none; visibility:hidden;"><?php echo EM_Events::outputjson($args); ?></div>
