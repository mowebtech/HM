<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_

' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-1.9.1.js"></script>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.flexslider.js"></script>
<?php wp_head(); ?>

<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/flexslider.css" rel="stylesheet" type="text/css" />
<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/jquery-ui.css" rel="stylesheet" type="text/css" />
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>-->


<script type="text/javascript">
<!--
    
    $(window).load(function(){
      $('.flexslider').flexslider({
        animation: "slide",
        start: function(slider){
          $('body').removeClass('loading');
        }
      });
    });
-->	
  </script>
</head>

<body <?php body_class(); ?>>

<div id="wrapper-outer">
  <!--Wrapper Inner-->
  <div id="wrapper-inr">
    <!--HEADER-->
    <div class="header">
      <div class="hedr-top"><a href="index.html" title="Home" class="logo-sp"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.jpg" alt="Home" /></a>
        <ul class="user-login">
            <?php 
            
            $level_id_var = $user_role_var =  wp_get_current_user()->membership_level->id;
            $user_role_var =  wp_get_current_user()->membership_level->name;?>
         <?php if (is_user_logged_in()) { ?> 
                         
			 <li><a href="<?php echo wp_login_url();?>&action=profile&level=<?php echo $level_id_var;?>" title="Logout">Profile</a></li>
                         
			<li><a href="<?php echo wp_logout_url( $redirect ); ?>" title="Logout">Logout</a></li>
		<?php }else{  ?> 
			<li><a href="<?php echo wp_login_url();?>" title="Login">Login</a>
		<? } ?>

	
          <li class="no-marg-rgt"><a href="#" title="Submit Events">Submit Events</a></li>
        </ul>
        <div class="spacer"></div>
        <!--NNAVIGATION-->
        <div class="nav-sp">
         <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
        </div>
        <!--/NAVIGATION-->
        <div class="spacer"></div>
      </div>


            
  <!--CAROUSEL START-->
        <div class="carousel-sp">
          <div class="slider">
            <div class="flexslider">
              <ul class="slides">
<?php if( class_exists( 'LenSlider' ) ) {$slides = LenSlider::my_lenslider_output_slider('BA36E8D207',true);
	foreach($slides as $key=>$value){

?>

                <li><img src="<?php echo site_url().'/'.$value['path']; ?>" alt="" />
                  <div class="slides-details"> <span><?php echo $value['ls_title']; ?></span>
                    <p><?php echo $value['ls_text']; ?></p>
                  </div>
                </li>
<?
	}

} 
?>
   
              </ul>
            </div>
          </div>
        </div>
        <!--CAROUSEL END-->
    </div>
    <!--/HEADER-->
    <!--CONTENT-->
    <div id="content">
