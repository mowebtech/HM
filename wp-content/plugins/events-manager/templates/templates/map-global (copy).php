<?php 
/*
 * This file contains the HTML generated for maps. You can copy this file to yourthemefolder/plugins/events/templates and modify it in an upgrade-safe manner.
 * 
 * There is one argument passed to you, which is the $args variable. This contains the arguments you could pass into shortcodes, template tags or functions like EM_Events::get().
 * 
 * In this template, we encode the $args array into JSON for javascript to easily parse and request the locations from the server via AJAX.
 */

if (get_option('dbem_gmap_is_active') == '1') { 

	$args['em_ajax'] = true; 
	$args['query'] = 'GlobalMapData';
	if(isset($_GET['scope']))	{
		$args['scope'] = $_GET['scope'];
	}
	
	$rand = substr(md5(rand().rand()),0,5);

	?>
<input type='button' id='button_call' name='button_call' onclick='map_filter("http://192.168.1.50/projects/wordpress/?page_id=2","asdfsadfsafsafs")' value='Call Me' />
	<div class='em-locations-map' id='em-locations-map-<?php echo $rand; ?>' style='width:<?php echo $args['width']; ?>px; height:<?php echo $args['height']; ?>px'><em><?php _e('Loading Map....', 'dbem'); ?></em></div>
	<div class='em-locations-map-coords' id='em-locations-map-coords-<?php echo $rand; ?>' style="display:none; visibility:hidden;">
		<?php echo EM_Object::json_encode($args); ?>	
	</div>

	
	<?php
//Filter By location start by user2  -----------------------
//To get current country of user
	$country=file_get_contents('http://api.hostip.info/get_json.php');
	$arr_country = json_decode($country, true);	
	echo $current_city = $arr_country['city']; 
	echo $current_country_code = $arr_country['country_code'];
	?>
	Use current Location ? 
	<input type="radio" onclick='map_filter("http://192.168.1.50/projects/wordpress/?page_id=2",this.value)' name="filter_by_location" value="<?php echo "&country=".$current_country_code; ?>" />Yes
	<input type="radio" name="filter_by_location" value="no" />No
	<input type="hidden" id="current_country_code" value="<?php echo $current_country_code; ?>" /> 
	
	Or Enter Post code: <input type="text" id="post_code" name="post_code" value="" />
	
<?php
//end by user2 -----------------------------

}
?>
