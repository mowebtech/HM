	<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

      <!--Content - Left-->
      <div class="cont-lft">
        <!--Map-->
        <div class="map">
          <div class="map-caption-sp">
            <h2>Map View of Events</h2>
          </div>
          <div class="map-details-sp-outer"> 
		  	<div class="map-img">
				<?php echo do_shortcode('[locations_map]') ?> 
			<!--	<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/pic-map.jpg" alt="" />  -->
			</div>
			<div class="map-description">
				<span class="map-title">Title goes here...</span>
				<p>Plusieurs variations de Lorem Ipsum peuvent &ecirc;tre trouv&eacute;es ici ou l&agrave;, mais la majeure partie plusieurs variations de Lorem Ipsum peuvent &ecirc;tre trouv&eacute;es ici ou l&agrave;, mais la majeure partie.....</p>
				
				<!--LOCATION POINT-->
				<form id="event_map_form" name="event_map_form" method="post">
				<div class="locate-point-sp">
					<ul>
						<li><span class="icon-location1"></span><input type='checkbox' class='locate-point1 event_types' name='event_types' value='try' />Try<a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" /></a></li>
						<li><span class="icon-location2"></span><input type='checkbox' class='locate-point1 event_types' name='event_types' value='meet' />Meet<a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" /></a></li>
						<li><span class="icon-location3"></span><input type='checkbox' class='locate-point1 event_types' name='event_types' value='spectate' />Spectate<a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" /></a></li>
						<li><span class="icon-location4"></span><input type='checkbox' class='locate-point1 event_types' name='event_types' value='participate' />Participate<a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" /></a></li>
					</ul>
					<input type="Submit" name="Submit" id="Submit" value="View List of Events" class="events" />
				</div>

				

				</form>
				<!--/LOCATION POINT-->
			</div>
		  </div>
		  </div>
        <!--/Map-->
        <!--Pod-->
        <div class="pod-outer">
          <!--TV Schedule-->
          <div class="pod-sp">
            <div class="pod-head-sp schedule">
              <h3>TV Schedule</h3>
            </div>
            <div class="pod-details">
              <ul class="tv-list">
                <li> 15th Feb 2013<br />
                  <span>Programme Title will go here..</span> <a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" />
                  <!--<span class="tooltip"><span class="top"></span><span class="middle">This is my Bubble Tooltip with CSS</span><span class="bottom"></span></span>-->
                  </a> </li>
                <li> 15th Feb 2013<br />
                  <span>Programme Title will go here..</span> <a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" /></a> </li>
                <li> 15th Feb 2013<br />
                  <span>Programme Title will go here..</span> <a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" /></a> </li>
                <li> 15th Feb 2013<br />
                  <span>Programme Title will go here..</span> <a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" /></a> </li>
                <li> 15th Feb 2013<br />
                  <span>Programme Title will go here..</span> <a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" /></a> </li>
                <li> 15th Feb 2013<br />
                  <span>Programme Title will go here..</span> <a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" /></a> </li>
                <li> 15th Feb 2013<br />
                  <span>Programme Title will go here..</span> <a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" /></a> </li>
                <li> 15th Feb 2013<br />
                  <span>Programme Title will go here..</span> <a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" /></a> </li>
                <li> 15th Feb 2013<br />
                  <span>Programme Title will go here..</span> <a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" /></a> </li>
                <li> 15th Feb 2013<br />
                  <span>Programme Title will go here..</span> <a href="#" title="" class="tt"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/link-tooltip.png" alt="" /></a> </li>
                <li class="view-more"> <a href="#" title="view more">view more</a> </li>
              </ul>
            </div>
          </div>
          <!--/TV Schedule-->
          <!--News Feeds-->
          <div class="pod-sp">
            <div class="pod-head-sp feeds">
              <h3>News Feeds</h3>
            </div>
            <div class="pod-details">
              <ul class="news-feeds">
                <li><span>News Title will go here</span><br />
                  15th Feb 2013</li>
                <li><span>News Title will go here</span><br />
                  15th Feb 2013</li>
                <li><span>News Title will go here</span><br />
                  15th Feb 2013</li>
                <li><span>News Title will go here</span><br />
                  15th Feb 2013</li>
                <li><span>News Title will go here</span><br />
                  15th Feb 2013</li>
                <li><span>News Title will go here</span><br />
                  15th Feb 2013</li>
                <li><span>News Title will go here</span><br />
                  15th Feb 2013</li>
                <li><span>News Title will go here</span><br />
                  15th Feb 2013</li>
                <li><span>News Title will go here</span><br />
                  15th Feb 2013</li>
                <li><span>News Title will go here</span><br />
                  15th Feb 2013</li>
                <li class="view-more-feeds"> <a href="#" title="view more">view more</a> </li>
              </ul>
            </div>
          </div>
          <!--/News Feeds-->
          <!--Twitwer Feeds-->
          <div class="pod-sp no-marg-rgt">
            <div class="pod-head-sp twitter-feeds">
              <h3>Twitter Feeds</h3>
            </div>
            <div class="pod-details">
              <ul class="twitter-feeds-list">
                <li>RT <span>@pollyfildes:</span> @Twing_IT Tickets to @sweden2013..</li>
                <li>RT <span>@pollyfildes:</span> @Twing_IT Tickets to @sweden2013..</li>
                <li>RT <span>@pollyfildes:</span> @Twing_IT Tickets to @sweden2013..</li>
                <li>RT <span>@pollyfildes:</span> @Twing_IT Tickets to @sweden2013..</li>
                <li>RT <span>@pollyfildes:</span> @Twing_IT Tickets to @sweden2013..</li>
                <li>RT <span>@pollyfildes:</span> @Twing_IT Tickets to @sweden2013..</li>
                <li>RT <span>@pollyfildes:</span> @Twing_IT Tickets to @sweden2013..</li>
                <li>RT <span>@pollyfildes:</span> @Twing_IT Tickets to @sweden2013..</li>
                <li>RT <span>@pollyfildes:</span> @Twing_IT Tickets to @sweden2013..</li>
                <li>RT <span>@pollyfildes:</span> @Twing_IT Tickets to @sweden2013..</li>
                <li class="view-more-twitter"> <a href="#" title="view more">view more</a> </li>
              </ul>
            </div>
          </div>
          <!--/Twitter Feeds-->
        </div>
        <!--/Pod-->
      </div>
      <!--/Content - Left-->
	<!--Content - Right-->
     <?php get_sidebar(); ?>
	<!--Content - Right-->
    </div>
    <!--/CONTENT-->
<?php get_footer(); ?>  

<!---#############################################################################################################################-->
<?php exit; ?>
	<div id="primary" class="site-content">
		<div id="content" role="main">
		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php twentytwelve_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<article id="post-0" class="post no-results not-found">

			<?php if ( current_user_can( 'edit_posts' ) ) :
				// Show a different message to a logged-in user who can add posts.
			?>
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'No posts to display', 'twentytwelve' ); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php printf( __( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'twentytwelve' ), admin_url( 'post-new.php' ) ); ?></p>
				</div><!-- .entry-content -->

			<?php else :
				// Show the default message to everyone else.
			?>
				<header class="entry-header">
					<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentytwelve' ); ?></h1>
				</header>

				<div class="entry-content">
					<p><?php _e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'twentytwelve' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .entry-content -->
			<?php endif; // end current_user_can() check ?>

			</article><!-- #post-0 -->

		<?php endif; // end have_posts() check ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
