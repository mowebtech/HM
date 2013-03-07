<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		 <!--Content - Right-->
		      <div class="cont-rgt"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/sidebar-pic-1.jpg" alt="" />
			<!--Featured Heroine-->
			<div class="sidebar-box">
			  <h3>Featured Heroine</h3>
			  <div class="sidebar-details">
			    <div class="sidebar-left">
			      <div class="sidebar-pic"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/sidebar-pic-2.jpg" alt="" /> </div>
			    </div>
			    <div class="sidebar-rgt"><span>Heroine name</span>
			      <p>Plusieurs variations de Lorem Ipsum peuvent être trouvées ici ou là, mais la majeure partie.....</p>
			      <a href="#" title="Read More">read more</a></div>
			  </div>
			</div>
			<!--/Featured Heroine-->
			<!--Featured Heroine-->
			<div class="sidebar-box">
			  <h3>Tryday</h3>
			  <div class="sidebar-details">
			    <div class="sidebar-left">
			      <div class="sidebar-pic"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/sidebar-pic-3.jpg" alt="" /> </div>
			    </div>
			    <div class="sidebar-rgt"> <span>National Women TRY Sport Day - 21 June</span>
			      <p>Plusieurs variations de Lorem Ipsum peuvent être trouvées ici ou là, mais la majeure partie.....</p>
			      <a href="#" title="Read More">read more</a> </div>
			  </div>
			</div>
			<!--/Featured Heroine-->
			<!--APP-->
			<div class="sidebar-box">
			  <h3>APP</h3>
			  <div class="sidebar-details">
			    <div class="sidebar-left">
			      <div class="sidebar-pic"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/sidebar-pic-4.jpg" alt="" /> </div>
			    </div>
			    <div class="sidebar-rgt"> <span>National Women TRY Sport Day - 21 June</span>
			      <p>Plusieurs variations de Lorem Ipsum peuvent être trouvées ici ou là, mais la majeure partie.....</p>
			      <a href="#" title="Read More">read more</a> </div>
			  </div>
			</div>
			<!--/APP-->
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ads.jpg" alt="" /> </div>
		      <!--/Content - Right-->
	<?php endif; ?>
